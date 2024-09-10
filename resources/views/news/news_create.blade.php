@extends('layouts.main')
@section('content')
<section class="ddl-forms">
    <header>
        <h1>{{ __('Add News') }}</h1>
    </header>
    <div class="content-body">
        @include('layouts.messages')
        <form method="POST" action="{{ route('add_news') }}">
        @csrf
        <div class="form-item">
            <label for="title"> 
                <strong>{{ __('Title') }}</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="title" name="title" size="40" type="text" value="{{ old('title') }}" required autofocus>
            @if ($errors->has('title'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('title') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="language"> 
                <strong>{{ __('Language') }}</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <select class="form-control{{ $errors->has('language') ? ' is-invalid' : '' }}" name="language" id="language" required>
                <option value="">- {{ __('None') }} -</option>
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <option value="{{ $localeCode }}" {{ old('language') == $localeCode ? "selected" : "" }}>{{ $properties['native'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-item">
            <label for="summary"> 
                <strong>{{ __('Summary') }}</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <div id="editor">
                <textarea class="form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" name="summary" style="height: 200px">{{ old('summary') }}</textarea>
            </div>
            @if ($errors->has('summary'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('summary') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="body"> 
                <strong>{{ __('Body') }}</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <div id="editor">
                <textarea class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" name="body" style="height: 200px">{{ old('body') }}</textarea>
            </div>
            @if ($errors->has('body'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('body') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="published"> 
                <strong>{{ __('Published?') }}</strong>
            </label>
            <label>
                <input type="radio" name="published" value="1"> {{ __('Yes') }}
            </label>
            <label>
                <input type="radio" name="published" checked value="0"> {{ __('No') }}
            </label>
        </div>
        <div class="left-side">
            <input class="form-control normalButton" type="submit" value="{{ __('Submit') }}">
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
        CKEDITOR.replace( 'summary', options );
    </script>

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
        CKEDITOR.replace( 'body', options );
    </script>
@endpush
@endsection