@extends('layouts.main')
@section('title')
@lang('Latest Resources') - @lang('Darakht-e Danesh Online Library')
@endsection
@section('description')
@lang('The Darakht-e Danesh Online Library for Educators is a repository of open educational resources for teachers, teacher trainers, school administrators, literacy workers and others involved in furthering education in Afghanistan.')
@endsection
@section('page_image')
{{ asset('storage/files/logo-dd.png') }}
@endsection
@section('search')
    @include('layouts.search')
@endsection
@section('content')

<section class="resource-list">
    <aside class="p-1">
        <form method="GET" id="side-form" action="{{ route('resourceList') }}">
            <input class="form-control normalButton" style="display:none;" id="side-submit" type="submit" value="@lang('Filter')">
        <fieldset>
            <legend class="accordion mb-1 bg-yellow black display-flex justify-content-space-between" id="resource-subjects">@lang('Resource Subject Areas')</legend>
            <ul class="panel resource-category">
                @foreach($subjects AS $subject)
                    @if($subject->parent == 0)
                    <li style="line-height: 2;" class="p-2" value="{{ $subject->id }}" data-type="subject" data-link="{{ route('resourceList', ['subject_area' => $subject->id])}}"><strong>{{ ucwords(strtolower($subject->name)) }}</strong></li>

                    <?php $sub_subjects = $subjects->where('parent', $subject->id); ?>
                    <div id="subject-{{ $subject->id }}" class="mt-1" style="display: none;">
                        @foreach($sub_subjects AS $subject)
                        <li class="p-2" value="{{ $subject->id }}" data-type="subject" data-link="{{ route('resourceList', ['subject_area' => $subject->id])}}">{{ ucwords(strtolower($subject->name)) }}</li>
                        @endforeach
                    </div>

                    @endif
                @endforeach
            </ul>
        </fieldset>
        <fieldset>
            <legend class="accordion mb-1 bg-yellow black display-flex justify-content-space-between">@lang('Resource Types')</legend>
            <ul class="panel resource-category">
                @foreach($types AS $type)
                    <li value="{{ $type->id }}" class="p-2" data-type="type" data-link="{{ route('resourceList', ['type' => $type->id])}}">{{ $type->name }}</li>
                @endforeach
            </ul>
        </fieldset>
        <fieldset>
        <legend class="accordion mb-1 bg-yellow black display-flex justify-content-space-between">@lang('Resource Levels')</legend>
        <ul class="panel resource-category">
            @foreach($levels AS $level)
                @if($level->parent == 0)
                    <li class="p-2" value="{{ $level->id }}" data-type="level" data-link="{{ route('resourceList', ['level' => $subject->id])}}">{{ $level->name }}</li>
                @endif
            @endforeach
        </ul>
        </fieldset>
        <fieldset>
            <legend class="glossary-accordion mb-1 bg-yellow black display-flex justify-content-space-between">
                <a href="/glossary" class="glossary-icon-sidebar black"><i class="fas fa-globe" title="@lang('DDL Glossary')" ><span class="glossary-text-sidebar">&nbsp;@lang('Glossary')</span> </i></a>
            </legend>
        </fieldset>
        </form>
    </aside>
    
    <section id="resource-information-section" class="resource-information-section">
        @include('resources.resources_list_content')
    </section>
</section>
@endsection
