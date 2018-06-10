@extends('layouts.main')

@section('content')
<section class="ddl-forms">
    <header>
        <h1>Add a new Resource - Step 2</h1>
    </header>
    <div class="content-body">
        <form method="POST" action="{{ route('step2') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-item">
            <label for="attachments"> 
                <strong>Attachments</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
        <input class="form-control{{ $errors->has('attachments') ? ' is-invalid' : '' }}" id="attachments" name="attachments" size="40" maxlength="40" type="file"><a href="{{ asset('storage/attachments/'.@$resource['attachments']) }}">{{@$resource['attachments']}}</a>
            @if ($errors->has('attachments'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('attachments') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="subject_areas"> 
                <strong>Subject Areas</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('subject_areas') ? ' is-invalid' : '' }}" id="subject_areas" name="subject_areas" size="40" maxlength="40" type="text" value="{{ @$resource['subject_areas'] }}" required>
            @if ($errors->has('subject_areas'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('subject_areas') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="keywords"> 
                <strong>Keywords</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('keywords') ? ' is-invalid' : '' }}" id="keywords" name="keywords" size="40" maxlength="40" type="text" value="{{ @$resource['keywords'] }}" required>
            @if ($errors->has('keywords'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('keywords') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="learning_resources_types"> 
                <strong>Learning Resources Types</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('learning_resources_types') ? ' is-invalid' : '' }}" id="learning_resources_types" name="learning_resources_types" size="40" maxlength="40" type="text" value="{{ @$resource['learning_resources_types'] }}" required>
            @if ($errors->has('learning_resources_types'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('learning_resources_types') }}</strong>
                </span><br>
            @endif
        </div>
        <div class="form-item">
            <label for="educational_use"> 
                <strong>Educational Use</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('educational_use') ? ' is-invalid' : '' }}" id="educational_use" name="educational_use" size="40" maxlength="40" type="text" value="{{ @$resource['educational_use'] }}" required>
            @if ($errors->has('educational_use'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('educational_use') }}</strong>
                </span><br>
            @endif
        </div>

        <div class="form-item">
            <label for="resource_levels"> 
                <strong>Resource Levels</strong>
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
                    <li><input type="checkbox" name="level[]" {{ in_array($level->tid, @$resource['level'] ) ? "checked" : ""}} value="{{ $level->tid }}" onchange="fnTest(this,'subLevel{{$level->tid}}');">{{ $level->name }}
                        <?php $levelParent = $levels->where('parent', $level->tid);?>
                        @if(count($levelParent) > 0)
                            <i class="fas fa-plus fa-xs" onclick="javascript:showHide(this,'subLevel{{$level->tid}}')"></i>
                        @endif
                    @if(count($levelParent) > 0)
                        <ul id="subLevel{{$level->tid}}" class="subItem" style="display:none;">
                            @foreach($levelParent as $item)
                                <li><input type="checkbox" name="level[]" onchange="fnTest(this,'subLevel{{$item->tid}}');" {{ in_array($item->tid, @$resource['level']) ?"checked":""}} class="child" value="{{ $item->tid }}">{{ $item->name }}
                            
                                <?php $levelItemParent = $levels->where('parent', $item->tid);?>
                                @if(count($levelItemParent) > 0)
                                    <i class="fas fa-plus fa-xs" onclick="javascript:showHide(this,'subLevel{{$item->tid}}')"></i>
                                @endif
                                @if(count($levelItemParent) > 0)
                                    <ul id="subLevel{{$item->tid}}" class="subItem" style="display:none;">
                                        @foreach($levelItemParent as $itemLevel)
                                            <li><input type="checkbox" name="level[]" {{ in_array($itemLevel->tid, @$resource['level']) ?"checked":""}} class="child" value="{{ $itemLevel->tid }}">{{ $itemLevel->name }}</li>
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
            <input style="margin-right: 10px;" class="form-control normalButton" type="button" value="Previous" onclick="location.href='{{ URL::to('resources/add/step1') }}'">
            <input class="form-control normalButton" type="submit" value="Next">
        </div>
        </form>
    </div>
</section>
@endsection