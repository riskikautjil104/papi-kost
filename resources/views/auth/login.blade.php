<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>D-TECT - Sistem Manajemen Kontrakan</title>

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
        <!-- Particles Background -->
        <div class="particles-container" id="particlesContainer"></div>

        <!-- Main Content -->
        <div class="main-container">
            <div class="login-card" style="animation: fadeIn 0.6s ease-out;">
                <!-- Logo -->
                <div class="logo-section">
                    <div class="logo-text">D-TECT</div>
                    <div class="logo-subtitle">Sistem Manajemen Kontrakan</div>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert alert-error">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!-- Email Input -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="form-input" 
                               placeholder="Masukkan email Anda" 
                               required 
                               autofocus>
                    </div>

                    <!-- Password Input -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-input" 
                               placeholder="Masukkan password Anda" 
                               required>
                    </div>

                    <!-- Remember Me -->
                    <div class="checkbox-group">
                        <input type="checkbox" 
                               id="remember_me" 
                               name="remember" 
                               class="checkbox-input">
                        <label for="remember_me" class="checkbox-label">Ingat saya</label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="login-button">
                        Masuk ke Sistem
                    </button>

                    <!-- Forgot Password -->
                    <div class="d-flex justify-between align-center">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                Lupa password?
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Divider -->
                @if (Route::has('register'))
                    <div class="divider">
                        <span>ATAU</span>
                    </div>

                    <!-- Register Link -->
                    <div class="register-section">
                        <span class="register-text">Belum punya akun?</span>
                        <a href="{{ route('register') }}" class="register-link">
                            Daftar sekarang
                        </a>
                    </div>
                @endif

                <!-- Footer Links -->
                <div class="footer-links">
                    <div class="mb-15">
                        <a href="{{ route('home') }}" class="footer-link">Kembali ke Beranda</a>
                        <span style="color: var(--text-gray);">•</span>
                        {{-- <a href="{{ route('about') }}" class="footer-link">Tentang Kami</a> --}}
                    </div>
                    <div class="copyright">
                        &copy; {{ date('Y') }} D-TECT. Hak cipta dilindungi.
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <nav class="mobile-nav">
            <div class="nav-items">
                <a href="{{ route('home') }}" class="nav-item">
                    <div class="nav-icon">🏠</div>
                    <div class="nav-label">Beranda</div>
                </a>
                <a href="{{ route('login') }}" class="nav-item active">
                    <div class="nav-icon">🔐</div>
                    <div class="nav-label">Login</div>
                </a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="nav-item">
                    <div class="nav-icon">📝</div>
                    <div class="nav-label">Daftar</div>
                </a>
                @endif
                {{-- <a href="{{ route('about') }}" class="nav-item">
                    <div class="nav-icon">ℹ️</div>
                    <div class="nav-label">Info</div>
                </a> --}}
            </div>
        </nav>

        <script>
            // Create particles
            function createParticles() {
                const container = document.getElementById('particlesContainer');
                const particleCount = 15;
                
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.classList.add('particle');
                    
                    // Random position
                    const size = Math.random() * 6 + 2;
                    const posX = Math.random() * 100;
                    const posY = Math.random() * 100;
                    
                    // Random animation
                    const duration = Math.random() * 20 + 10;
                    const delay = Math.random() * 5;
                    
                    particle.style.width = `${size}px`;
                    particle.style.height = `${size}px`;
                    particle.style.left = `${posX}%`;
                    particle.style.top = `${posY}%`;
                    particle.style.animation = `float ${duration}s ease-in-out ${delay}s infinite`;
                    
                    container.appendChild(particle);
                }
            }

            // Form submission handler
            function handleFormSubmit(e) {
                const form = e.target;
                const submitBtn = form.querySelector('button[type="submit"]');
                
                if (submitBtn) {
                    submitBtn.innerHTML = 'Memproses...';
                    submitBtn.disabled = true;
                    
                    // Re-enable after 3 seconds if still on page
                    setTimeout(() => {
                        submitBtn.innerHTML = 'Masuk ke Sistem';
                        submitBtn.disabled = false;
                    }, 3000);
                }
            }

            // Auto-hide alerts
            function autoHideAlerts() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    setTimeout(() => {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            alert.style.display = 'none';
                        }, 300);
                    }, 5000);
                });
            }

            // Input focus effects
            function setupInputEffects() {
                const inputs = document.querySelectorAll('.form-input');
                
                inputs.forEach(input => {
                    // Focus effect
                    input.addEventListener('focus', function() {
                        this.parentElement.style.transform = 'translateY(-2px)';
                    });
                    
                    // Blur effect
                    input.addEventListener('blur', function() {
                        this.parentElement.style.transform = 'translateY(0)';
                    });
                });
            }

            // Initialize everything when page loads
            document.addEventListener('DOMContentLoaded', function() {
                createParticles();
                setupInputEffects();
                autoHideAlerts();
                
                // Form submission
                const loginForm = document.getElementById('loginForm');
                if (loginForm) {
                    loginForm.addEventListener('submit', handleFormSubmit);
                }
                
                // Mobile nav active state
                const navItems = document.querySelectorAll('.nav-item');
                navItems.forEach(item => {
                    item.addEventListener('click', function() {
                        navItems.forEach(nav => nav.classList.remove('active'));
                        this.classList.add('active');
                    });
                });
            });

            // Add mouse move effect for particles
            document.addEventListener('mousemove', function(e) {
                const particles = document.querySelectorAll('.particle');
                const mouseX = e.clientX / window.innerWidth;
                const mouseY = e.clientY / window.innerHeight;
                
                particles.forEach((particle, index) => {
                    const speed = (index % 3 + 1) * 0.5;
                    const x = (mouseX - 0.5) * speed * 40;
                    const y = (mouseY - 0.5) * speed * 40;
                    
                    particle.style.transform = `translate(${x}px, ${y}px)`;
                });
            });
        </script>
    </body>
</html>