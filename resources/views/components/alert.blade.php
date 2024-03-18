<div style="position: absolute; display: none;z-index:2" id="alert-message">
    <div class="alert alert-{{ $level }}">
        <p>
            {{ $message }}
        <div class="progress-bar progress-bar-{{$level}}"></div>
        </p>
    </div>
</div>
<script>
    window.onload = function() {
        var successMessageElement = document.getElementById('alert-message');
        successMessageElement.style.display = 'block';
        setTimeout(function() {
            successMessageElement.style.display = 'none';
        }, 10000);
    };
</script>
