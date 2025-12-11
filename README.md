# Unsrat Foodcourt - Sistem Manajemen Stok & Kantin

## 📖 Deskripsi
**Unsrat Foodcourt** adalah aplikasi berbasis web yang dirancang untuk mempermudah pengelolaan operasional kantin, mulai dari manajemen stok barang hingga pemantauan aktivitas pengguna. Aplikasi ini dikembangkan sebagai bagian dari Tugas Besar/Proyek Akhir mata kuliah Rekayasa Perangkat Lunak.

Sistem ini mendukung *Multi-User* yang membedakan hak akses antara **Administrator** dan **User/Staf**, sehingga pengelolaan data menjadi lebih terstruktur dan aman.

## 🚀 Fitur Utama
* **Sistem Login & Register:** Otentikasi aman untuk membatasi akses pengguna.
* **Multi-Level User:**
    * **Admin:** Memiliki akses penuh ke `admin_dashboard.php` untuk manajemen sistem dan reset akses.
    * **User/Staf:** Memiliki akses ke `dashboard.php` untuk operasional harian.
* **Manajemen Stok:** Memantau ketersediaan barang secara *real-time* (Login untuk mengelola stok).
* **Responsive Design:** Tampilan antarmuka yang menyesuaikan ukuran layar (Desktop & Mobile).

## 🛠️ Teknologi yang Digunakan
* **Bahasa Pemrograman:** PHP (Native)
* **Database:** MySQL
* **Server:** Apache (via XAMPP)
* **Frontend:** HTML, CSS (Bootstrap), JavaScript

## 📂 Struktur File
Berikut adalah penjelasan singkat mengenai file-file utama dalam proyek ini:

| Nama File | Deskripsi |
| :--- | :--- |
| `index.php` | Halaman utama untuk Login pengguna. |
| `register.php` | Halaman pendaftaran akun baru. |
| `dashboard.php` | Halaman antarmuka utama untuk User/Staf. |
| `admin_dashboard.php` | Halaman antarmuka khusus untuk Administrator. |
| `koneksi.php` | Konfigurasi koneksi ke database MySQL. |
| `reset_admin.php` | Utilitas untuk mereset akses administrator (Development tool). |
| `logout.php` | Skrip untuk mengakhiri sesi pengguna. |
| `assets/` | Folder penyimpanan gambar (bg.png, bg2.png) dan aset lainnya. |

## 💻 Cara Instalasi & Menjalankan (Localhost)

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/username-kamu/unsrat-foodcourt.git](https://github.com/username-kamu/unsrat-foodcourt.git)
    ```
    Atau download ZIP dan ekstrak.

2.  **Pindahkan Folder**
    Pindahkan folder `foodcourt` ke dalam direktori server lokal:
    * XAMPP: `C:\xampp\htdocs\`

3.  **Konfigurasi Database**
    * Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
    * Buat database baru dengan nama `foodcourt_db` (atau sesuaikan dengan file `koneksi.php`).
    * Import file `.sql` database (jika ada) ke dalamnya.

4.  **Konfigurasi Koneksi**
    Buka file `koneksi.php` dan pastikan settingan berikut sesuai:
    ```php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "foodcourt_db";
    ```

5.  **Jalankan Aplikasi**
    Buka browser dan akses:
    `http://localhost/foodcourt`

## 👥 Tim Pengembang
Proyek ini disusun oleh Kelompok A5:
* **DANILL HENRY SUMOLANG** - NIM: 240211060011
* **ANGGREINI PRISILIA LUMI** - NIM: 240211060010
* **JONATHAN CHRISTIAN KONDOY** - NIM: 240211060015
* **MARCHELLO ANDREAS LENGKONG** - NIM: 240211060045

---
**Catatan:** Aplikasi ini dibuat untuk tujuan pendidikan dan simulasi sistem akademik.
