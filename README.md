<!-- @format -->

# Proyek CRUD PHP - RateItUp

Aplikasi web sederhana berbasis PHP dan MySQL untuk manajemen data dengan fungsionalitas Create, Read, Update, dan Delete (CRUD).

---

### Teknologi

- **Backend**: PHP (Native)
- **Database**: MySQL / MariaDB
- **Frontend**: HTML & CSS

### Prasyarat

- Web Server (XAMPP, WAMP, atau sejenisnya)
- PHP 7.4+
- MySQL atau MariaDB

---

## Panduan Instalasi

### 1. Instalasi Lokal

**a. Siapkan Proyek & Database**

1.  Clone repository ini ke komputer Anda.
2.  Pindahkan folder proyek `rateitup` ke dalam direktori `htdocs` Anda.
3.  Buka phpMyAdmin, buat database baru dengan nama `rateitup_db`.
4.  Pilih database `rateitup_db`, klik tab **Import**, dan unggah file `database/rateitup.sql`.

**b. Konfigurasi Koneksi**

1.  Di dalam folder proyek, salin file `database.example.php` dan ubah nama salinannya menjadi `database.php`.
2.  Buka `database.php` dan sesuaikan isinya dengan detail koneksi lokal Anda.

    ```php
    <?php
    // Contoh Konfigurasi untuk Localhost
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db   = 'rateitup_db';

    $koneksi = mysqli_connect($host, $user, $pass, $db);
    if (!$koneksi) {
        die("Koneksi Gagal: " . mysqli_connect_error());
    }
    ?>
    ```

**c. Jalankan Aplikasi**

- Buka browser dan akses: `http://localhost/rateitup/`

---

### 2. Instalasi di Hosting

**a. Unggah File & Database**

1.  Unggah seluruh isi folder `rateitup` ke direktori `public_html` di hosting Anda.
2.  Buat database dan user database baru melalui cPanel.
3.  Impor file `database/rateitup.sql` ke database yang baru dibuat.

**b. Konfigurasi Koneksi**

1.  Di server hosting, buat file `database.php`.
2.  Isi file tersebut dengan kredensial dari hosting Anda.

    ```php
    <?php
    // Contoh Konfigurasi untuk Hosting
    $host = 'localhost'; // Biasanya 'localhost'
    $user = 'user_database_hosting';
    $pass = 'password_database_hosting';
    $db   = 'nama_database_hosting';

    $koneksi = mysqli_connect($host, $user, $pass, $db);
    if (!$koneksi) {
        die("Koneksi Gagal: " . mysqli_connect_error());
    }
    ?>
    ```

**c. Selesai**

- Aplikasi kini dapat diakses melalui domain Anda.
