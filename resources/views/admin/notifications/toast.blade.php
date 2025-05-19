<style>
    .toast {
    display: none;
    font-size: 13px;
    min-width: 250px;
    margin: 10px;
    padding: 15px;
    color: white;
    border-radius: 5px;
    opacity: 0.9;
    transition: opacity 0.5s ease;
}

.toast.success {
    background-color: green;
}

.toast.error {
    background-color: red;
}
</style>

<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<script>
    function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = 'toast ' + type;
    toast.innerText = message;

    document.getElementById('toast-container').appendChild(toast);
    toast.style.display = 'block';

    // Fade out and remove the toast after 5 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => {
            toast.remove();
        }, 500); // Wait for fade out before removing
    }, 5000);
}
</script>