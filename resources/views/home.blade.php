<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D-TECT - Sistem Manajemen Kost & Kontrakan</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-blue: #0066FF;
            --dark-blue: #001F3F;
            --light-blue: #00A8FF;
            --accent-cyan: #00FFFF;
            --bg-dark: #000814;
            --text-white: #FFFFFF;
            --text-gray: #B8C5D6;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--bg-dark) 0%, var(--dark-blue) 100%);
            color: var(--text-white);
            overflow-x: hidden;
            position: relative;
        }

        /* Animated background particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: var(--accent-cyan);
            border-radius: 50%;
            opacity: 0.3;
            animation: float 20s infinite ease-in-out;
        }

        .particle:nth-child(1) { width: 4px; height: 4px; top: 20%; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 6px; height: 6px; top: 40%; left: 80%; animation-delay: 2s; }
        .particle:nth-child(3) { width: 3px; height: 3px; top: 60%; left: 30%; animation-delay: 4s; }
        .particle:nth-child(4) { width: 5px; height: 5px; top: 80%; left: 70%; animation-delay: 6s; }
        .particle:nth-child(5) { width: 4px; height: 4px; top: 30%; left: 50%; animation-delay: 8s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) translateX(0); }
            25% { transform: translateY(-30px) translateX(20px); }
            50% { transform: translateY(-60px) translateX(-20px); }
            75% { transform: translateY(-30px) translateX(10px); }
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        /* Header */
        header {
            padding: 30px 0;
            animation: slideDown 0.8s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            font-weight: 900;
            background: linear-gradient(45deg, var(--light-blue), var(--accent-cyan));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 2px;
            text-shadow: 0 0 30px rgba(0, 255, 255, 0.5);
        }

        .nav-links {
            display: flex;
            gap: 40px;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-white);
            text-decoration: none;
            font-weight: 400;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-cyan);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a:hover {
            color: var(--accent-cyan);
        }

        /* Hero Section */
        .hero {
            padding: 100px 0;
            text-align: center;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 4rem;
            font-weight: 900;
            margin-bottom: 20px;
            background: linear-gradient(90deg, var(--primary-blue), var(--accent-cyan), var(--light-blue));
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease infinite;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .hero p {
            font-size: 1.3rem;
            color: var(--text-gray);
            margin-bottom: 40px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.8;
        }

        .cta-button {
            display: inline-block;
            padding: 18px 50px;
            background: linear-gradient(135deg, var(--primary-blue), var(--light-blue));
            color: var(--text-white);
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.4s ease;
            box-shadow: 0 10px 40px rgba(0, 102, 255, 0.4);
            position: relative;
            overflow: hidden;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }

        .cta-button:hover::before {
            width: 300px;
            height: 300px;
        }

        .cta-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0, 255, 255, 0.6);
        }

        .cta-button span {
            position: relative;
            z-index: 1;
        }

        /* Features Section */
        .features {
            padding: 80px 0;
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        .features h2 {
            text-align: center;
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 60px;
            color: var(--light-blue);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .feature-card {
            background: rgba(0, 31, 63, 0.6);
            padding: 40px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(0, 168, 255, 0.2);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(0, 255, 255, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: var(--accent-cyan);
            box-shadow: 0 20px 60px rgba(0, 168, 255, 0.3);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            display: inline-block;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--accent-cyan);
        }

        .feature-card p {
            color: var(--text-gray);
            line-height: 1.7;
        }

        /* Payment Methods */
        .payment-methods {
            padding: 80px 0;
            background: rgba(0, 8, 20, 0.5);
            animation: fadeInUp 1s ease-out 0.9s both;
        }

        .payment-methods h2 {
            text-align: center;
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 60px;
            color: var(--light-blue);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .payment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 30px;
            max-width: 900px;
            margin: 0 auto;
        }

        .payment-item {
            background: rgba(0, 102, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            border: 2px solid rgba(0, 168, 255, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .payment-item:hover {
            background: rgba(0, 168, 255, 0.2);
            border-color: var(--accent-cyan);
            transform: scale(1.05);
        }

        .payment-item span {
            font-size: 2rem;
            display: block;
            margin-bottom: 10px;
        }

        .payment-item p {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        /* Stats Section */
        .stats {
            padding: 80px 0;
            animation: fadeInUp 1s ease-out 1.2s both;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            text-align: center;
        }

        .stat-item h3 {
            font-family: 'Orbitron', sans-serif;
            font-size: 3rem;
            color: var(--accent-cyan);
            margin-bottom: 10px;
        }

        .stat-item p {
            color: var(--text-gray);
            font-size: 1.1rem;
        }

        /* Footer */
        footer {
            padding: 60px 0 30px;
            border-top: 2px solid rgba(0, 168, 255, 0.2);
            margin-top: 80px;
            animation: fadeInUp 1s ease-out 1.5s both;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h4 {
            color: var(--light-blue);
            font-size: 1.2rem;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .footer-section p, .footer-section a {
            color: var(--text-gray);
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: var(--accent-cyan);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(0, 168, 255, 0.1);
            color: var(--text-gray);
        }

        /* Bottom Navigation Bar - Mobile Only */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(180deg, rgba(0, 31, 63, 0.95) 0%, rgba(0, 8, 20, 0.98) 100%);
            backdrop-filter: blur(20px);
            border-top: 2px solid rgba(0, 168, 255, 0.3);
            box-shadow: 0 -4px 30px rgba(0, 102, 255, 0.3);
            z-index: 1000;
            padding: 10px 0;
        }

        .bottom-nav-content {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 600px;
            margin: 0 auto;
            padding: 0 10px;
        }

        .bottom-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 8px 5px;
            text-decoration: none;
            color: var(--text-gray);
            transition: all 0.3s ease;
            position: relative;
            border-radius: 12px;
        }

        .bottom-nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-blue), var(--accent-cyan));
            transition: width 0.3s ease;
            border-radius: 0 0 3px 3px;
        }

        .bottom-nav-item.active::before {
            width: 70%;
        }

        .bottom-nav-item.active {
            color: var(--accent-cyan);
            background: rgba(0, 168, 255, 0.1);
        }

        .bottom-nav-item:active {
            transform: scale(0.95);
        }

        .bottom-nav-icon {
            font-size: 1.4rem;
            margin-bottom: 4px;
            transition: all 0.3s ease;
        }

        .bottom-nav-item.active .bottom-nav-icon {
            animation: iconBounce 0.5s ease;
        }

        @keyframes iconBounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        .bottom-nav-label {
            font-size: 0.7rem;
            font-weight: 500;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .nav-links {
                display: none;
            }

            .features-grid, .payment-grid {
                grid-template-columns: 1fr;
            }

            /* Show bottom navigation on mobile */
            .bottom-nav {
                display: block;
            }

            /* Add padding to body to prevent content from being hidden behind bottom nav */
            body {
                padding-bottom: 70px;
            }

            /* Adjust footer padding on mobile */
            footer {
                padding-bottom: 20px;
            }
        }

        @media (max-width: 480px) {
            .bottom-nav-icon {
                font-size: 1.2rem;
            }

            .bottom-nav-label {
                font-size: 0.65rem;
            }

            .hero h1 {
                font-size: 2rem;
            }
        }

        /* Scroll Animation */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Animated Background Particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="container">
        <!-- Header -->
        <header>
            <nav>
                <div class="logo">D-TECT</div>
                <ul class="nav-links">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#features">Fitur</a></li>
                    <li><a href="#payment">Pembayaran</a></li>
                    <li><a href="#stats">Statistik</a></li>
                    <li><a href="#rooms">Kamar</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}" style="color: var(--accent-cyan); font-weight: 600;">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('login') }}" style="color: var(--accent-cyan); font-weight: 600;">Login</a></li>
                    @endauth
                </ul>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="hero" id="home">
            <h1>D-TECT Papi Kost</h1>
            <p>Sistem manajemen pembayaran kost & kontrakan yang rapi, transparan, dan otomatis. Upload bukti bayar, pantau keuangan, dan terima notifikasi langsung di WhatsApp.</p>
            @auth
                <a href="{{ route('dashboard') }}" class="cta-button"><span>Buka Dashboard</span></a>
            @else
                <a href="{{ route('login') }}" class="cta-button"><span>Login Sekarang</span></a>
            @endauth
        </section>

        <!-- Features Section -->
        <section class="features animate-on-scroll" id="features">
            <h2>Fitur Unggulan</h2>
            <div class="features-grid">
                @foreach($features as $feature)
                <div class="feature-card">
                    <div class="feature-icon">{{ $feature['icon'] }}</div>
                    <h3>{{ $feature['title'] }}</h3>
                    <p>{{ $feature['description'] }}</p>
                </div>
                @endforeach
            </div>
        </section>
         <!-- Room Details -->
        <section class="room-details animate-on-scroll" id="rooms" style="padding: 80px 0;">
            <h2 style="text-align: center; font-family: 'Orbitron', sans-serif; font-size: 2.5rem; margin-bottom: 60px; color: var(--light-blue); text-transform: uppercase; letter-spacing: 2px;">Detail Kamar dan User</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px;">
                @forelse($rooms as $room)
                <div style="background: rgba(0, 31, 63, 0.6); padding: 30px; border-radius: 20px; backdrop-filter: blur(10px); border: 2px solid rgba(0, 168, 255, 0.2);">
                    <h3 style="color: var(--accent-cyan); margin-bottom: 20px; font-size: 1.5rem;">Kamar {{ $room['room_number'] }}</h3>
                    <div style="space-y: 15px;">
                        @forelse($room['occupants'] as $occupant)
                        <div style="background: rgba(0, 102, 255, 0.1); padding: 15px; border-radius: 10px; border: 1px solid rgba(0, 168, 255, 0.2); display: flex; align-items: center; gap: 15px;">
                            @if(!empty($occupant['profile_photo_url']))
                                <img src="{{ $occupant['profile_photo_url'] }}" 
                                     alt="{{ $occupant['name'] }}" 
                                     style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent-cyan);">
                            @else
                                <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-blue), var(--light-blue)); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem; color: var(--text-white); border: 2px solid var(--accent-cyan);">
                                    {{ substr($occupant['name'], 0, 1) }}
                                </div>
                            @endif
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--text-white); margin-bottom: 5px;">{{ $occupant['name'] }}</div>
                                <div style="color: var(--text-gray); font-size: 0.9rem;">
                                    <i class="fas fa-phone" style="margin-right: 5px;"></i>{{ $occupant['phone'] }}<br>
                                    <i class="fas fa-calendar" style="margin-right: 5px;"></i>Bergabung: {{ $occupant['join_date'] }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div style="color: var(--text-gray); font-style: italic;">Tidak ada penghuni</div>
                        @endforelse
                    </div>
                </div>
                @empty
                <div style="background: rgba(0, 31, 63, 0.6); padding: 30px; border-radius: 20px; backdrop-filter: blur(10px); border: 2px solid rgba(0, 168, 255, 0.2); text-align: center; grid-column: 1 / -1;">
                    <i class="fas fa-home" style="font-size: 3rem; color: var(--text-gray); margin-bottom: 20px;"></i>
                    <h3 style="color: var(--text-gray);">Belum ada kamar terdaftar</h3>
                </div>
                @endforelse
            </div>
        </section>

        <!-- Cara Kerja -->
        <section class="payment-methods animate-on-scroll" id="payment">
            <h2>Cara Pembayaran</h2>
            <div class="payment-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); max-width: 1100px;">
                <div class="payment-item" style="position: relative;">
                    <span>1️⃣</span>
                    <div style="font-size: 2.5rem; margin-bottom: 8px;">🔐</div>
                    <p style="font-weight: 600; color: var(--accent-cyan); margin-bottom: 5px;">Login</p>
                    <p style="font-size: 0.8rem;">Masuk ke akun kamu melalui halaman login</p>
                </div>
                <div class="payment-item" style="position: relative;">
                    <span>2️⃣</span>
                    <div style="font-size: 2.5rem; margin-bottom: 8px;">💸</div>
                    <p style="font-weight: 600; color: var(--accent-cyan); margin-bottom: 5px;">Transfer / Bayar</p>
                    <p style="font-size: 0.8rem;">Bayar iuran via transfer bank, e-wallet, QRIS, atau tunai</p>
                </div>
                <div class="payment-item" style="position: relative;">
                    <span>3️⃣</span>
                    <div style="font-size: 2.5rem; margin-bottom: 8px;">📸</div>
                    <p style="font-weight: 600; color: var(--accent-cyan); margin-bottom: 5px;">Upload Bukti</p>
                    <p style="font-size: 0.8rem;">Upload foto bukti pembayaran di dashboard kamu</p>
                </div>
                <div class="payment-item" style="position: relative;">
                    <span>4️⃣</span>
                    <div style="font-size: 2.5rem; margin-bottom: 8px;">✅</div>
                    <p style="font-weight: 600; color: var(--accent-cyan); margin-bottom: 5px;">Admin Verifikasi</p>
                    <p style="font-size: 0.8rem;">Admin cek & approve pembayaran kamu</p>
                </div>
                <div class="payment-item" style="position: relative;">
                    <span>5️⃣</span>
                    <div style="font-size: 2.5rem; margin-bottom: 8px;">🔔</div>
                    <p style="font-weight: 600; color: var(--accent-cyan); margin-bottom: 5px;">Notifikasi</p>
                    <p style="font-size: 0.8rem;">Status dikirim otomatis ke grup WhatsApp</p>
                </div>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <p style="color: var(--text-gray); max-width: 600px; margin: 0 auto; line-height: 1.8;">
                    Semua proses tercatat otomatis. Kamu bisa pantau status pembayaran, riwayat, dan sisa tunggakan langsung dari dashboard.
                </p>
            </div>
        </section>

        <!-- Filter Section -->
        <section class="filter-section animate-on-scroll" style="padding: 40px 0; text-align: center;">
            <div style="background: rgba(0, 31, 63, 0.6); padding: 30px; border-radius: 20px; backdrop-filter: blur(10px); border: 2px solid rgba(0, 168, 255, 0.2); max-width: 600px; margin: 0 auto;">
                <h3 style="color: var(--light-blue); margin-bottom: 20px; font-family: 'Orbitron', sans-serif;">Filter Statistik</h3>
                <form method="GET" style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                    <div>
                        <label style="color: var(--text-gray); display: block; margin-bottom: 5px;">Bulan</label>
                        <select name="month" style="padding: 10px; border-radius: 10px; border: 2px solid rgba(0, 168, 255, 0.3); background: rgba(0, 102, 255, 0.1); color: var(--text-white);">
                            @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ (isset($month) ? $month : date('m')) == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label style="color: var(--text-gray); display: block; margin-bottom: 5px;">Tahun</label>
                        <select name="year" style="padding: 10px; border-radius: 10px; border: 2px solid rgba(0, 168, 255, 0.3); background: rgba(0, 102, 255, 0.1); color: var(--text-white);">
                            @php
                                $availableYears = isset($availableYears) ? $availableYears : range(date('Y') - 2, date('Y') + 1);
                                $currentYear = isset($year) ? $year : date('Y');
                            @endphp
                            @foreach($availableYears as $yearOption)
                            <option value="{{ $yearOption }}" {{ $currentYear == $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="align-self: end;">
                        <button type="submit" style="padding: 10px 20px; background: linear-gradient(135deg, var(--primary-blue), var(--light-blue)); color: var(--text-white); border: none; border-radius: 10px; cursor: pointer; font-weight: 600;">Filter</button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Stats -->
        <section class="stats animate-on-scroll" id="stats">
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>{{ number_format($totalUsers) }}</h3>
                    <p>Pengguna Terdaftar</p>
                </div>
                <div class="stat-item">
                    <h3>{{ number_format($totalRooms) }}</h3>
                    <p>Total Kamar</p>
                </div>
                <div class="stat-item">
                    <h3>Rp {{ number_format($totalCollected, 0, ',', '.') }}</h3>
                    <p>Pemasukan {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}</p>
                </div>
                <div class="stat-item">
                    <h3>Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
                    <p>Pengeluaran {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}</p>
                </div>
                <div class="stat-item">
                    <h3>Rp {{ number_format($walletBalance, 0, ',', '.') }}</h3>
                    <p>Saldo Saat Ini</p>
                </div>
                <div class="stat-item">
                    <h3>{{ number_format($pendingPayments) }}</h3>
                    <p>Pembayaran Pending</p>
                </div>
            </div>
        </section>

       

        <!-- Footer -->
        <footer id="contact">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>D-TECT Papi Kost</h4>
                    <p>Sistem manajemen pembayaran kost & kontrakan yang transparan dan mudah digunakan. Semua tercatat rapi, dari pembayaran hingga pengeluaran.</p>
                </div>
                <div class="footer-section">
                    <h4>Fitur</h4>
                    <a href="#features">Upload Bukti Bayar</a>
                    <a href="#features">Approval Pembayaran</a>
                    <a href="#features">Laporan Keuangan</a>
                    <a href="#features">Notifikasi WhatsApp</a>
                </div>
                <div class="footer-section">
                    <h4>Akses</h4>
                    @auth
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <a href="{{ route('profile.edit') }}">Profil Saya</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Daftar</a>
                    @endauth
                </div>
                <div class="footer-section">
                    <h4>Informasi</h4>
                    <p>📍 Papi Kost</p>
                    <p>📞 Hubungi Admin</p>
                    <p>💬 Grup WhatsApp</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} D-TECT Papi Kost. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Bottom Navigation Bar (Mobile Only) -->
    <nav class="bottom-nav">
        <div class="bottom-nav-content">
            <a href="#home" class="bottom-nav-item active" data-section="home">
                <div class="bottom-nav-icon">🏠</div>
                <div class="bottom-nav-label">Home</div>
            </a>
            <a href="#features" class="bottom-nav-item" data-section="features">
                <div class="bottom-nav-icon">⚡</div>
                <div class="bottom-nav-label">Fitur</div>
            </a>
            <a href="#payment" class="bottom-nav-item" data-section="payment">
                <div class="bottom-nav-icon">💳</div>
                <div class="bottom-nav-label">Bayar</div>
            </a>
            <a href="#stats" class="bottom-nav-item" data-section="stats">
                <div class="bottom-nav-icon">📊</div>
                <div class="bottom-nav-label">Statistik</div>
            </a>
            <a href="#rooms" class="bottom-nav-item" data-section="rooms">
                <div class="bottom-nav-icon">🏠</div>
                <div class="bottom-nav-label">Kamar</div>
            </a>
        </div>
    </nav>

    <script>
        // Scroll Animation
        const observerOptions = {
            threshold: 0.2,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Bottom Navigation Active State
        const bottomNavItems = document.querySelectorAll('.bottom-nav-item');
        const sections = document.querySelectorAll('section[id], footer[id]');

        // Update active state on scroll
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= (sectionTop - 200)) {
                    current = section.getAttribute('id');
                }
            });

            bottomNavItems.forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('data-section') === current) {
                    item.classList.add('active');
                }
            });
        });

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add hover effect sound (optional - silent by default)
        const buttons = document.querySelectorAll('.cta-button, .payment-item, .feature-card');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            });
        });

        // Parallax effect for particles
        document.addEventListener('mousemove', (e) => {
            const particles = document.querySelectorAll('.particle');
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            particles.forEach((particle, index) => {
                const speed = (index + 1) * 0.5;
                const xMove = (x - 0.5) * speed * 20;
                const yMove = (y - 0.5) * speed * 20;
                particle.style.transform = `translate(${xMove}px, ${yMove}px)`;
            });
        });
    </script>
</body>
</html>
