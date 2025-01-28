@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Example DataTables Card-->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> Crop the Image
                </div>
                <div class="card-body">
                    <div >
                        <form id="cropper-form">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="image">
                                        <strong>@lang('Image')</strong>
                                        <span class="form-required" title="This field is required.">*</span>
                                    </label>
                                    <input type="file" id="cropper-image" name="cropper_image"
                                        class="form-control w-100 box-sizing" style="padding:3px" accept="image/*" required>
                                   
                                </div>
                                <div class="col-md-4">
                                   
                                    <div id="cropper" style="width: 100%; height: 100%;"></div>
                                    
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="download-cropped-image" class="btn btn-primary mt-2"
                                        style="display: none;">@lang('Download Cropped Image')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
        @push('scripts')
            <script>
                $(document).ready(function() {
                    let cropper;
                    $('#cropper-image').on('change', function(event) {
                        const files = event.target.files;
                        const done = (url) => {
                            $('#cropper-image').val('');
                            return url;
                        };
                        if (files && files.length > 0) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const image = document.createElement('img');
                                image.src = e.target.result;
                                $('#cropper').html(image);
                                if (cropper) {
                                    cropper.destroy();
                                }
                                cropper = new Cropper(image, {
                                    aspectRatio: 1, // Square crop
                                    viewMode: 1,
                                    autoCropArea: 1,
                                    responsive: true,
                                    ready() {
                                        $('#download-cropped-image').show();
                                    }
                                });
                                // Set the cropper to fill the div
                                $(image).css({
                                    width: '100%',
                                    height: '100%',
                                    objectFit: 'cover' // Ensures the image covers the div
                                });
                            };
                            reader.readAsDataURL(files[0]);
                        }
                    });

                    $('#download-cropped-image').on('click', function() {
                        if (cropper) {
                            const canvas = cropper.getCroppedCanvas();
                            canvas.toBlob((blob) => {
                                const url = URL.createObjectURL(blob);
                                const a = document.createElement('a');
                                a.href = url;
                                a.download = 'cropped-image.png'; // Default file name
                                document.body.appendChild(a);
                                a.click();
                                document.body.removeChild(a);
                            });
                        }
                    });

                });
            </script>
        @endpush
    @endsection
