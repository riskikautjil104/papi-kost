<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link href="{{ asset('css/dtect-style.css') }}" rel="stylesheet">

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
        <script src="{{ asset('js/dtect-scripts.js') }}"></script>

        <style>
            body {
                font-family: 'Figtree', sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
            }
            .login-card {
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            }
            .form-control:focus, .form-select:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
            }
            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
            }
            .btn-primary:hover {
                background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
            }
            .logo-text {
                font-family: 'Orbitron', sans-serif;
                font-size: 2rem;
                font-weight: 900;
                background: linear-gradient(45deg, #0066FF, #00A8FF);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
        </style>
    </head>
    <body>
        <div class="d-flex align-items-center justify-content-center min-vh-100 py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-5">
                        <div class="login-card p-5">
                            <!-- Logo/Title -->
                            <div class="text-center mb-4">
                                <h2 class="logo-text"><i class="fas fa-building"></i> D-TECT</h2>
                                <p class="text-muted">Manajemen Kontrakan</p>
                            </div>

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <!-- Email Address -->
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium">Email</label>
                                    <input type="email" 
                                           id="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           autofocus 
                                           placeholder="Masukkan email Anda">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-medium">Password</label>
                                    <input type="password" 
                                           id="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           name="password" 
                                           required 
                                           autocomplete="current-password"
                                           placeholder="Masukkan password Anda">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Remember Me -->
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                    <label class="form-check-label" for="remember_me">Ingat saya</label>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-decoration-none small">
                                            Lupa password?
                                        </a>
                                    @endif

                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-sign-in-alt me-2"></i>Login
                                    </button>
                                </div>

                                <!-- Register Link -->
                                @if (Route::has('register'))
                                    <hr class="my-4">
                                    <p class="text-center text-muted mb-0">
                                        Belum punya akun? 
                                        <a href="{{ route('register') }}" class="text-decoration-none fw-medium">
                                            Daftar sekarang
                                        </a>
                                    </p>
                                @endif
                            </form>
                        </div>

                        <!-- Footer -->
                        <div class="text-center mt-4 text-white-50 small">
                            &copy; {{ date('Y') }} D-TECT. All rights reserved.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

