@extends('layouts.main')
@section('search')
    @include('layouts.search')
@endsection
@section('content')
<section class="resourceList">
    <aside>
        <h3>320 Results</h3>
        <h4>Subject</h4>
        <ul>
            <li><input type="checkbox">Applied Science</li>
            <li><input type="checkbox">Arts and Hummanities</li>
            <li><input type="checkbox">Business Communication</li>
            <li><input type="checkbox">Education</li>
            <li><input type="checkbox">Earth Science</li>
            <li><input type="checkbox">History</li>
        </ul>
        <h4>Type</h4>
        <ul>
            <li><input type="checkbox">Applied Science</li>
            <li><input type="checkbox">Arts and Hummanities</li>
            <li><input type="checkbox">Business Communication</li>
            <li><input type="checkbox">Education</li>
            <li><input type="checkbox">Earth Science</li>
            <li><input type="checkbox">History</li>
        </ul>
        <h4>Resource Level</h4>
        <ul>
            <li><input type="checkbox">Applied Science</li>
            <li><input type="checkbox">Arts and Hummanities</li>
            <li><input type="checkbox">Business Communication</li>
            <li><input type="checkbox">Education</li>
            <li><input type="checkbox">Earth Science</li>
            <li><input type="checkbox">History</li>
        </ul>
    </aside>
    <section class="resourceInformationSection">
    @if (count($resources) > 0)
    @foreach ($resources AS $resource)
    <article class="resourceInformation">
        <img class="resourceImg" src="{{ getImagefromResource($resource->abstract) }}">
        <div class="resourceTitle">
            <a href="{{ URL::to('resources/view/'.$resource->resourceid) }}">
                {{ str_limit($resource->title, 55), ' (..)' }}
            </a>
        </div>
        <div class="resourceDetails">
            <article>
                <i class="far fa-file-audio"></i><span>Audio</span>
            </article>
            <article>
                <i class="far fa-file-audio"></i><span>3999</span>
            </article>
            <article>
                <i class="far fa-file-audio"></i><span>26</span>
            </article>
            <article>
                <i class="far fa-file-audio"></i><span>4</span>
            </article>
        </div>
    </article>
    @endforeach
    @else
    <h2>No records found!</h2>
    @endif
    </section>
</section>
@endsection 