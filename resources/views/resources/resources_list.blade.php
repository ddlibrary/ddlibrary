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
                        <label for="subject-{{ $subject->id }}">
                        <input type="checkbox" name="subject_area[]" id="subject-{{ $subject->id }}" {{ (in_array($subject->id, $subjectAreaIds)?"checked":"")}} value="{{ $subject->id }}"><span>{{ ucwords(strtolower($subject->name)) }}</span>
                        </label>
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
                        <label for="type-{{ $type->id }}">
                        <input type="checkbox" name="type[]" id="type-{{ $type->id }}" {{ (in_array($type->id, $typeIds)?"checked":"")}} value="{{ $type->id }}"><span>{{ $type->name }}</span>
                        </label>
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
                        <label for="level-{{ $level->id }}">
                        <input type="checkbox" name="level[]" id="level-{{ $level->id }}" {{ (in_array($level->id, $levelIds)?"checked":"")}} value="{{ $level->id }}"><span>{{ $level->name }}</span>
                    </li>
                @endif
            @endforeach
        </ul>
        </fieldset>
        </form>
    </aside>
    
    <section class="resource-information-section">
        @include('resources.resources_list_content')
    </section>
</section>
@endsection

<script src="{{ URL::to('vendor/jquery/jquery.min.js') }}"></script>

<script type="text/javascript">
    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            }else{
                getData(page);
            }
        }
    });
    
    $(document).ready(function()
    {
        $(document).on('click', '.pagination a',function(event)
        {
            event.preventDefault();
  
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
  
            var myurl = $(this).attr('href');
            var page=$(this).attr('href').split('page=')[1];
  
            getData(page);
        });
  
    });
  
    function getData(page){
        $.ajax(
        {
            url: "{{ route('resourceListContent') }}",
            data: {page: page},
            type: "get",
            datatype: "html"
        }).done(function(data){
            $(".resource-information-section").empty().html(data);
            location.hash = page;
        }).fail(function(jqXHR, ajaxOptions, thrownError){
              alert('No response from server');
        });
    }
</script>