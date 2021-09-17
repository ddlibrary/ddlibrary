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
<div class="container-fluid">
    @include('layouts.messages')
    <div class="row m-2 mt-md-4">
        <div class="col-md-8">
            @if (Auth::check() && auth()->user()->hasVerifiedEmail())
                @if($resource->attachments)
                    @foreach($resource->attachments as $file)
                        @if($file->file_mime=="application/pdf")
                            <iframe
                                src="{{ URL::to(config('constants.ddlmain_s3_file_storage_url').'/resources/'.$file->file_name) }}#toolbar=0"
                                height="500"
                                width="100%"
                                style="border: none;"
                            ></iframe>
                        @elseif(
                            $file->file_mime == "application/msword"
                            ||
                            $file->file_mime == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                        )
                            <iframe
                                src="{{ URL::to(config('constants.google_doc_viewer_url').config('constants.ddlmain_s3_file_storage_url').'/resources/'.$file->file_name.'&embedded=true') }}"
                                height="500"
                                width="100%"
                                style="border: none;"
                            ></iframe>
                        @elseif($file->file_mime == "audio/mpeg")
                            <span class="download-item">
                                    <audio controls>
                                        <source src="{{ URL::to(config('constants.ddlmain_s3_file_storage_url').'/resources/'.$file->file_name) }}" type="audio/mpeg">
                                    </audio>
                                </span>
                        @else
                            <span class="download-item no-preview">@lang('No preview available.')</span>
                        @endif

                        {{-- revert to older direct download format until we have the correct packages installed for PDF watermarking <span class="download-item"><a class="btn btn-primary"
                                                        href="{{ URL::to('resource/'.$resource->id.'/download/'.$file->id) }}"><i
                            class="fa fa-download" aria-hidden="true"></i> @lang('Download') ({{ formatBytes($file->file_size) }})</a>
                        <br>
                        <hr>
                        </span>--}}
                        <h6>@lang('File :id', ['id' => $loop->iteration])
                            <span class="badge badge-secondary">
                                @php
                                    /* @var $file */
                                    echo(pathinfo($file->file_name, PATHINFO_EXTENSION));
                                @endphp
                            </span>
                            <span class="">
                                <a class="btn btn-primary btn-sm"
                                   href="{{ URL::to(config('constants.ddlmain_s3_file_storage_url').'/resources/'.$file->file_name) }}"
                                >
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    @lang('Download') ({{ formatBytes($file->file_size) }})
                                </a>
                            </span>
                        </h6>
                    @endforeach
                @endif
            @elseif(Auth::check() && !auth()->user()->hasVerifiedEmail())
                <iframe
                    src="{{ URL::to('resource/no-show/no-verify') }}"
                    height="500"
                    width="100%"
                    style="border: 1px solid #000000"
                ></iframe>
            @else
                <iframe
                    src="{{ URL::to('resource/no-show/no-auth') }}"
                    height="500"
                    width="100%"
                    style="border: 1px solid #000000"
                ></iframe>
            @endif
            <div id="resource-title" class="row pt-md-2">
                <h4 class="col-md-8" >{{ $resource->title }}</h4>
                <div class="col-md-4 p-1 text-right">
                    @if (isLibraryManager() or isAdmin())
                        <a href="{{ URL::to($resource->language.'/resources/edit/step1/'.$resource->id) }}"><i class="far fa-lg fa-edit" aria-hidden="true" title="@lang('Edit')"></i></a>
                    @endif
                    &nbsp;
                    <i class="far fa-lg fa-star @if(empty($resource->favorites[0])) @else active @endif"
                        title="@lang('Mark this resource as your favorite')"
                        id="resourceFavorite"
                        style="cursor: pointer;"
                        @if(Auth::check())
                            onclick="favorite('resourceFavorite','{{ URL::to("resources/favorite/") }}','{{ $resource->id }}','{{ Auth::id() }}')"
                        @else
                            onclick="alert('Please login to mark a resource as your favorite')"
                        @endif
                    ></i>
                    &nbsp;
                    <i class="fas fa-lg fa-share-alt" style="cursor: pointer;" data-toggle="modal" aria-hidden="true" data-target="#shareModal" title="@lang('Share this resource')"></i>
                    <div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="shareModalLabel">@lang('Share this resource')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body row justify-content-center">
                            <i class="fab fa-twitter fa-4x col-md-2"
                               title="Share to Twitter"
                               style="color: #1da1f2; cursor: pointer;"
                               onclick="window.location.href='https://twitter.com/intent/tweet?url={{ Request::url() }}'"
                            ></i>
                            <i class="fab fa-facebook fa-4x col-md-2"
                               title="Share to Facebook"
                               style="color: #4267b2; cursor: pointer;"
                               onclick="window.location.href='https://www.facebook.com/sharer/sharer.php?u={{ Request::url() }}'"
                            ></i>
                            <i class="fab fa-linkedin fa-4x col-md-2"
                               title="Share to LinkedIn"
                               style="color: #0077b5; cursor: pointer;"
                               onclick="window.location.href='https://www.linkedin.com/sharing/share-offsite/?url={{ Request::url() }}'"
                            ></i>
                          </div>
                        </div>
                      </div>
                    </div>
                    &nbsp;
                    <i class="far fa-lg fa-flag" style="cursor: pointer;" data-toggle="modal" aria-hidden="true" data-target="#reportModal" title="@lang('Report this resource')"></i>
                    <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="reportModalLabel">@lang('Report this resource')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('flag') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="type">
                                                @lang('Type')
                                            </label>
                                            <select name="type" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" required>
                                                <option value="">-</option>
                                                <option value="1">@lang('Graphic Violence')</option>
                                                <option value="2">@lang('Graphic Sexual Content')</option>
                                                <option value="3">@lang('Spam, Scam or Fraud')</option>
                                                <option value="4">@lang('Broken or Empty Data')</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="details">
                                                @lang('Details')
                                            </label>
                                            <textarea name="details" class="form-control" rows="5" required></textarea>
                                        </div>
                                        <input type="hidden" value="{{ $resource->id }}" name="resource_id">
                                        <input type="hidden" value="{{ Auth::id() }}" name="userid">
                                        <input class="form-control normalButton" type="submit" value="@lang('Submit')">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <span class="text-secondary">{{ $views->where('resource_id', $resource->id)->count() }} @lang('views')</span>
                </div>
                <div class="col-2 text-secondary">
                    <i class="far fa-star"></i> <span class="text-secondary">{{ $favorites->where('resource_id', $resource->id)->count()  }}</span>
                </div>
                <div class="col-8 text-right">
                    <a href="{{ URL::to('glossary') }}" class="glossary-icon"><i class="fas fa-globe" title="@lang('DDL Glossary')" ><span class="glossary-text">&nbsp;@lang('Glossary')</span> </i></a>
                </div>
            </div>
            <hr>
            <div id="resource-view-title-box">
                {!! fixImage($resource->abstract, $resource->id) !!}
            </div>
            <br>
            <h5 class="py-1 pl-1" id=@if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr') "meta-box-title" @else "meta-box-title-rtl" @endif>@lang('About this resource')</h5>
            <div class="row mb-2 pl-2">
                <div class="col-md-4 mb-1">
                    <h6>@lang('Available in the following languages')</h6>
                    <ul>
                        @foreach($languages_available as $locale => $properties)
                            <li>
                                <a rel="alternate" title="language" hreflang="{{ $locale }}" href="{{ LaravelLocalization::getLocalizedURL($locale, $properties['url'], [], true) }}">
                                    {{ $properties['native'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-4 mb-1">
                    <h6>@lang('Subject area')</h6>
                    @foreach ($resource->subjects AS $subject)
                        <a class="badge badge-info" href="{{ URL::to('resources/list?subject_area='.$subject->id) }}" title="{{ $subject->name }}">{{ $subject->name }}</a>
                    @endforeach
                </div>
                <div class="col-md-4 mb-1">
                    <h6>@lang('Resource level')</h6>
                    @foreach ($resource->levels AS $level)
                        <a class="badge badge-info" href="{{ URL::to('resources/list?level='.$level->id) }}" title="{{ $level->name }}">{{ $level->name }}</a>
                    @endforeach
                </div>
            </div>
            <div class="row mb-2 pl-2">
                <div class="col-md-4 mb-1">
                    <h6>@lang('Resource type')</h6>
                    @foreach($resource->LearningResourceTypes AS $ltype)
                        <p><a href="{{ URL::to('resources/list?type='.$ltype->id) }}" title="{{ $ltype->name }}">{{ $ltype->name }}</a></p>
                    @endforeach
                </div>
                @if($resource->authors[0])
                    <div class="col-md-4 mb-1">
                        <h6>@lang('Author')</h6>
                        @foreach ($resource->authors AS $author)
                            <p>{{ $author->name }}</p>
                        @endforeach
                    </div>
                @endif

                @if($resource->translators[0])
                    <div class="col-md-4 mb-1">
                        <h6>@lang('Translator')</h6>
                        @foreach ($resource->translators AS $translator)
                            <p>{{ $translator->name }}</p>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="row mb-2 pl-2">
                <div class="col-md-4 mb-1">
                    <h6>@lang('Publisher')</h6>
                    @foreach($resource->publishers AS $publisher)
                    <p><a href="{{ URL::to('resources/list?publisher='.$publisher->id) }}" title="{{ $publisher->name }}">{{ $publisher->name }}</a></p>
                    @endforeach
                </div>
                <div class="col-md-4 mb-1">
                    <h6>@lang('License')</h6>
                    <p class="badge badge-info">{{ $resource->creativeCommons?$resource->creativeCommons[0]->name:"" }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="p-3">
                    <img class="resource-view-img" src="{{ getImagefromResource($resource->abstract, '282x254') }}" alt="Resource Main Image">
                </div>
            </div>

            <div class="card">
                <div class="card-body p-1">
                    <h4>@lang('Similar resources')</h4>
                    @foreach ($relatedItems AS $item)
                    <div class="row mb-2">
                        <div class="col-3">
                            <img class="resource-view-img" src="{{ getImagefromResource($item->abstract,'55x50') }}" alt="Resource Image">
                        </div>
                        <div class="col-8">
                            <span><a title="Resource Title" href="{{ URL::to('resource/'.$item->id) }}">{{ $item->title }}</a><br/>
                            {!! str_limit(strip_tags($item->abstract), 25) !!}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body p-3">
                    @if (isAdmin() or isLibraryManager())
                        @if(isset($resource->user))
                            <p>@lang('Added by'):
                                <a href="{{ route('user-view', $resource->user->id) }}">
                                    {{ $resource->user->username }}
                                </a>
                            </p>
                        @endif

                        <form class="form-row" method="post" action="{{ route('updatetid', $resource->id) }}">
                            @csrf
                            <label for="link">If this resource is translated, enter the id of the translated resource and click submit.</label>
                            <div class="form-group col-5 my-2">
                                <input type="text" name="link" class="form-control">
                            </div>
                            <input type="submit" class="btn btn-primary col-2 my-2" value="@lang('Submit')">
                        </form>
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
                <div class="form-group">
                    <label for="commentTextArea">
                        @if (Auth::check())
                            @lang('Enter your comment below')
                        @else
                            @lang('Please login to add a comment')
                        @endif
                    </label>
                    <textarea class="form-control" name="comment" id="commentTextArea" rows="3" @if (!Auth::check()) disabled @endif></textarea>
                </div>
                <button type="submit" class="btn btn-primary offset-md-11" @if (!Auth::check()) disabled @endif>@lang('Submit')</button>
            </form>

            @foreach($comments AS $cm)
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
@endsection




