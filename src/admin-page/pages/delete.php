<?php
require_once __DIR__ . '/../../config.php';

$id_multivalue = isset($_GET['id_multivalue']) ? (int)$_GET['id_multivalue'] : 0;
$id_transaksi = isset($_GET['id_transaksi']) ? (int)$_GET['id_transaksi'] : 0;

if (!$id_multivalue || !$id_transaksi) {
    // Jika parameter kurang lengkap, kembali ke tabel
    header("Location: tabel_result.php");
    exit();
}

// Mulai transaksi supaya konsisten
$conn->begin_transaction();

try {
    // Hapus dari transaksi_sampah_jenis berdasarkan id_multivalue
    $stmt1 = $conn->prepare("DELETE FROM transaksi_sampah_jenis WHERE id_multivalue = ?");
    $stmt1->bind_param("i", $id_multivalue);
    $stmt1->execute();
    $stmt1->close();

    // Hapus dari jenis_sampah_multivalue berdasarkan id_multivalue
    $stmt2 = $conn->prepare("DELETE FROM jenis_sampah_multivalue WHERE id_multivalue = ?");
    $stmt2->bind_param("i", $id_multivalue);
    $stmt2->execute();
    $stmt2->close();

    // Cek apakah masih ada id_multivalue terkait id_transaksi
    $stmt3 = $conn->prepare("SELECT COUNT(*) as count FROM transaksi_sampah_jenis WHERE id_transaksi = ?");
    $stmt3->bind_param("i", $id_transaksi);
    $stmt3->execute();
    $res = $stmt3->get_result();
    $row = $res->fetch_assoc();
    $stmt3->close();

    if ($row['count'] == 0) {
        // Jika tidak ada data, hapus transaksi_sampah
        $stmt4 = $conn->prepare("DELETE FROM transaksi_sampah WHERE id_transaksi = ?");
        $stmt4->bind_param("i", $id_transaksi);
        $stmt4->execute();
        $stmt4->close();
    }

    $conn->commit();

    // Redirect kembali ke halaman utama atau halaman list sesuai kebutuhan
    header("Location: tabel_result.php");
    exit();
} catch (Exception $e) {
    $conn->rollback();
    echo "Terjadi kesalahan saat menghapus data: " . $e->getMessage();
}