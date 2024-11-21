@extends('layouts.main')
@section('title')
    @lang('Add a new Resource - Step 1')
@endsection
@push('styles')
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            width: 100%;
            max-width: 1600px;
            margin: 2% auto;
            background-color: #f8f9fa;
            border-radius: 10px;
            min-height: 400px;
            max-height: 80vh;
            overflow-y: scroll;
        }

        .modal-header {
            padding: 15px 20px;
            background-color: #e9ecef;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            display: flex;
            flex-direction: row;
        }

        .image-manager-options {
            flex: 1;
            padding: 20px;
            min-height: 55vh;
            background-color: #e9ecef;
            border-right: 1px solid #dee2e6;
        }

        .image-manager-content {
            flex: 4;
            padding: 20px;
            overflow-y: auto;
        }

        .btn-option {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            text-align: left;
            border: none;
            background-color: transparent;
            cursor: pointer;
        }

        .btn-option.active {
            font-weight: bold;
            background-color: #007bff;
            color: white;
        }

        #select-image-btn {
            margin-top: 20px;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        #selected-image-preview {
            margin-bottom: 20px;
        }

        #preview-image {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
            display: block;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .image-item {
            display: flex;
            flex-direction: column;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .image-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .image-item.selected {
            border-color: #007bff;
            box-shadow: 0 0 0 2px #007bff;
        }

        .image-container {
            position: relative;
            width: 100%;
            overflow: hidden;
        }

        .resource-cover {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            max-width: 100%;
            max-height: 100%;
        }

        .image-name {
            padding: 10px;
            text-align: center;
            font-size: 0.9em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pagination-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .image-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            .image-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            }

            .image-manager-options {
                min-height: 60px;

            }
        }

        @media (max-width: 768px) {
            .modal-body {
                flex-direction: column;
            }

            .image-manager-options {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #dee2e6;
                min-height: 60px;
            }

            .image-manager-options button {
                display: inline-block;
                width: calc(50% - 10px);
                margin-right: 10px;
            }

            .image-manager-options button:last-child {
                margin-right: 0;
            }
        }

        #loading-message {
            text-align: center;
            font-size: 1.2em;
            color: #007bff;
            margin: 20px 0;
        }

        #preview {
            max-width: 100%;
            max-height: 200px;
            display: none;
            margin-top: 20px;
        }

        #dimensions {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
