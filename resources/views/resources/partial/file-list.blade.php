<style>
    .image-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .image-item {
        position: relative;
        width: calc(25% - 10px);
        aspect-ratio: 1;
        overflow: hidden;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .image-container {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        /* Hide overflow */
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Cover the entire area while maintaining aspect ratio */
    }

    .pagination {
        display: flex;
        list-style-type: none;
        padding: 0;
        margin-top: 30px;
    }

    .pagination li {
        margin: 0 2px;
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

    @media (max-width: 768px) {
        .image-item {
            width: calc(50% - 10px);
            /* 2 images per row on smaller screens */
        }
    }

    @media (max-width: 480px) {
        .image-item {
            width: 100%;
            /* 1 image per row on very small screens */
        }
    }

    .responsive-square {
        width: 100%;
        aspect-ratio: 1 / 1;
        background-color: #f0f0f0;
        overflow: hidden;
    }

    .responsive-square img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
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

@if ($files->hasPages())
    <div class="pagination-container">
        {{ $files->links() }}
    </div>
@endif
