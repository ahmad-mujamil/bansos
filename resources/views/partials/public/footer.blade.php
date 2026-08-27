@php
    $variant = $variant ?? 'full';
@endphp

@if($variant === 'minimal')
    <footer class="border-t border-slate-200 dark:border-slate-800 py-8 px-6 text-center text-sm text-slate-500 dark:text-slate-400">
        © {{ date('Y') }} Si-BATUR
    </footer>
@else
    <footer class="bg-slate-50 dark:bg-slate-950 w-full border-t border-slate-200 dark:border-slate-800">
        <div class="flex flex-col md:flex-row justify-between items-center px-8 py-12 max-w-7xl mx-auto gap-6">
            <div class="space-y-4 text-center md:text-left">
                <div class="text-lg font-bold text-blue-800 dark:text-blue-400 font-public-sans">Si-BATUR</div>
                <p class="text-sm font-public-sans leading-relaxed text-slate-500 max-w-xs">
                    Platform resmi pemerintah daerah kabupaten lombok barat.
                </p>
            </div>
            <div class="flex flex-wrap justify-center gap-8">
                <a class="text-sm font-public-sans leading-relaxed text-slate-500 hover:text-blue-600 dark:hover:text-blue-300 underline-offset-4 hover:underline transition-opacity opacity-90 hover:opacity-100" href="#">Privacy Policy</a>
                <a class="text-sm font-public-sans leading-relaxed text-slate-500 hover:text-blue-600 dark:hover:text-blue-300 underline-offset-4 hover:underline transition-opacity opacity-90 hover:opacity-100" href="#">Terms of Service</a>
                <a class="text-sm font-public-sans leading-relaxed text-slate-500 hover:text-blue-600 dark:hover:text-blue-300 underline-offset-4 hover:underline transition-opacity opacity-90 hover:opacity-100" href="#">Contact Us</a>
                <a class="text-sm font-public-sans leading-relaxed text-slate-500 hover:text-blue-600 dark:hover:text-blue-300 underline-offset-4 hover:underline transition-opacity opacity-90 hover:opacity-100" href="#">Help Center</a>
            </div>
            <div class="text-sm font-public-sans text-slate-500 text-center">
                © {{ date('Y') }} Si-BATUR. All rights reserved.
            </div>
        </div>
    </footer>
@endif
