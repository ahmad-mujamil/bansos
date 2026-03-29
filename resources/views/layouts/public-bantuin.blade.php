<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Berita') | Bantu-In</title>
    <link rel="icon" href="{{ asset('img/logo-only.png') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary-container": "#005353",
                        "on-primary-container": "#84c5c4",
                        "on-surface-variant": "#3f4948",
                        "surface-container-low": "#f2f4f4",
                        "surface-container-lowest": "#ffffff",
                        "surface-dim": "#d8dada",
                        "surface-container": "#eceeee",
                        "surface-container-high": "#e6e8e8",
                        "outline-variant": "#bfc8c8",
                        "on-surface": "#191c1d",
                        "surface": "#f8fafa",
                        "background": "#f8fafa",
                        "primary": "#003a3a",
                    },
                    fontFamily: {
                        "public-sans": ["Public Sans", "sans-serif"],
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Public Sans', sans-serif; }
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
