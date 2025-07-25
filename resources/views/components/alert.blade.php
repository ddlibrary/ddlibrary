<style>
.custom-alert {
    position: absolute;
    padding: 0.75rem 1.25rem;
    border: 1px solid transparent;
    border-radius: 0.25rem;
    margin:90px 30px;
    top:0;
    min-width: 312px;
    max-width: 350px;
}

.custom-alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.custom-alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
.progress-bar-success{
    background-color: lightseagreen;
}

.progress-bar-danger{
    background-color: #f79898;
}
.progress-bar {
    height: 5px;
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    animation: progress 10s linear forwards;
}

@keyframes progress {
    0% {
        width: 100%;
    }

    100% {
        width: 0%;
    }
}
</style>
<div id="alert-message" class="z-index-2 ">
    <div class="custom-alert custom-alert-{{ $level }} {{ Lang::locale() == 'en' ? 'position-right-0' : 'position-left-0' }}">
        <div>
            <div class="d-flex">
                <div class="{{ Lang::locale() == 'en' ? 'me-2' : 'ms-2' }}">
                    <span class="cursor-pointer" onclick="alertBox()" aria-hidden="true">&times;</span>
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
