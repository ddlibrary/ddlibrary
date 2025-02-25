@extends('layouts.main')
@section('title')
    @lang('Add a new Resource - Step 1')
@endsection
@section('style')
<link rel="stylesheet" href="{{ asset('css/resource.css') }}">
@endsection
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
                        <button id="cropper-image-option" class="btn-option">@lang('Crop your image')</button>
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
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/resource.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
@endpush
