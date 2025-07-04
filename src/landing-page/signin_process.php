<?php
session_start();
include(__DIR__ . '/../function.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = loginUser($email, $password);

    if ($user) {
        // Simpan informasi user ke session
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['user_name'] = $user['nama_user']; 
        $_SESSION['user_email'] = $user['email'];

        // Redirect ke halaman dashboard
        header("Location: dashboard.php");
        exit;
    } else {
        // Login gagal, kembali ke halaman login dengan pesan
        $_SESSION['error'] = "Email atau password salah.";
        header("Location: signup-login.php");
        exit;
    }
} else {
    // Akses tidak sah
    header("Location: signup-login.php");
    exit;
}