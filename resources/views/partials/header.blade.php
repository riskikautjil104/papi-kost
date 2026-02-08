<header>
    <nav>
        <div class="logo">
            <a href="{{ route('home') }}">D-TECT</a>
        </div>
        <ul class="nav-links">
            <li><a href="{{ route('home') }}" class="{{ Request::routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('features') }}" class="{{ Request::routeIs('features') ? 'active' : '' }}">Fitur</a></li>
            <li><a href="{{ route('payment') }}" class="{{ Request::routeIs('payment') ? 'active' : '' }}">Pembayaran</a></li>
            <li><a href="{{ route('contact') }}" class="{{ Request::routeIs('contact') ? 'active' : '' }}">Kontak</a></li>
        </ul>
        <div class="mobile-menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>
</header>
