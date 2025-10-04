
<p align="center">
   <img src="https://img.shields.io/badge/PHP-7.4%2B-blue?logo=php" alt="PHP">
   <img src="https://img.shields.io/badge/MySQL-5.7%2B-orange?logo=mysql" alt="MySQL">
   <img src="https://img.shields.io/badge/Bootstrap-5.3-purple?logo=bootstrap" alt="Bootstrap">
   <img src="https://img.shields.io/badge/SweetAlert2-11.7.3-success?logo=javascript" alt="SweetAlert2">
</p>

# SIDAMA - Sistem Informasi Data Mahasiswa

<p align="center"><b>Aplikasi CRUD modern, responsif, dan aman untuk pengelolaan data mahasiswa.</b></p>

---

SIDAMA adalah aplikasi web CRUD berbasis PHP & MySQL yang dirancang untuk memudahkan pengelolaan data mahasiswa. Dilengkapi fitur dashboard statistik, import/export CSV, upload foto, dark mode, dan keamanan berbasis role.


## 🚀 Fitur Unggulan

<details>
<summary><b>Fungsional</b></summary>

- ✅ <b>CRUD Lengkap</b> (Create, Read, Update, Delete)
- 🔍 <b>Pencarian & Filter</b> (NIM, Nama, Usia)
- 📊 <b>Statistik Dashboard</b> (total, rata-rata umur, termuda/tertua)
- 📄 <b>Import/Export CSV</b>
- 🖼️ <b>Upload Foto Mahasiswa</b>
- 📑 <b>Pagination & Sorting</b>
</details>

<details>
<summary><b>UI/UX Modern</b></summary>

- 🌗 <b>Dark/Light Mode</b> toggle
- 🎨 <b>Responsive Design</b> (Bootstrap 5)
- 🛡️ <b>SweetAlert2</b> notifikasi interaktif
- 📝 <b>Preview Data</b> sebelum simpan
- ✨ <b>Animasi Hover & Transisi</b>
</details>

<details>
<summary><b>Keamanan</b></summary>

- 🔒 <b>Login Sistem</b> dengan role (Admin & Viewer)
- 🛡️ <b>Role-based Access Control</b>
- ⚠️ <b>Konfirmasi Hapus Data</b>
- 🧩 <b>Prepared Statements</b> (SQL Injection prevention)
- 🧹 <b>XSS Protection</b>
</details>


## 🛠️ Teknologi yang Digunakan

- <img src="https://img.shields.io/badge/PHP-7.4%2B-blue?logo=php" height="18"> PHP 7.4+
- <img src="https://img.shields.io/badge/MySQL-5.7%2B-orange?logo=mysql" height="18"> MySQL 5.7+
- <img src="https://img.shields.io/badge/Bootstrap-5.3-purple?logo=bootstrap" height="18"> Bootstrap 5.3
- <img src="https://img.shields.io/badge/SweetAlert2-11.7.3-success?logo=javascript" height="18"> SweetAlert2
- <img src="https://img.shields.io/badge/FontAwesome-6.4-informational?logo=fontawesome" height="18"> FontAwesome 6.4
- <img src="https://img.shields.io/badge/JavaScript-ES6-yellow?logo=javascript" height="18"> JavaScript (Vanilla)


## ⚡ Instalasi & Setup

1. **Clone repository**
   ```bash
   git clone https://github.com/username/sidama-crud.git
   cd sidama-crud
   ```

2. **Import database**
   - Buka <b>phpMyAdmin</b>
   - Buat database baru: <code>sidama</code>
   - Import file <code>database.sql</code>

3. **Konfigurasi koneksi database**
   - Edit file <code>db.php</code> jika perlu mengubah user/password database

4. **Buat folder uploads (jika belum ada)**
   ```bash
   mkdir uploads
   chmod 777 uploads
   ```

5. **Jalankan aplikasi**
   - Akses di browser: <code>http://localhost/sidama-crud/login.php</code>


## 🔑 Login Default

| Role   | Username | Password  |
|--------|----------|-----------|
| Admin  | admin    | password  |
| Viewer | viewer   | password  |


## 📁 Struktur Folder

<details>
<summary>Klik untuk melihat struktur lengkap</summary>

```text
sidama-crud/
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
```
</details>


## 👥 Hak Akses Role

| Role   | CRUD | Import/Export | Menu Lengkap |
|--------|------|---------------|--------------|
| Admin  | ✅   | ✅            | ✅           |
| Viewer | ❌   | ❌            | ❌           |

<details>
<summary>Penjelasan</summary>

- <b>Admin</b>: Akses penuh ke seluruh fitur aplikasi
- <b>Viewer</b>: Hanya dapat melihat data, tidak bisa menambah/edit/hapus/import/export
</details>


## 📥 Format Import CSV

```csv
NIM,Nama,Email,Tanggal Lahir,Jenis Kelamin,Alamat
2021004,John Doe,john@email.com,2003-01-15,L,Jl. Example No. 1
```



## 🖼️ Screenshot

<p align="center">
   <img src="uploads/ss-an.png.png" alt="Tampilan Dashboard SIDAMA" width="800">
   <br>
   <i>Tampilan Dashboard SIDAMA - Sistem Informasi Data Mahasiswa</i>
</p>


## 📄 Lisensi

MIT License — bebas digunakan & dikembangkan.


## ☎️ Kontak & Dukungan

Butuh bantuan, saran, atau ingin berkontribusi?

- GitHub: [slmnalfauzi](https://github.com/slmnalfauzi)

---

<p align="center"><b>⭐ Star & Fork repo ini jika bermanfaat!</b></p>
