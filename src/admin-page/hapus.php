<?php
include '../config.php';

if (isset($_GET['id_multivalue'])) {
    $id_multivalue = (int) $_GET['id_multivalue'];

    // Cari id_transaksi yang berkaitan dengan id_multivalue ini
    $queryTransaksi = "
        SELECT tsj.id_transaksi 
        FROM transaksi_sampah_jenis tsj
        WHERE tsj.id_multivalue = ?
        LIMIT 1
    ";
    $stmt = mysqli_prepare($conn, $queryTransaksi);
    mysqli_stmt_bind_param($stmt, 'i', $id_multivalue);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $id_transaksi = $row['id_transaksi'];

        // Hapus relasi di transaksi_sampah_jenis
        $deleteRelasi = "DELETE FROM transaksi_sampah_jenis WHERE id_multivalue = ?";
        $stmtRelasi = mysqli_prepare($conn, $deleteRelasi);
        mysqli_stmt_bind_param($stmtRelasi, 'i', $id_multivalue);
        mysqli_stmt_execute($stmtRelasi);

        // Hapus data dari jenis_sampah_multivalue
        $deleteMultivalue = "DELETE FROM jenis_sampah_multivalue WHERE id_multivalue = ?";
        $stmtMultivalue = mysqli_prepare($conn, $deleteMultivalue);
        mysqli_stmt_bind_param($stmtMultivalue, 'i', $id_multivalue);
        mysqli_stmt_execute($stmtMultivalue);

        // Cek apakah masih ada id_multivalue lain yang terkait dengan id_transaksi
        $checkRemaining = "
            SELECT COUNT(*) as total 
            FROM transaksi_sampah_jenis 
            WHERE id_transaksi = ?
        ";
        $stmtCheck = mysqli_prepare($conn, $checkRemaining);
        mysqli_stmt_bind_param($stmtCheck, 'i', $id_transaksi);
        mysqli_stmt_execute($stmtCheck);
        $checkResult = mysqli_stmt_get_result($stmtCheck);
        $countRow = mysqli_fetch_assoc($checkResult);

        if ($countRow['total'] == 0) {
            // Tidak ada lagi multivalue terkait, hapus transaksi utama
            $deleteTransaksi = "DELETE FROM transaksi_sampah WHERE id_transaksi = ?";
            $stmtDeleteTransaksi = mysqli_prepare($conn, $deleteTransaksi);
            mysqli_stmt_bind_param($stmtDeleteTransaksi, 'i', $id_transaksi);
            mysqli_stmt_execute($stmtDeleteTransaksi);
        }

        // Redirect kembali ke halaman utama
        header("Location: index.php?hapus=success");
        exit;
    }
}

header("Location: index.php?hapus=failed");
exit;
