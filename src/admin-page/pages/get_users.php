<?php
require_once '../../function.php';
header('Content-Type: application/json');

$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : null;

if ($kelas) {
    $users = getUsersByClass($kelas);
    echo json_encode($users);
} else {
    echo json_encode([]);
}