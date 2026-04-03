

<?php $__env->startSection('title', 'Quản lý Phiếu đăng ký'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h4 class="text-xl font-semibold text-gray-800">
                <i class="fas fa-clipboard-list mr-2 text-blue-500"></i>
                Quản lý Phiếu đăng ký
            </h4>
            <a href="<?php echo e(route('employee.requests.create')); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                <i class="fas fa-plus mr-1"></i>
                Tạo mới Phiếu đăng ký
            </a>
        </div>
        
        <div class="p-6">
            <!-- Thông tin bộ phận và kỳ -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded">
                <div>
                    <span class="font-medium text-gray-700">Bộ phận:</span> 
                    <span class="text-gray-600"><?php echo e(auth()->user()->department ?? 'N/A'); ?></span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Kỳ:</span> 
                    <span class="text-gray-600"><?php echo e(now()->format('F Y')); ?></span>
                </div>
            </div>

            <!-- Flash messages -->
            <?php if(session('success')): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <!-- Bảng danh sách yêu cầu -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 border-b text-left text-sm font-medium text-gray-700">#</th>
                            <th class="px-4 py-3 border-b text-left text-sm font-medium text-gray-700">Mã đơn</th>
                            <th class="px-4 py-3 border-b text-left text-sm font-medium text-gray-700">Ngày tạo</th>
                            <th class="px-4 py-3 border-b text-left text-sm font-medium text-gray-700">Ngày cần</th>
                            <th class="px-4 py-3 border-b text-left text-sm font-medium text-gray-700">Ưu tiên</th>
                            <th class="px-4 py-3 border-b text-left text-sm font-medium text-gray-700">Trạng thái</th>
                            <th class="px-4 py-3 border-b text-left text-sm font-medium text-gray-700">Người duyệt</th>
                            <th class="px-4 py-3 border-b text-left text-sm font-medium text-gray-700">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm"><?php echo e($requests->firstItem() + $index); ?></td>
                                <td class="px-4 py-3 text-sm">
                                    <a href="<?php echo e(route('employee.requests.show', $request)); ?>" class="text-blue-600 hover:text-blue-800">
                                        <?php echo e($request->request_code); ?>

                                    </a>
                                </td>
                                <td class="px-4 py-3 text-sm"><?php echo e($request->created_at->format('d/m/Y')); ?></td>
                                <td class="px-4 py-3 text-sm"><?php echo e(\Carbon\Carbon::parse($request->needed_date)->format('d/m/Y')); ?></td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                        <?php if($request->priority === 'Normal'): ?> bg-blue-100 text-blue-800
                                        <?php elseif($request->priority === 'High'): ?> bg-yellow-100 text-yellow-800
                                        <?php else: ?> bg-red-100 text-red-800
                                        <?php endif; ?>">
                                        <?php echo e($request->priority); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                        <?php if($request->status === 'pending'): ?> bg-gray-100 text-gray-800
                                        <?php elseif($request->status === 'forwarded'): ?> bg-yellow-100 text-yellow-800
                                        <?php elseif($request->status === 'approved'): ?> bg-green-100 text-green-800
                                        <?php else: ?> bg-red-100 text-red-800
                                        <?php endif; ?>">
                                        <?php switch($request->status):
                                            case ('pending'): ?> Chờ xử lý <?php break; ?>
                                            <?php case ('forwarded'): ?> Đã chuyển <?php break; ?>
                                            <?php case ('approved'): ?> Đã duyệt <?php break; ?>
                                            <?php case ('rejected'): ?> Từ chối <?php break; ?>
                                        <?php endswitch; ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <?php echo e($request->approver->name ?? '-'); ?>

                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex space-x-2">
                                        <a href="<?php echo e(route('employee.requests.show', $request)); ?>" 
                                           class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <?php if($request->status === 'pending'): ?>
                                            <form action="<?php echo e(route('employee.requests.forward', $request)); ?>" 
                                                  method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs" 
                                                        onclick="return confirm('Bạn có chắc muốn chuyển đơn này để phê duyệt?')">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <a href="<?php echo e(route('employee.requests.history', $request)); ?>" 
                                           class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs">
                                            <i class="fas fa-history"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center">
                                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">Chưa có yêu cầu nào</p>
                                    <a href="<?php echo e(route('employee.requests.create')); ?>" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                        Tạo yêu cầu đầu tiên
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <?php echo e($requests->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\employee\requests\index-new.blade.php ENDPATH**/ ?>