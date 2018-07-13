@extends('layouts.main')

@section('content')
<section class="ddl-forms">
    <header>
        <h1>Add a new Resource - Step 3</h1>
    </header>
    <div class="content-body">
        <form method="POST" action="{{ route('step3') }}">
        @csrf
        <div class="form-item">
            <label for="translation_rights"> 
                <strong>1. Translation Rights</strong>
            </label>
            <input type="checkbox" value="1" name="translation_rights"> 
            I am providing a new translation. I have selected the license that appears on the original resource.
            If this is not translation, please skip this question and go to #2.
            @if ($errors->has('translation_rights'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('translation_rights') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="educational_resource"> 
                <strong>2. Educational Resource</strong>
            </label>
            <input type="checkbox" value="1" name="educational_resource"> 
            I am submitting a resource to DDL that is already published. I have selected the license that is on the original resource.
            If you are the original author, please skip this question and go to #3.
            @if ($errors->has('educational_resource'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('educational_resource') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="copyright_holder"> 
                <strong>License/Copyright Holder</strong>
            </label>
            <input class="form-control{{ $errors->has('copyright_holder') ? ' is-invalid' : '' }}" id="copyright_holder" name="copyright_holder" size="40" maxlength="40" type="text" value="{{ @$resource['copyright_holder'] }}">
            <div class="description">
                Please enter the name of the person or organization owning or managing rights over the resource.
            </div>
            @if ($errors->has('copyright_holder'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('copyright_holder') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <h3>Select one of these</h3>
            <label for="creative_commons"> 
                <strong>If there is no Creative Commons License on the resource, select one of these</strong>
            </label>
            @foreach($creativeCommons AS $cc)
            <input type="radio" value="{{ $cc->tid }}" name="creative_commons">{{ $cc->name }}<br>
            @endforeach
            <div class="description">
                    Unsure of which option to select? Click here for guidance on licensing this resource.
            </div>
        </div>
        <div class="form-item">
            <label for="creative_commons_other"> 
                <strong>If there is no Creative Commons License on the resource, select one these:</strong>
            </label>
            @foreach($creativeCommonsOther AS $other)
            <input type="radio" value="{{ $other->tid }}" name="creative_commons_other">{{ $other->name }}<br>
            @endforeach
        </div>
        <div style="display:flex;">
            <input style="margin-right: 10px;" class="form-control normalButton" type="button" value="Previous" onclick="location.href='{{ URL::to('resources/add/step2') }}'">
            <input class="form-control normalButton" type="submit" value="Submit">
        </div>
        </form>
    </div>
</section>
@endsection