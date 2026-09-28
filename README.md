# Product Manager

Mini project Pemrograman Web Pertemuan 3: aplikasi manajemen produk menggunakan PHP dan MySQL.

## Fitur
- Create produk
- Read produk dalam card responsif
- Update produk berdasarkan ID
- Delete menggunakan POST + CSRF
- Validasi server-side
- Nama produk unik
- PDO prepared statement
- PRG setelah Create/Update/Delete
- `htmlspecialchars()` untuk keamanan output
- Search/filter dengan GET

## Struktur
```text
product-manager/
├── config/
│   └── db.php
├── public/
│   ├── index.php
│   ├── create.php
│   ├── edit.php
│   ├── delete.php
│   └── assets/
│       └── style.css
├── database/
│   └── store_db.sql
└── README.md
```

## Cara menjalankan
1. Install XAMPP dan aktifkan Apache + MySQL.
2. Copy folder `product-manager` ke `C:/xampp/htdocs/`.
3. Buka phpMyAdmin.
4. Import file `database/store_db.sql`.
5. Pastikan database `store_db` sudah dibuat.
6. Buka:
   `http://localhost/product-manager/public/`

## Catatan koneksi
Konfigurasi default pada `config/db.php`:
- host: localhost
- database: store_db
- user: root
- password: kosong

Jika konfigurasi MySQL berbeda, ubah bagian koneksi tersebut.

## Pengujian
Coba:
- Tambah produk dengan data valid.
- Nama kurang dari 3 karakter.
- Harga 0 atau negatif.
- Stok negatif.
- Nama produk yang sama.
- Refresh setelah menambah produk.
- Nama produk `<b>Promo</b>` untuk melihat escaping HTML.
- Edit produk.
- Hapus produk.
- Cari berdasarkan nama atau kategori.
