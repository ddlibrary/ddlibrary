@extends('layouts.main')
@section('search')
    @include('layouts.search')
@endsection
@section('content')
<section class="subjects">
    <header>
        <h2>Browse by Subjects</h2>
    </header>
    <hr>
    <div class="content">
        <article>
            <i class="fas fa-angle-double-left fa-3x"></i>
        </article>
        <article>
            <img src="{{ Storage::disk('public')->url('applied-sciences-icon-2.png') }}">
            <p>Social Science</p>
            <p>950 Resources</p>
        </article>
        <article>
            <img src="{{ Storage::disk('public')->url('applied-sciences-icon-2.png') }}">
            <p>Social Science</p>
            <p>950 Resources</p>
        </article>
        <article>
            <img src="{{ Storage::disk('public')->url('applied-sciences-icon-2.png') }}">
            <p>Social Science</p>
            <p>950 Resources</p>
        </article>
        <article>
            <img src="{{ Storage::disk('public')->url('applied-sciences-icon-2.png') }}">
            <p>Social Science</p>
            <p>950 Resources</p>
        </article>
        <article>
            <img src="{{ Storage::disk('public')->url('applied-sciences-icon-2.png') }}">
            <p>Social Science</p>
            <p>950 Resources</p>
        </article>
        <article>
            <img src="{{ Storage::disk('public')->url('applied-sciences-icon-2.png') }}">
            <p>Social Science</p>
            <p>950 Resources</p>
        </article>
        <article>
            <img src="{{ Storage::disk('public')->url('applied-sciences-icon-2.png') }}">
            <p>Social Science</p>
            <p>950 Resources</p>
        </article>
        <article>
            <i class="fas fa-angle-double-right fa-3x"></i>
        </article>
    </div>
</section>
<section class="collections">
    <header>
        <h2>Browse by Collections</h2>
    </header>
    <hr>
    <div class="content">
        <article>
            <i class="fas fa-angle-double-left fa-3x"></i>
        </article>
        <article>
            <img src="{{ Storage::disk('public')->url('applied-sciences-icon-2.png') }}">
            <p>Social Science</p>
            <p>950 Resources</p>
        </article>
        <article>
            <img src="{{ Storage::disk('public')->url('applied-sciences-icon-2.png') }}">
            <p>Social Science</p>
            <p>950 Resources</p>
        </article>
        <article>
            <img src="{{ Storage::disk('public')->url('applied-sciences-icon-2.png') }}">
            <p>Social Science</p>
            <p>950 Resources</p>
        </article>
        <article>
            <img src="{{ Storage::disk('public')->url('applied-sciences-icon-2.png') }}">
            <p>Social Science</p>
            <p>950 Resources</p>
        </article>
        <article>
            <img src="{{ Storage::disk('public')->url('applied-sciences-icon-2.png') }}">
            <p>Social Science</p>
            <p>950 Resources</p>
        </article>
        <article>
            <img src="{{ Storage::disk('public')->url('applied-sciences-icon-2.png') }}">
            <p>Social Science</p>
            <p>950 Resources</p>
        </article>
        <article>
            <img src="{{ Storage::disk('public')->url('applied-sciences-icon-2.png') }}">
            <p>Social Science</p>
            <p>950 Resources</p>
        </article>
        <article>
            <i class="fas fa-angle-double-right fa-3x"></i>
        </article>
    </div>
</section>
<section class="latestNews">
    <div class="latestNewsDiv">
        <header>
            <h2>Latest News</h2>
        </header>
        <hr>
        <article class="latestNewsContent">
            <h3>Translation Day in Kabul</h3>
            <i class="newsDescription">April 29th, 2018</i>
        </article>
        <article class="latestNewsContent">
            <h3>DDL Director to Be a Judge With Story Shares</h3>
            <i class="newsDescription">April 17th, 2018</i>
        </article>
        <article class="latestNewsContent">
            <h3>DDL in Teacher Training Colleges</h3>
            <i class="newsDescription">April 9th, 2018</i>
        </article>
        <article class="latestNewsContent">
            <h3>DD Library Interviewed in New York Times</h3>
            <i class="newsDescription">February 16th, 2018</i>
        </article>
        <article class="latestNewsContent">
            <h3>Open Education Global</h3>
            <i class="newsDescription">December 19th, 2017</i>
        </article>
    </div>
    <div class="ddlVideo">
        <header>
            <h2>DDL Video</h2>
        </header>
        <hr>
        <article class="ddlVideoContent">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/bF5dpED9W64" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </article>
    </div>
    <div class="ddlVideo">
        <header>
            <h2>CW4WAfghan Video</h2>
        </header>
        <hr>
        <article class="ddlVideoContent">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/Kl37icKnzd4" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </article>
    </div>
</section>
@endsection
