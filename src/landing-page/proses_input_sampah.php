<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../function.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User belum login']);
    exit;
}

$id_user = $_SESSION['user_id'];
$jenis_sampah_map = [
    'organik' => 2,
    'anorganik' => 1,
    'b3' => 3
];

$jumlah_total = 0;
$keterangan_tergabung = [];
$id_satuan_utama = 0;
$multivalue_ids = [];

foreach ($jenis_sampah_map as $jenis => $id_jenis) {
    if (isset($_POST["checkbox_$jenis"])) {
        $nama = trim($_POST["nama_sampah_$jenis"] ?? '');
        $jumlah = floatval($_POST["jumlah_$jenis"] ?? 0);
        $id_satuan = intval($_POST["satuan_$jenis"] ?? 0);
        $keterangan = trim($_POST["keterangan_$jenis"] ?? '');

        if ($nama && $jumlah > 0 && $id_satuan) {
            $id_multivalue = simpanSubjenis($id_jenis, $nama, $id_satuan, $jumlah, $keterangan);
            $multivalue_ids[] = $id_multivalue;
            $jumlah_total += $jumlah;
            $keterangan_tergabung[] = $keterangan;
            if ($id_satuan_utama === 0) {
                $id_satuan_utama = $id_satuan;
            }
        }
    }
}

if ($jumlah_total > 0 && $id_satuan_utama && count($multivalue_ids) > 0) {
    $keterangan_final = implode(" | ", $keterangan_tergabung);
    $id_transaksi = simpanTransaksiUtama($id_user, $jumlah_total, $id_satuan_utama, $keterangan_final);

    if (!$id_transaksi) {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan transaksi utama.']);
        exit;
    }

    $relasi_ok = true;
    foreach ($multivalue_ids as $id_multi) {
        $relasi_ok = $relasi_ok && simpanRelasiMultivalue($id_transaksi, $id_multi);
    }

    if ($relasi_ok) {
        echo json_encode(['success' => true, 'message' => 'Berhasil menyimpan transaksi.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan relasi transaksi.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap atau tidak valid.']);
}