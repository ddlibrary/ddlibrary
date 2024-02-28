@if ($resources)
    @foreach ($resources->unique('id') as $resource)
        @if ($resource->status)
            <article class="resource-article resource-information">
                <div class="resource-items">
                    <div class="p-1">
                        <a href="{{ URL::to('resource/' . $resource->id) }}" target="_blank" class="image-link">
                            <div class="overflow-hidden">
                                <img class="resource-img lazyload"
                                    data-src="{{ getImagefromResource($resource->abstract) }}" alt="Resource Image">
                            </div>
                        </a>
                    </div>
                    <div class="resource-title flex-1">
                        <a href="{{ URL::to('resource/' . $resource->id) }}" target="_blank" class="resource-link">
                            {{ $resource->title }}
                        </a>
                    </div>
                    <div class="resource-details">
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
@else
    <h2>@lang('No records found!')</h2>
@endif
<div class="resource-pagination">
    {{ $resources->appends(request()->input())->links() }}
</div>
