@extends('index')

@section('title', 'Login | Laravel Training')

@section('section-content')
<div class="bg-dark d-flex flex-column min-vh-100">
    <header class="text-center mt-5">
        <h1 class="display-1 fw-bolder text-light p-4 mb-5">Laravel Training</h1>
    </header>
    <main class="d-flex justify-content-center align-items-center flex-grow-2">
        <div class="bg-light card shadow rounded border-0 p-4">
            <div class="card-body">
                <h3 class="card-title text-center mb-3 pb-2">Management Login</h3>
                <div id="error-message" class="mb-3 danger text-danger">
                    @error('email')
                    <small>{{ $message }}</small>
                    @enderror
                </div>
                <form action="{{ route('management.login') }}" method="post">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-dark w-100">Log in</button>
                </form>
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
