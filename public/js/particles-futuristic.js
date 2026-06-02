/**
 * Futuristic Particle Field
 * - Canvas-based particle system with constellation network and mouse interaction.
 * - Auto-initializes on `.login-particles` and `.particles-layer` containers.
 * - Each container gets its own field; respects prefers-reduced-motion.
 */
(function () {
    'use strict';

    const PALETTE = [
        { core: '#ffffff', glow: 'rgba(255,255,255,0.85)' },
        { core: '#93c5fd', glow: 'rgba(147,197,253,0.85)' },
        { core: '#a78bfa', glow: 'rgba(167,139,250,0.85)' },
        { core: '#22d3ee', glow: 'rgba(34,211,238,0.85)' },
        { core: '#c4b5fd', glow: 'rgba(196,181,253,0.85)' },
    ];

    const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    class ParticleField {
        constructor(container, opts) {
            this.container = container;
            this.opts = Object.assign({
                density: 0.00012,        // particles per pixel area
                minCount: 30,
                maxCount: 90,
                linkDistance: 130,       // px to draw connecting lines
                mouseRadius: 140,        // px area of mouse influence
                mouseForce: 1.6,         // push strength
                speed: 0.35,             // base px/frame
                pulse: true,
            }, opts || {});
            this.particles = [];
            this.mouse = { x: -9999, y: -9999, active: false };
            this.dpr = Math.min(window.devicePixelRatio || 1, 2);

            this.canvas = document.createElement('canvas');
            this.canvas.className = 'futuristic-particles-canvas';
            this.canvas.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;display:block;pointer-events:none;';
            this.container.appendChild(this.canvas);
            this.ctx = this.canvas.getContext('2d');

            this.container.classList.add('has-futuristic-particles');

            this.resize = this.resize.bind(this);
            this.frame  = this.frame.bind(this);
            this.onMove = this.onMove.bind(this);
            this.onLeave = this.onLeave.bind(this);

            window.addEventListener('resize', this.resize, { passive: true });

            // Mouse listeners bound to the window so cursor near (but outside)
            // the container still influences edge particles.
            window.addEventListener('mousemove', this.onMove, { passive: true });
            // Use mouseleave on documentElement (fires only when leaving the
            // viewport, doesn't bubble) instead of `mouseout` which fires
            // on every element-to-element transition and would constantly
            // reset the mouse state.
            document.documentElement.addEventListener('mouseleave', this.onLeave, { passive: true });

            // Some auth pages have flex/grid layout that resolves after the
            // initial paint, so the container can be 0×0 at constructor time.
            // Observe size changes and re-render when it actually has a box.
            if (typeof ResizeObserver !== 'undefined') {
                this._ro = new ResizeObserver(() => this.resize());
                this._ro.observe(this.container);
            }

            this.resize();
            this._raf = requestAnimationFrame(this.frame);
        }

        resize() {
            const rect = this.container.getBoundingClientRect();
            // Skip until the container has a real box.
            if (rect.width < 40 || rect.height < 40) {
                this.width = rect.width;
                this.height = rect.height;
                return;
            }
            const w = rect.width, h = rect.height;
            const sizeChanged = (w !== this.width || h !== this.height);
            this.width = w;
            this.height = h;
            this.canvas.width  = Math.round(this.width  * this.dpr);
            this.canvas.height = Math.round(this.height * this.dpr);
            this.ctx.setTransform(this.dpr, 0, 0, this.dpr, 0, 0);
            // Re-seed particles only if size actually changed
            // (avoid wiping current state on every observer tick).
            if (sizeChanged || this.particles.length === 0) {
                this.regenerate();
            }
        }

        regenerate() {
            const area = this.width * this.height;
            let count = Math.round(area * this.opts.density);
            count = Math.max(this.opts.minCount, Math.min(this.opts.maxCount, count));
            this.particles = [];
            for (let i = 0; i < count; i++) {
                this.particles.push(this.makeParticle());
            }
        }

        makeParticle() {
            const palette = PALETTE[Math.floor(Math.random() * PALETTE.length)];
            const radius = 0.6 + Math.random() * 1.2;
            const angle = Math.random() * Math.PI * 2;
            const speed = this.opts.speed * (0.4 + Math.random() * 1.2);
            return {
                x: Math.random() * this.width,
                y: Math.random() * this.height,
                vx: Math.cos(angle) * speed,
                vy: Math.sin(angle) * speed - speed * 0.4, // slight upward drift
                r: radius,
                baseR: radius,
                pulse: Math.random() * Math.PI * 2,
                pulseSpeed: 0.01 + Math.random() * 0.02,
                core: palette.core,
                glow: palette.glow,
            };
        }

        onMove(e) {
            const rect = this.container.getBoundingClientRect();
            this.mouse.x = e.clientX - rect.left;
            this.mouse.y = e.clientY - rect.top;
            this.mouse.active = true;
        }

        onLeave() {
            this.mouse.active = false;
            this.mouse.x = -9999;
            this.mouse.y = -9999;
        }

        frame() {
            // Defer drawing until container has dimensions.
            if (!this.width || !this.height || this.particles.length === 0) {
                this._raf = requestAnimationFrame(this.frame);
                return;
            }
            const ctx = this.ctx;
            ctx.clearRect(0, 0, this.width, this.height);

            const { mouseRadius, mouseForce, linkDistance, pulse } = this.opts;
            const mr2 = mouseRadius * mouseRadius;
            const ld2 = linkDistance * linkDistance;

            // Update + draw particles
            for (let i = 0; i < this.particles.length; i++) {
                const p = this.particles[i];

                // Mouse repulsion
                if (this.mouse.active) {
                    const dx = p.x - this.mouse.x;
                    const dy = p.y - this.mouse.y;
                    const d2 = dx * dx + dy * dy;
                    if (d2 < mr2 && d2 > 1) {
                        const d = Math.sqrt(d2);
                        const f = (1 - d / mouseRadius) * mouseForce;
                        p.vx += (dx / d) * f * 0.4;
                        p.vy += (dy / d) * f * 0.4;
                    }
                }

                // Velocity damping (so mouse-pushed particles ease back)
                p.vx *= 0.985;
                p.vy *= 0.985;

                // Tiny drift to keep movement when not pushed
                const angle = Math.random() * Math.PI * 2;
                p.vx += Math.cos(angle) * 0.005;
                p.vy += Math.sin(angle) * 0.005 - 0.006; // slight upward bias

                p.x += p.vx;
                p.y += p.vy;

                // Wrap around edges with margin
                const m = 30;
                if (p.x < -m) p.x = this.width + m;
                if (p.x > this.width + m) p.x = -m;
                if (p.y < -m) p.y = this.height + m;
                if (p.y > this.height + m) p.y = -m;

                // Pulse radius
                if (pulse) {
                    p.pulse += p.pulseSpeed;
                    p.r = p.baseR + Math.sin(p.pulse) * 0.25;
                }
            }

            // Draw lines (constellation network) — subtle, no glow.
            ctx.lineWidth = 1;
            for (let i = 0; i < this.particles.length; i++) {
                const a = this.particles[i];
                for (let j = i + 1; j < this.particles.length; j++) {
                    const b = this.particles[j];
                    const dx = a.x - b.x;
                    const dy = a.y - b.y;
                    const d2 = dx * dx + dy * dy;
                    if (d2 < ld2) {
                        const alpha = (1 - d2 / ld2) * 0.45;
                        ctx.strokeStyle = 'rgba(180, 210, 255,' + alpha.toFixed(3) + ')';
                        ctx.beginPath();
                        ctx.moveTo(a.x, a.y);
                        ctx.lineTo(b.x, b.y);
                        ctx.stroke();
                    }
                }

                // Connect to mouse if near
                if (this.mouse.active) {
                    const dxm = a.x - this.mouse.x;
                    const dym = a.y - this.mouse.y;
                    const dm2 = dxm * dxm + dym * dym;
                    if (dm2 < mr2) {
                        const alpha = (1 - dm2 / mr2) * 0.7;
                        ctx.strokeStyle = 'rgba(125, 211, 252,' + alpha.toFixed(3) + ')';
                        ctx.lineWidth = 1.2;
                        ctx.beginPath();
                        ctx.moveTo(a.x, a.y);
                        ctx.lineTo(this.mouse.x, this.mouse.y);
                        ctx.stroke();
                        ctx.lineWidth = 1;
                    }
                }
            }

            // Draw particles (glow + core)
            for (let i = 0; i < this.particles.length; i++) {
                const p = this.particles[i];

                // Soft glow
                const glowR = p.r * 6;
                const grad = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, glowR);
                grad.addColorStop(0, p.glow);
                grad.addColorStop(1, 'rgba(255,255,255,0)');
                ctx.fillStyle = grad;
                ctx.beginPath();
                ctx.arc(p.x, p.y, glowR, 0, Math.PI * 2);
                ctx.fill();

                // Core
                ctx.fillStyle = p.core;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fill();
            }

            // Mouse cursor highlight pulse
            if (this.mouse.active) {
                const pulse = (Math.sin(Date.now() * 0.005) + 1) * 0.5;
                const grad = this.ctx.createRadialGradient(
                    this.mouse.x, this.mouse.y, 0,
                    this.mouse.x, this.mouse.y, 60 + pulse * 20
                );
                grad.addColorStop(0, 'rgba(125, 211, 252, 0.18)');
                grad.addColorStop(1, 'rgba(125, 211, 252, 0)');
                ctx.fillStyle = grad;
                ctx.beginPath();
                ctx.arc(this.mouse.x, this.mouse.y, 60 + pulse * 20, 0, Math.PI * 2);
                ctx.fill();
            }

            this._raf = requestAnimationFrame(this.frame);
        }

        destroy() {
            cancelAnimationFrame(this._raf);
            window.removeEventListener('resize', this.resize);
            window.removeEventListener('mousemove', this.onMove);
            document.documentElement.removeEventListener('mouseleave', this.onLeave);
            if (this._ro) this._ro.disconnect();
            if (this.canvas.parentNode) this.canvas.parentNode.removeChild(this.canvas);
            this.container.classList.remove('has-futuristic-particles');
        }
    }

    function init() {
        if (reduceMotion) return;

        // Hide legacy span-based particles & stars to avoid double-rendering.
        // NOTE: do NOT set `position: relative` here — the existing
        // `.login-particles` / `.particles-layer` rules already use
        // `position: absolute; inset: 0;`, and overriding that collapses
        // the container to 0×0 (which hides the canvas entirely).
        const style = document.createElement('style');
        style.textContent = `
            .has-futuristic-particles .particle,
            .has-futuristic-particles .star { display: none !important; }
        `;
        document.head.appendChild(style);

        // Find candidate containers
        const selectors = [
            '.login-particles',
            '.particles-layer',
        ];
        const seen = new Set();
        selectors.forEach((sel) => {
            document.querySelectorAll(sel).forEach((el) => {
                if (seen.has(el)) return;
                seen.add(el);
                // Always create the field — the ResizeObserver inside
                // ParticleField will regenerate particles once the
                // container gets its actual size (works for late-laid-out
                // flex/grid hosts like the auth full-page layout).
                new ParticleField(el);
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
