@extends('layouts.main')
@section('search')
    @include('layouts.search')
@endsection
@section('content')
<section class="resource-list">
    <aside>
        <h3><span class="strong-text">{{ $resources->total() }}</span> Results</h3>
        <form method="POST" action="{{ route('resourceList') }}">
        @csrf
        <fieldset>
            <legend class="accordion" id="resource-subjects">Resource Subject Areas</legend>
            <ul class="panel">
            @foreach($subjects AS $subject)
                @if($subject->parent == 0)
                    <li>
                        <input type="checkbox" name="subject_area[]" {{ (in_array($subject->id, $subjectAreaIds)?"checked":"")}} onchange="fnTest(this,'js-sub-subject{{$subject->id}}');this.form.submit();" value="{{ $subject->id }}"><span>{{ ucwords(strtolower($subject->name)) }}</span>
                    </li>
                @endif
            @endforeach
            </ul>
        </fieldset>
        <fieldset>
            <legend class="accordion">Resource Types</legend>
            <ul class="panel">
                @foreach($types AS $type)
                    <li>
                        <input type="checkbox" name="type[]" value="{{ $type->id }}" onchange="this.form.submit()" {{ (in_array($type->id, $typeIds)?"checked":"")}}><span>{{ $type->name }}</span>
                    </li>
                @endforeach
            </ul>
        </fieldset>
        <fieldset>
        <legend class="accordion">Resource Levels</legend>
        <ul class="panel">
            @foreach($levels AS $level)
                @if($level->parent == 0)
                    <li>
                        <input type="checkbox" name="level[]" {{ (in_array($level->id, $levelIds)?"checked":"")}} value="{{ $level->id }}" onchange="fnTest(this,'subLevel{{$level->id}}');this.form.submit()"><span>{{ $level->name }}</span>
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
        <article class="resource-article resource-information" onclick="location.href='{{ URL::to('resources/view/'.$resource->id) }}'">
            <img class="resource-img" src="{{ getImagefromResource($resource->abstract) }}">
            <div class="resource-title">{{ str_limit($resource->title, 50), ' (..)' }}</div>
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
        @endforeach
        @else
        <h2>No records found!</h2>
        @endif
        <div class="resource-pagination">
            {{ $resources->appends(request()->input())->links() }}
        </div>
    </section>
</section>
@endsection