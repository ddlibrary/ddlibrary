<style>
.custom-alert {
    margin:90px 30px;
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
.cursor-pointer {
    cursor: pointer;
}
</style>
<div id="alert-message" class="z-index-2 ">
    <div class="position-absolute p-3 border-transparent rounded min-w-100 top-0 custom-alert custom-alert-{{ $level }} {{ Lang::locale() == 'en' ? 'position-right-0' : 'position-left-0' }}">
        <div>
            <div class="d-flex">
                <div class="{{ Lang::locale() == 'en' ? 'me-2' : 'ms-2' }}">
                    <span class="cursor-pointer" onclick="alertBox()" aria-hidden="true">&times;</span>
                </div>
                <div>
                    {{ $message }}
                </div>
            </div>
            <div class="position-absolute bottom-0 start-0  progress-bar progress-bar-{{ $level }}"></div>
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
