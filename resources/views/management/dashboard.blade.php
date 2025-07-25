@extends('index')

@section('title', 'Management | Laravel Training')

@section('section-content')
<div class="bg-light d-flex min-vh-100" id="wrapper">

    {{-- Sidebar --}}
    <nav id="sidebar" class="bg-dark text-white p-3 sidebar-expanded"
         style="width: 250px; height: 100vh; position: fixed; top: 0; left: 0; overflow-y: auto; transition: all 0.3s;">
        <div class="d-flex justify-content-between align-items-center mb-4 position-relative mt-3">
            <a href="/management" class="text-white text-decoration-none"><h2 class="fs-4 mb-0">Laravel Training</h2></a>
            <button id="sidebarToggle"
                    class="toggle-btn position-absolute bg-dark"
                    style="left: 200px; transition: left 0.3s;">
                «
            </button>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('team.index') }}" class="nav-link side text-white">Manage Teams</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('employee.index') }}" class="nav-link side  text-white">Manage Employees</a>
            </li>
        </ul>
        <div class="nav position-absolute" style="bottom: 5%">
            <form action="{{ route('management.logout') }}" method="post">
                @csrf
                <button type="submit" class="nav-link text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         style="height: 1.5em; width: 1.5em; vertical-align: text-bottom;">
                        <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                    </svg>
                    <span class="fs-5">Logout</span>
                </button>
            </form>
        </div>
    </nav>

    {{-- Main Content --}}
    <div id="main-content"
         class="d-flex flex-column flex-grow-1 p-4"
         style="margin-left: 250px; height: 100vh; overflow-y: auto; transition: all 0.3s;">
    {{-- Header --}}
        <header class="mb-4">
            <h1 class="fs-1"> @yield('pageTitle', 'Welcome!')</h1>
        </header>

        {{-- Main Content --}}
        <main>
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="bg-light text-center py-3 mt-auto">
            <div class="container">
                <small>&copy; {{ date('Y') }} Laravel Training. All rights reserved.</small>
            </div>
        </footer>
    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

{{-- Sidebar Toggle Script --}}
<script>
    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('main-content');
    const toggleBtn = document.getElementById('sidebarToggle');

    toggleBtn.addEventListener('click', function () {
        const isCollapsed = sidebar.classList.toggle('sidebar-collapsed');
        main.classList.toggle('main-expanded');

        // Move toggle button to match sidebar edge
        toggleBtn.style.left = isCollapsed ? '0px' : '200px';
        toggleBtn.textContent = isCollapsed ? '»' : '«';
    });
</script>
@endsection
