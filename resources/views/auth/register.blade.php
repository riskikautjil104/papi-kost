<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Daftar</title>

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
            /* Import Fonts */
            @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Poppins:wght@300;400;600;700&display=swap');
            
            /* CSS Variables */
            :root {
                --primary-blue: #0066FF;
                --dark-blue: #001F3F;
                --light-blue: #00A8FF;
                --accent-cyan: #00FFFF;
                --bg-dark: #000814;
                --text-white: #FFFFFF;
                --text-gray: #B8C5D6;
                --gradient-primary: linear-gradient(135deg, #0066FF 0%, #00A8FF 100%);
                --gradient-accent: linear-gradient(45deg, #0066FF, #00FFFF);
            }

            /* Reset */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            /* Body Styles */
            body {
                font-family: 'Poppins', sans-serif;
                background: linear-gradient(135deg, var(--bg-dark) 0%, var(--dark-blue) 100%);
                color: var(--text-white);
                min-height: 100vh;
                overflow-x: hidden;
                position: relative;
            }

            /* Particles Container */
            .particles-container {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 0;
                pointer-events: none;
                overflow: hidden;
            }

            /* Individual Particles */
            .particle {
                position: absolute;
                background: var(--accent-cyan);
                border-radius: 50%;
                opacity: 0.2;
            }

            /* Main Container */
            .main-container {
                position: relative;
                z-index: 1;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            /* Login Card */
            .login-card {
                background: rgba(0, 31, 63, 0.95);
                border-radius: 25px;
                border: 2px solid rgba(0, 168, 255, 0.4);
                box-shadow: 0 20px 60px rgba(0, 102, 255, 0.5);
                padding: 40px;
                width: 100%;
                max-width: 450px;
                position: relative;
                backdrop-filter: blur(10px);
            }

            /* Logo Section */
            .logo-section {
                text-align: center;
                margin-bottom: 35px;
            }

            .logo-text {
                font-family: 'Orbitron', sans-serif;
                font-size: 2.8rem;
                font-weight: 900;
                background: linear-gradient(45deg, var(--light-blue), var(--accent-cyan));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                letter-spacing: 2px;
                margin-bottom: 8px;
            }

            .logo-subtitle {
                color: var(--text-gray);
                font-size: 1.1rem;
                font-weight: 300;
            }

            /* Form Styles */
            .form-group {
                margin-bottom: 25px;
            }

            .form-label {
                display: block;
                color: var(--text-gray);
                margin-bottom: 8px;
                font-weight: 500;
                font-size: 0.95rem;
            }

            .form-input {
                width: 100%;
                padding: 14px 18px;
                background: rgba(0, 102, 255, 0.15);
                border: 2px solid rgba(0, 168, 255, 0.4);
                border-radius: 12px;
                color: var(--text-white);
                font-size: 1rem;
                font-family: 'Poppins', sans-serif;
                transition: all 0.3s ease;
            }

            .form-input:focus {
                outline: none;
                border-color: var(--accent-cyan);
                box-shadow: 0 0 0 3px rgba(0, 255, 255, 0.2);
                background: rgba(0, 102, 255, 0.25);
            }

            .form-input::placeholder {
                color: rgba(255, 255, 255, 0.5);
            }

            /* Remember Me Checkbox */
            .checkbox-group {
                display: flex;
                align-items: center;
                margin-bottom: 25px;
            }

            .checkbox-input {
                width: 18px;
                height: 18px;
                margin-right: 10px;
                accent-color: var(--accent-cyan);
                cursor: pointer;
            }

            .checkbox-label {
                color: var(--text-gray);
                font-size: 0.95rem;
                cursor: pointer;
            }

            /* Login Button */
            .login-button {
                width: 100%;
                padding: 16px;
                background: linear-gradient(135deg, var(--primary-blue), var(--light-blue));
                border: none;
                border-radius: 12px;
                color: var(--text-white);
                font-size: 1.1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                font-family: 'Poppins', sans-serif;
                margin-bottom: 20px;
                position: relative;
                overflow: hidden;
            }

            .login-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(0, 255, 255, 0.3);
            }

            .login-button:active {
                transform: translateY(0);
            }

            /* Forgot Password Link */
            .forgot-link {
                display: inline-block;
                color: var(--accent-cyan);
                text-decoration: none;
                font-size: 0.9rem;
                font-weight: 500;
                margin-bottom: 25px;
                transition: all 0.3s ease;
            }

            .forgot-link:hover {
                color: var(--light-blue);
                text-decoration: underline;
            }

            /* Divider */
            .divider {
                display: flex;
                align-items: center;
                margin: 25px 0;
                color: var(--text-gray);
            }

            .divider::before,
            .divider::after {
                content: '';
                flex: 1;
                border-bottom: 1px solid rgba(0, 168, 255, 0.3);
            }

            .divider span {
                padding: 0 15px;
                font-size: 0.9rem;
            }

            /* Register Link */
            .register-section {
                text-align: center;
                margin-bottom: 25px;
            }

            .register-text {
                color: var(--text-gray);
                font-size: 0.95rem;
            }

            .register-link {
                color: var(--accent-cyan);
                text-decoration: none;
                font-weight: 600;
                margin-left: 5px;
                transition: all 0.3s ease;
            }

            .register-link:hover {
                color: var(--light-blue);
                text-decoration: underline;
            }

            /* Footer Links */
            .footer-links {
                text-align: center;
                margin-top: 30px;
            }

            .footer-link {
                color: var(--text-gray);
                text-decoration: none;
                font-size: 0.9rem;
                margin: 0 10px;
                transition: all 0.3s ease;
            }

            .footer-link:hover {
                color: var(--accent-cyan);
            }

            .copyright {
                color: var(--text-gray);
                font-size: 0.85rem;
                margin-top: 10px;
                opacity: 0.8;
            }

            /* Error/Success Messages */
            .alert {
                padding: 15px;
                border-radius: 12px;
                margin-bottom: 25px;
                border: 2px solid rgba(0, 168, 255, 0.4);
                background: rgba(0, 102, 255, 0.15);
                color: var(--text-white);
            }

            .alert-success {
                border-color: rgba(0, 255, 0, 0.4);
                background: rgba(0, 255, 0, 0.15);
            }

            .alert-error {
                border-color: rgba(255, 0, 0, 0.4);
                background: rgba(255, 0, 0, 0.15);
            }

            /* Mobile Bottom Navigation */
            .mobile-nav {
                display: none;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: rgba(0, 31, 63, 0.95);
                backdrop-filter: blur(10px);
                border-top: 2px solid rgba(0, 168, 255, 0.4);
                z-index: 1000;
                padding: 10px 0;
            }

            .nav-items {
                display: flex;
                justify-content: space-around;
                max-width: 500px;
                margin: 0 auto;
            }

            .nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                color: var(--text-gray);
                text-decoration: none;
                padding: 8px 12px;
                transition: all 0.3s ease;
                border-radius: 8px;
            }

            .nav-item.active {
                color: var(--accent-cyan);
                background: rgba(0, 168, 255, 0.1);
            }

            .nav-icon {
                font-size: 1.3rem;
                margin-bottom: 4px;
            }

            .nav-label {
                font-size: 0.75rem;
                font-weight: 500;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .main-container {
                    padding: 15px;
                    padding-bottom: 70px; /* Space for bottom nav */
                }
                
                .login-card {
                    padding: 30px 25px;
                }
                
                .logo-text {
                    font-size: 2.2rem;
                }
                
                .logo-subtitle {
                    font-size: 1rem;
                }
                
                .form-input {
                    padding: 12px 15px;
                }
                
                .login-button {
                    padding: 14px;
                }
                
                .mobile-nav {
                    display: block;
                }
            }

            @media (max-width: 480px) {
                .login-card {
                    padding: 25px 20px;
                }
                
                .logo-text {
                    font-size: 1.8rem;
                }
                
                .logo-subtitle {
                    font-size: 0.9rem;
                }
            }

            /* Utility Classes */
            .text-center {
                text-align: center;
            }

            .mb-20 {
                margin-bottom: 20px;
            }

            .mb-15 {
                margin-bottom: 15px;
            }

            .d-flex {
                display: flex;
            }

            .justify-between {
                justify-content: space-between;
            }

            .align-center {
                align-items: center;
            }

            /* Animation Keyframes */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes float {
                0%, 100% {
                    transform: translateY(0) rotate(0deg);
                }
                50% {
                    transform: translateY(-20px) rotate(180deg);
                }
            }
        </style>
    </head>
    <body>
        <div class="d-flex align-items-center justify-content-center min-vh-100 py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="register-card p-5">
                            <!-- Logo/Title -->
                            <div class="text-center mb-4">
                                <h2 class="logo-text"><i class="fas fa-building"></i> D-TECT</h2>
                                <p class="text-muted">Manajemen Kontrakan</p>
                                <h5 class="fw-medium">Daftar Akun Baru</h5>
                            </div>

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <!-- Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-medium">Nama Lengkap</label>
                                    <input type="text" 
                                           id="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required 
                                           autofocus 
                                           placeholder="Masukkan nama lengkap Anda">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email Address -->
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium">Email</label>
                                    <input type="email" 
                                           id="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
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
                                           autocomplete="new-password"
                                           placeholder="Masukkan password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label fw-medium">Konfirmasi Password</label>
                                    <input type="password" 
                                           id="password_confirmation" 
                                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           name="password_confirmation" 
                                           required 
                                           autocomplete="new-password"
                                           placeholder="Ulangi password">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <a href="{{ route('login') }}" class="text-decoration-none small">
                                        Sudah punya akun?
                                    </a>

                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-user-plus me-2"></i>Daftar
                                    </button>
                                </div>
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

