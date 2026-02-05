# 🎓 Sistem Informasi Akademik (SIAKAD)

Sistem Informasi Akademik berbasis **Laravel REST API** yang mendukung **multi-role access control** dan terintegrasi dengan aplikasi mobile.

---

## ✨ Fitur Utama

- Manajemen pengguna
- Manajemen jadwal
- FRS (Formulir Rencana Studi)
- Manajemen nilai

---

## 👤 Role Pengguna

Sistem ini memiliki **3 role pengguna** dengan hak akses berbeda:

- Admin
- Dosen
- Mahasiswa

---

## 🔐 Autentikasi & Hak Akses

Sistem autentikasi dan otorisasi diimplementasikan menggunakan:

- **Laravel Sanctum** untuk autentikasi berbasis token (API)
- **Role & Permission** untuk pembatasan hak akses setiap pengguna

Setiap role memiliki permission masing-masing untuk mengatur akses terhadap modul:

- pengguna
- jadwal
- FRS
- nilai

---

## 📱 Integrasi Aplikasi Mobile

Sistem ini terintegrasi dengan aplikasi mobile:

**SIAKAD Trivium**

Aplikasi mobile hanya digunakan oleh:

- Mahasiswa

Mahasiswa mengakses sistem melalui **API yang sama** menggunakan token yang dihasilkan oleh Laravel Sanctum.

---

## 🧩 Arsitektur Akses (Ringkas)

- Backend menggunakan **Laravel REST API**
- Autentikasi menggunakan **Laravel Sanctum**
- Pembatasan hak akses dilakukan di sisi backend menggunakan:
  - middleware
  - policy / gate
  - role & permission

Dengan demikian, baik aplikasi web maupun aplikasi mobile menggunakan mekanisme hak akses yang sama.

---

## ⚠️ Catatan Penting

> Pengaturan hak akses memperhatikan urutan baris letak aturan hak akses.

Urutan penulisan middleware, policy, dan aturan otorisasi harus diperhatikan agar tidak terjadi konflik akses atau kesalahan validasi hak akses.

---

## 🛠️ Teknologi yang Digunakan

- Laravel
- Laravel Sanctum
- REST API
- Role & Permission
- Mobile API Integration
