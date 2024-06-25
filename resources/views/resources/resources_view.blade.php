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
@section('content')
    <section class="resource-view">
        @if ($resource)
            <section class="resource-view-information-section">
                <article class="resource-view-title-box">
                    <div class="resource-view-title">
                        <header>
                            <h2>{{ $resource->title }}</h2>
                        </header>
                        <div class="resource-icons">
                            <div class="resource-icons-group">
                                @if (isLibraryManager() or isAdmin())
                                    <a
                                        href="{{ URL::to($resource->language . '/resources/edit/step1/' . $resource->id) }}">@lang('Edit')</a>
                                @endif
                                <i class="fas fa-lg fa-star pointer {{ $resource->favorites ? 'active' : '' }}"
                                    title="@lang('Favorite this resource')" id="resourceFavorite"
                                    onclick="favorite('resourceFavorite','{{ URL::to('resources/favorite/') }}','{{ $resource->id }}','{{ Auth::id() }}')"></i>
                                <i class="fas fa-lg fa-share-square" title="@lang('Share this resource')"></i>
                                <i class="fas fa-lg fa-flag" title="@lang('Flag this resource')"></i>
                            </div>
                        </div>

                        <!-- The Share Modal -->
                        <div id="shareModal" class="modal">
                            <!-- Modal content -->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <span class="close" id="share-close">&times;</span>
                                    <h2>@lang('Share this item')</h2>
                                </div>
                                <div class="modal-body">
                                    <div class="modal-body">
                                        <div class="social-share">
                                            <i class="fab fa-twitter fa-4x" title="Share to Twitter"
                                                onclick="window.location.href='https://twitter.com/intent/tweet?url={{ Request::url() }}'"></i>
                                            <i class="fab fa-facebook fa-4x" title="Share to Facebook"
                                                onclick="window.location.href='https://www.facebook.com/sharer/sharer.php?u={{ Request::url() }}'"></i>
                                            <i class="fab fa-google-plus-g fa-4x" title="Share to Google+"
                                                onclick="window.location.href='https://plus.google.com/share?url={{ Request::url() }}'"></i>
                                            <i class="fab fa-reddit fa-4x" title="Share to Reddit"
                                                onclick="window.location.href='https://reddit.com/submit?url={{ Request::url() }}'"></i>
                                            <i class="fab fa-tumblr fa-4x" title="Share to Tumblr"
                                                onclick="window.location.href='https://www.tumblr.com/widgets/share/tool?canonicalUrl={{ Request::url() }}'"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- The favorite Modal -->
                        <div id="favoriteModal" class="modal">
                            <!-- Modal content -->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <span class="close" id="favorite-close">&times;</span>
                                    <h2>@lang('Favorite this item')</h2>
                                </div>
                                <div class="modal-body">
                                    <div class="modal-body">
                                        <h2>@lang('In order to favorite a resource, you are required to') <a href="{{ URL::to('login') }}"
                                                title="@lang('login')">@lang('login')</a>.</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- The Flag Modal -->
                        <div id="flagModal" class="modal">
                            <!-- Modal content -->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <span class="close" id="flag-close">&times;</span>
                                    <h2>@lang('Flag this item')</h2>
                                </div>
                                <div class="modal-body">
                                    <div class="modal-body">
                                        <section class="ddl-forms">
                                            <div class="content-body">
                                                <form method="POST" action="{{ route('flag') }}">
                                                    @csrf
                                                    <div class="form-item">
                                                        <label for="type">
                                                            <strong>@lang('Type')</strong>
                                                            <span class="form-required"
                                                                title="This field is required.">*</span>
                                                        </label>
                                                        <select name="type"
                                                            class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}"
                                                            required>
                                                            <option value="">- @lang('None') -</option>
                                                            <option value="1">@lang('Graphic Violence')</option>
                                                            <option value="2">@lang('Graphic Sexual Content')</option>
                                                            <option value="3">@lang('Spam, Scam or Fraud')</option>
                                                            <option value="4">@lang('Broken or Empty Data')</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-item">
                                                        <label for="details">
                                                            <strong>@lang('Details')</strong>
                                                            <span class="form-required"
                                                                title="This field is required.">*</span>
                                                        </label>
                                                        <textarea name="details" class="form-control" cols="40" rows="5" required></textarea>
                                                    </div>
                                                    <input type="hidden" value="{{ $resource->id }}" name="resource_id">
                                                    <input type="hidden" value="{{ Auth::id() }}" name="userid">
                                                    <div class="left-side">
                                                        <input class="form-control normalButton" type="submit"
                                                            value="@lang('Submit')">
                                                    </div>
                                                </form>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {!! fixImage($resource->abstract, $resource->id) !!}
                </article>
                <article class="resource-view-details">
                    <div class="resource-view-download">
                        <h3 style="display: inline;">@lang('Please click the button(s) below to download the resource(s)')</h3>
                        <a href="{{ URL::to('glossary') }}" class="glossary-icon"><i class="fas fa-globe"
                                title="@lang('DDL Glossary')"><span class="glossary-text">&nbsp;@lang('Glossary')</span>
                            </i></a>
                    </div>
                    <div class="download-box">
                        @if ($resource->attachments)
                            @foreach ($resource->attachments as $file)
                                <h4>@lang('File :id', ['id' => $loop->iteration])</h4>
                                <h4>
                                    <span class="badge badge-secondary">
                                        @php
                                            /* @var $file */
                                            echo pathinfo($file->file_name, PATHINFO_EXTENSION);
                                            $time = time();
                                            $key = encrypt(config('s3.config.secret') * $time);
                                        @endphp
                                    </span>
                                </h4>
                                <div class="mt-2 w-100">
                                    @if ($file->file_mime == 'application/pdf')
                                        <iframe src="{{ URL::to('/resource/view/' . $file->id . '/' . $key) }}#toolbar=0"
                                            height="500" width="100%"></iframe>
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
                                    @else
                                        <span class="download-item no-preview">@lang('No preview available.')</span>
                                    @endif
                                </div>

                                <span class="download-item">
                                    @if (Auth::check())
                                        @php
                                            $user = Auth::id();
                                            $hash = hash(
                                                'sha256',
                                                config('s3.config.secret') * ($user + $resource->id + $file->id),
                                            );
                                        @endphp
                                        <a class="btn btn-primary"
                                            href="{{ URL::to('resource/' . $resource->id . '/download/' . $file->id . '/' . $hash) }}">
                                            <i class="fa fa-download" aria-hidden="true"></i> @lang('Download')
                                            ({{ formatBytes($file->file_size) }})
                                        </a>
                                    @else
                                        @lang('Please login to download this file.')
                                    @endif
                                    <br>
                                </span>
                            @endforeach
                        @endif
                    </div>
                </article><br>
                <div style="border:1px solid lightgray;" class="p-2 border-radius-5">

                    <h3 class="bg-yellow-600 p-2 border-radius-top-5 black-400">@lang('About this resource')</h3>
                    @if (count($resource->authors))
                        <article class="resource-view-details">
                            <h3>@lang('Author')</h3>
                            @foreach ($resource->authors as $author)
                                <p>{{ $author->name }}</p>
                            @endforeach
                        </article>
                    @endif

                    @if (count($resource->translators))
                        <article class="resource-view-details">
                            <h3>@lang('Translator')</h3>
                            @foreach ($resource->translators as $translator)
                                <p>{{ $translator->name }}</p>
                            @endforeach
                        </article>
                    @endif

                    <article class="resource-view-details">
                        <h3>@lang('Resource Level')</h3>
                        @foreach ($resource->levels as $level)
                            <p><a href="{{ URL::to('resources/list?level=' . $level->id) }}"
                                    title="{{ $level->name }}">{{ $level->name }}</a></p>
                        @endforeach
                    </article>
                    <article class="resource-view-details">
                        <h3>@lang('Subject Area')</h3>
                        @foreach ($resource->subjects as $subject)
                            <p><a href="{{ URL::to('resources/list?subject_area=' . $subject->id) }}"
                                    title="{{ $subject->name }}">{{ $subject->name }}</a></p>
                        @endforeach
                    </article>
                    <article class="resource-view-details">
                        <h3>@lang('Learning Resource Type')</h3>
                        @foreach ($resource->LearningResourceTypes as $ltype)
                            <p><a href="{{ URL::to('resources/list?type=' . $ltype->id) }}"
                                    title="{{ $ltype->name }}">{{ $ltype->name }}</a></p>
                        @endforeach
                    </article>
                    <article class="resource-view-details">
                        <h3>@lang('Publisher')</h3>
                        @foreach ($resource->publishers as $publisher)
                            <p><a href="{{ URL::to('resources/list?publisher=' . $publisher->id) }}"
                                    title="{{ $publisher->name }}">{{ $publisher->name }}</a></p>
                        @endforeach
                    </article>
                    <article class="resource-view-details">
                        <h3>@lang('Languages Available')</h3>

                        <?php
                        $supportedLocals = [];
                        $newId = [];
                        foreach (config('laravellocalization.localesOrder') as $localeCode) {
                            $supportedLocals[] = $localeCode;
                        }
                        
                        if (isset($translations)) {
                            foreach ($translations as $tr) {
                                if (in_array($tr->language, $supportedLocals)) {
                                    $newId[$tr->language] = $tr->id;
                                }
                            }
                        }
                        ?>
                        <div class="display-flex gap-2" style="flex-wrap: wrap">

                            @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                @if (isset($newId[$localeCode]) && $newId != 0)
                                    <?php
                                    $currentUrl = explode('/', url()->current());
                                    $index = count($currentUrl) - 1;
                                    $value = $currentUrl[$index];
                                    $currentUrl[$index] = $newId[$localeCode];
                                    $newUrl = implode('/', $currentUrl);
                                    ?>
                                    <p class="bg-lightseagreen p-2 border-radius-5 white">
                                        {{ $properties['native'] }}
                                        <span class="fa fa-check-circle"></span>
                                    </p>
                                @else
                                    <p class="bg-red-300 p-2 border-radius-5 white">
                                        {{ $properties['native'] }}
                                    </p>
                                @endif
                            @endforeach
                        </div>
                    </article>
                    <article class="resource-view-details">
                        <h3>@lang('License')</h3>
                        <p>{{ $resource->creativeCommons ? $resource->creativeCommons[0]->name : '' }}</p>
                    </article>
                </div>
            </section>
            <aside>
                <img class="resource-view-img border-radius-5" src="{{ getImagefromResource($resource->abstract, '282x254') }}"
                    alt="Resource Main Image">

                <div class="resource-view-related-items">
                    <header>
                        <h2>@lang('Related Items')</h2>
                    </header>
                    <div class="resource-related-items-box">
                        @foreach ($relatedItems as $item)
                            <div class="related-item">
                                <img class="related-items-img border-radius-5" src="{{ getImagefromResource($item->abstract, '55x50') }}"
                                    alt="Resource Image">
                                <span><a title="{{ $item->abstract }}"
                                        href="{{ URL::to('resource/' . $item->id) }}">{{ $item->title }}</a><br />
                                    <small>
                                        {!! str_limit(strip_tags($item->abstract), 25) !!}
                                    </small>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @if (isAdmin() && $resource->user)
                    <br>
                    <div class="translated-resource-id">
                        <div>
                            @lang('Added by'):
                        </div>
                        <a class="flex-1" href="">
                            {{ $resource->user->profile?->first_name }}
                            {{ $resource->user->profile?->last_name }}
                        </a>
                    </div>
                @endif
                @if (isAdmin())
                    <div class="mt-1">
                        <form method="post" action="{{ route('updatetid', $resource->id) }}">
                            @csrf
                            <div class="translated-resource-id">
                                {{ __('If this resource is translated, enter the translated resource id and click submit:') }}
                                <div class="display-flex gap-1 mt-2">
                                    <input type="number" class="flex-1 border-radius-5 border-0" name="link" min=0
                                        placeholder=" {{ __('Enter the translated resource id') }} "
                                        class="form-control tnid-input">
                                    <input type="submit" class="form-control normalButton" value="{{ __('Submit') }}">
                                </div>
                            </div>
                        </form>
                        @if ($translations)
                            <br><b>Linked resources:</b>
                            @foreach ($translations as $resource)
                                <a href="{{ URL::to($resource->language . '/resource/' . $resource->id) }}"
                                    target="_blank">{{ $resource->id }} ({{ $resource->language }})</a>
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @endif
                    </div>
                @endif
            </aside>
            <section class="resource-view-comment border-radius-5">
                <header class="bg-yellow-600 align-items-center border-radius-top-5">
                    <h2 class="black-400"> @lang('Comments') </h2>
                    <h3 class="black-400"> {{ count($comments) }} @lang('comment(s) so far') </h3>
                </header>
                @foreach ($comments as $cm)
                    <article style="border:1px solid #f1f1f1; border-radius: 5px;" class="p-2">
                        <div>
                            <div class="display-flex align-items-center g-1">
                                <div>
                                    <img src="{{ $cm->user->avatar }}" class="user-profile-avatar">
                                </div>
                                <div class="ml-2 mr-2">
                                    <strong>
                                        {{ $cm->user->profile->first_name }}
                                        {{ $cm->user->profile->last_name }}
                                    </strong>
                                    <div>
                                        <span class="time">
                                            {{ $cm->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5">
                            {{ $cm->comment }}
                        </div>
                    </article>
                    <br>
                @endforeach
                @if (Auth::check())
                    <form method="POST" action="{{ route('comment') }}">
                        @csrf
                        <article>
                            <textarea name="comment" cols="40" rows="10" required></textarea>
                        </article>
                        <input type="hidden" value="{{ $resource->id }}" name="resource_id">
                        <input type="hidden" value="{{ Auth::id() }}" name="userid">
                        <div class="left-side mt-2">
                            <input class="form-control normalButton" type="submit" value="{{ __('Submit') }}">
                        </div>
                    </form>
                @else
                    <h4 class="download-resource">@lang('Please login to add comments.')</h4>
                @endif
            </section>
        @else
            <h1>@lang('Resource not found or is not yet translated!')</h1>
        @endif
    </section>
@endsection
