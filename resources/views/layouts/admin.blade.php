<!DOCTYPE html>
<html class="dark" lang="vi">
<head>
    @include('components.admin.head')
</head>
<body class="bg-surface text-on-surface selection:bg-primary/30">
    @include('components.admin.sidebar')

    <!-- Main Content Wrapper -->
    <main class="ml-64 min-h-screen">
        @include('components.admin.topbar')

        <!-- Page Content -->
        <div class="pt-24 px-8 pb-12">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
