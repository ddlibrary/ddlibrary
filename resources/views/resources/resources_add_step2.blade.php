@extends('layouts.main')
@section('title')
    {{ __('Add a new Resource - Step 2') }}
@endsection
@section('content')

    @push('styles')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    @endpush

    <section class="ddl-forms">
        <header>
            <h1>{{ __('Add a new Resource - Step 2') }}</h1>
        </header>
        <div class="content-body">
            @include('layouts.messages')
            <form method="POST" action="{{ route('step2') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-item">
                    <label for="attachments">
                        <strong>{{ __('Attachments') }} {{ en('Attachments') }}</strong>
                    </label>
                    <input type="file" id="resource-file" onchange="checkFile(event)" class="form-control {{ $errors->has('attachments') ? ' is-invalid' : '' }}" id="attachments"
                        name="attachments[]">
                    <button type='button' class="add_more btn-primary btn mt-1 d-block">{{ __('Add More Files') }}</button>
                    @if (isset($resource['attc']))
                        @foreach ($resource['attc'] as $item)
                            <br><a
                                href="{{ asset('/storage/attachments/' . $item['file_name']) }}">{{ $item['file_name'] }}</a>
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
                        <strong>{{ __('Subject Areas') }} {{ en('Subject Areas') }}</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <select class="form-control {{ $errors->has('subject_areas') ? ' is-invalid' : '' }}" id="subject_areas"
                        name="subject_areas[]" required multiple="multiple">
                        @foreach ($subjects as $item)
                            @if ($item->parent == 0)
                                <optgroup label="{{ $item->name }}">
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    <?php if (isset($subjects) && isset($item)) {
                                        $parentItems = $subjects->where('parent', $item->id);
                                    } ?>
                                    @foreach ($parentItems as $pitem)
                                        <option value="{{ $pitem->id }}">{{ $pitem->name . termEn($pitem->id) }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
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
                        <strong>{{ __('Keywords') }} {{ en('Keywords') }}</strong>
                    </label>
                    <input class="form-control {{ $errors->has('keywords') ? ' is-invalid' : '' }}" id="keywords"
                        name="keywords" size="40" type="text"
                        value="{{ isset($resource['keywords']) ? $resource['keywords'] : '' }}"
                        onkeydown="javascript:bringMeAttr('keywords','{{ URL::to('resources/attributes/keywords') }}')">
                    @if ($errors->has('keywords'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('keywords') }}</strong>
                        </span><br>
                    @endif
                </div>
                <div class="form-item">
                    <label for="learning_resources_types">
                        <strong>{{ __('Learning Resources Types') }} {{ en('Learning Resources Types') }}</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <select class="form-control {{ $errors->has('learning_resources_types') ? ' is-invalid' : '' }}"
                        id="learning_resources_types" name="learning_resources_types[]" required multiple="multiple">
                        @foreach ($learningResourceTypes as $item)
                            <option value="{{ $item->id }}">{{ $item->name . termEn($item->id) }}</option>
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
                        <strong>{{ __('Educational Use') }} {{ en('Educational Use') }}</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <select class="form-control {{ $errors->has('educational_use') ? ' is-invalid' : '' }}"
                        id="educational_use" name="educational_use[]" required multiple="multiple">
                        @foreach ($educationalUse as $item)
                            <option value="{{ $item->id }}">{{ $item->name . termEn($item->id) }}</option>
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
                        <strong>{{ __('Resource Levels') }} {{ en('Resource Levels') }}</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <ul>
                        <?php
                        if (!isset($resource['level'])) {
                            $resource['level'] = [];
                        }
                        ?>
                        @foreach ($levels as $level)
                            @if ($level->parent == 0)
                                <li><input type="checkbox" name="level[]"
                                        {{ in_array($level->id, @$resource['level']) ? 'checked' : '' }}
                                        value="{{ $level->id }}"
                                        onchange="fnTest(this,'subLevel{{ $level->id }}');">{{ $level->name . termEn($level->id) }}
                                    <?php if (isset($levels) && isset($level)) {
                                        $levelParent = $levels->where('parent', $level->id);
                                    } ?>
                                    @if ($levelParent)
                                        <i class="fas fa-plus fa-xs"
                                            onclick="showHide(this,'subLevel{{ $level->id }}')"></i>
                                    @endif
                                    @if ($levelParent)
                                        <ul id="subLevel{{ $level->id }}" class="subItem" style="display:none;">
                                            @foreach ($levelParent as $item)
                                                <li><input type="checkbox" name="level[]"
                                                        onchange="fnTest(this,'subLevel{{ $item->id }}');"
                                                        {{ in_array($item->id, @$resource['level']) ? 'checked' : '' }}
                                                        class="js-child"
                                                        value="{{ $item->id }}">{{ $item->name . termEn($item->id) }}

                                                    <?php $levelItemParent = $levels->where('parent', $item->id); ?>
                                                    @if ($levelItemParent)
                                                        <i class="fas fa-plus fa-xs"
                                                            onclick="showHide(this,'subLevel{{ $item->id }}')"></i>
                                                    @endif
                                                    @if ($levelItemParent)
                                                        <ul id="subLevel{{ $item->id }}" class="subItem"
                                                            style="display:none;">
                                                            @foreach ($levelItemParent as $itemLevel)
                                                                <li><input type="checkbox" name="level[]"
                                                                        {{ in_array($itemLevel->id, @$resource['level']) ? 'checked' : '' }}
                                                                        class="js-child"
                                                                        value="{{ $itemLevel->id }}">{{ $itemLevel->name . termEn($itemLevel->id) }}
                                                                </li>
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
                    <input style="margin-{{ app()->getLocale() == 'en' ? 'right' : 'left' }}: 10px;"
                        class="form-control  normalButton" type="button" value="{{ __('Previous') }} {{ en('Previous') }}"
                        onclick="location.href='{{ URL::to('resources/add/step1') }}'">
                    <input class="form-control  normalButton" type="submit" value="{{ __('Next') }} {{ en('Next') }}"
                        onclick="this.style.display='none';document.getElementById('wait').style.display='block'"
                        ondblclick="this.style.display='display';document.getElementById('wait').style.display='block'">
                    <input type="button" class="form-control " id="wait"
                        value="{{ __('Please wait..') }} {{ en('Please wait ..') }}" style="color:red;display:none" disabled>
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

            // Check file size and type
            function checkFile(event){
                let file = event.target.files[0];
                let currentFileSizeInMB = Math.ceil(file.size / 1048576);

                // Check file size
                if( currentFileSizeInMB > 128){
                    alert(`{{ __('Sorry! you can not upload a file more the 128MB') }}`)
                }

                // Allowed file types
                const fileTypes = [
                    'image/png',
                    'image/jpeg',
                    'image/bmp',
                    'image/jpg',
                    'image/tiff',
                    'audio/mp3',
                    'audio/mpga',
                    'application/pdf',
                    'application/vnd.ms-excel',
                    'application/vnd.ms-powerpoint',
                    'application/msword',
                    'application/vnd.ms-office',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ];

                // Check file type
                if(!fileTypes.includes(file.type)){
                    alert("{{ __('Please select a valid file.You can upload these files only') }} [xlsx,xls,csv,jpg,jpeg,png,bmp,mpga,ppt,pptx,doc,docx,pdf,tif,tiff,mp3]");
                    const fileInput = document.getElementById(event.target.id);
                    resetFileInput(fileInput);
                }
            }

            // Reset file input
            function resetFileInput(inputElement) {
                inputElement.value = null;
            }
        </script>
    @endpush
@endsection
