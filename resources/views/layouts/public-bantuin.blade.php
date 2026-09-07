<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Berita') | Si-BATUR</title>
    <link rel="icon" href="{{ asset('img/logo-only.png') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#c1121f",
                        "primary-container": "#8a0f18",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#f9dcde",
                        "background": "#ffffff",
                        "surface": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#fbf8f5",
                        "surface-container": "#f7f2ee",
                        "surface-container-high": "#f0e9e4",
                        "surface-dim": "#e6dedb",
                        "outline-variant": "#e6dedb",
                        "on-surface": "#241c1a",
                        "on-surface-variant": "#6e625f",
                    },
                    fontFamily: {
                        "jakarta": ["Plus Jakarta Sans", "system-ui", "sans-serif"],
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.375rem",
                        "xl": "0.5rem",
                        "2xl": "0.5rem",
                        "3xl": "0.625rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; -webkit-font-smoothing: antialiased; }
        .heading { font-weight: 800; letter-spacing: -0.028em; line-height: 1.1; }
        .prose-narrow { max-width: 62ch; }
        a:focus-visible, button:focus-visible { outline: 2px solid #c1121f; outline-offset: 3px; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-background text-on-surface selection:bg-primary-container selection:text-on-primary-container">
    @include('partials.public.header', ['extendedNav' => true])
<main class="pt-24 pb-16 px-6 md:px-8 max-w-7xl mx-auto">
    @yield('content')
</main>
    @include('partials.public.footer')
@stack('scripts')
</body>
</html>
