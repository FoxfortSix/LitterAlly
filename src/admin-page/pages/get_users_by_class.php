<?php
require_once '../../function.php';

$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$users = getUsersByClass($kelas);

header('Content-Type: application/json');
echo json_encode($users);