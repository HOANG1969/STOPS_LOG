<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Office Supplies Management')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    @auth
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-blue-600 to-blue-800 text-white z-50">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="p-4 border-b border-white border-opacity-20">
                <h2 class="text-xl font-bold flex items-center">
                    <i class="fas fa-boxes mr-2"></i>
                    PVGAS SE
                </h2>
                <p class="text-sm text-blue-200 mt-1">Quản lý văn phòng phẩm</p>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2">
                <!-- Menu cho Employee - Chỉ xem phiếu của mình và bộ phận -->
                @if(Auth::user()->isEmployee())
                    <!-- Danh sách văn phòng phẩm -->
                    <!-- <a href="{{ route('office-supplies.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('office-supplies.*') && !request()->routeIs('office-supplies.admin') ? 'bg-white bg-opacity-20 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                        <i class="fas fa-boxes mr-3"></i>
                        Danh sách văn phòng phẩm
                    </a> -->
                    
                    <!-- Đăng ký văn phòng phẩm -->
                    <a href="{{ route('employee.requests.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('employee.requests.*') ? 'bg-white bg-opacity-20 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                        <i class="fas fa-list me-2"></i>
                        Đăng ký văn phòng phẩm
                    </a>
                    
                    <!-- Yêu cầu của tôi -->
                    <a href="{{ route('supply-requests.my-requests') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('supply-requests.my-requests') ? 'bg-white bg-opacity-20 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                        <i class="fas fa-file-alt mr-3"></i>
                        Yêu cầu của tôi
                    </a>
                @endif

                <!-- Menu cho Approver - Chỉ phê duyệt phiếu bộ phận mình -->
                @if(Auth::user()->isApprover())
                    <!-- Dashboard Phê duyệt -->
                    <a href="{{ route('dashboard.approval') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard.approval') ? 'bg-white bg-opacity-20 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                        <i class="fas fa-check-circle mr-3"></i>
                        Danh sách phiếu phê duyệt
                    </a>

                    <!-- Quản lý Phiếu đăng ký bộ phận -->
                    <a href="{{ route('supply-requests.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('supply-requests.index') ? 'bg-white bg-opacity-20 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Quản lý Phiếu đăng ký
                    </a>

                    <!-- Danh sách văn phòng phẩm -->
                    <!-- <a href="{{ route('office-supplies.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('office-supplies.*') && !request()->routeIs('office-supplies.admin') ? 'bg-white bg-opacity-20 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                        <i class="fas fa-boxes mr-3"></i>
                        Danh sách văn phòng phẩm
                    </a> -->
                @endif
                
                <!-- Menu Quản trị - Chỉ cho Admin -->
                @if(Auth::user()->isAdmin())
                <div class="border-t border-white border-opacity-20 pt-4 mt-4">
                    <p class="text-xs text-indigo-300 uppercase tracking-wider mb-2">Quản trị hệ thống</p>
                    <a href="{{ route('users.index') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('users.*') ? 'bg-white bg-opacity-20 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                        <i class="fas fa-users mr-3"></i>
                        Quản lý người dùng
                    </a>
                    <a href="{{ route('office-supplies.admin') }}" 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('office-supplies.admin') ? 'bg-white bg-opacity-20 text-white' : 'text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white' }}">
                        <i class="fas fa-cogs mr-3"></i>
                        Quản lý văn phòng phẩm
                    </a>
                </div>
                @endif
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="ml-64 min-h-screen">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">@yield('page-title', 'PVGAS SE')</h1>
                    @if(isset($breadcrumb))
                    <nav class="text-sm text-gray-500">{{ $breadcrumb }}</nav>
                    @endif
                </div>
                
                <!-- User Menu -->
                <div class="relative">
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="font-medium text-gray-900">{{ Auth::user()->full_name ?? Auth::user()->name }}</div>
                            <div class="text-sm text-gray-500">{{ Auth::user()->position }} - {{ Auth::user()->department }}</div>
                        </div>
                        <div class="relative">
                            <button onclick="toggleUserMenu()" class="flex items-center p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <i class="fas fa-chevron-down ml-2 text-gray-400"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                                <div class="py-1">
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-3"></i>Hồ sơ cá nhân
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-3"></i>Cài đặt
                                    </a>
                                    <hr class="my-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
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
            @if(session('success'))
                <div class="mx-6 mt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-6 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mx-6 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Có lỗi xảy ra:</strong>
                        <ul class="mt-2 ml-4 list-disc">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    
    <script>
    function toggleUserMenu() {
        const menu = document.getElementById('userMenu');
        menu.classList.toggle('hidden');
    }
    
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
    @else
    <!-- Not authenticated content -->
    @yield('content')
    @endauth

    @stack('scripts')
</body>
</html>