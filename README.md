# API Platform Digital Pariwisata Kota Banda Aceh

Ini adalah repositori untuk layanan backend (API) dari proyek skripsi "Platform Digital Pariwisata Kota Banda Aceh". API ini dibangun menggunakan Laravel dan dirancang untuk melayani aplikasi frontend (Next.js) dengan menyediakan semua data dan logika bisnis yang diperlukan.

## ✨ Fitur Utama

- **Sistem Otentikasi Berbasis Token:** Menggunakan Laravel Sanctum untuk otentikasi yang aman.
- **Manajemen Peran & Izin (Multi-Peran):** Dibangun dengan `spatie/laravel-permission` untuk tiga peran utama:
  - **Admin (Dinas Pariwisata):** Kontrol penuh atas seluruh sistem.
  - **Pengelola:** Dapat mengelola informasi destinasi miliknya sendiri.
  - **Wisatawan:** Pengguna terdaftar yang dapat berinteraksi dengan platform.
- **Alur Verifikasi Pengelola:** Admin dapat menyetujui atau menolak pendaftaran pengelola baru.
- **CRUD Penuh untuk Admin:**
  - Manajemen Berita & Acara
  - Manajemen Kategori Wisata
  - Manajemen Pengguna
- **Manajemen Konten oleh Pengelola:** Pengelola yang terverifikasi dapat membuat dan mengelola destinasi wisatanya.
- **Fitur Interaksi Pengguna:**
  - Memberikan ulasan dan rating pada destinasi.
  - Menyimpan destinasi ke daftar favorit (*bookmark*).
  - Mengelola profil pengguna.

## 🚀 Teknologi yang Digunakan

- **Framework:** Laravel 11
- **Database:** PostgreSQL
- **Otentikasi:** Laravel Sanctum
- **Manajemen Peran:** Spatie Laravel Permission
- **Testing:** Postman

## 🛠️ Panduan Instalasi

1.  **Instal dependency Composer:**
    ```bash
    composer install
    ```

2.  **Buat file `.env`:**
    * Salin file `.env.example` menjadi `.env`.
    * Sesuaikan konfigurasi database PostgreSQL (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

3.  **Generate kunci aplikasi:**
    ```bash
    php artisan key:generate
    ```

4.  **Buat struktur database dan isi data awal:**
    * Perintah ini akan membuat semua tabel dan mengisi data peran (roles).
    ```bash
    php artisan migrate:fresh --seed
    ```

5.  **Buat tautan storage:**
    ```bash
    php artisan storage:link
    ```

6.  **Jalankan server pengembangan:**
    ```bash
    php artisan serve
    ```
    API akan berjalan di `http://127.0.0.1:8000`.

## Endpoints API

Berikut adalah daftar endpoint utama yang tersedia.

### Otentikasi
| Method | URL | Deskripsi |
| :--- | :--- | :--- |
| `POST` | `/api/register` | Registrasi untuk peran 'Wisatawan'. |
| `POST` | `/api/register/pengelola` | Registrasi untuk peran 'Pengelola' (memerlukan unggah file). |
| `POST` | `/api/login` | Login universal untuk semua peran. |

### Rute Publik
| Method | URL | Deskripsi |
| :--- | :--- | :--- |
| `GET` | `/api/destinations` | Melihat semua destinasi yang sudah di-*publish*. |
| `GET` | `/api/destinations/{id}` | Melihat detail satu destinasi. |

### Rute Pengguna Terotentikasi (`auth:sanctum`)
| Method | URL | Deskripsi |
| :--- | :--- | :--- |
| `GET` | `/api/user` | Mendapatkan data pengguna yang sedang login. |
| `POST` | `/api/destinations` | Pengelola membuat destinasi baru. |
| `PUT` | `/api/destinations/{id}` | Pengelola/Admin mengupdate destinasi. |
| `DELETE`| `/api/destinations/{id}` | Pengelola/Admin menghapus destinasi. |
| `POST` | `/api/destinations/{id}/reviews`| Wisatawan memberikan ulasan. |
| `POST` | `/api/destinations/{id}/bookmark`| Wisatawan menyimpan destinasi. |
| `DELETE`| `/api/destinations/{id}/bookmark`| Wisatawan menghapus bookmark. |
| `GET` | `/api/profile` | Wisatawan melihat profilnya. |
| `PUT` | `/api/profile` | Wisatawan mengupdate profilnya. |
| `PUT` | `/api/profile/password` | Wisatawan mengganti password. |
| `POST` | `/api/destinations/{id}/photos`| Pengelola mengunggah foto. |
| `DELETE`| `/destination-photos/{id}` | Pengelola menghapus foto. |


### Rute Khusus Admin (`role:admin`)
| Method | URL | Deskripsi |
| :--- | :--- | :--- |
| `GET` | `/api/admin/verifications` | Melihat daftar pendaftar yang 'pending'. |
| `POST` | `/api/admin/verifications/{id}/approve` | Menyetujui pendaftaran. |
| `POST` | `/api/admin/verifications/{id}/reject` | Menolak pendaftaran. |
| `GET` | `/api/admin/news` | Melihat semua berita. |
| `POST` | `/api/admin/news` | Membuat berita baru. |
| `PUT` | `/api/admin/news/{id}` | Mengupdate berita. |
| `DELETE`| `/api/admin/news/{id}` | Menghapus berita. |
| `...` | `/api/admin/categories` | CRUD untuk Kategori. |
| `...` | `/api/admin/users` | CRUD untuk Pengguna. |

---

Dibuat oleh **Dzakkivansyah**.