@endpush
@section('content')
    <section class="resource-form">
        <header>
            <h1>@lang('Add a new Resource - Step 1')</h1>
        </header>
        <div class="">
            @include('layouts.messages')
            <form method="POST" action="{{ route('step1') }}" enctype="multipart/form-data">
                @csrf
                <div class="display-flex py-2 gap-5 flex-column">
                    {{-- Title --}}
                    <div class="flex-1">
                        <label for="title">
                            <strong>@lang('Title') {{ en('Title') }}</strong>
                            <span class="form-required" title="This field is required.">*</span>
                        </label>
                        <div class="mt-1">
                            <input class="form-control box-sizing w-100 {{ $errors->has('title') ? ' is-invalid' : '' }}"
                                id="title" name="title" size="40" type="text"
                                value="{{ @$resource['title'] }}" required autofocus>
                        </div>
                        @if ($errors->has('title'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span><br>
                        @endif
                    </div>

                    {{-- Author --}}
                    <div class="flex-1">
                        <label for="author">
                            <strong>@lang('Author') {{ en('Author') }}</strong>
                        </label>
                        <div class="mt-1">
                            <input class="form-control box-sizing w-100 {{ $errors->has('author') ? ' is-invalid' : '' }}"
                                id="author" name="author" size="40" type="text"
                                value="{{ @$resource['author'] }}"
                                onkeydown="javascript:bringMeAttr('author','{{ URL::to('resources/attributes/authors') }}')">
                        </div>
                        @if ($errors->has('author'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('author') }}</strong>
                            </span><br>
                        @endif
                    </div>
                </div>
                <div class="display-flex py-2 gap-5 mt-5 flex-column">
                    {{-- Publisher --}}
                    <div class="flex-1">
                        <label for="publisher">
                            <strong>@lang('Publisher') {{ en('Publisher') }}</strong>
                        </label>
                        <div class="mt-1">
                            <input
                                class="form-control box-sizing w-100 {{ $errors->has('publisher') ? ' is-invalid' : '' }}"
                                id="publisher" name="publisher" size="40" type="text"
                                value="{{ old('publisher') ? old('publisher') : @$resource['publisher'] }}"
                                onkeydown="javascript:bringMeAttr('publisher','{{ URL::to('resources/attributes/publishers') }}')">
                        </div>
                        @if ($errors->has('publisher'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('publisher') }}</strong>
                            </span><br>
                        @endif
                    </div>

                    {{-- Translator --}}
                    <div class="flex-1">
                        <label for="translator">
                            <strong>@lang('Translator') {{ en('Translator') }}</strong>
                        </label>
                        <div class="mt-1">
                            <input
                                class="form-control box-sizing w-100 {{ $errors->has('translator') ? ' is-invalid' : '' }}"
                                id="translator" name="translator" size="40" type="text"
                                value="{{ @$resource['translator'] }}"
                                onkeydown="javascript:bringMeAttr('translator','{{ URL::to('resources/attributes/translators') }}')">
                        </div>
                        @if ($errors->has('translator'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('translator') }}</strong>
                            </span><br>
                        @endif
                    </div>
                </div>
                <div class="display-flex py-2 gap-5 mt-5 flex-column">
                    {{-- Language --}}
                    <div class="flex-1">
                        <label for="language">
                            <strong>@lang('Language') {{ en('Language') }}</strong>
                            <span class="form-required" title="This field is required.">*</span>
                        </label>
                        <div class="mt-1">
                            <select
                                class="form-control box-sizing w-100 {{ $errors->has('language') ? ' is-invalid' : '' }}"
                                name="language" id="language" required>
                                <option value="">- @lang('None') -</option>
                                @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    <option value="{{ $localeCode }}"
                                        {{ @$resource['language'] == $localeCode ? 'selected' : '' }}>
                                        {{ $properties['native'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Image --}}
                    <div class="flex-1">
                        <label for="image">
                            <strong>@lang('Image') {{ en('Image') }}</strong>
                            <span class="form-required" title="This field is required.">*</span>
                        </label>
                        <div class="mt-1 display-flex align-items-center">
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
                </div>

                {{-- Selected Image Preview --}}
                <div id="selected-image-preview" class="flex-1 mt-1 border-radius-5" style="display: none;">
                    <img id="preview-image" class="border-radius-5" src="" alt="Selected Image">
                </div>

                {{-- Abstract --}}
                <div class="flex-1 mt-2">
                    <label for="abstract">
                        <strong>@lang('Abstract') {{ en('Abstract') }}</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <div id="editor">
                        <textarea class="form-control box-sizing w-100 {{ $errors->has('abstract') ? ' is-invalid' : '' }}" name="abstract"
                            style="height: 200px">{{ @$resource['abstract'] }}</textarea>
                    </div>
                    @if ($errors->has('abstract'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('abstract') }}</strong>
                        </span><br>
                    @endif
                </div>

                {{-- Submit --}}
                <div class="left-side mt-1">
                    <input class="form-control normalButton" type="submit"
                        value="@lang('Next')  {{ en('Next') }}">
                </div>
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
                    </div>
                    <div class="image-manager-content">
                        <!-- Select Image Content -->
                        <div id="select-image-content">
                            <h3> @lang('Select image from file manager') <span id="result"></span></h3>
                            <div class="display-flex gap-5">
                                <div class="form-item flex-1">
                                    <label for="subject_areas">
                                        <strong>@lang('Subject Areas') {{ en('Subject Areas') }}</strong>
                                        <span class="form-required" title="This field is required.">*</span>
                                    </label>
                                    <select
                                        class="form-control w-100 box-sizing {{ $errors->has('subject_areas') ? ' is-invalid' : '' }}"
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
                                <div class="flex-1">
                                    <label for="search-input" class="display-inline-block mb-1">
                                        <strong>@lang('Search by image name')</strong>
                                        <span class="form-required" title="This field is required.">*</span>
                                    </label>
                                    <input type="text" id="search-input" placeholder="@lang('Search by image name')"
                                        class="form-control w-100 box-sizing">
                                </div>
                                <div class="flex-2">

                                </div>
                            </div>
                            <div id="file-list" class="w-100">
                                <!-- File items will be populated dynamically -->
                            </div>
                            <div id="loading-message" style="display: none;">Loading, please wait...</div>
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
                                            <option>...</option>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function openModal(e) {
            e.preventDefault();
        }
        $(document).ready(function() {
            const fileManagerModal = $('#file-manager-modal');
            const selectImageBtn = $('#select-image-btn');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const selectImageContent = $('#select-image-content');
            const uploadImageContent = $('#upload-image-content');
            const selectImageOption = $('#select-image-option');
            const uploadImageOption = $('#upload-image-option');

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
                selectImageContent.show();
                uploadImageContent.hide();
            });

            uploadImageOption.click(function() {
                $(this).addClass('active');
                selectImageOption.removeClass('active');
                uploadImageContent.show();
                selectImageContent.hide();
            });

            $('#open-file-manager').click(function(e) {
                e.preventDefault();
                fileManagerModal.show();
                selectImageOption.click(); // Default to select image option
                searchImages()
            });

            function searchImages(url = '{{ route('search.images') }}') {
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
                            language: "{{config('app.locale') }}"
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
                    url: '{{ route('upload.image') }}',
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

        });
    </script>
@endpush
