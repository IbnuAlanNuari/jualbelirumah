<?php
// db.php - Untuk koneksi ke database
$host = 'localhost';  // Ganti dengan host database, biasanya 'localhost'
$username = 'u772674178_properties';   // Ganti dengan username database
$password = 'Propertiesalan123';       // Ganti dengan password database (kosong untuk XAMPP)
$dbname = 'u772674178_properties'; // Nama database yang digunakan

// Membuat koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>