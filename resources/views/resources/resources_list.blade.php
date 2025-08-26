@extends('layouts.main')
@section('title')
@lang('Latest Resources') - @lang('Darakht-e Danesh Online Library')
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
    <div class="container-fluid">
        @if ($resources)
            <div class="row justify-content-center">
                @foreach ($resources->unique('id') AS $resource)
                    @if ($resource->status)
                        <div class="card resource-card col-8 col-md-4 col-xl-3 col-xxl-2 m-1 p-0">
                            <img class="card-img-top lazyload" data-src="{{ $resource->image ? $resource->image : getImagefromResource($resource->abstract, '282x254') }}" alt="Resource image" src="">
                            <div class="card-body" style="padding: 0.75rem;">
                                <p class="card-text">{{ $resource->title }}</p>
                            </div>
                            <div class="card-footer text-muted resource-list-footer-style-override">
                                <span><i class="fa-solid fa-eye"></i> {{ $views->where('resource_id', $resource->id)->count() }}</span>
                                <span class="resource-list-card-footer-separator"><i class="fa-solid fa-comments"></i> {{ $comments->where('resource_id', $resource->id)->count() }}</span>
                                <span class="resource-list-card-footer-separator"><i class="fa-solid fa-star"></i> {{ $favorites->where('resource_id', $resource->id)->count() }}</span>
                            </div>
                            <a href="{{ URL::to('resource/'.$resource->id) }}" class="stretched-link"></a>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <h2>@lang('No records found!')</h2>
        @endif
        <div class="resource-pagination">
            {{ $resources->appends(request()->input())->links() }}
        </div>
    </div>
@endsection
