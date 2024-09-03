@extends('layouts.main')
@section('title')
@lang('Add a new Resource - Step 1')
@endsection
@section('content')
<section class="ddl-forms">
    <header>
        <h1>@lang('Add a new Resource - Step 1')</h1>
    </header>
    <div class="content-body">
        @include('layouts.messages')
        <form method="POST" action="{{ route('step1') }}">
        @csrf
        <div class="form-item">
            <label for="title"> 
                <strong>@lang('Title') {{ en('Title') }}</strong>
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
                <strong>@lang('Author')  {{ en('Author') }}</strong>
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
            <strong>@lang('Publisher') {{ en('Publisher') }}</strong>
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
                <strong>@lang('Translator')  {{ en('Translator') }}</strong>
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
                <strong>@lang('Language')  {{ en('Language') }}</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <select class="form-control{{ $errors->has('language') ? ' is-invalid' : '' }}" name="language" id="language" required>
                <option value="">- @lang('None') -</option>
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <option value="{{ $localeCode }}" {{ @$resource['language'] == $localeCode ? "selected" : "" }}>{{ $properties['native'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-item">
            <label for="abstract"> 
                <strong>@lang('Abstract')  {{ en('Abstract') }}</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <div id="editor">
                <textarea class="form-control w-100 {{ $errors->has('abstract') ? ' is-invalid' : '' }}" name="abstract" style="height: 200px">{{ @$resource['abstract'] }}</textarea>
            </div>
            @if ($errors->has('abstract'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('abstract') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="left-side">
            <input class="form-control normalButton" type="submit" value="@lang('Next')  {{ en('Next') }}">
        </div>
        </form>
    </div>
</section>

@endsection
