@if ($resources)
    <div class="w-100 book-list">

        @foreach ($resources->unique('id') as $resource)
            @if ($resource->status)
                <article class="">
                    <div class="resource-items border-radius-5 no-border book-items">
                        <div class="w-100">
                            <a href="{{ URL::to('resource/' . $resource->id) }}" target="_blank" class="image-link">
                                <div class="overflow-hidden w-100" style="border-radius: 5px 5px 0px 0px">
                                    <div class="display-flex">
                                        <img class="w-100 book-cover lazyload"
                                            data-src="{{ getImagefromResource($resource->abstract) }}"
                                            alt="Resource Image">
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="resource-title flex-1 w-100" style="padding:15px 10px">
                            <a href="{{ URL::to('resource/' . $resource->id) }}" target="_blank" class="resource-link">
                                {{ $resource->title }}
                            </a>
                        </div>
                        <div class="resource-details resource-icons w-100">
                            <article class="align-items-center gap-1">
                                <i class="fas fa-eye yellow"></i> <span class="yellow">
                                    {{ $views->where('resource_id', $resource->id)->count() }}</span>
                            </article>
                            <article class="align-items-center gap-1">
                                <i class="fas fa-star yellow"></i> <span class="yellow">
                                    {{ $favorites->where('resource_id', $resource->id)->count() }}</span>
                            </article>
                            <article class="align-items-center gap-1">
                                <i class="fas fa-comment yellow"></i> <span class="yellow">
                                    {{ $comments->where('resource_id', $resource->id)->count() }}</span>
                            </article>
                        </div>
                    </div>
                </article>
            @endif
        @endforeach
    </div>
@else
    <h2>{{ __('No records found!') }}</h2>
@endif
<div class="resource-pagination">
    {{ $resources->appends(request()->input())->links() }}
</div>
