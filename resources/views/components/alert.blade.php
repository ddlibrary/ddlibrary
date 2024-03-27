<div id="alert-message" class="z-index-2 display-none">
    <div class="alert alert-{{ $level }} {{ Lang::locale() == 'en' ? 'position-right-0' : 'position-left-0' }}">
        <div>
            <div class="display-flex">
                <div class="{{ Lang::locale() == 'en' ? 'mr-2' : 'ml-2' }}">
                    <span class="pointer" onclick="alertBox()" aria-hidden="true">&times;</span>
                </div>
                <div>
                    {{ $message }}
                </div>
            </div>
            <div class="progress-bar progress-bar-{{ $level }}"></div>
        </div>
    </div>
</div>
<script>
    window.onload = function() {
        alertBox('block');
        setTimeout(function() {
            alertBox()
        }, 10000);
    };

    function alertBox(display = 'none') {
        document.getElementById('alert-message').setAttribute('style', `display:${display} !important`);
    }
</script>
