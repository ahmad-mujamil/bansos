<!-- Vendor Scripts Start -->
<script src="{{ asset('/js/vendor/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('/js/vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/js/vendor/OverlayScrollbars.min.js') }}"></script>
<script src="{{ asset('/js/vendor/autoComplete.min.js') }}"></script>
<script src="{{ asset('/js/vendor/clamp.min.js') }}"></script>
@stack('js_vendor')
<!-- Vendor Scripts End -->

<!-- Livewire Scripts -->

<!-- Template Base Scripts Start -->
<script src="{{ asset('/js/base/helpers.js') }}"></script>
<script src="{{ asset('/js/base/globals.js') }}"></script>
<script src="{{ asset('/js/base/nav.js') }}"></script>
<script src="{{ asset('/js/base/search.js') }}"></script>
<script src="{{ asset('/js/base/settings.js') }}"></script>
<!-- Template Base Scripts End -->
<!-- Page Specific Scripts Start -->
<script src="{{ asset('/js/common.js') }}"></script>
<script src="{{ asset('/js/scripts.js') }}"></script>
@stack('js_page')
<!-- Page Specific Scripts End -->
