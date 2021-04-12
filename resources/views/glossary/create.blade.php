@extends('layouts.main')
@section('title')
    @lang('Create new glossary item') - @lang('Darakht-e Danesh Library')
@endsection
@section('description')
    @lang('The Darakht-e Danesh Online Library for Educators is a repository of open educational resources for teachers, teacher trainers, school administrators, literacy workers and others involved in furthering education in Afghanistan.')
@endsection

@section('content')
    <section class="ddl-forms">
        <header><h1>@lang('Add a new glossary item')</h1></header>
        <div class="content-body">
            @include('layouts.messages')
            <div style="color: #777; font-size: 14px;">@lang('One of the three fields (English, Farsi or Pashto) is required.')</div><br>
            <form method="POST" action="{{ route('glossary_store') }}">
                @csrf
                <div class="form-item">
                    <label for="english"><strong>@lang('English')</strong></label>
                    <textarea class="form-control" rows="4" cols="100" name="english" id="english"></textarea>
                </div>
                <div class="form-item">
                    <label for="farsi"><strong>@lang('Farsi')</strong></label>
                    <textarea class="form-control" rows="4" cols="100" name="farsi" id="farsi"></textarea>
                </div>
                <div class="form-item">
                    <label for="pashto"><strong>@lang('Pashto')</strong></label>
                    <textarea class="form-control" rows="4" cols="100" name="pashto" id="pashto"></textarea>
                </div>
                <div class="form-item">
                    <select name="subject" class="form-control">
                        <label for="subject"><strong>@lang('Subject')</strong> <span class="form-required" title="This field is required.">*</span></label>
                        @foreach($glossary_subjects as $id => $subject)
                            <option value="{{ $id }}" >{{ $subject }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="left-side">
                    <input class="form-control submit-button btn btn-primary" type="submit" value="@lang('Submit')">
                </div>
            </form>
        </div>
    </section>
@endsection
