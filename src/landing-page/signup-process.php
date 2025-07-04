<?php
session_start();
include(__DIR__ . '/../function.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $nama_user = trim($_POST['nama_user'] ?? '');

    // Validasi awal
    if (empty($email) || empty($password) || empty($confirmPassword) || empty($nama_user)) {
        $_SESSION['error'] = "Semua field harus diisi.";
        header("Location: signup-login.php#signup");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Format email tidak valid.";
        header("Location: signup-login.php#signup");
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Password dan konfirmasi tidak cocok.";
        header("Location: signup-login.php#signup");
        exit();
    }

    // Cek apakah email sudah digunakan
    $stmtEmail = $conn->prepare("SELECT id_user FROM user_input WHERE email = ?");
    $stmtEmail->bind_param("s", $email);
    $stmtEmail->execute();
    $stmtEmail->store_result();
    if ($stmtEmail->num_rows > 0) {
        $_SESSION['error'] = "Email sudah digunakan.";
        $stmtEmail->close();
        header("Location: signup-login.php#signup");
        exit();
    }
    $stmtEmail->close();

    // Cek apakah username (nama_user) sudah digunakan
    $stmtUser = $conn->prepare("SELECT id_user FROM user_input WHERE nama_user = ?");
    $stmtUser->bind_param("s", $nama_user);
    $stmtUser->execute();
    $stmtUser->store_result();
    if ($stmtUser->num_rows > 0) {
        $_SESSION['error'] = "Username sudah digunakan.";
        $stmtUser->close();
        header("Location: signup-login.php#signup");
        exit();
    }
    $stmtUser->close();

    // Hash dan simpan
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $timestamp_input = date('Y-m-d H:i:s');

    $stmtInsert = $conn->prepare("INSERT INTO user_input (email, password, nama_user, timestamp_input) VALUES (?, ?, ?, ?)");
    $stmtInsert->bind_param("ssss", $email, $passwordHash, $nama_user, $timestamp_input);

    if ($stmtInsert->execute()) {
        $_SESSION['success'] = "Pendaftaran berhasil! Silakan login.";
        header("Location: signup-login.php#login");
        exit();
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat menyimpan data: " . $stmtInsert->error;
        header("Location: signup-login.php#signup");
        exit();
    }
} else {
    $_SESSION['error'] = "Metode tidak valid.";
    header("Location: signup-login.php");
    exit();
}
?>