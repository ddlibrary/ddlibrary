@if (count($files))
    <div class="row g-2">
        @foreach ($files as $file)
            <div class="col-6 col-sm-4 col-md-3" data-uuid="{{ $file->uuid }}"
                onclick="selectImage('{{ $file->uuid }}', '{{ $file->path }}')">
                <div
                    class="position-relative border border-secondary rounded overflow-hidden w-100  h-100 image-{{ $file->uuid }}">
                    <div class="ratio ratio-1x1">
                        <img src="{{ $file->thumbnail_path }}" alt="{{ $file->name }}" class="img-fluid">
                    </div>
                    <p class="text-center mb-0">{{ $file->name }}</p>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="border border-secondary rounded p-3 w-100">
        <h2 class="text-center text-danger">@lang('Resource not found')</h2>
    </div>
@endif

@if ($files->hasPages())
    <div class="pagination-container mt-3">
        {{ $files->links() }}
    </div>
@endif
