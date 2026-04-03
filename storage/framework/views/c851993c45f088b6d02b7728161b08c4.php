

<?php $__env->startSection('title', 'Test Create'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-xl font-semibold mb-4">Test Create Page</h1>
        <p>Đây là trang test. Nếu bạn thấy được nội dung này, nghĩa là view đã hoạt động.</p>
        
        <p><strong>Số lượng văn phòng phẩm:</strong> <?php echo e($officeSupplies->count()); ?></p>
        
        <?php if($officeSupplies->count() > 0): ?>
        <div class="mt-4">
            <h3 class="font-semibold">Danh sách văn phòng phẩm (5 item đầu tiên):</h3>
            <ul class="mt-2">
                <?php $__currentLoopData = $officeSupplies->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($supply->name); ?> - <?php echo e($supply->unit); ?> (<?php echo e($supply->stock_quantity); ?>)</li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <div class="mt-4">
            <a href="<?php echo e(route('employee.requests.index')); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Về danh sách
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\test-create.blade.php ENDPATH**/ ?>