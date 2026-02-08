<footer id="contact">
    <div class="footer-content">
        <div class="footer-section">
            <h4>D-TECT</h4>
            <p>Platform pembayaran online terpercaya untuk bisnis modern. Kami berkomitmen memberikan layanan terbaik dengan teknologi terdepan.</p>
        </div>
        <div class="footer-section">
            <h4>Layanan</h4>
            <a href="{{ route('services.gateway') }}">Payment Gateway</a>
            <a href="{{ route('services.disbursement') }}">Disbursement</a>
            <a href="{{ route('services.link') }}">Payment Link</a>
            <a href="{{ route('services.subscription') }}">Subscription</a>
        </div>
        <div class="footer-section">
            <h4>Perusahaan</h4>
            <a href="{{ route('about') }}">Tentang Kami</a>
            <a href="{{ route('career') }}">Karir</a>
            <a href="{{ route('blog') }}">Blog</a>
            <a href="{{ route('contact') }}">Kontak</a>
        </div>
        <div class="footer-section">
            <h4>Dukungan</h4>
            <a href="{{ route('docs') }}">Dokumentasi</a>
            <a href="{{ route('faq') }}">FAQ</a>
            <a href="{{ route('support') }}">Support Center</a>
            <a href="{{ route('status') }}">Status System</a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} D-TECT. All rights reserved. | Powered by Innovation</p>
    </div>
</footer>
