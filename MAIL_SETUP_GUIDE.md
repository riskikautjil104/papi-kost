# 📧 Panduan Setup Email untuk Reset Password

## 🔴 Masalah
Default mailer Laravel di-set ke `log` (hanya tersimpan di file `storage/logs/laravel.log`), jadi email tidak benar-benar dikirim.

## ✅ Solusi (Pilih Salah Satu)

---

## Opsi 1: Mailtrap (Recommended untuk Testing) 🧪

**Gratis, email masuk ke inbox Mailtrap (tidak ke email asli)**

### Step 1: Daftar Mailtrap
1. Buka https://mailtrap.io
2. Daftar akun gratis
3. Buat inbox baru
4. Copy SMTP credentials

### Step 2: Update `.env`
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@dtect-kost.com"
MAIL_FROM_NAME="D-TECT Kost"
```

### Step 3: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### Step 4: Test
1. Buka http://127.0.0.1:8000/forgot-password
2. Masukkan email user
3. Cek inbox di dashboard Mailtrap
4. Email reset password akan muncul di sana

---

## Opsi 2: Gmail SMTP (Production) 📧

**Email benar-benar masuk ke inbox Gmail**

### Step 1: Buat App Password Gmail
1. Buka https://myaccount.google.com
2. Security → 2-Step Verification (aktifkan dulu)
3. Security → App passwords
4. Generate app password untuk "Mail"
5. Copy password yang muncul (16 karakter)

### Step 2: Update `.env`
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_gmail@gmail.com
MAIL_PASSWORD=your_16_char_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_gmail@gmail.com"
MAIL_FROM_NAME="D-TECT Kost"
```

### Step 3: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

---

## Opsi 3: Mailpit (Local Development) 🖥️

**Untuk development local, email ditangkap di localhost**

### Step 1: Install Mailpit
```bash
# macOS
brew install mailpit

# Windows (via scoop)
scoop install mailpit

# Linux
curl -sL https://raw.githubusercontent.com/axllent/mailpit/develop/install.sh | bash
```

### Step 2: Jalankan Mailpit
```bash
mailpit
```
Buka http://localhost:8025 untuk melihat inbox

### Step 3: Update `.env`
```env
MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@dtect-kost.com"
MAIL_FROM_NAME="D-TECT Kost"
```

### Step 4: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

---

## 🧪 Testing Reset Password

Setelah setup salah satu opsi di atas:

1. **Buka halaman forgot password:**
   ```
   http://127.0.0.1:8000/forgot-password
   ```

2. **Masukkan email user yang terdaftar**

3. **Klik "Email Password Reset Link"**

4. **Cek email di:**
   - Mailtrap dashboard (Opsi 1)
   - Gmail inbox (Opsi 2)
   - Mailpit web UI http://localhost:8025 (Opsi 3)

5. **Klik link reset password di email**

6. **Masukkan password baru**

---

## 🔧 Troubleshooting

### "Failed to authenticate on SMTP server"
- Cek username/password
- Untuk Gmail: pastikan pakai App Password, bukan password Gmail biasa
- Cek 2-Step Verification sudah aktif

### "Connection refused"
- Cek MAIL_HOST dan MAIL_PORT
- Pastikan tidak ada firewall blocking
- Untuk Mailtrap: cek koneksi internet

### Email tidak muncul
- Cek `storage/logs/laravel.log` untuk error detail
- Pastikan `php artisan config:clear` sudah dijalankan
- Cek email user benar-benar terdaftar di database

### "Unable to connect with TLS"
- Coba ganti `MAIL_ENCRYPTION=tls` menjadi `ssl` atau `null`
- Cek port yang digunakan (587 untuk tls, 465 untuk ssl)

---

## 📋 Checklist Setup

- [ ] Pilih salah satu opsi (Mailtrap/Gmail/Mailpit)
- [ ] Update file `.env` dengan konfigurasi
- [ ] Jalankan `php artisan config:clear`
- [ ] Jalankan `php artisan cache:clear`
- [ ] Test forgot password
- [ ] Verifikasi email masuk ke inbox

---

## 🎯 Rekomendasi

| Environment | Opsi | Alasan |
|-------------|------|--------|
| Development Local | Mailpit | Gratis, tidak perlu internet |
| Testing/Staging | Mailtrap | Aman, tidak spam email asli |
| Production | Gmail/SendGrid/Postmark | Email benar-benar terkirim |

---

**Butuh bantuan lebih lanjut? Cek log error di `storage/logs/laravel.log` 🚀**
