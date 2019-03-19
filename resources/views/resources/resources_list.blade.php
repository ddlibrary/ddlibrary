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
                    <li style="line-height: 2; cursor: pointer;" value="{{ $subject->id }}" data-type="subject"><strong>{{ ucwords(strtolower($subject->name)) }}</strong></li>

                    <?php $sub_subjects = $subjects->where('parent', $subject->id); ?>
                    <div id="subject-{{ $subject->id }}" style="display: none;">
                        @foreach($sub_subjects AS $subject)
                        <li style="padding:0 10px 0 10px; cursor: pointer;" value="{{ $subject->id }}" data-type="subject">{{ ucwords(strtolower($subject->name)) }}</li>
                        @endforeach
                    </div>

                    @endif
                @endforeach
            </ul>
        </fieldset>
        <fieldset>
            <legend class="accordion">@lang('Resource Types')</legend>
            <ul class="panel">
                @foreach($types AS $type)
                    <li value="{{ $type->id }}" data-type="type">{{ $type->name }}</li>
                @endforeach
            </ul>
        </fieldset>
        <fieldset>
        <legend class="accordion">@lang('Resource Levels')</legend>
        <ul class="panel">
            @foreach($levels AS $level)
                @if($level->parent == 0)
                    <li value="{{ $level->id }}" data-type="level">{{ $level->name }}</li>
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
    
    $(document).ready(function()
    {
        $(document).on('click', '.pagination a',function(event)
        {
            event.preventDefault();
  
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
  
            var myurl = $(this).attr('href');
  
            getData(myurl);
        });
  
        $(document).on('click', '#side-form ul li',function(event)
        {
            var subject_area = $(this).data('type')=="subject"?$(this).attr('value'):"";
            var level = $(this).data('type')=="level"?$(this).attr('value'):"";
            var type = $(this).data('type')=="type"?$(this).attr('value'):"";

            console.log(type);


            $('.resource-list ul li').removeClass('active-header');
            $(this).addClass('active-header');

            $.ajax(
            {
                url: "{{ route('resourceList') }}",
                data: {subject_area: subject_area, level: level, type: type},
                type: "get",
                datatype: "html"
            }).done(function(data){
                $('#subject-'+subject_area).toggle();
                $(".resource-information-section").empty().html(data);
            }).fail(function(jqXHR, ajaxOptions, thrownError){
                alert('No response from server');
            });
        });

        $(document).on('click', '.resource-information-section article', function(event)
        {
            var url = $(this).data('link');
            window.location.href = url;
        });

    });
  
    function getData(url){
        $.ajax(
        {
            url: url,
            type: "get",
            datatype: "html"
        }).done(function(data){
            $(".resource-information-section").empty().html(data);
        }).fail(function(jqXHR, ajaxOptions, thrownError){
            alert('No response from server');
        });
    }
</script>