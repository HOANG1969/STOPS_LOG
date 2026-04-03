<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Office Supplies Management'); ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-100">
    <?php if(auth()->guard()->check()): ?>
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-green-400 to-blue-500 text-white z-50">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="p-4 border-b border-white border-opacity-20">
                <img src="<?php echo e(asset('images/logopvgas.png')); ?>" alt="PVGAS LOGISTICS" class="h-10 mb-2" style="width: 300px; height: 150px; object-fit: contain;">
                <h2 class="text-xl font-bold flex items-center" >
                    <i class="fas fa-boxes mr-2"></i>
                    PVGAS LOGISTICS
                </h2>
                <!-- <p class="text-sm text-blue-200 mt-1">Quản lý văn phòng phẩm</p> -->
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2" >
                <!-- Menu cho Admin - FULL ACCESS -->
                <?php if(Auth::user()->isAdmin()): ?>
                    <!-- Quản lý và Đăng ký VPP -->
                    <a href="<?php echo e(route('supply-requests.index')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('supply-requests.*') ? 'bg-green-500 bg-opacity-80 text-white' : 'text-white-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Đăng ký văn phòng phẩm
                    </a>
                    
                    <!-- STOP Management -->
                    <a href="<?php echo e(route('stops.index')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('stops.*') && !request()->routeIs('reports.*') ? 'bg-yellow-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-exclamation-triangle mr-3"></i>
                        Đăng ký thẻ STOP
                    </a>
                    
                    <!-- STOP Reports -->
                    <a href="<?php echo e(route('reports.index')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('reports.*') ? 'bg-blue-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-chart-bar mr-3"></i>
                        Báo cáo STOP
                    </a>
                    
                    <a href="<?php echo e(route('dashboard.approval')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('dashboard.approval') ? 'bg-blue-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-check-circle mr-3"></i>
                        Phê duyệt yêu cầu
                    </a>
                    
                    <a href="<?php echo e(route('tchc.checker.dashboard')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('tchc.checker.*') ? 'bg-orange-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-search mr-3"></i>
                        TCHC Kiểm tra
                    </a>
                    
                    <a href="<?php echo e(route('tchc.manager.dashboard')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('tchc.manager.*') ? 'bg-purple-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-stamp mr-3"></i>
                        TCHC Phê duyệt
                    </a>

                    <!-- Menu Quản trị -->
                    <div class="border-t border-white border-opacity-20 pt-4 mt-4">
                        <p class="text-xs text-indigo-300 uppercase tracking-wider mb-2">Quản trị hệ thống</p>
                        <a href="<?php echo e(route('users.index')); ?>" 
                           class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('users.*') ? 'bg-red-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                            <i class="fas fa-users mr-3"></i>
                            Quản lý nhân sự
                        </a>
                        <a href="<?php echo e(route('office-supplies.admin.manage')); ?>" 
                           class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('office-supplies.admin.*') ? 'bg-indigo-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                            <i class="fas fa-cogs mr-3"></i>
                            Quản lý văn phòng phẩm
                        </a>
                        <a href="<?php echo e(route('office-supplies.import.form')); ?>" 
                           class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('office-supplies.import.*') ? 'bg-yellow-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                            <i class="fas fa-file-import mr-3"></i>
                            Import văn phòng phẩm
                        </a>
                    </div>
                
                <!-- Menu cho TCHC Manager - CHỈ phê duyệt VPP -->
                <?php elseif(Auth::user()->isTchcManager()): ?>
                    <!-- <a href="<?php echo e(route('tchc.manager.dashboard')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('tchc.manager.*') ? 'bg-purple-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-stamp mr-3"></i>
                        Phê duyệt đăng ký VPP
                    </a> -->
                    
                    <!-- STOP Management -->
                    <a href="<?php echo e(route('stops.index')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('stops.*') && !request()->routeIs('reports.*') ? 'bg-yellow-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-exclamation-triangle mr-3"></i>
                        Đăng ký STOP
                    </a>
                    
                    <!-- STOP Reports -->
                    <a href="<?php echo e(route('reports.index')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('reports.*') ? 'bg-blue-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-chart-bar mr-3"></i>
                        Báo cáo STOP
                    </a>
                
                <!-- Menu cho TCHC Checker -->
                <?php elseif(Auth::user()->isTchcChecker()): ?>
                    <a href="<?php echo e(route('tchc.checker.dashboard')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('tchc.checker.*') ? 'bg-orange-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-search mr-3"></i>
                        Kiểm tra phiếu VPP
                    </a>
                    
                    <!-- STOP Management -->
                    <a href="<?php echo e(route('stops.index')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('stops.*') && !request()->routeIs('reports.*') ? 'bg-yellow-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-exclamation-triangle mr-3"></i>
                        Đăng ký STOP
                    </a>
                    
                    <!-- STOP Reports -->
                    <a href="<?php echo e(route('reports.index')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('reports.*') ? 'bg-blue-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-chart-bar mr-3"></i>
                        Báo cáo STOP
                    </a>

                <!-- Menu cho Approver - CHỈ quản lý phiếu đăng ký -->
                <?php elseif(Auth::user()->isApprover()): ?>
                    <!-- Quản lý Phiếu đăng ký bộ phận -->
                    <!-- <a href="<?php echo e(route('dashboard.approval')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('dashboard.approval') ? 'bg-green-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-check-circle mr-3"></i>
                        Danh sách phiếu phê duyệt
                    </a> -->

                    <!-- Quản lý Phiếu đăng ký bộ phận -->
                    <!-- <a href="<?php echo e(route('supply-requests.index')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('supply-requests.index') ? 'bg-green-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Quản lý Phiếu đăng ký
                    </a> -->
                    
                    <!-- STOP Management -->
                    <a href="<?php echo e(route('stops.index')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('stops.*') && !request()->routeIs('reports.*') ? 'bg-yellow-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-exclamation-triangle mr-3"></i>
                        Đăng ký STOP
                    </a>
                    
                    <!-- STOP Reports -->
                    <a href="<?php echo e(route('reports.index')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('reports.*') ? 'bg-blue-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-chart-bar mr-3"></i>
                        Báo cáo STOP
                    </a>
                
                <!-- Menu cho Employee - CHỈ đăng ký VPP -->
                <?php else: ?>
                    <!-- Đăng ký văn phòng phẩm -->
                    <!-- <a href="<?php echo e(route('supply-requests.index')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('supply-requests.*') ? 'bg-green-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Đăng ký văn phòng phẩm
                    </a> -->
                    
                    <!-- STOP Management -->
                    <a href="<?php echo e(route('stops.index')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('stops.*') && !request()->routeIs('reports.*') ? 'bg-yellow-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-exclamation-triangle mr-3"></i>
                        Đăng ký STOP
                    </a>
                    

                    <!-- STOP Reports -->
                     <?php if(Auth::user()->isAdmin() || Auth::user()->isApprover() || Auth::user()->isTchcManager() || Auth::user()->isTchcChecker()): ?>
                    <a href="<?php echo e(route('reports.index')); ?>" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('reports.*') ? 'bg-blue-500 bg-opacity-80 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white'); ?>">
                        <i class="fas fa-chart-bar mr-3"></i>
                        Báo cáo STOP
                    </a>
                  <?php endif; ?>
                <?php endif; ?>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="ml-64 min-h-screen">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="px-6 py-4 flex items-center justify-between">
             
                <div>
                    
                    <h1 class="text-2xl font-semibold text-gray-900" style="color: #1b50e0;"><?php echo $__env->yieldContent('page-title', 'PVGAS LOGISTICS'); ?></h1>
                    <?php if(isset($breadcrumb)): ?>
                    <nav class="text-sm text-gray-500"><?php echo e($breadcrumb); ?></nav>
                    <?php endif; ?>
                </div>
                
                <!-- User Menu -->
                <div class="relative">
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="font-medium text-gray-900"><?php echo e(Auth::user()->full_name ?? Auth::user()->name); ?></div>
                            <div class="text-sm text-gray-500"><?php echo e(Auth::user()->position); ?> - <?php echo e(Auth::user()->department); ?></div>
                        </div>
                        <div class="relative">
                            <button onclick="toggleUserMenu()" class="flex items-center p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-medium text-transform: uppercase">
                                    <?php echo e(substr(Auth::user()->name,0,1)); ?>

                                </div>
                                <i class="fas fa-chevron-down ml-2 text-gray-400"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                                <div class="py-1">
                                    <a href="<?php echo e(route('profile.show')); ?>" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-3"></i>Hồ sơ cá nhân
                                    </a>
                                    <a href="<?php echo e(route('profile.change-password')); ?>" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-key mr-3"></i>Đổi mật khẩu
                                    </a>
                                    <!-- <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-3"></i>Cài đặt
                                    </a> -->
                                    <hr class="my-1">
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                            <i class="fas fa-sign-out-alt mr-3"></i>Đăng xuất
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <?php if(session('success')): ?>
                <div class="mx-6 mt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mx-6 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span class="block sm:inline"><?php echo e(session('error')); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mx-6 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Có lỗi xảy ra:</strong>
                        <ul class="mt-2 ml-4 list-disc">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
    
    <script>
    function toggleUserMenu() {
        const menu = document.getElementById('userMenu');
        menu.classList.toggle('hidden');
    }

    // Keep the authenticated session and backend connection warm while users are active.
    (function setupHeartbeat() {
        const heartbeatUrl = '<?php echo e(route('app.heartbeat')); ?>';
        const intervalMs = 4 * 60 * 1000;
        let lastPingAt = 0;

        function pingHeartbeat(force = false) {
            const now = Date.now();
            if (!force && now - lastPingAt < 30 * 1000) {
                return;
            }

            lastPingAt = now;
            fetch(heartbeatUrl, {
                method: 'GET',
                credentials: 'same-origin',
                cache: 'no-store',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).catch(function () {
                // Silent fail: this optimization must never block normal user actions.
            });
        }

        pingHeartbeat(true);
        setInterval(function () {
            if (document.visibilityState === 'visible') {
                pingHeartbeat();
            }
        }, intervalMs);

        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'visible') {
                pingHeartbeat(true);
            }
        });

        window.addEventListener('focus', function () {
            pingHeartbeat(true);
        });
    })();
    
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('userMenu');
        const button = event.target.closest('[onclick="toggleUserMenu()"]');
        
        if (!button && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php else: ?>
    <!-- Not authenticated content -->
    <?php echo $__env->yieldContent('content'); ?>
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH D:\KDNVPP-new\KDNVPP-new\resources\views\layouts\app.blade.php ENDPATH**/ ?>