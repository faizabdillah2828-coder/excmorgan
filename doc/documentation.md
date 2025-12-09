# Dokumentasi Proyek E-commerce Excmorgan

## Deskripsi Umum

Excmorgan adalah aplikasi e-commerce fashion yang dibangun dengan PHP dan MySQL. Aplikasi ini menyediakan platform untuk menjual produk fashion secara online dengan fitur lengkap untuk pengguna dan administrator.

## Fitur Utama

### 1. Sistem Otentikasi Pengguna
- Pendaftaran akun baru
- Login dan logout
- Manajemen profil pengguna
- Sistem otentikasi berbasis sesi
- Peran admin (dengan flag is_admin)

### 2. Manajemen Produk
- Tampilan katalog produk
- Detail produk lengkap
- Pencarian produk
- Kategori produk (terbaru dan best seller)
- Filter dan pencarian produk

### 3. Sistem Keranjang Belanja
- Penambahan produk ke keranjang
- Pengurangan jumlah produk
- Penghapusan item dari keranjang
- Pembaruan jumlah produk secara real-time

### 4. Proses Pemesanan
- Checkout dengan alamat pengiriman
- Pilihan metode pembayaran
- Manajemen status pesanan
- Riwayat pemesanan untuk pengguna

### 5. Panel Admin
- Dashboard dengan statistik
- Manajemen produk (tambah, edit, hapus)
- Manajemen pesanan
- Pengaturan kontak dan media sosial
- Manajemen pesan kontak

### 6. Sistem Kontak
- Formulir kontak interaktif
- Manajemen informasi kontak
- Simpan pesan kontak ke database

## Struktur Database

### Tabel: users
- `id` (Primary Key, auto-increment): Identifier unik pengguna
- `name`: Nama lengkap pengguna
- `email`: Alamat email (unik), digunakan untuk login
- `password`: Kata sandi yang telah di-hash
- `profile_picture`: URL atau path file gambar profil (opsional)
- `is_admin`: Boolean untuk menandai apakah pengguna adalah admin
- `created_at`, `updated_at`: Timestamp pembuatan dan pembaruan

### Tabel: products
- `id` (Primary Key, auto-increment): Identifier unik produk
- `name`: Nama produk
- `description`: Deskripsi lengkap produk
- `price`: Harga produk dalam format DECIMAL(10, 2)
- `image_url`: URL gambar utama produk
- `is_active`: Boolean untuk menandai apakah produk ditampilkan
- `created_at`, `updated_at`: Timestamp pembuatan dan pembaruan

### Tabel: carts
- `id` (Primary Key, auto-increment): Identifier unik keranjang
- `user_id` (Foreign Key ke users.id): Pengguna pemilik keranjang
- `created_at`, `updated_at`: Timestamp pembuatan dan pembaruan

### Tabel: cart_items
- `id` (Primary Key, auto-increment): Identifier unik item keranjang
- `cart_id` (Foreign Key ke carts.id): Keranjang tempat item ini disimpan
- `product_id` (Foreign Key ke products.id): Produk yang ditambahkan
- `quantity`: Jumlah unit produk dalam keranjang
- `created_at`, `updated_at`: Timestamp pembuatan dan pembaruan

### Tabel: orders
- `id` (Primary Key, auto-increment): Nomor unik order
- `user_id` (Foreign Key ke users.id): Pengguna yang melakukan pembelian
- `total_amount`: Total harga seluruh item dalam order
- `status`: ENUM (Diproses, Dikirim, Selesai, Dibatalkan)
- `shipping_address`: Alamat pengiriman lengkap
- `payment_method`: Metode pembayaran yang dipilih
- `created_at`, `updated_at`: Timestamp pembuatan dan pembaruan

### Tabel: order_items
- `id` (Primary Key, auto-increment): Identifier unik item order
- `order_id` (Foreign Key ke orders.id): Order tempat item ini termasuk
- `product_id` (Foreign Key ke products.id): Produk yang dibeli
- `product_name`: Salinan nama produk saat transaksi
- `product_price`: Salinan harga satuan saat transaksi
- `quantity`: Jumlah unit yang dibeli
- `total_price`: Harga total item (quantity × product_price)

### Tabel: site_settings
- `id` (Primary Key, auto-increment)
- `key_name`: Nama kunci pengaturan (unik)
- `value`: Nilai pengaturan
- `updated_at`: Timestamp terakhir pembaruan

### Tabel: contact_messages
- `id` (Primary Key, auto-increment)
- `name`: Nama pengirim
- `email`: Email pengirim
- `message`: Isi pesan
- `created_at`: Timestamp saat pesan dikirim

## Struktur File dan Direktori

