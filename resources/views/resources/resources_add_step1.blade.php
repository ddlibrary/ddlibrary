@extends('layouts.main')
@section('title')
    @lang('Add a new Resource - Step 1')
@endsection
@section('content')
    <section class="resource-form">
        <header>
            <h1>@lang('Add a new Resource - Step 1')</h1>
        </header>
        <div class="">
            @include('layouts.messages')
            <form method="POST" action="{{ route('step1') }}"  enctype="multipart/form-data">
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
                        <div class="mt-1">

                            <input class="form-control box-sizing w-100 {{ $errors->has('image') ? ' is-invalid' : '' }}"
                                id="image" name="image" type="file"
                                value="{{ @$resource['image'] }}" required autofocus>
                        </div>
                        @if ($errors->has('image'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('image') }}</strong>
                            </span><br>
                        @endif
                    </div>
                </div>
                <div class="display-flex py-2 gap-5 mt-5 flex-column">

                    {{-- Abstract --}}
                    <div class="flex-1">
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
                </div>

                {{-- Submit --}}
                <div class="left-side mt-1">
                    <input class="form-control normalButton" type="submit"
                        value="@lang('Next')  {{ en('Next') }}">
                </div>
            </form>
        </div>
    </section>
@endsection
