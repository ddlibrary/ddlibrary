@extends('layouts.main')
@section('title')
    {{ trim(strip_tags($resource->title)) }}
@endsection
@section('description')
    {{ html_entity_decode(trim(strip_tags(fixImage($resource->abstract, $resource->id)))) }}
@endsection
@section('page_image')
    {{ getImagefromResource($resource->abstract, '282x254') }}
@endsection
@section('search')
    @include('layouts.search')
@endsection
<style>
    html {
        background-color: oklch(1 0 0);
    }

    html.dark {
        background-color: oklch(0.145 0 0);
    }

    /* EPUB Viewer Styles */
    .epub-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2px;
        font-family: 'Georgia', serif;
        line-height: 1.6;
    }

    .epub-header {
        text-align: center;
        margin-bottom: 30px;
        padding: 2px;
        background: linear-gradient(135deg, #d5b577 0%, #ffa800 100%);
        color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .epub-title {
        font-size: 2.5em;
        margin: 0 0 10px 0;
        font-weight: 300;
    }

    .epub-author {
        font-size: 1.2em;
        opacity: 0.9;
        margin: 0;
    }

    .epub-controls {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin: 20px 0;
        flex-wrap: wrap;
    }

    .epub-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 25px;
        background: linear-gradient(135deg, #d5b577 0%, #ffa800 100%);
        color: white;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .epub-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .epub-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .epub-content {
        background: white;
        padding: 4px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        min-height: 600px;
        position: relative;
    }

    .epub-page {
        font-size: 18px;
        color: #333;
        text-align: justify;
        max-width: 800px;
        margin: 0 auto;
    }

    .epub-page h1,
    .epub-page h2,
    .epub-page h3 {
        color: #2c3e50;
        margin-top: 30px;
        margin-bottom: 15px;
    }

    .epub-page p {
        margin-bottom: 20px;
        text-indent: 2em;
    }

    .epub-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        padding: 2px;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .epub-progress {
        flex: 1;
        margin: 0 20px;
    }

    .epub-progress-bar {
        width: 100%;
        height: 8px;
        background: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }

    .epub-progress-fill {
        /* height: 100%; */
        background: linear-gradient(90deg, #d5b577 0%, #ffa800 100%);
        transition: width 0.3s ease;
    }

    .epub-status {
        text-align: center;
        color: #6c757d;
        font-size: 14px;
        margin-top: 10px;
    }

    .epub-loading {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .epub-loading::after {
        content: '';
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #d5b577;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 10px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .epub-error {
        text-align: center;
        padding: 40px 20px;
        color: #dc3545;
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 10px;
        margin: 20px 0;
    }

    /* Dark mode support */
    html.dark .epub-content {
        background: #1a1a1a;
        color: #e0e0e0;
    }

    html.dark .epub-page {
        color: #e0e0e0;
    }

    html.dark .epub-page h1,
    html.dark .epub-page h2,
    html.dark .epub-page h3 {
        color: #ffffff;
    }

    html.dark .epub-navigation {
        background: #2d2d2d;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .epub-container {
            padding: 2px;
        }

        .epub-content {
            padding: 2px;
        }

        .epub-title {
            font-size: 2em;
        }

        .epub-controls {
            flex-direction: column;
            align-items: center;
        }

        .epub-btn {
            width: 100%;
            max-width: 200px;
        }
    }
</style>
@section('content')
    <div class="container-fluid">
        @include('layouts.messages')
        <div class="row m-2 mt-md-4">
            <div class="col-md-8">
                @php
                    $epubBook = null;
                    $epubBookKey = null;
                @endphp
                @if ($resource->attachments)
                    @foreach ($resource->attachments as $file)
                        @php
                            $time = time();
                            $key = encrypt(config('s3.config.secret') * $time);
                        @endphp
                        @if ($file->file_mime == 'application/pdf')
                            <iframe src="{{ URL::to('/resource/view/' . $file->id . '/' . $key) }}{{ URL::to('/resource/view/' . $file->id . '/' . $key) }}{{ URL::to('/resource/view/' . $file->id . '/' . $key) }}#toolbar=0" height="500"
                                width="100%"></iframe>
                        @elseif(
                            $file->file_mime == 'application/msword' ||
                                $file->file_mime == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                            <iframe
                                src="{{ URL::to(config('constants.google_doc_viewer_url') . URL::to('/resource/view/' . $file->id . '/' . $key) . '&embedded=true') }}"
                                height="500" width="100%"></iframe>
                        @elseif($file->file_mime == 'audio/mpeg')
                            <span class="download-item">
                                <audio controls>
                                    <source src="{{ URL::to('/resource/view/' . $file->id . '/' . $key) }}"
                                        type="audio/mpeg">
                                </audio>
                            </span>
                        @elseif($file->file_mime == 'application/epub+zip')
                            <div>
                                @php
                                    $epubBook = $file;
                                    $epubBookKey = $key;
                                @endphp

                                <div class="epub-container" id="epubViewer" style="display: none;">
                                    <div class="epub-header" style="display: none">
                                        <h1 class="epub-title" id="epubTitle">Loading...</h1>
                                        <p class="epub-author" id="epubAuthor">Author</p>
                                    </div>

                                    <div class="epub-controls">
                                        <button class="epub-btn" id="prevBtn" onclick="previousPage()">Previous</button>
                                        <button class="epub-btn" id="tocBtn" onclick="showTableOfContents()">Table of
                                            Contents</button>
                                        <button class="epub-btn" id="fontSizeBtn" onclick="toggleFontSize()">Font
                                            Size</button>
                                        <button class="epub-btn" id="nextBtn" onclick="nextPage()">Next</button>
                                    </div>

                                    <div class="epub-content">
                                        <div class="epub-page" id="epubContent">
                                            <!-- EPUB content will be rendered here by epubjs -->
                                        </div>
                                    </div>

                                    <div class="epub-navigation">
                                        <button class="epub-btn" onclick="previousPage()">← Previous</button>
                                        <div class="epub-progress">
                                            <div class="epub-progress-bar d-none">
                                                <div class="epub-progress-fill" id="progressBar"></div>
                                            </div>
                                            <div class="epub-status" id="epubStatus">Page 1 of 1</div>
                                        </div>
                                        <button class="epub-btn" onclick="nextPage()">Next →</button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="download-item no-preview">@lang('No preview available.')</span>
                        @endif

                        {{-- revert to older direct download format until we have the correct packages installed for PDF watermarking <span class="download-item"><a class="btn btn-primary"
                                                        href="{{ URL::to('resource/'.$resource->id.'/download/'.$file->id) }}"><i
                            class="fa fa-download" aria-hidden="true"></i> @lang('Download') ({{ formatBytes($file->file_size) }})</a>
                        <br>
                        <hr>
                        </span> --}}
                        <h6>@lang('File :id', ['id' => $loop->iteration])</h6>
                        <span class="badge text-bg-secondary">
                            @php
                                /* @var $file */
                                echo pathinfo($file->file_name, PATHINFO_EXTENSION);
                            @endphp
                        </span>
                        <span class="">
                            @if (Auth::check())
                                @php
                                    $user = Auth::id();
                                    $hash = hash(
                                        'sha256',
                                        config('s3.config.secret') * ($user + $resource->id + $file->id),
                                    );
                                @endphp
                                <a class="btn btn-primary btn-sm"
                                    href="{{ URL::to('resource/' . $resource->id . '/download/' . $file->id . '/' . $hash) }}">
                                    <i class="fa fa-download" aria-hidden="true"></i> @lang('Download')
                                    ({{ formatBytes($file->file_size) }})
                                </a>
                            @else
                                @lang('Please login to download this file.')
                            @endif
                        </span>
                    @endforeach
                @endif
                <div id="resource-title" class="row pt-md-2">
                    <h4 class="col-md-8">{{ $resource->title }}</h4>
                    <div class="col-md-4 {{ Lang::locale() != 'en' ? 'text-start' : 'text-end' }}">
                        @if (isLibraryManager() or isAdmin())
                            <a href="{{ URL::to($resource->language . '/resources/edit/step1/' . $resource->id) }}"><i
                                    class="far fa-lg fa-edit" aria-hidden="true" title="@lang('Edit')"></i></a>
                        @endif
                        &nbsp;
                        <i class="fa-solid fa-star fa-lg @if (empty($resource->favorites[0])) @else active @endif"
                            title="@lang('Mark this resource as your favorite')" id="resourceFavorite" style="cursor: pointer;"
                            @if (Auth::check()) onclick="favorite('resourceFavorite','{{ URL::to('resources/favorite/') }}','{{ $resource->id }}','{{ Auth::id() }}')"
                           @else
                               onclick="alert('Please login to mark a resource as your favorite')" @endif></i>
                        &nbsp;
                        <i class="fas fa-lg fa-share-alt" style="cursor: pointer;" data-bs-toggle="modal" aria-hidden="true"
                            data-bs-target="#shareModal" title="@lang('Share this resource')"></i>
                        <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="shareModalLabel">@lang('Share this resource')</h5>
                                    </div>
                                    <div class="modal-body row justify-content-center">
                                        <i class="fab fa-twitter fa-4x col-md-2" title="Share to Twitter"
                                            style="color: #1da1f2; cursor: pointer;"
                                            onclick="window.open('https://twitter.com/intent/tweet?url={{ Request::url() }}', '_blank')"></i>
                                        <i class="fab fa-facebook fa-4x col-md-2" title="Share to Facebook"
                                            style="color: #4267b2; cursor: pointer;"
                                            onclick="window.open('https://www.facebook.com/sharer/sharer.php?u={{ Request::url() }}', '_blank')"></i>
                                        <i class="fab fa-linkedin fa-4x col-md-2" title="Share to LinkedIn"
                                            style="color: #0077b5; cursor: pointer;"
                                            onclick="window.open('https://www.linkedin.com/sharing/share-offsite/?url={{ Request::url() }}', '_blank')"></i>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">@lang('Close')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        &nbsp;
                        <i class="far fa-lg fa-flag" style="cursor: pointer;" data-bs-toggle="modal" aria-hidden="true"
                            data-bs-target="#reportModal" title="@lang('Report this resource')"></i>
                        <div class="modal fade" id="reportModal" tabindex="-1" role="dialog"
                            aria-labelledby="reportModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('flag') }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="reportModalLabel">@lang('Report this resource')</h5>
                                        </div>
                                        <div class="modal-body">
                                            @csrf
                                            <div class="form-group mb-3">
                                                <label for="type">
                                                    @lang('Type')
                                                </label>
                                                <select name="type"
                                                    class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}"
                                                    required>
                                                    <option value="">-</option>
                                                    <option value="1">@lang('Graphic Violence')</option>
                                                    <option value="2">@lang('Graphic Sexual Content')</option>
                                                    <option value="3">@lang('Spam, Scam or Fraud')</option>
                                                    <option value="4">@lang('Broken or Empty Data')</option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="details">
                                                    @lang('Details')
                                                </label>
                                                <textarea name="details" class="form-control" rows="5" required></textarea>
                                            </div>
                                            <input type="hidden" value="{{ $resource->id }}" name="resource_id">
                                            <input type="hidden" value="{{ Auth::id() }}" name="userid">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">@lang('Close')</button>
                                            <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <span class="text-secondary">{{ $views->where('resource_id', $resource->id)->count() }}
                            @lang('views')</span>
                    </div>
                    <div class="col-2 text-secondary">
                        <i class="far fa-star"></i> <span
                            class="text-secondary">{{ $favorites->where('resource_id', $resource->id)->count() }}</span>
                    </div>
                    <div class="col-8 {{ Lang::locale() != 'en' ? 'text-start' : 'text-end' }}">
                        <a href="{{ URL::to('glossary') }}" class="glossary-icon"><i class="fas fa-globe"
                                title="@lang('DDL Glossary')"><span class="glossary-text">&nbsp;@lang('Glossary')</span>
                            </i></a>
                    </div>
                </div>
                <hr>
                <div id="resource-view-title-box">

                    {!! fixImage($resource->abstract, $resource->id) !!}
                </div>
                <br>
                <h5 class="py-1 pl-1"
                    id=@if (LaravelLocalization::getCurrentLocaleDirection() == 'ltr') "meta-box-title" @else "meta-box-title-rtl" @endif>
                    @lang('About this resource')</h5>
                <div class="row mb-2 pl-2">
                    <div class="col-md-4 mb-1">
                        <h6>@lang('Available in the following languages')</h6>
                        <ul>
                            @foreach ($languages_available as $locale => $properties)
                                <li>
                                    <a rel="alternate" title="language" hreflang="{{ $locale }}"
                                        href="{{ LaravelLocalization::getLocalizedURL($locale, $properties['url'], [], true) }}">
                                        {{ $properties['native'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-4 mb-1">
                        <h6>@lang('Subject area')</h6>
                        @foreach ($resource->subjects as $subject)
                            <a class="badge text-bg-primary"
                                href="{{ URL::to('resources/list?subject_area=' . $subject->id) }}"
                                title="{{ $subject->name }}">{{ $subject->name }}</a>
                        @endforeach
                    </div>
                    <div class="col-md-4 mb-1">
                        <h6>@lang('Resource level')</h6>
                        @foreach ($resource->levels as $level)
                            <a class="badge text-bg-primary" href="{{ URL::to('resources/list?level=' . $level->id) }}"
                                title="{{ $level->name }}">{{ $level->name }}</a>
                        @endforeach
                    </div>
                </div>
                <div class="row mb-2 pl-2">
                    <div class="col-md-4 mb-1">
                        <h6>@lang('Resource type')</h6>
                        @foreach ($resource->LearningResourceTypes as $ltype)
                            <p><a href="{{ URL::to('resources/list?type=' . $ltype->id) }}"
                                    title="{{ $ltype->name }}">{{ $ltype->name }}</a></p>
                        @endforeach
                    </div>
                    @if ($resource->authors[0])
                        <div class="col-md-4 mb-1">
                            <h6>@lang('Author')</h6>
                            @foreach ($resource->authors as $author)
                                <p>{{ $author->name }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if ($resource->translators[0])
                        <div class="col-md-4 mb-1">
                            <h6>@lang('Translator')</h6>
                            @foreach ($resource->translators as $translator)
                                <p>{{ $translator->name }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="row mb-2 pl-2">
                    <div class="col-md-4 mb-1">
                        <h6>@lang('Publisher')</h6>
                        @foreach ($resource->publishers as $publisher)
                            <p><a href="{{ URL::to('resources/list?publisher=' . $publisher->id) }}"
                                    title="{{ $publisher->name }}">{{ $publisher->name }}</a></p>
                        @endforeach
                    </div>
                    <div class="col-md-4 mb-1">
                        <h6>@lang('License')</h6>
                        <p class="badge text-bg-secondary">
                            {{ $resource->creativeCommons ? $resource->creativeCommons[0]->name : '' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="p-3">
                        <img class="resource-view-img" src="{{ getImagefromResource($resource->abstract, '282x254') }}"
                            alt="Resource Main Image">
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-1">
                        <h4>@lang('Similar resources')</h4>
                        <hr>
                        @foreach ($relatedItems as $item)
                            <a title="@lang('Resource title')" href="{{ URL::to('resource/' . $item->id) }}">
                                <div class="row mb-2 similar-resources">
                                    <div class="d-none d-lg-block col-lg-4">
                                        <img class="resource-view-img"
                                            src="{{ getImagefromResource($item->abstract, '55x50') }}"
                                            alt="Resource Image">
                                    </div>
                                    <div class="col-12 col-lg-8">
                                        {{ $item->title }}<br />
                                        <span class="similar-resources-text">
                                            {!! str_limit(strip_tags($item->abstract), 25) !!}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body p-3">
                        @if (isAdmin() or isLibraryManager())
                            @if (isset($resource->user))
                                <p>@lang('Added by'):
                                    <a href="{{ route('user-view', $resource->user->id) }}">
                                        {{ $resource->user->username }}
                                    </a>
                                </p>
                            @endif

                            <form class="form-row" method="post" action="{{ route('updatetid', $resource->id) }}">
                                @honeypot
                                @csrf
                                <label for="link">
                                    {{ __('If this resource is translated, enter the translated resource id and click submit:') }}
                                </label>
                                <div class="row">
                                    <div class="col-sm-9">
                                        <input type="number" name="link" class="form-control">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="submit" class="btn btn-primary" value="@lang('Submit')">
                                    </div>
                                </div>
                            </form>

                            <div class="mt-2">
                                <strong>@lang('Linked resources:')</strong>
                            </div>
                            @foreach ($translations as $resource)
                                <div class="d-flex gap-1 mt-1 bg-secondary-subtle p-2">
                                    <div class="p-1">
                                        {{ $loop->iteration }}.
                                    </div>
                                    <div class="p-1">
                                        <a href="{{ URL::to($resource->language . '/resource/' . $resource->id) }}"
                                            target="_blank">
                                            {{ $resource->title }} ({{ $resource->language }})
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <h5>{{ count($comments) }} @lang('Comment(s)')</h5>
                <form method="POST" action="{{ route('comment') }}">
                    @csrf
                    @honeypot
                    <input type="hidden" value="{{ $resource->id }}" name="resource_id">
                    <input type="hidden" value="{{ Auth::id() }}" name="userid">
                    <div class="form-group mb-3">
                        <label for="commentTextArea">
                            @if (Auth::check())
                                @lang('Enter your comment below')
                            @else
                                @lang('Please login to add a comment')
                            @endif
                        </label>
                        <textarea class="form-control" name="comment" id="commentTextArea" rows="3"
                            @if (!Auth::check()) disabled @endif required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary offset-md-11"
                        @if (!Auth::check()) disabled @endif>@lang('Submit')</button>
                </form>

                @foreach ($comments as $cm)
                    <div class="card m-2">
                        <div class="card-body">
                            <p class="card-text">{{ $cm->comment }}</p>
                            <span style="font-size: 12px; color: #535659;">
                                @lang('By :user', ['user' => $cm->user->username])
                            </span>&nbsp;
                            <span style="font-size: 10px; color: #8795a1;">
                                {{ $cm->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @if ($epubBook)
        <div id="app" data-user-id="{{ asset('resources/'.$epubBook->file_name) }}"></div>

        <script src="{{ asset('epub/epub.js') }}"></script>
    @endif
@endsection
