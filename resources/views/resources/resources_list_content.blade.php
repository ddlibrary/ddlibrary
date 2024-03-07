@if ($resources)
    <div class="w-100 book-list">

        @foreach ($resources->unique('id') as $resource)
            @if ($resource->status)
                <article class="">
                    <div class="resource-items border-radius-5 no-border book-items">
                        <div class="p-1 w-100">
                            <a href="{{ URL::to('resource/' . $resource->id) }}" target="_blank" class="image-link">
                                <div class="overflow-hidden w-100">
                                    <div class="display-flex">
                                        <img class="w-100 book-cover lazyload"
                                            data-src="{{ getImagefromResource($resource->abstract) }}" alt="Resource Image">
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="resource-title flex-1 w-100">
                            <a href="{{ URL::to('resource/' . $resource->id) }}" target="_blank" class="resource-link">
                                {{ $resource->title }}
                            </a>
                        </div>
                        <div class="resource-details w-100">
                            <article>
                                <i
                                    class="fas fa-eye"></i><span>{{ $views->where('resource_id', $resource->id)->count() }}</span>
                            </article>
                            <article>
                                <i
                                    class="fas fa-star"></i><span>{{ $favorites->where('resource_id', $resource->id)->count() }}</span>
                            </article>
                            <article>
                                <i
                                    class="fas fa-comment"></i><span>{{ $comments->where('resource_id', $resource->id)->count() }}</span>
                            </article>
                        </div>
                    </div>
                </article>
            @endif
        @endforeach
    </div>
@else
    <h2>@lang('No records found!')</h2>
@endif
<div class="resource-pagination">
    {{ $resources->appends(request()->input())->links() }}
</div>
