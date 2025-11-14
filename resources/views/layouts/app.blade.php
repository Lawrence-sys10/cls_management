<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CLS Management System') - Techiman Customary Lands</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    
    @stack('styles')
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Navigation -->
        <nav class="bg-green-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-8 w-8" src="/images/logo.png" alt="CLS Logo">
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                </x-nav-link>
                                <x-nav-link href="{{ route('lands.index') }}" :active="request()->routeIs('lands.*')">
                                    <i class="fas fa-map-marked-alt mr-2"></i>Lands
                                </x-nav-link>
                                <x-nav-link href="{{ route('clients.index') }}" :active="request()->routeIs('clients.*')">
                                    <i class="fas fa-users mr-2"></i>Clients
                                </x-nav-link>
                                <x-nav-link href="{{ route('allocations.index') }}" :active="request()->routeIs('allocations.*')">
                                    <i class="fas fa-handshake mr-2"></i>Allocations
                                </x-nav-link>
                                <x-nav-link href="{{ route('chiefs.index') }}" :active="request()->routeIs('chiefs.*')">
                                    <i class="fas fa-crown mr-2"></i>Chiefs
                                </x-nav-link>
                                @can('admin')
                                <x-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.*')">
                                    <i class="fas fa-cog mr-2"></i>Admin
                                </x-nav-link>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            <!-- User dropdown -->
                            <x-user-dropdown />
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            <!-- Page Heading -->
            @if(isset($header))
            <header class="bg-white shadow">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                            {{ $header }}
                        </h1>
                        @yield('actions')
                    </div>
                </div>
            </header>
            @endif

            <!-- Page Content -->
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                <!-- Notifications -->
                @if(session('success'))
                <x-alert type="success" message="{{ session('success') }}" />
                @endif
                @if(session('error'))
                <x-alert type="error" message="{{ session('error') }}" />
                @endif
                @if($errors->any())
                <x-alert type="error" message="Please check the form for errors." />
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    @stack('scripts')
</body>
</html>
