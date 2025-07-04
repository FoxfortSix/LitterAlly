<?php
session_start();
require_once __DIR__ . '/../function.php';

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo 'Unauthorized';
  exit;
}

$data = getTabelSampahByUser($_SESSION['user_id']);

header('Content-Type: application/json');
echo json_encode($data);