```
excmorgan/
├── admin/                  # Direktori panel admin
│   ├── includes/           # File layout admin
│   │   └── admin_layout.php
│   ├── admin.php           # Dashboard admin
│   ├── admin_products.php  # Manajemen produk
│   ├── admin_orders.php    # Manajemen pesanan
│   ├── admin_contact.php   # Pengaturan kontak
│   ├── admin_contact_messages.php # Pesan kontak
│   ├── admin_order_detail.php
│   ├── hash_generator.php  # Utilitas untuk hash password
│   └── index.php
├── config/                 # File konfigurasi
│   ├── database.php        # Konfigurasi database
│   └── functions.php       # Fungsi-fungsi utama
├── includes/               # File layout utama
│   └── layout.php
├── img/                    # Folder gambar produk
├── doc/                    # Dokumentasi
│   └── documentation.md
├── .htaccess               # File konfigurasi server
├── index.php               # Halaman beranda
├── products.php            # Halaman katalog produk
├── product_detail.php      # Halaman detail produk
├── cart.php                # Halaman keranjang belanja
├── checkout.php            # Halaman checkout
├── account.php             # Halaman akun pengguna
├── history.php             # Riwayat pesanan
├── contact.php             # Halaman kontak
├── process_contact.php     # Proses formulir kontak
├── create_contact_table.php # Membuat tabel kontak
├── login.php               # Halaman login
├── register.php            # Halaman pendaftaran
├── logout.php              # Proses logout
├── edit_profile.php        # Edit profil pengguna
├── order_detail.php        # Detail pesanan
├── excmorgan_db.sql        # File struktur database
└── (dll.)
```

## Instalasi dan Konfigurasi

### Prasyarat
- XAMPP (Apache, MySQL, PHP)
- Web browser

### Langkah-langkah Instalasi
1. Ekstrak file ke folder htdocs XAMPP
2. Nyalakan Apache dan MySQL di XAMPP Control Panel
3. Impor file `excmorgan_db.sql` ke database MySQL
4. Pastikan konfigurasi database di `config/database.php` sesuai
5. Akses aplikasi melalui browser dengan URL: `http://localhost/excmorgan`

### Konfigurasi Database
File: `config/database.php`
```php
$host = 'localhost';
$dbname = 'excmorgan_db';
$username = 'root'; // Sesuaikan dengan konfigurasi MySQL Anda
$password = '';     // Sesuaikan dengan konfigurasi MySQL Anda
```

## Fungsi-fungsi Utama

### Di config/functions.php:
- `getProducts($limit)`: Mendapatkan produk dengan batas jumlah
- `getProductById($id)`: Mendapatkan produk berdasarkan ID
- `getCartItems($user_id)`: Mendapatkan item-item di keranjang pengguna
- `addProductToCart($user_id, $product_id, $quantity)`: Menambahkan produk ke keranjang
- `createOrder($user_id, $shipping_address, $payment_method)`: Membuat pesanan baru
- `getUserOrders($user_id)`: Mendapatkan pesanan pengguna
- `getSiteSettings()`: Mendapatkan pengaturan situs
- `uploadProductImage($file)`: Mengupload gambar produk

## Keamanan Aplikasi

1. **Otentikasi**: Password di-hash menggunakan `password_hash()`
2. **SQL Injection**: Menggunakan prepared statements untuk semua query
3. **XSS Prevention**: Menggunakan `htmlspecialchars()` untuk output user
4. **Sesi**: Sistem otentikasi berbasis sesi dengan validasi peran

## API dan Integrasi

### Formulir Kontak
- Menggunakan AJAX untuk pengiriman asinkron
- Validasi sisi klien dan server
- Pesan disimpan ke database `contact_messages`

### Upload Gambar Produk
- Validasi ekstensi file (jpg, jpeg, png, gif)
- Batas ukuran file (5MB)
- Nama file unik dengan timestamp

## Panduan Penggunaan

### Untuk Pengunjung:
1. Jelajahi produk di halaman beranda atau katalog
2. Daftar akun untuk berbelanja
3. Tambahkan produk ke keranjang
4. Checkout untuk menyelesaikan pembelian

### Untuk Admin:
1. Login ke panel admin
2. Kelola produk, pesanan, dan pengaturan kontak
3. Tanggapi pesan kontak dari pengguna

## Fitur Tambahan

### hash_generator.php
- Utilitas untuk menghasilkan hash password
- Berguna untuk manajemen akun admin secara manual

### create_contact_table.php
- File untuk membuat tabel kontak jika belum ada
- Dapat dijalankan sekali saat setup awal

## Penyesuaian dan Pengembangan

Aplikasi ini dapat dikembangkan lebih lanjut dengan:
- Sistem rating dan ulasan produk
- Fitur wishlist
- Sistem diskon dan kupon
- Integrasi pembayaran online
- Sistem notifikasi
- CMS untuk manajemen konten
- Fitur multi-bahasa
- Sistem inventaris
- Laporan dan analitik

## Troubleshooting

### Umum
- Pastikan XAMPP Apache dan MySQL berjalan
- Periksa konfigurasi database
- Pastikan file .htaccess aktif

### Masalah Database
- Impor ulang excmorgan_db.sql jika struktur tabel hilang
- Pastikan nama database cocok dengan konfigurasi

### Masalah Gambar
- Pastikan direktori img/ dapat ditulisi
- Cek izin akses folder untuk upload gambar