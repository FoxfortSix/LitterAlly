<?php
include '../config.php';

if (isset($_GET['id_multivalue'])) {
  $id_multivalue = (int) $_GET['id_multivalue'];

  // Cari id_transaksi terkait
  $queryTransaksi = "SELECT id_transaksi FROM transaksi_sampah_jenis WHERE id_multivalue = ? LIMIT 1";
  $stmt = $conn->prepare($queryTransaksi);
  $stmt->bind_param('i', $id_multivalue);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $id_transaksi = $row['id_transaksi'];

    // Hapus relasi di transaksi_sampah_jenis
    $deleteRelasi = $conn->prepare("DELETE FROM transaksi_sampah_jenis WHERE id_multivalue = ?");
    $deleteRelasi->bind_param('i', $id_multivalue);
    $deleteRelasi->execute();

    // Hapus data jenis_sampah_multivalue
    $deleteMultivalue = $conn->prepare("DELETE FROM jenis_sampah_multivalue WHERE id_multivalue = ?");
    $deleteMultivalue->bind_param('i', $id_multivalue);
    $deleteMultivalue->execute();

    // Cek sisa multivalue pada transaksi tersebut
    $checkRemaining = $conn->prepare("SELECT COUNT(*) as total FROM transaksi_sampah_jenis WHERE id_transaksi = ?");
    $checkRemaining->bind_param('i', $id_transaksi);
    $checkRemaining->execute();
    $checkResult = $checkRemaining->get_result();
    $countRow = $checkResult->fetch_assoc();

    if ($countRow['total'] == 0) {
      // Hapus transaksi utama
      $deleteTransaksi = $conn->prepare("DELETE FROM transaksi_sampah WHERE id_transaksi = ?");
      $deleteTransaksi->bind_param('i', $id_transaksi);
      $deleteTransaksi->execute();
    }

    header("Location: dashboard.php?hapus=success");
    exit;
  } else {
    // Kalau relasi tidak ketemu
    header("Location: dashboard.php?hapus=notfound");
    exit;
  }
}

// Jika parameter tidak ada
header("Location: dashboard.php?hapus=failed");
exit;
