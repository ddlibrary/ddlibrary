@extends('layouts.main')
@section('title')
@lang('Create or edit a resource - step 1')
@endsection
@section('content')
<div class="container mt-3">
    <h3>@lang('Create or edit a resource - step 1 of 3')</h3>
    <hr>
    @include('layouts.messages')
    <form method="POST" action="@if($edit){{ route('edit1', $resource['id']) }}@else{{ route('step1') }}@endif">
        @csrf
        <div class="form-group">
            <label for="title">
                @lang('Title')
            </label>
            <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }} col-md-6"
                   id="title"
                   name="title"
                   type="text"
                   value="{{ @$resource['title'] }}"
                   required
                   autofocus
            >
            @if ($errors->has('title'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group ui-widget">
            <label for="author">
                @lang('Author')
            </label>
            <input class="form-control{{ $errors->has('author') ? ' is-invalid' : '' }} col-md-6"
                   id="author"
                   name="author"
                   type="text"
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
        <div class="form-group ui-widget">
            <label for="publisher">
                @lang('Publisher')
            </label>
            <input class="form-control{{ $errors->has('publisher') ? ' is-invalid' : '' }} col-md-6"
                   id="publisher"
                   name="publisher"
                   type="text"
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
        <div class="form-group ui-widget">
            <label for="translator">
                @lang('Translator')
            </label>
            <input class="form-control{{ $errors->has('translator') ? ' is-invalid' : '' }} col-md-6"
                   id="translator"
                   name="translator"
                   type="text"
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
        <div class="form-group">
            <label for="language">
                @lang('Language')
            </label>
            <select class="form-control{{ $errors->has('language') ? ' is-invalid' : '' }} col-md-6"
                    name="language"
                    id="language"
                    required
            >
                <option value=""></option>
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <option value="{{ $localeCode }}" {{ @$resource['language'] == $localeCode ? "selected" : "" }}>{{ $properties['native'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="abstract">
                @lang('Abstract')
            </label>
            <div id="editor">
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
        <input class="btn btn-primary" type="submit" value="@lang('Next')">
    </form>
</div>
@push('scripts')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('ckeditor/config.js') }}"></script>

    <script>
        var getUrl = window.location;
        var baseUrl = <?php echo json_encode(URL::to('/')); ?>;
        var options = {
            filebrowserImageBrowseUrl: baseUrl+'/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: baseUrl+'/laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: baseUrl+'/laravel-filemanager?type=Files',
            filebrowserUploadUrl: baseUrl+'/laravel-filemanager/upload?type=Files&_token='
        };
        CKEDITOR.config.contentsLangDirection = '{{ app()->getLocale() != "en"?"rtl":"ltr"}}';
        CKEDITOR.config.filebrowserUploadMethod = 'form';
        CKEDITOR.replace( 'abstract', options );
    </script>
@endpush
@endsection
