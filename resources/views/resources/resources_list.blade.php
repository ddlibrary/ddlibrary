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
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $( document ).ready(function() {
        $('#resource-list').hide();
        $.ajax({
            url: "{{ url()->full() }}",
            success: function(html){
                $('#content-loading').hide();
                $('#resource-list').show();

                $("#resource-list").append(html);
                var acc = document.getElementsByClassName("accordion");
                var i;

                for (i = 0; i < acc.length; i++) {
                    acc[i].addEventListener("click", function() {
                    this.classList.toggle("active");
                    var panel = this.nextElementSibling;

                    if (panel.style.maxHeight){
                        panel.style.maxHeight = null;
                    } else {
                        panel.style.maxHeight = panel.scrollHeight + "px";
                    } 
                    });
                }
                $('#resource-subjects').trigger('click');
            }
        });
    });
</script>

<section class="resource-list">
    <aside>
        <form method="POST" id="side-form" action="{{ route('resourceList') }}">
            <input class="form-control normalButton" style="display:none;" id="side-submit" type="submit" value="@lang('Filter')">
        <fieldset>
            <legend class="accordion" id="resource-subjects">@lang('Resource Subject Areas')</legend>
            <ul class="panel">
            @foreach($subjects AS $subject)
                @if($subject->parent == 0)
                    <li>
                        <input type="checkbox" name="subject_area[]" value="{{ $subject->id }}"><span>{{ ucwords(strtolower($subject->name)) }}</span>
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
                        <input type="checkbox" name="type[]" value="{{ $type->id }}"><span>{{ $type->name }}</span>
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
                        <input type="checkbox" name="level[]" value="{{ $level->id }}"><span>{{ $level->name }}</span>
                    </li>
                @endif
            @endforeach
        </ul>
        </fieldset>
        </form>
    </aside>
    <section class="resource-information-section" id="resource-list">
    </section>

    <section class="resource-information-section" id="content-loading">
        <img class="loading" src="{{ asset('storage/files/loading.svg') }}">
    </section>
</section>
@push('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
@endpush
@endsection