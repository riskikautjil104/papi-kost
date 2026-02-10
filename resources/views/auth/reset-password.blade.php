<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Reset Password - D-TECT</title>

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Poppins:wght@300;400;600;700&display=swap');
            
            :root {
                --primary-blue: #0066FF;
                --dark-blue: #001F3F;
                --light-blue: #00A8FF;
                --accent-cyan: #00FFFF;
                --bg-dark: #000814;
                --text-white: #FFFFFF;
                --text-gray: #B8C5D6;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Poppins', sans-serif;
                background: linear-gradient(135deg, var(--bg-dark) 0%, var(--dark-blue) 100%);
                color: var(--text-white);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            .card {
                background: rgba(0, 31, 63, 0.95);
                border-radius: 25px;
                border: 2px solid rgba(0, 168, 255, 0.4);
                box-shadow: 0 20px 60px rgba(0, 102, 255, 0.5);
                padding: 40px;
                width: 100%;
                max-width: 450px;
                backdrop-filter: blur(10px);
            }

            .logo-section {
                text-align: center;
                margin-bottom: 30px;
            }

            .logo-text {
                font-family: 'Orbitron', sans-serif;
                font-size: 2.5rem;
                font-weight: 900;
                background: linear-gradient(45deg, var(--light-blue), var(--accent-cyan));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                letter-spacing: 2px;
            }

            .title {
                font-size: 1.5rem;
                font-weight: 600;
                text-align: center;
                margin-bottom: 10px;
            }

            .description {
                color: var(--text-gray);
                text-align: center;
                font-size: 0.95rem;
                margin-bottom: 25px;
                line-height: 1.6;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-label {
                display: block;
                color: var(--text-gray);
                margin-bottom: 8px;
                font-weight: 500;
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
            }

            .btn {
                width: 100%;
                padding: 16px;
                background: linear-gradient(135deg, var(--primary-blue), var(--light-blue));
                border: none;
                border-radius: 12px;
                color: var(--text-white);
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                font-family: 'Poppins', sans-serif;
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(0, 255, 255, 0.3);
            }

            .back-link {
                display: block;
                text-align: center;
                margin-top: 20px;
                color: var(--accent-cyan);
                text-decoration: none;
                font-size: 0.9rem;
            }

            .back-link:hover {
                text-decoration: underline;
            }

            .alert-error {
                padding: 15px;
                border-radius: 12px;
                margin-bottom: 20px;
                border: 2px solid rgba(255, 0, 0, 0.4);
                background: rgba(255, 0, 0, 0.15);
                color: #ff6b6b;
            }

            @media (max-width: 480px) {
                .card {
                    padding: 30px 20px;
                }
                .logo-text {
                    font-size: 2rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="card">
            <!-- Logo -->
            <div class="logo-section">
                <div class="logo-text">D-TECT</div>
            </div>

            <h1 class="title">Reset Password</h1>
            <p class="description">
                Masukkan password baru Anda untuk mengganti password lama.
            </p>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address (Read-only) -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $request->email) }}"
                           class="form-input" 
                           readonly>
                </div>

                <!-- New Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-input" 
                           placeholder="Masukkan password baru" 
                           required 
                           autocomplete="new-password">
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-input" 
                           placeholder="Ulangi password baru" 
                           required 
                           autocomplete="new-password">
                </div>

                <button type="submit" class="btn">
                    Reset Password
                </button>
            </form>

            <a href="{{ route('login') }}" class="back-link">
                ← Kembali ke Login
            </a>
        </div>
    </body>
</html>
