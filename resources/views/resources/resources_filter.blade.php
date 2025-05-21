@extends('layouts.main')
@section('title')
    @lang('Resource Filter') - @lang('Darakht-e Danesh Online Library')
@endsection
@section('description')
    @lang('The Darakht-e Danesh Online Library for Educators is a repository of open educational resources for teachers, teacher trainers, school administrators, literacy workers and others involved in furthering education in Afghanistan.')
@endsection
@section('page_image')
    {{ asset('storage/files/logo-dd.png') }}
@endsection
@section('content')
    <div class="container mt-md-5 pb-4" id="advanced-filter-container">
        <h3 class="mb-3 pt-3">@lang('Filter')</h3>
        <form class="row justify-content-center mb-lg-2" method="GET" action="{{ route('resourceList') }}">
            <div class="form-group col-md-3">
                <label for="selectSubjectAreaParent">@lang('Subjects')</label>
                <select class="form-control custom-select"
                        name="subjectAreaParent[]"
                        id="selectSubjectAreaParent"
                        onchange="getSubjectChildren()"
                        multiple
                        size="20"
                >
                    <option value></option>
                    @foreach($parentSubjects as $subject)
                        <option value="{{ $subject->id }}" data-type="subject">{{ ucfirst(strtolower($subject->name)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="selectSubjectAreaChild">@lang('Subjects (sub-categories)')</label>
                <select class="form-control"
                        name="subjectAreaChild[]"
                        id="selectSubjectAreaChild"
                        multiple
                        size="15"
                >
                    <option value></option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="selectResourceType">@lang('Resource types')</label>
                <select class="form-control"
                        name="type[]"
                        id="selectResourceType"
                        multiple
                        size="15"
                >
                    <option value></option>
                    @foreach($resourceTypes as $type)
                        <option value="{{ $type->id }}" data-type="type">{{ ucfirst(strtolower($type->name)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="selectLiteracyLevel">@lang('Resource literacy levels')</label>
                <select class="form-control"
                        name="level[]"
                        id="selectLiteracyLevel"
                        multiple
                        size="8"
                >
                    <option value></option>
                    @foreach($literacyLevels as $level)
                        <option value="{{ $level->id }}" data-type="level">{{ ucfirst(strtolower($level->name)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-item col-6 my-3 {{ (Lang::locale() != 'en') ? 'ms-auto' : 'me-auto' }}">
                <label for="search" class="sr-only">@lang('Keywords')</label>
                <input type="text" name="search" class="form-control" placeholder="@lang('Keywords to filter by')">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary col-md-4 col-4 my-2" value="@lang('Apply filters')">
            </div>
        </form>
        <span class="text-muted">@lang('Hold the CTRL key on Windows and Linux, and Command key (⌘) on Mac to select multiple entries.')</span>
    </div>
@endsection
@push('scripts')
    <script>
        function getSubjectChildren() {
            let selected_values = selectSubjectAreaParent.selectedOptions;
            selected_values = Array.from(selected_values).map(({ value }) => value);
            $("#selectSubjectAreaChild option").remove();

            $.ajax({
                type: 'GET',
                url: 'filter/subject?IDs=' + selected_values,
                success: function(res) {
                    let option = document.createElement('option');
                    option.value = "";
                    selectSubjectAreaChild.append(option)
                    if (res) {
                        $.each(res, function(name, id) {
                            let option = document.createElement('option');
                            option.innerHTML = name;
                            option.value = id;
                            selectSubjectAreaChild.append(option)
                        });
                    }
                }
            });
        }
    </script>
@endpush