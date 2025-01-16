@if(count($files))
<div class="image-grid">
    @foreach ($files as $file)
        <div class="image-item" style="position:relative;" data-uuid="{{ $file->uuid }}" data-url="{{ $file->thumbnail_path }}">
            <div class="image-container responsive-square">
                <img src="{{ $file->thumbnail_path }}" alt="{{ $file->name }}" class="resource-cover">
            </div>
            <p class="image-name">{{ $file->name }}</p>
        </div>
    @endforeach
</div>
@else
<div style="border:1px solid lightgray; border-radius:5px; padding: 20px;">

    <h2 class="text-center text-red">@lang('Resource Not Found')</h2>
</div>
@endif

@if ($files->hasPages())
    <div class="pagination-container">
        {{ $files->links() }}
    </div>
@endif
