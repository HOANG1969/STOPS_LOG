

<?php $__env->startSection('title', 'Lịch sử yêu cầu'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-xl font-semibold text-gray-800">
                Lịch sử yêu cầu - <?php echo e($request->request_code); ?>

            </h4>
        </div>
        
        <div class="p-6">
            <!-- Thông tin tóm tắt -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 rounded">
                <div>
                    <span class="font-medium">Mã yêu cầu:</span><br>
                    <span class="text-blue-600"><?php echo e($request->request_code); ?></span>
                </div>
                <div>
                    <span class="font-medium">Người yêu cầu:</span><br>
                    <?php echo e($request->requester_name); ?>

                </div>
                <div>
                    <span class="font-medium">Ngày tạo:</span><br>
                    <?php echo e($request->created_at->format('d/m/Y H:i')); ?>

                </div>
                <div>
                    <span class="font-medium">Trạng thái hiện tại:</span><br>
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

            <!-- Timeline lịch sử -->
            <div class="mb-6">
                <h5 class="font-semibold mb-4">Lịch sử xử lý:</h5>
                
                <div class="space-y-4">
                    <?php $__currentLoopData = $timeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                <?php if($event['action'] === 'created'): ?> bg-blue-100
                                <?php elseif($event['action'] === 'forwarded'): ?> bg-yellow-100
                                <?php elseif($event['action'] === 'approved'): ?> bg-green-100
                                <?php else: ?> bg-red-100
                                <?php endif; ?>">
                                <i class="fas 
                                    <?php if($event['action'] === 'created'): ?> fa-plus text-blue-600
                                    <?php elseif($event['action'] === 'forwarded'): ?> fa-paper-plane text-yellow-600
                                    <?php elseif($event['action'] === 'approved'): ?> fa-check text-green-600
                                    <?php else: ?> fa-times text-red-600
                                    <?php endif; ?>"></i>
                            </div>
                        </div>
                        <div class="flex-grow">
                            <div class="bg-gray-50 p-4 rounded">
                                <h6 class="font-medium"><?php echo e($event['title']); ?></h6>
                                <p class="text-sm text-gray-600 mt-1"><?php echo e($event['description']); ?></p>
                                <div class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?php echo e($event['timestamp']->format('d/m/Y H:i:s')); ?>

                                    <span class="ml-3"><i class="fas fa-user mr-1"></i><?php echo e($event['user']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

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
                    Về danh sách
                </a>
                
                <a href="<?php echo e(route('employee.requests.show', $request)); ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Xem chi tiết
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\employee\requests\history-simple.blade.php ENDPATH**/ ?>