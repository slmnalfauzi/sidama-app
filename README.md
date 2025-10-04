# SIDAMA - Sistem Informasi Data Mahasiswa

Aplikasi CRUD modern untuk mengelola data mahasiswa dengan PHP, MySQL, Bootstrap 5, dan SweetAlert2.

## Fitur Utama

### Fungsional
- ✅ CRUD lengkap (Create, Read, Update, Delete)
- ✅ Pencarian data (NIM atau Nama)
- ✅ Filter berdasarkan usia
- ✅ Statistik dashboard (total, rata-rata umur, termuda/tertua)
- ✅ Pagination (10 data per halaman)
- ✅ Sorting kolom (klik header tabel)
- ✅ Import data dari CSV
- ✅ Export data ke CSV
- ✅ Upload foto mahasiswa

### UI/UX
- ✅ Dark/Light mode toggle
- ✅ SweetAlert2 untuk notifikasi modern
- ✅ Validasi form (front-end + back-end)
- ✅ Responsive design (Bootstrap 5)
- ✅ Preview data sebelum simpan
- ✅ Animasi hover dan transisi

### Keamanan
- ✅ Login sistem dengan role (Admin & Viewer)
- ✅ Role-based access control
- ✅ Konfirmasi sebelum hapus data
- ✅ Prepared statements (SQL Injection prevention)
- ✅ XSS protection

## Teknologi

- PHP 7.4+
- MySQL 5.7+
- Bootstrap 5.3
- SweetAlert2
- FontAwesome 6.4
- JavaScript (Vanilla)

## Instalasi

1. **Clone atau download project**
   \`\`\`bash
   git clone [repository-url]
   \`\`\`

2. **Import database**
   - Buka phpMyAdmin
   - Buat database baru bernama `sidama`
   - Import file `database.sql`

3. **Konfigurasi database**
   - Edit file `db.php` jika perlu mengubah kredensial database

4. **Buat folder uploads**
   \`\`\`bash
   mkdir uploads
   chmod 777 uploads
   \`\`\`

5. **Jalankan di localhost**
   - Akses: `http://localhost/sidama/login.php`

## Login Default

- **Admin**: username: `admin`, password: `password`
- **Viewer**: username: `viewer`, password: `password`

## Struktur Folder

\`\`\`
sidama/
├── index.php              # Dashboard
├── mahasiswa.php          # Tabel data mahasiswa
├── add.php                # Form tambah data
├── edit.php               # Form edit data
├── delete.php             # Hapus data
├── import.php             # Import CSV
├── export.php             # Export CSV
├── login.php              # Form login
├── logout.php             # Logout
├── get_detail.php         # API detail mahasiswa
├── db.php                 # Koneksi database
├── auth.php               # Autentikasi
├── database.sql           # SQL database
├── template.csv           # Template import
├── uploads/               # Folder foto
├── assets/
│   ├── css/
│   │   └── style.css      # Custom styles
│   └── js/
│       └── script.js      # Custom scripts
└── includes/
    ├── header.php         # Header template
    ├── footer.php         # Footer template
    └── functions.php      # Utility functions
\`\`\`

## Fitur Role

### Admin
- Dapat melakukan semua operasi CRUD
- Akses import/export data
- Akses semua menu

### Viewer
- Hanya dapat melihat data
- Tidak dapat menambah/edit/hapus
- Tidak dapat import/export

## Format Import CSV

\`\`\`csv
NIM,Nama,Email,Tanggal Lahir,Jenis Kelamin,Alamat
2021004,John Doe,john@email.com,2003-01-15,L,Jl. Example No. 1
\`\`\`

## Screenshot

(Tambahkan screenshot aplikasi di sini)

## Lisensi

MIT License

## Kontak

Untuk pertanyaan atau dukungan, silakan hubungi developer.
