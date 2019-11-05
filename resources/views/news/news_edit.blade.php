@extends('layouts.main')
@section('content')
<section class="ddl-forms">
    <header>
        <h1>@lang('Edit News')</h1>
    </header>
    <div class="content-body">
        @include('layouts.messages')
        <form method="POST" action="{{ route('update_news', $news->id) }}">
        @csrf
        <div class="form-item">
            <label for="title"> 
                <strong>@lang('Title')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" size="40" type="text" value="{{ $news->title }}" required autofocus>
            @if ($errors->has('title'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('title') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="language"> 
                <strong>@lang('Language')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <select class="form-control{{ $errors->has('language') ? ' is-invalid' : '' }}" name="language" id="language" required>
                <option value="">- @lang('None') -</option>
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <option value="{{ $localeCode }}" {{ $news->language == $localeCode ? "selected" : "" }}>{{ $properties['native'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-item">
            <label for="summary"> 
                <strong>@lang('Summary')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <div id="editor">
                <textarea class="form-control{{ $errors->has('summary') ? ' is-invalid' : '' }}" name="summary" style="height: 200px">{{ $news->summary }}</textarea>
            </div>
            @if ($errors->has('summary'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('summary') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="body"> 
                <strong>@lang('Body')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <div id="editor">
                <textarea class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" name="body" style="height: 200px">{{ fixImage($news->body, $news->id) }}</textarea>
            </div>
            @if ($errors->has('body'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('body') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="published"> 
                <strong>@lang('Published?')</strong>
            </label>
            <input type="radio" name="published" {{ ($news->status  == 1)?"checked":""}} value="1"> @lang('Yes')
            <input type="radio" name="published" {{ ($news->status == 0)?"checked":""}} value="0"> @lang('No')
        </div>
        <div class="left-side">
            <input class="form-control normalButton" type="submit" value="@lang('Submit')">
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
        CKEDITOR.config.contentsLangDirection = '{{ $news->language != "en"?"rtl":"ltr"}}';
        CKEDITOR.replace( 'body', options );
    </script>
@endpush
@endsection