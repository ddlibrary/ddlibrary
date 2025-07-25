@extends('layouts.main')
@section('title')
    @lang('Create or edit a resource - step 1')
@endsection
@section('style')
<link rel="stylesheet" href="{{ asset('css/resource.css') }}">
@endsection
@section('content')
    <div class="container mt-3">
        <h3>@lang('Create or edit a resource - step 1 of 3')</h3>
        <hr>
        <form method="POST" action="@if($edit){{ route('edit1', $resource['id']) }}@else{{ route('step1') }}@endif">
            @csrf
            <div class="row">

                <div class="form-group col-6 mb-3">
                    <label for="title">
                        @lang('Title')
                    </label>
                    <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                           id="title"
                           name="title"
                           type="text"
                           value="{{ @$resource['title'] }}"
                           required
                           autofocus
                           placeholder="@lang('Title')"
                    >
                    @if ($errors->has('title'))
                        <span class="invalid-feedback">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group ui-widget col-6 mb-3">
                    <label for="author">
                        @lang('Author')
                    </label>
                    <input class="form-control{{ $errors->has('author') ? ' is-invalid' : '' }} col-md-6"
                           id="author"
                           name="author"
                           type="text"
                           placeholder="@lang('Author')"
                           value="{{ @$resource['author'] }}"
                           aria-describedby="authorOptional"
                           onkeydown="bringMeAttr('author','{{ URL::to('resources/attributes/authors') }}')"
                    >
                    <small id="authorOptional" class="form-text text-muted">
                        @lang('Optional')
                    </small>
                    @if ($errors->has('author'))
                        <span class="invalid-feedback">
                        <strong>{{ $errors->first('author') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group ui-widget col-6 mb-3">
                    <label for="publisher">
                        @lang('Publisher')
                    </label>
                    <input class="form-control{{ $errors->has('publisher') ? ' is-invalid' : '' }} col-md-6"
                           id="publisher"
                           name="publisher"
                           type="text"
                           placeholder="@lang('Publisher')"
                           value="{{ old('publisher')?old('publisher'):@$resource['publisher'] }}"
                           aria-describedby="publisherOptional"
                           onkeydown="bringMeAttr('publisher','{{ URL::to('resources/attributes/publishers') }}')"
                    >
                    <small id="publisherOptional" class="form-text text-muted">
                        @lang('Optional')
                    </small>
                    @if ($errors->has('publisher'))
                        <span class="invalid-feedback">
                        <strong>{{ $errors->first('publisher') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group ui-widget col-6 mb-3">
                    <label for="translator">
                        @lang('Translator')
                    </label>
                    <input class="form-control{{ $errors->has('translator') ? ' is-invalid' : '' }} col-md-6"
                           id="translator"
                           name="translator"
                           type="text"
                           placeholder="@lang('Translator')"
                           value="{{ @$resource['translator'] }}"
                           aria-describedby="translatorOptional"
                           onkeydown="bringMeAttr('translator','{{ URL::to('resources/attributes/translators') }}')"
                    >
                    <small id="translatorOptional" class="form-text text-muted">
                        @lang('Optional')
                    </small>
                    @if ($errors->has('translator'))
                        <span class="invalid-feedback">
                        <strong>{{ $errors->first('translator') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group col-6 mb-3">
                    <label for="language">
                        @lang('Language')
                    </label>
                    <select class="form-control{{ $errors->has('language') ? ' is-invalid' : '' }} col-md-6"
                            name="language"
                            id="language"
                            required
                    >
                        <option value="">...</option>
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <option value="{{ $localeCode }}" {{ @$resource['language'] == $localeCode ? "selected" : "" }}>{{ $properties['native'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-6 mb-3">
                    <label for="image">
                        <strong>@lang('Image')</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <div class="">
                        <div class="flex-1">
                            <button type="button" class="btn btn-primary" id="open-file-manager">@lang('Select or upload your image') </button>

                            <input type="hidden" id="file_uuid" name="image" required>
                        </div>
                    </div>
                    @if ($errors->has('image'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('image') }}</strong>
                        </span><br>
                    @endif
                </div>
                {{-- Selected Image Preview --}}
                <div id="selected-image-preview" class="flex-1 mt-1 border-radius-5" style="display: none;">
                    <img id="preview-image" class="border-radius-5" src="" alt="Selected Image">
                </div>
                <div class="form-group mb-3">
                    <label for="abstract">
                        @lang('Abstract')
                    </label>
                    <div id="editor" class="mb-2">
                    <textarea class="form-control{{ $errors->has('abstract') ? ' is-invalid' : '' }}"
                              name="abstract"
                              style="height: 200px"
                    >
                        {{ @$resource['abstract'] }}
                    </textarea>
                    </div>
                    @if ($errors->has('abstract'))
                        <span class="invalid-feedback">
                        <strong>{{ $errors->first('abstract') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            @include('layouts.messages')
            <input class="btn btn-primary" type="submit" value="@lang('Next')">
        </form>
    </div>
     <!-- File Manager Modal -->
     <div class="modal" id="file-manager-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>@lang('Image manager')</h2>
                <span class="close" id="close-file-manager-modal">&times;</span>
            </div>
            <div class="modal-body">
                <div class="image-manager-options">
                    <button id="select-image-option" class="btn-option active">@lang('Select image')</button>
                    <button id="upload-image-option" class="btn-option">@lang('Upload image')</button>
                    <button id="cropper-image-option" class="btn-option">@lang('Crop your image')</button>
                </div>
                <div class="image-manager-content">
                    <!-- Select Image Content -->
                    <div id="select-image-content">
                        <h3> @lang('Select image from file manager') <span id="result"></span></h3>
                        <div class="d-flex gap-5 mb-4">
                            <div class="flex-fill">
                                <label for="subject_areas" class="mb-2">
                                    <strong>@lang('Subject Areas') {{ en('Subject Areas') }}</strong>
                                    <span class="form-required" title="This field is required.">*</span>
                                </label>
                                <select
                                    class="form-control box-sizing {{ $errors->has('subject_areas') ? ' is-invalid' : '' }}"
                                    id="subject_areas" name="subject_areas" required>
                                    <option value="">...</option>
                                    @foreach ($subjects as $item)
                                        @if ($item->parent == 0)
                                            <optgroup label="{{ $item->name }}">
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                <?php if (isset($subjects) && isset($item)) {
                                                    $parentItems = $subjects->where('parent', $item->id);
                                                } ?>
                                                @foreach ($parentItems as $pitem)
                                                    <option value="{{ $pitem->id }}">
                                                        {{ $pitem->name . termEn($pitem->id) }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach
                                </select>

                                @if ($errors->has('subject_areas'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('subject_areas') }}</strong>
                                    </span><br>
                                @endif
                            </div>
                            <div class="flex-fill">
                                <label for="search-input" class="display-inline-block mb-2">
                                    <strong>@lang('Search by image name')</strong>
                                    <span class="form-required" title="This field is required.">*</span>
                                </label>
                                <input type="text" id="search-input" placeholder="@lang('Search by image name')"
                                    class="form-control w-100 box-sizing">
                            </div>
                        </div>
                        <div id="file-list" class="w-100">
                            <!-- File items will be populated dynamically -->
                        </div>
                        <div id="loading-message" style="display: none;">@lang('Loading, please wait')</div>
                        <button id="select-image-btn" class="btn btn-primary" style="display: none;">@lang('Select image')</button>
                    </div>
                    <!-- Upload Image Content -->
                    <div id="upload-image-content" style="display: none;">
                        <h3>@lang('Upload New Image')</h3>
                        <form id="upload-form">
                            <div class="display-flex" style="flex-direction: column">
                                <div class="flex-1 mb-2">
                                    <label for="image">
                                        <strong>@lang('Image')</strong>
                                        <span class="form-required" title="This field is required.">*</span>
                                    </label>
                                    <input type="file" id="image" name="image"
                                        class="form-control w-100 box-sizing" accept="image/*" required>
                                    <img id="preview" alt="Image Preview">
                                    <div id="dimensions"></div>
                                </div>

                                <div class="flex-1 mb-2">
                                    <label for="image-name">
                                        <strong>@lang('File name')</strong>
                                    </label>
                                    <input type="text" id="image-name" name="image_name"
                                        class="form-control w-100 box-sizing">
                                </div>
                                <div class="flex-1 mb-2">
                                    <label for="license">
                                        <strong>@lang('License')</strong>
                                    </label>
                                    <select class="form-control w-100 box-sizing" name="taxonomy_term_data_id" id="license">
                                        <option value="">...</option>
                                        @foreach ($creativeCommons as $creativeCommon)
                                            <option value="{{ $creativeCommon->id}}">{{ $creativeCommon->name }}</option>
                                        @endforeach
                                        <input type="hidden" name="language" value="{{config('app.locale') }}">
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">@lang('Upload')</button>
                            </div>
                        </form>
                    </div>
                    <div id="cropper-image-content" style="display: none;">
                        <h3>@lang('Crop your image')</h3>
                        <form id="cropper-form">
                            <div class="display-flex" style="flex-direction: column">
                                <div class="flex-1 mb-2">
                                    <label for="image">
                                        <strong>@lang('Image')</strong>
                                        <span class="form-required" title="This field is required.">*</span>
                                    </label>
                                    <input type="file" id="cropper-image" name="cropper_image"
                                        class="form-control w-100 box-sizing" accept="image/*" required>
                                        <div style="width: 60%; padding:20px;" class="text-center">

                                            <div id="cropper" style="width: 100%; height: 100%;"></div>
                                        </div>
                                    <button type="button" id="download-cropped-image" class="btn btn-primary mt-2" style="display: none;">@lang('Download Cropped Image')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

    <script src="{{ asset('js/resource.js') }}"></script>
    <script>
        // resources_add_step1 script
function openModal(e) {
    e.preventDefault();
}
$(document).ready(function() {
    const fileManagerModal = $('#file-manager-modal');
    const selectImageBtn = $('#select-image-btn');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const selectImageContent = $('#select-image-content');
    const uploadImageContent = $('#upload-image-content');
    const cropperImageContent = $('#cropper-image-content');
    const selectImageOption = $('#select-image-option');
    const uploadImageOption = $('#upload-image-option');
    const cropperImageOption = $('#cropper-image-option');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    $('#close-file-manager-modal').click(function() {
        fileManagerModal.hide();
    });

    // Image search functionality
    let searchTimeout;
    $('#search-input').on('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val();
        searchTimeout = setTimeout(() => {
            if (searchTerm.length >= 0) {
                searchImages();
            } else {
                selectImageBtn.hide();
            }
        }, 300);
    });

    $('#subject_areas').on('change', function() {
        searchImages();
    });

    selectImageOption.click(function() {
        $(this).addClass('active');
        uploadImageOption.removeClass('active');
        cropperImageOption.removeClass('active');
        selectImageContent.show();
        uploadImageContent.hide();
        cropperImageContent.hide();
    });

    uploadImageOption.click(function() {
        $(this).addClass('active');
        selectImageOption.removeClass('active');
        cropperImageOption.removeClass('active');
        uploadImageContent.show();
        selectImageContent.hide();
        cropperImageContent.hide();
    });

    cropperImageOption.click(function() {
        $(this).addClass('active');
        uploadImageOption.removeClass('active');
        selectImageOption.removeClass('active');
        cropperImageContent.show();
        uploadImageContent.hide();
        selectImageContent.hide();
    });

    $('#open-file-manager').click(function(e) {
        e.preventDefault();
        fileManagerModal.show();
        selectImageOption.click(); // Default to select image option
        searchImages()
    });

    function searchImages(url = null) {
        url = url ? url : `${baseUrl}/search-images`;
        let subjectArea = $('#subject_areas').val();
        let search = $('#search-input').val();

            // Show loading message
            $('#loading-message').show();
            $('#file-list').empty(); // Clear previous results

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    search: search,
                    subject_area_id: subjectArea,
                    language: localLanguage
                },
                success: function(response) {
                    $("#file-list").html(response);
                    initializePagination();
                    initializeImageSelection();
                },
                error: function(xhr) {
                    console.error('Error searching images:', xhr.responseText);
                    alert('Error searching images. Please try again.');
                },
                complete: function() {
                    // Hide loading message
                    $('#loading-message').hide();
                }
            });

    }

    function initializePagination() {
        $('.pagination a').on('click', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            searchImages(url);
        });
    }

    function initializeImageSelection() {
        $('.image-item').on('click', function() {
            $('.image-item').removeClass('selected');
            $(this).addClass('selected');
            selectImageBtn.show();
        });
    }

    selectImageBtn.click(function() {
        const selectedImage = $('.image-item.selected');
        if (selectedImage.length) {
            const imageUuid = selectedImage.data('uuid');
            const imageUrl = selectedImage.data('url');
            $('#file_uuid').val(imageUuid);
            displaySelectedImage(imageUrl);
            fileManagerModal.hide();
        } else {
            alert('Please select an image.');
        }
    });

    function displaySelectedImage(imageUrl) {
        // Update the image on the main page
        $('#preview-image').attr('src', imageUrl);
        $('#selected-image-preview').show();
    }

    // Handle file selection
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const fileName = file.name.split('.').slice(0, -1).join('.');
            $('#image-name').val(fileName);
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('preview');
                img.src = e.target.result;

                img.onload = function() {
                    const width = img.naturalWidth;
                    const height = img.naturalHeight;
                    $("#dimensions").html(
                        `<span class='d-inline-block progress-bar-${width == height ? 'success' :'danger'} white border-radius-5 p-1'>${width} x ${height}</span>`
                        )
                };

                img.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }

    });


    $('#upload-form').on('submit', function(e) {
        e.preventDefault();
        uploadNewImage();
        return false;
    });

    $('#upload-form button[type="submit"]').on('click', function(e) {
        e.preventDefault(); // Prevent default button click behavior
        uploadNewImage();
        return false;
    });

    function uploadNewImage() {
        const formData = new FormData($('#upload-form')[0]);
        const submitButton = $('#upload-form button[type="submit"]');

        // Clear previous error messages
        $('.error-message').remove();

        // Disable submit button and show loading text
        submitButton.prop('disabled', true).html('Uploading...');

        $.ajax({
            url: `${baseUrl}/upload-image`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#file_uuid').val(response.imageUuid);
                    displaySelectedImage(response.imageUrl);

                    // Clear the form
                    $('#upload-form')[0].reset();

                    // Display success message
                    alert('Image uploaded successfully!');

                    // Close the modal
                    $('#file-manager-modal').hide();

                    // Refresh the image list
                    searchImages();
                } else {
                    alert('Error uploading image: ' + response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = JSON.parse(xhr.responseText).errors;
                    displayErrors(errors);
                } else {
                    alert('Error uploading image. Please try again.');
                }
            },
            complete: function() {
                // Re-enable submit button and restore original text
                submitButton.prop('disabled', false).html('Upload');
            }
        });
    }

    function displayErrors(errors) {
        for (const [field, messages] of Object.entries(errors)) {
            const inputField = $(`#${field}`);
            const errorMessage = messages.join(', ');
            inputField.after(`<span class="error-message text-danger">${errorMessage}</span>`);
        }
    }

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
                        // Show the download button when the cropper is ready
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js" integrity="sha384-P65gU1u4/dZpqRQ0AVqW+DHPwXmNAR84Qk31dC95hjk0WatF1GsVF1zRm/0uB+o0" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" integrity="sha384-1arqhTHsGLPVJdhZo8SAycbI+y5k+G7khi5bTZ4BxHJIpCfvWoeSDgXEXXRxB/9G" crossorigin="anonymous">
    
@endsection
