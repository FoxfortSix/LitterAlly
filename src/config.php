<?php
$host     = "localhost";
$username = "root";
$password = "";
$database = "litterally";

// Membuat koneksi
$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
  die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set karakter encoding ke UTF-8
mysqli_set_charset($conn, "utf8");
