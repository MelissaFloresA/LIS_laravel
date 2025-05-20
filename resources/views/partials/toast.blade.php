<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="statusToast"
        class="toast align-items-center {{ session('success') ? 'text-bg-success' : 'text-bg-danger' }} border-0"
        role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">{{ session('success') ? session('success') : session('error') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toastEl = document.getElementById('statusToast');
        
        @if (session('success') || session('error'))
            new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 5000
            }).show();
        @endif
    });
</script>
