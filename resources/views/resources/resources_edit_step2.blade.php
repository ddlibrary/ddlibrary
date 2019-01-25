@extends('layouts.main')
@section('title')
@lang('Add a new Resource - Step 2')
@endsection
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')

<section class="ddl-forms">
    <header>
        <h1>@lang('Add a new Resource - Step 2')</h1>
    </header>
    <div class="content-body">
        @include('layouts.messages')
        <form method="POST" action="{{ route('edit2', $resource["id"]) }}" enctype="multipart/form-data">
        @csrf
        <div class="form-item">
            <label for="attachments"> 
                <strong>@lang('Attachments')</strong>
            </label>
        <input class="form-control{{ $errors->has('attachments') ? ' is-invalid' : '' }}" id="attachments" name="attachments[]" size="40" type="file">
            <button type='button' class="add_more">@lang('Add More Files')</button>
            @if(isset($resource['attc']))
            <?php  $i = 0; ?>
            @foreach($resource['attc'] as $item)
                <br><a href="{{ asset('/storage/attachments/'.$item['file_name']) }}">{{ $item['file_name'] }}</a>
                @if (isAdmin())
                <a style="color:red;" href="{{ route('delete-file', ['fileName' => $item['file_name'], 'resourceId' => $resource['id']]) }}">Delete</a>
                @endif
                <?php  $i++; ?>
            @endforeach
            @endif

            @if ($errors->has('attachments'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('attachments') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="subject_areas"> 
                <strong>@lang('Subject Areas')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <select class="form-control{{ $errors->has('subject_areas') ? ' is-invalid' : '' }}" id="subject_areas" name="subject_areas[]" required  multiple="multiple">
                @foreach ($subjects AS $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>

            @if ($errors->has('subject_areas'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('subject_areas') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
                <label for="keywords"> 
                    <strong>@lang('Keywords')</strong>
                </label>
                <input class="form-control{{ $errors->has('keywords') ? ' is-invalid' : '' }}" id="keywords" name="keywords" size="40" type="text" value="{{ isset($resourceKeywords)?$resourceKeywords:"" }}" onkeydown="javascript:bringMeAttr('keywords','{{ URL::to('resources/attributes/keywords') }}')">
                @if ($errors->has('keywords'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('keywords') }}</strong>
                    </span><br>
                @endif
            </div>
        <div class="form-item">
            <label for="learning_resources_types"> 
                <strong>@lang('Learning Resources Types')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <select class="form-control{{ $errors->has('learning_resources_types') ? ' is-invalid' : '' }}" id="learning_resources_types" name="learning_resources_types[]" required  multiple="multiple">
                @foreach ($learningResourceTypes AS $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>

            @if ($errors->has('learning_resources_types'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('learning_resources_types') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="educational_use"> 
                <strong>@lang('Educational Use')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <select class="form-control{{ $errors->has('educational_use') ? ' is-invalid' : '' }}" id="educational_use" name="educational_use[]" required  multiple="multiple">
                @foreach ($educationalUse AS $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>

            @if ($errors->has('educational_use'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('educational_use') }}</strong>
                </span><br>
            @endif
        </div>

        <div class="form-item">
            <label for="resource_levels"> 
                <strong>@lang('Resource Levels')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <ul>
            <?php
                if(!isset($resource['level'])){
                    $resource['level'] = [];
                }
            ?>
            @foreach($levels AS $level)
                @if($level->parent == 0)
                    <li><input type="checkbox" name="level[]" {{ in_array($level->id, $resourceLevels) ? "checked" : ""}} value="{{ $level->id }}" onchange="fnTest(this,'subLevel{{$level->id}}');">{{ $level->name }}
                        <?php $levelParent = $levels->where('parent', $level->id);?>
                        @if(count($levelParent) > 0)
                            <i class="fas fa-plus fa-xs" onclick="javascript:showHide(this,'subLevel{{$level->id}}')"></i>
                        @endif
                    @if(count($levelParent) > 0)
                        <ul id="subLevel{{$level->id}}" class="subItem" style="display:none;">
                            @foreach($levelParent as $item)
                                <li><input type="checkbox" name="level[]" onchange="fnTest(this,'subLevel{{$item->id}}');" {{ in_array($item->id, $resourceLevels) ?"checked":""}} class="child" value="{{ $item->id }}">{{ $item->name }}
                            
                                <?php $levelItemParent = $levels->where('parent', $item->id);?>
                                @if(count($levelItemParent) > 0)
                                    <i class="fas fa-plus fa-xs" onclick="javascript:showHide(this,'subLevel{{$item->id}}')"></i>
                                @endif
                                @if(count($levelItemParent) > 0)
                                    <ul id="subLevel{{$item->id}}" class="subItem" style="display:none;">
                                        @foreach($levelItemParent as $itemLevel)
                                            <li><input type="checkbox" name="level[]" {{ in_array($itemLevel->id, $resourceLevels) ?"checked":""}} class="child" value="{{ $itemLevel->id }}">{{ $itemLevel->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
                @endif
            @endforeach
            </ul>
        </div>
        <div style="display:flex;">
            <input style="margin-{{ (app()->getLocale()=="en")?"right":"left" }}: 10px;" class="form-control normalButton" type="button" value="@lang('Previous')" onclick="location.href='{{ route('edit1', $resource["id"]) }}'">
            <input class="form-control normalButton" type="submit" value="@lang('Next')" onclick="this.style.display='none';document.getElementById('wait').style.display='block'" ondblclick="this.style.display='display';document.getElementById('wait').style.display='block'">
            <input type="button" class="form-control" id="wait" value="@lang('Please wait..')" style="color:red;display:none" disabled>
        </div>
        </form>
    </div>
</section>
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#subject_areas').select2();
        $('#learning_resources_types').select2();
        $('#educational_use').select2();

        $('#subject_areas').val({{ $resourceSubjectAreas }});
        $('#learning_resources_types').val({{ $resourceLearningResourceTypes }});
        $('#educational_use').val({{ $EditEducationalUse }});

        $('#subject_areas').trigger('change'); // Notify any JS components that the value changed
        $('#learning_resources_types').trigger('change'); // Notify any JS components that the value changed
        $('#educational_use').trigger('change'); // Notify any JS components that the value changed
    });
</script>
@endpush
@endsection