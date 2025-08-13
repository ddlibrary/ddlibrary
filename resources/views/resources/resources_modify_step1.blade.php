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
        <form method="POST"
            action="@if ($edit) {{ route('edit1', $resource['id']) }}@else{{ route('step1') }} @endif">
            @csrf

            <div class="row">

                <div class="form-group col-6 mb-3">
                    <label for="title">
                        @lang('Title')
                    </label>
                    <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title"
                        type="text" value="{{ @$resource['title'] }}" required autofocus placeholder="@lang('Title')">
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
                    <input class="form-control{{ $errors->has('author') ? ' is-invalid' : '' }} col-md-6" id="author"
                        name="author" type="text" value="{{ $resource->authors?->pluck('name')->implode(', ') ?? '' }}"
                        aria-describedby="authorOptional"
                        onkeydown="bringMeAttr('author','{{ URL::to('resources/attributes/authors') }}')">
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
                    <input class="form-control{{ $errors->has('publisher') ? ' is-invalid' : '' }} col-md-6" id="publisher"
                        name="publisher" type="text"
                        value="{{ old('publisher') ? old('publisher') : @$resource['publisher'] }}"
                        aria-describedby="publisherOptional"
                        onkeydown="bringMeAttr('publisher','{{ URL::to('resources/attributes/publishers') }}')">
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
                        id="translator" name="translator" type="text"
                        value="{{ $resource->translators?->pluck('name')->implode(', ') ?? '' }}"
                        aria-describedby="translatorOptional"
                        onkeydown="bringMeAttr('translator','{{ URL::to('resources/attributes/translators') }}')">
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
                        name="language" id="language" required>
                        <option value="">...</option>
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <option value="{{ $localeCode }}"
                                {{ @$resource['language'] == $localeCode ? 'selected' : '' }}>{{ $properties['native'] }}
                            </option>
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
                            <button type="button" class="btn btn-primary" id="open-file-manager">@lang('Select or upload your image')
                            </button>

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
                        <textarea class="form-control{{ $errors->has('abstract') ? ' is-invalid' : '' }}" name="abstract"
                            style="height: 200px">
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
                        <button id="select-image-btn" class="btn btn-primary"
                            style="display: none;">@lang('Select image')</button>
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
                                    <select class="form-control w-100 box-sizing" name="taxonomy_term_data_id"
                                        id="license">
                                        <option value="">...</option>
                                        @foreach ($creativeCommons as $creativeCommon)
                                            <option value="{{ $creativeCommon->id }}">{{ $creativeCommon->name }}
                                            </option>
                                        @endforeach
                                        <input type="hidden" name="language" value="{{ config('app.locale') }}">
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
                                    <button type="button" id="download-cropped-image" class="btn btn-primary mt-2"
                                        style="display: none;">@lang('Download Cropped Image')</button>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"
        integrity="sha384-P65gU1u4/dZpqRQ0AVqW+DHPwXmNAR84Qk31dC95hjk0WatF1GsVF1zRm/0uB+o0" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css"
        integrity="sha384-1arqhTHsGLPVJdhZo8SAycbI+y5k+G7khi5bTZ4BxHJIpCfvWoeSDgXEXXRxB/9G" crossorigin="anonymous">
@endsection
