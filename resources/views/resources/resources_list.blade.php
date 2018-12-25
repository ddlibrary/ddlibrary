@extends('layouts.main')
@section('title')
@lang('Latest Resources') - @lang('Darakht-e Danish Online Library')
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
    <aside>
        <form method="GET" id="side-form" action="{{ route('resourceList') }}">
            <input class="form-control normalButton" style="display:none;" id="side-submit" type="submit" value="@lang('Filter')">
        <fieldset>
            <legend class="accordion" id="resource-subjects">@lang('Resource Subject Areas')</legend>
            <ul class="panel">
            @foreach($subjects AS $subject)
                @if($subject->parent == 0)
                    <li>
                        <input type="checkbox" name="subject_area[]" {{ (in_array($subject->id, $subjectAreaIds)?"checked":"")}} value="{{ $subject->id }}"><span>{{ ucwords(strtolower($subject->name)) }}</span>
                    </li>
                @endif
            @endforeach
            </ul>
        </fieldset>
        <fieldset>
            <legend class="accordion">@lang('Resource Types')</legend>
            <ul class="panel">
                @foreach($types AS $type)
                    <li>
                        <input type="checkbox" name="type[]" {{ (in_array($type->id, $typeIds)?"checked":"")}} value="{{ $type->id }}"><span>{{ $type->name }}</span>
                    </li>
                @endforeach
            </ul>
        </fieldset>
        <fieldset>
        <legend class="accordion">@lang('Resource Levels')</legend>
        <ul class="panel">
            @foreach($levels AS $level)
                @if($level->parent == 0)
                    <li>
                        <input type="checkbox" name="level[]" {{ (in_array($level->id, $levelIds)?"checked":"")}} value="{{ $level->id }}"><span>{{ $level->name }}</span>
                    </li>
                @endif
            @endforeach
        </ul>
        </fieldset>
        </form>
    </aside>
    
    <section class="resource-information-section">
        @if (count($resources) > 0)
        @foreach ($resources AS $resource)
        <a href="{{ URL::to('resource/'.$resource->id) }}" title="{{ $resource->title }}">
            <article class="resource-article resource-information">
                <img class="resource-img" src="{{ getImagefromResource($resource->abstract) }}" alt="Resource Image">	
                <div class="resource-title">{{ $resource->title }}</div>	
                <div class="resource-details">	
                    <article>	
                        <i class="fas fa-eye"></i><span>{{ $resource->totalviews }}</span>	
                    </article>	
                    <article>	
                        <i class="fas fa-star"></i><span>{{ $resource->totalfavorite }}</span>	
                    </article>	
                    <article>	
                        <i class="fas fa-comment"></i><span>{{ $resource->totalcomments }}</span>	
                    </article>	
                </div>	
            </article>	
        </a>	
        @endforeach	
        @else	
        <h2>@lang('No records found!')</h2>	
        @endif	
        <div class="resource-pagination">	
            {{ $resources->appends(request()->input())->links() }}	
        </div>
    </section>
</section>
@endsection