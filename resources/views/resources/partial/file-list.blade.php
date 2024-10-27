<style>
    .pagination {
        display: flex;
        list-style-type: none;
        padding: 0;
        margin-top:30px;
    }

    .pagination li {
        margin: 0 5px;
    }

    .pagination a,
    .pagination span {
        display: block;
        padding: 10px 15px;
        border: 1px solid #aab3bd;
        color: #007bff;
        text-decoration: none;
    }

    .pagination .active span {
        background-color: #cfddec;
        color: white;
    }

    .pagination .disabled span {
        color: #ccc;
    }

    .image-list div.selected {
        border: 2px solid #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
</style>
<div class="image-grid">
    @foreach ($files as $file)
        <div class="image-item" style="position:relative;" data-uuid="{{ $file->uuid }}" data-url="{{ $file->path }}">
            <div class="image-container" style="height:150px;width:150px;">
                <img src="{{ $file->path }}" alt="{{ $file->name }}" class="resource-cover">
            </div>
            <p class="image-name">{{ $file->name }}</p>
        </div>
    @endforeach
</div>

@if($files->hasPages())
<div class="pagination-container">
    {{ $files->links() }}
</div>
@endif
