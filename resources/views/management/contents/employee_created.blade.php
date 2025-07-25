@extends('index')

@section('title', 'Welcome Mail')
@section('section-content')
    <div class="bg-light d-flex flex-column min-vh-100">
        <header class="text-center mt-5">
            <h1 class="display-1 fw-bolder text-dark p-4 mb-5">Welcome to Laravel Training, {{ $name }}!</h1>
        </header>
        <main class="d-flex justify-content-center align-items-center flex-grow-2">
            <div class="bg-dark text-light card shadow rounded border-0 p-3">
                <div class="card-body w-100">
                    <h3 class="card-title text-center mb-3 pb-2">Your login details</h3>
                    <h5 class="text-left mb-4">Email: {{ $email }}</h5>
                    <h5 class="text-left mb-4">Password: {{ $password }}</h5>
                    <div class="d-flex justify-content-center mt-3">
                        <a href="{{ route('management.login') }}" class="text-decoration-none btn btn-outline-light">
                            Click to login
                        </a>
                    </div>
                </div>
            </div>
        </main>
        <footer class="text-center py-3 mt-auto">
            <div class="container">
                <small class="text-light">&copy; {{ date('Y') }} Laravel Training. All rights reserved.</small>
            </div>
        </footer>
    </div>
@endsection
