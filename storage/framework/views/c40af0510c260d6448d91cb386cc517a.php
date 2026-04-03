<!-- Redirect to supply-requests page -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Redirect to the correct supply requests page
    window.location.href = '<?php echo e(route("supply-requests.index")); ?>';
});
</script>

<div class="card">
    <div class="card-body text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Đang chuyển hướng tới trang đăng ký văn phòng phẩm...</p>
        <p><a href="<?php echo e(route('supply-requests.index')); ?>" class="btn btn-primary">Nhấn vào đây nếu không tự động chuyển hướng</a></p>
    </div>
</div><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\partials\request-form.blade.php ENDPATH**/ ?>