{{-- Tema halaman auth (login, register, lupa & reset sandi): putih–merah, minimalis.
     Di-include dari @push('css') masing-masing halaman. --}}
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;800&display=swap" rel="stylesheet"/>
<style>
    body {
        font-family: 'Plus Jakarta Sans', 'Nunito Sans', sans-serif;
        -webkit-font-smoothing: antialiased;
    }

    :root {
        --auth-red: #c1121f;
        --auth-red-deep: #8a0f18;
        --auth-red-tint: #fdedee;
        --auth-ink: #241c1a;
        --auth-ink-soft: #6e625f;
        --auth-line: #e6dedb;
    }

    /* ── Sisi kiri: ilustrasi penuh, veil merah ────────────────────── */
    .auth-hero {
        position: relative;
        width: 100%;
        height: 100vh;
        overflow: hidden;
        background: var(--auth-red-deep);
    }
    .auth-hero-photo {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 38%;
    }

    /* Dua lapis: merah multiply mewarnai seluruh ilustrasi,
       lalu gelap ke bawah supaya teks terbaca. */
    .auth-hero-veil { position: absolute; inset: 0; }
    .auth-hero-veil::before,
    .auth-hero-veil::after {
        content: '';
        position: absolute;
        inset: 0;
    }
    .auth-hero-veil::before {
        background: #a5111c;
        mix-blend-mode: multiply;
        opacity: 0.62;
    }
    .auth-hero-veil::after {
        background: linear-gradient(to top,
            rgba(38, 3, 6, 0.94) 0%,
            rgba(45, 4, 8, 0.70) 26%,
            rgba(70, 8, 13, 0.22) 55%,
            rgba(90, 12, 18, 0.10) 100%);
    }

    .auth-hero-mark {
        position: absolute;
        top: 2.75rem;
        left: 3.25rem;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }
    .auth-hero-mark img { width: 46px; height: 46px; object-fit: contain; }
    .auth-hero-mark span {
        font-size: 0.78rem;
        font-weight: 600;
        line-height: 1.35;
        color: rgba(255, 255, 255, 0.9);
    }

    .auth-hero-copy {
        position: absolute;
        left: 3.25rem;
        right: 3.25rem;
        bottom: 3.5rem;
        z-index: 2;
        max-width: 30rem;
    }
    .auth-hero-copy h1 {
        font-size: clamp(2.25rem, 3.8vw, 3.4rem);
        font-weight: 800;
        letter-spacing: -0.035em;
        line-height: 1.02;
        color: #ffffff;
        margin: 0;
    }
    .auth-hero-copy h1.is-wordmark {
        font-size: clamp(2.75rem, 4.5vw, 3.9rem);
        line-height: 0.95;
    }
    .auth-hero-expand {
        font-size: 0.95rem;
        font-weight: 600;
        color: #ffffff;
        margin: 0.75rem 0 0;
        padding-bottom: 1.1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.28);
    }
    .auth-hero-lede {
        font-size: 0.95rem;
        line-height: 1.65;
        color: rgba(255, 255, 255, 0.82);
        margin: 1.1rem 0 0;
        max-width: 27rem;
    }
    @media (max-width: 1399.98px) {
        .auth-hero-mark { top: 2rem; left: 2.25rem; }
        .auth-hero-copy { left: 2.25rem; right: 2.25rem; bottom: 2.5rem; }
    }

    /* ── Sisi kanan: kertas putih, satu aksen merah ────────────────── */
    .auth-panel,
    .login-right-panel {
        background: #ffffff;
    }
    .auth-form,
    .login-form-wrapper {
        width: 100%;
        padding: 0 1.5rem;
    }
    .auth-form { max-width: 25rem; }
    .login-form-wrapper { max-width: 34rem; }

    .auth-form-mark {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 2.25rem;
    }
    .auth-form-mark img { width: 42px; height: 42px; object-fit: contain; }
    .auth-form-mark span {
        font-size: 1.05rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--auth-ink);
        line-height: 1.2;
    }
    .auth-form-mark small {
        font-size: 0.72rem;
        font-weight: 500;
        color: var(--auth-ink-soft);
        letter-spacing: 0;
    }

    .auth-title,
    .login-form-title {
        font-size: 1.65rem;
        font-weight: 800;
        letter-spacing: -0.025em;
        color: var(--auth-ink);
        margin: 0 0 0.4rem;
    }
    .auth-subtitle,
    .login-form-subtitle {
        font-size: 0.92rem;
        line-height: 1.6;
        color: var(--auth-ink-soft);
        margin: 0;
    }
    .auth-body { margin-top: 2rem; }

    .auth-alert {
        display: flex;
        gap: 0.7rem;
        align-items: flex-start;
        background: var(--auth-red-tint);
        border-left: 3px solid var(--auth-red);
        border-radius: 6px;
        padding: 0.85rem 1rem;
        margin-bottom: 1.5rem;
        color: var(--auth-red-deep);
    }
    .auth-alert i { flex-shrink: 0; margin-top: 1px; }
    .auth-alert p { margin: 0; font-size: 0.88rem; line-height: 1.5; }
    .auth-alert p + p { margin-top: 0.25rem; }
    .auth-alert-ok {
        background: #eef7f0;
        border-left-color: #2f7d4f;
        color: #1e5c39;
    }

    /* ── Kolom isian ───────────────────────────────────────────────── */
    .auth-field { margin-bottom: 1.25rem; }
    .auth-field label,
    .login-field-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--auth-ink);
        margin-bottom: 0.45rem;
    }
    /* Baris label dengan tautan pendamping (mis. "Lupa kata sandi?") */
    .auth-field-head {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 1rem;
    }
    .auth-field-aside {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--auth-red);
        text-decoration: none;
        white-space: nowrap;
    }
    .auth-field-aside:hover { text-decoration: underline; }

    .auth-control { position: relative; }
    .auth-control-icon,
    .login-field-icon {
        position: absolute;
        left: 0.95rem;
        top: 50%;
        transform: translateY(-50%);
        display: inline-flex;
        align-items: center;
        color: #a2938f;
        pointer-events: none;
        z-index: 2;
    }
    .auth-input,
    .login-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.6rem;
        border: 1px solid var(--auth-line);
        border-radius: 6px;
        background: #ffffff;
        font-family: inherit;
        font-size: 0.95rem;
        color: var(--auth-ink);
        box-shadow: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .auth-input::placeholder,
    .login-input::placeholder { color: #b3a6a2; }
    .auth-input:focus,
    .login-input:focus {
        outline: none;
        border-color: var(--auth-red);
        box-shadow: 0 0 0 3px rgba(193, 18, 31, 0.12);
    }
    .auth-input.is-invalid,
    .login-input.is-invalid {
        border-color: var(--auth-red);
        background-image: none;
    }
    .auth-input.password-input,
    .login-input.password-input { padding-right: 3rem; }
    select.login-input { padding-right: 2.5rem; }

    .auth-reveal,
    .password-addon {
        z-index: 3;
        background: transparent;
        border: none;
    }
    .auth-reveal {
        position: absolute;
        right: 0;
        top: 0;
        height: 100%;
        padding: 0 0.9rem;
    }
    /* AcornIcons mengganti <i> menjadi <svg>, jadi keduanya perlu diwarnai. */
    .auth-reveal,
    .password-addon { color: #a2938f; }
    .auth-reveal i, .auth-reveal svg,
    .password-addon i, .password-addon svg { color: #a2938f !important; }
    .auth-reveal:hover, .auth-reveal:focus,
    .password-addon:hover, .password-addon:focus { color: var(--auth-red); }
    .auth-reveal:hover i, .auth-reveal:hover svg,
    .auth-reveal:focus i, .auth-reveal:focus svg,
    .password-addon:hover i, .password-addon:hover svg,
    .password-addon:focus i, .password-addon:focus svg { color: var(--auth-red) !important; }

    .auth-remember { margin: 0 0 1.75rem; padding-left: 1.6rem; }
    .auth-remember .form-check-input {
        width: 1.05rem;
        height: 1.05rem;
        margin-left: -1.6rem;
        margin-top: 0.15rem;
        border: 1.5px solid #cfc3bf;
        border-radius: 4px;
        cursor: pointer;
    }
    .auth-remember .form-check-label {
        font-size: 0.88rem;
        color: var(--auth-ink-soft);
        cursor: pointer;
    }
    .form-check-input:checked {
        background-color: var(--auth-red);
        border-color: var(--auth-red);
    }
    .form-check-input:focus {
        border-color: var(--auth-red);
        box-shadow: 0 0 0 3px rgba(193, 18, 31, 0.12);
    }

    /* ── Tombol ────────────────────────────────────────────────────── */
    .auth-submit,
    .login-submit,
    .wizard-btn-prev,
    .wizard-btn-next,
    .wizard-btn-submit {
        border-radius: 6px;
        padding: 0.75rem 1.35rem;
        font-family: inherit;
        font-size: 0.95rem;
        font-weight: 700;
        transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    }
    .auth-submit,
    .login-submit,
    .wizard-btn-next,
    .wizard-btn-submit {
        background: var(--auth-red);
        border: none;
        color: #ffffff;
    }
    .auth-submit:hover, .auth-submit:focus,
    .login-submit:hover, .login-submit:focus,
    .wizard-btn-next:hover, .wizard-btn-next:focus,
    .wizard-btn-submit:hover, .wizard-btn-submit:focus {
        background: var(--auth-red-deep);
        color: #ffffff;
    }
    .auth-submit:focus-visible {
        outline: 2px solid var(--auth-red);
        outline-offset: 2px;
    }
    .wizard-btn-prev {
        background: #ffffff;
        border: 1px solid var(--auth-line);
        color: var(--auth-ink-soft);
    }
    .wizard-btn-prev:hover,
    .wizard-btn-prev:focus {
        border-color: var(--auth-red);
        color: var(--auth-red);
    }

    /* ── Ekor halaman ──────────────────────────────────────────────── */
    .auth-switch,
    .login-register-prompt {
        margin: 1.5rem 0 0;
        font-size: 0.9rem;
        color: var(--auth-ink-soft);
        text-align: center;
    }
    .auth-switch a,
    .login-register-prompt a {
        color: var(--auth-red);
        font-weight: 600;
        text-decoration: none;
    }
    .auth-switch a:hover,
    .login-register-prompt a:hover { text-decoration: underline; }

    .auth-meta,
    .login-footer-meta {
        margin: 3rem 0 0;
        padding-top: 1.25rem;
        border-top: 1px solid var(--auth-line);
        font-size: 0.75rem;
        color: #a2938f;
        text-align: center;
    }

    .login-divider {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        margin: 1.75rem 0 1rem;
        color: #a2938f;
        font-size: 0.82rem;
    }
    .login-divider::before,
    .login-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--auth-line);
    }

    .alert-danger {
        background: var(--auth-red-tint);
        border-left: 3px solid var(--auth-red) !important;
        border-radius: 6px !important;
        color: var(--auth-red-deep);
        box-shadow: none !important;
    }
    .alert-success {
        background: #eef7f0;
        border-left: 3px solid #2f7d4f !important;
        border-radius: 6px !important;
        color: #1e5c39;
        box-shadow: none !important;
    }

    /* ── Stepper wizard (halaman daftar) ───────────────────────────── */
    .wizard-stepper { display: flex; align-items: center; margin: 0 0 2rem; }
    .wizard-step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
    }
    .wizard-step-circle {
        position: relative;
        width: 2.1rem;
        height: 2.1rem;
        border-radius: 9999px;
        border: 1.5px solid var(--auth-line);
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }
    .wizard-step-num,
    .wizard-step-check {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        font-weight: 700;
        color: #a2938f;
    }
    .wizard-step-check { display: none; color: #ffffff; }
    .wizard-step-check svg { width: 15px; height: 15px; }
    .wizard-step-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--auth-ink-soft);
        white-space: nowrap;
    }
    .wizard-step-line {
        flex: 1;
        height: 1.5px;
        background: var(--auth-line);
        margin: 0 0.5rem 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .wizard-step-line::after {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--auth-red);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }
    .wizard-step-line.completed::after { transform: scaleX(1); }
    .wizard-step-item.active .wizard-step-circle,
    .wizard-step-item.completed .wizard-step-circle {
        border-color: var(--auth-red);
        background: var(--auth-red);
    }
    .wizard-step-item.active .wizard-step-num { color: #ffffff; }
    .wizard-step-item.active .wizard-step-label,
    .wizard-step-item.completed .wizard-step-label { color: var(--auth-red); }
    .wizard-step-item.completed .wizard-step-num { display: none; }
    .wizard-step-item.completed .wizard-step-check { display: flex; }

    .wizard-step-panel { display: none; }
    .wizard-step-panel.active { display: block; }
    .wizard-step-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--auth-ink);
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--auth-line);
    }
    .wizard-step-title i { color: var(--auth-red); }

    .wizard-review {
        border: 1px solid var(--auth-line);
        border-radius: 6px;
        overflow: hidden;
    }
    .wizard-review-row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--auth-line);
    }
    .wizard-review-row:last-child { border-bottom: none; }
    .wizard-review-label {
        font-size: 0.82rem;
        color: var(--auth-ink-soft);
        flex-shrink: 0;
    }
    .wizard-review-value {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--auth-ink);
        text-align: right;
        word-break: break-word;
    }
    .wizard-nav { gap: 0.75rem; }
    .wizard-nav .wizard-btn-next,
    .wizard-nav .wizard-btn-submit { margin-left: auto; }

    @media (max-width: 991.98px) {
        .auth-panel,
        .login-right-panel { min-height: 100vh; }
        .auth-form { max-width: 24rem; }
        .auth-form,
        .login-form-wrapper { padding: 0 0.5rem; }
    }
    @media (max-width: 575.98px) {
        .wizard-step-label { font-size: 0.65rem; }
    }
</style>
