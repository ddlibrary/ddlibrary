<script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>

<script>
    var filebrowserImageUploadUrl = "{{ route('upload.image.from.editor') }}";
    
    // Ensure TinyMCE is fully loaded before initializing
    if (typeof tinymce === 'undefined') {
        // Wait for TinyMCE to load
        var checkTinyMCE = setInterval(function() {
            if (typeof tinymce !== 'undefined' && typeof tinymce.init === 'function') {
                clearInterval(checkTinyMCE);
            }
        }, 50);
    }
</script>

<script src="{{ asset('js/tinymce.js') }}?v={{ filemtime(public_path('js/tinymce.js')) }}" referrerpolicy="origin"></script>
