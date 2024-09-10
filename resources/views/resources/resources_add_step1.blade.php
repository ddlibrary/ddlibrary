@extends('layouts.main')
@section('title')
{{ __('Add a new Resource - Step 1') }}
@endsection
@section('content')
<section class="ddl-forms">
    <header>
        <h1>{{ __('Add a new Resource - Step 1') }}</h1>
    </header>
    <div class="content-body">
        @include('layouts.messages')
        <form method="POST" action="{{ route('step1') }}">
        @csrf
        <div class="form-item">
            <label for="title"> 
                <strong>{{ __('Title') }} {{ en('Title') }}</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" size="40" type="text" value="{{ @$resource['title'] }}" required autofocus>
            @if ($errors->has('title'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('title') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="author"> 
                <strong>{{ __('Author') }}  {{ en('Author') }}</strong>
            </label>
            <input class="form-control{{ $errors->has('author') ? ' is-invalid' : '' }}" id="author" name="author" size="40" type="text" value="{{ @$resource['author'] }}" onkeydown="javascript:bringMeAttr('author','{{ URL::to('resources/attributes/authors') }}')">
            @if ($errors->has('author'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('author') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="publisher"> 
            <strong>{{ __('Publisher') }} {{ en('Publisher') }}</strong>
            </label>
            <input class="form-control{{ $errors->has('publisher') ? ' is-invalid' : '' }}" id="publisher" name="publisher" size="40" type="text" value="{{ old('publisher')?old('publisher'):@$resource['publisher'] }}" onkeydown="javascript:bringMeAttr('publisher','{{ URL::to('resources/attributes/publishers') }}')">
            @if ($errors->has('publisher'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('publisher') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="translator"> 
                <strong>{{ __('Translator') }}  {{ en('Translator') }}</strong>
            </label>
            <input class="form-control{{ $errors->has('translator') ? ' is-invalid' : '' }}" id="translator" name="translator" size="40" type="text" value="{{ @$resource['translator'] }}" onkeydown="javascript:bringMeAttr('translator','{{ URL::to('resources/attributes/translators') }}')">
            @if ($errors->has('translator'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('translator') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="language"> 
                <strong>{{ __('Language') }}  {{ en('Language') }}</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <select class="form-control{{ $errors->has('language') ? ' is-invalid' : '' }}" name="language" id="language" required>
                <option value="">- {{ __('None') }} -</option>
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <option value="{{ $localeCode }}" {{ @$resource['language'] == $localeCode ? "selected" : "" }}>{{ $properties['native'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-item">
            <label for="abstract"> 
                <strong>{{ __('Abstract') }}  {{ en('Abstract') }}</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <div id="editor">
                <textarea class="form-control{{ $errors->has('abstract') ? ' is-invalid' : '' }}" name="abstract" style="height: 200px">{{ @$resource['abstract'] }}</textarea>
            </div>
            @if ($errors->has('abstract'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('abstract') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="left-side">
            <input class="form-control normalButton" type="submit" value="{{ __('Next') }}  {{ en('Next') }}">
        </div>
        </form>
    </div>
</section>
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
