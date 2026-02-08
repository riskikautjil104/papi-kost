# 📋 TODO List - Kontrakan Management System

## Status Route & Auth ✅
- [x] Fix redirect login/register berdasarkan role (admin/user)
- [x] Tambah middleware checkAdmin di routes/admin.php
- [x] Update web.php dashboard redirect

## 📊 Dashboard Admin
- [x] Perbaiki dashboard dengan statistik lengkap
  - [x] Total uang sekarang (wallet balance)
  - [x] Tunggakan per orang (yang dibuat admin)
  - [x] Statistik produksi (pemasukan per bulan)
  - [x] Statistik pengeluaran per kategori

## 👥 User Management (Admin)
- [x] Form tambah user dengan validasi:
  - [x] Email aktif (unique, format valid)
  - [x] Nomor telepon aktif (unique, format Indonesia)
  - [x] Iuran bulanan bervariasi per orang
  - [x] Room number
  - [x] Join date & Contract end date

## 💰 Payment System
- [x] Admin lihat semua pembayaran
- [x] Admin approve/reject bukti pembayaran
- [x] User biasa bisa:
  - [x] Login/Register
  - [x] Upload bukti pembayaran
  - [x] Lihat history pembayaran
  - [x] Bayar sesuai iuran yang diassign admin

## 💵 Finance Management
- [x] Tambah pemasukan (income)
- [x] Tambah pengeluaran (expense)
- [x] Kategori pengeluaran (listrik, air, maintenance, dll)
- [x] Lihat saldo wallet sekarang

## 📊 Dashboard User
- [x] Lihat pemasukan & pengeluaran kontrakan
- [x] Filter data per bulan & tahun
- [x] Lihat tunggakan pribadi
- [x] Status pembayaran per bulan (bulan apa saja sudah bayar)
- [x] Total uang kas kontrakan

## 📤 Export Reports
- [ ] Export Excel (sudah ada)
- [ ] Export PDF
  - [ ] Laporan keuangan bulanan
  - [ ] Laporan tunggakan
  - [ ] Laporan user

## 🎨 UI/UX Improvements
- [x] Dashboard home dengan ringkasan
- [x] Halaman tunggakan per user
- [x] Chart statistik (Chart.js)
- [x] Notifikasi pembayaran (Email - tiap pagi jam 7 WIT)

## 📧 Email Notification System
- [x] Migration kolom notifikasi
- [x] Notification Class (PaymentReminderNotification)
- [x] Artisan Command (payment:send-reminders)
- [x] Scheduler (jam 7 pagi WIT otomatis)
- [x] Test mode (--test flag)

---

## 🚀 Progress
### Done ✅
1. Route redirect berdasarkan role (admin/user)
2. Middleware checkAdmin & User
3. Auth controller redirect fix
4. Database migration & model relationships
5. Admin dashboard dengan Chart.js
6. User management CRUD lengkap
7. Payment system (approve/reject)
8. Finance management (income/expense)
9. User dashboard dengan filter & statistik

### In Progress 🔄
1. -

### Pending ⏳
1. Export PDF reports
