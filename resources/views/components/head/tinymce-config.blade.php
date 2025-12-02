<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!-- TinyMCE Free Version from Composer Package -->
<script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>

<script>
    var filebrowserImageUploadUrl = '{{ url('/upload-abstract-image') }}';
</script>
<script src="{{ asset('js/tinymce.js') }}" referrerpolicy="origin"></script>
