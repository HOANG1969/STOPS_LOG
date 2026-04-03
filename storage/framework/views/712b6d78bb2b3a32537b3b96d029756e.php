

<?php $__env->startSection('title', 'Chi tiết yêu cầu'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-xl font-semibold text-gray-800">
                Chi tiết yêu cầu - <?php echo e($request->request_code); ?>

            </h4>
        </div>
        
        <div class="p-6">
            <!-- Thông tin chung -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="space-y-3">
                    <div><span class="font-medium">Mã yêu cầu:</span> <?php echo e($request->request_code); ?></div>
                    <div><span class="font-medium">Người yêu cầu:</span> <?php echo e($request->requester_name); ?></div>
                    <div><span class="font-medium">Email:</span> <?php echo e($request->requester_email); ?></div>
                    <div><span class="font-medium">Bộ phận:</span> <?php echo e($request->department); ?></div>
                </div>
                <div class="space-y-3">
                    <div><span class="font-medium">Ngày tạo:</span> <?php echo e($request->created_at->format('d/m/Y H:i')); ?></div>
                    <div><span class="font-medium">Ngày cần:</span> <?php echo e(\Carbon\Carbon::parse($request->needed_date)->format('d/m/Y')); ?></div>
                    <div><span class="font-medium">Ưu tiên:</span> 
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                            <?php if($request->priority === 'Normal'): ?> bg-blue-100 text-blue-800
                            <?php elseif($request->priority === 'High'): ?> bg-yellow-100 text-yellow-800
                            <?php else: ?> bg-red-100 text-red-800
                            <?php endif; ?>">
                            <?php echo e($request->priority); ?>

                        </span>
                    </div>
                    <div><span class="font-medium">Trạng thái:</span>
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
                    </div>
                </div>
            </div>

            <?php if($request->notes): ?>
            <div class="mb-6">
                <h5 class="font-semibold mb-2">Ghi chú:</h5>
                <div class="bg-gray-50 p-3 rounded"><?php echo e($request->notes); ?></div>
            </div>
            <?php endif; ?>

            <!-- Danh sách văn phòng phẩm -->
            <div class="mb-6">
                <h5 class="font-semibold mb-3">Danh sách văn phòng phẩm:</h5>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 border text-left">STT</th>
                                <th class="px-4 py-2 border text-left">Tên VPP</th>
                                <th class="px-4 py-2 border text-left">ĐVT</th>
                                <th class="px-4 py-2 border text-left">Số lượng</th>
                                <th class="px-4 py-2 border text-left">Mục đích</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $request->requestItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border"><?php echo e($index + 1); ?></td>
                                    <td class="px-4 py-2 border"><?php echo e($item->officeSupply->name); ?></td>
                                    <td class="px-4 py-2 border"><?php echo e($item->officeSupply->unit); ?></td>
                                    <td class="px-4 py-2 border"><?php echo e($item->quantity); ?></td>
                                    <td class="px-4 py-2 border"><?php echo e($item->purpose); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Nút hành động -->
            <div class="flex justify-between">
                <a href="<?php echo e(route('employee.requests.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Quay lại
                </a>
                
                <div class="space-x-2">
                    <?php if($request->status === 'pending'): ?>
                        <form action="<?php echo e(route('employee.requests.forward', $request)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded" 
                                    onclick="return confirm('Chuyển đơn này để phê duyệt?')">
                                Chuyển phê duyệt
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('employee.requests.history', $request)); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Xem lịch sử
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\employee\requests\show-simple.blade.php ENDPATH**/ ?>