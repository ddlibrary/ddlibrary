<script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>

<script>
    var filebrowserImageUploadUrl = "{{ route('upload.image.from.editor') }}";
</script>

@vite('resources/assets/js/tinymce.js')
