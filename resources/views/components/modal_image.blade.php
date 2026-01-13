<div
    class="modal fade modal-close-out"
    id="previewImageModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header p-3">
                <h5 class="fw-bold text-primary" id="modalLabel">Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="gallery-container">
                    <div class="gallery">
                        <a href="" data-caption="Image Preview">
                            <img src="" alt="Preview" class="img-fluid" id="previewImage">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div
    class="modal fade modal-close-out"
    id="viewVideoModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalLabelVideo"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header p-3">
                <h5 class="fw-bold text-primary" id="modalLabelVideo">Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="gallery-container">
                    <div class="gallery">
                        <video class="player" poster="{{ asset('img/logo/img.png') }}" id="previewVideo">
                            <source src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4" type="video/mp4" />
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('css')
    <link rel="stylesheet" href="{{asset('css/vendor/plyr.css')}}" />
@endpush
@push('js_page')
    <script src="{{ asset('js/vendor/plyr.min.js') }}"></script>
    <script>
        _initPlayer();
        function _initPlayer() {
            document.querySelectorAll('.player').forEach((el) => {
                new Plyr(el);
            });
        }

        function previewImage(imageUrl) {
            const previewImageElement = document.getElementById('previewImage');
            previewImageElement.src = imageUrl;
            console.log(imageUrl);
            const modal = new bootstrap.Modal(document.getElementById('previewImageModal'));
            modal.show();
        }

        function previewVideo(videoUrl) {
            const previewVideoElement = document.getElementById('previewVideo');
            previewVideoElement.src = videoUrl;
            const modal = new bootstrap.Modal(document.getElementById('viewVideoModal'));
            modal.show();
        }
    </script>
@endpush
