<!-- Favicon Tags Start -->
<link rel="icon" type="image/png" href="{{ asset('img/favicon.ico') }}" sizes="196x196" />
<meta name="application-name" content="memofit" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">

<!-- Favicon Tags End -->
<!-- Font Tags Start -->
<link rel="preconnect" href="https://fonts.gstatic.com" />
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('/font/CS-Interface/style.css') }}" />
<!-- Font Tags End -->
<!-- Vendor Styles Start -->
<link rel="stylesheet" href="{{ asset('/css/vendor/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('/css/vendor/OverlayScrollbars.min.css') }}" />
<!-- Vendor Styles End -->

<!-- Livewire Styles -->
@livewireStyles

<!-- Template Base Styles Start -->
<link rel="stylesheet" href="{{ asset('/css/styles.css') }}" />
<link rel="stylesheet" href="{{ asset('/css/main.css') }}" />
<!-- Template Base Styles End -->

<script src="{{ asset('/icon/acorn-icons.js') }}"></script>
<script src="{{ asset('/icon/acorn-icons-interface.js') }}"></script>
<script src="{{ asset('/icon/acorn-icons-commerce.js') }}"></script>
<script src="{{ asset('/icon/acorn-icons-medical.js') }}"></script>

<script src="{{ asset('/js/base/loader.js') }}"></script>
@stack('css')
