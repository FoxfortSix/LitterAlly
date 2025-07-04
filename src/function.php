<?php

include('config.php');

function getSampahData()
{
  global $conn;

  $query = "
        SELECT 
            DATE(ts.timestamp) AS tanggal,
            js.jenis_sampah,
            SUM(jsm.jumlah) AS total_jumlah
        FROM transaksi_sampah ts
        JOIN transaksi_sampah_jenis tsj ON ts.id_transaksi = tsj.id_transaksi
        JOIN jenis_sampah_multivalue jsm ON tsj.id_multivalue = jsm.id_multivalue
        JOIN jenis_sampah js ON jsm.id_jenis = js.id_jenis
        GROUP BY tanggal, js.jenis_sampah
        ORDER BY tanggal ASC
    ";

  $result = mysqli_query($conn, $query);

  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  return $data;
}

function loginUser($email, $password)
{
  global $conn;
  $email = mysqli_real_escape_string($conn, $email);

  $query = "SELECT * FROM user_input WHERE email = '$email'";
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    $storedPassword = $user['password'];

    // Deteksi apakah password tersimpan adalah hash
    if (preg_match('/^\$2[ayb]\$/', $storedPassword)) {
      // Password di database sudah di-hash
      if (password_verify($password, $storedPassword)) {
        return $user;
      }
    } else {
      // Password masih plain text
      if ($password === $storedPassword) {
        // Migrasi ke hash (opsional tapi direkomendasikan)
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE user_input SET password = ? WHERE id_user = ?");
        $stmt->bind_param("si", $newHash, $user['id_user']);
        $stmt->execute();
        $stmt->close();

        return $user;
      }
    }
  }

  return false;
}

function getSampahDataByUser($id_user)
{
  global $conn;

  $query = "
        SELECT 
            DATE(ts.timestamp) AS tanggal,
            js.jenis_sampah,
            SUM(jsm.jumlah) AS total_jumlah
        FROM transaksi_sampah ts
        JOIN transaksi_sampah_jenis tsj ON ts.id_transaksi = tsj.id_transaksi
        JOIN jenis_sampah_multivalue jsm ON tsj.id_multivalue = jsm.id_multivalue
        JOIN jenis_sampah js ON jsm.id_jenis = js.id_jenis
        WHERE ts.id_user = ?
        GROUP BY tanggal, js.jenis_sampah
        ORDER BY tanggal ASC
    ";

  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 'i', $id_user);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  return $data;
}

function getTabelSampahByUser($id_user)
{
  global $conn;

  $query = "
        SELECT 
            jsm.id_multivalue,                 -- ambil ID untuk edit & hapus
            ts.timestamp,
            ts.id_transaksi,
            js.jenis_sampah,
            jsm.nama_subjenis,
            jsm.jumlah,
            s.nama_satuan,
            jsm.keterangan
        FROM transaksi_sampah ts
        JOIN transaksi_sampah_jenis tsj ON ts.id_transaksi = tsj.id_transaksi
        JOIN jenis_sampah_multivalue jsm ON tsj.id_multivalue = jsm.id_multivalue
        JOIN jenis_sampah js ON jsm.id_jenis = js.id_jenis
        JOIN satuan s ON jsm.id_satuan = s.id_satuan
        WHERE ts.id_user = ?
        ORDER BY ts.timestamp DESC
    ";

  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 'i', $id_user);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  return $data;
}



function getSatuanOptions()
{
  global $conn;
  $sql = "SELECT id_satuan, nama_satuan FROM satuan ORDER BY id_satuan ASC";
  $result = mysqli_query($conn, $sql);
  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }
  return $data;
}

function simpanTransaksiUtama($id_user)
{
  global $conn;

  $stmt = $conn->prepare("
        INSERT INTO transaksi_sampah (timestamp, id_user)
        VALUES (NOW(), ?)
    ");
  $stmt->bind_param("i", $id_user);
  $stmt->execute();

  return $conn->insert_id;
}

function simpanRelasiMultivalue($id_transaksi, $id_multivalue)
{
  global $conn;
  $stmt = mysqli_prepare($conn, "
        INSERT INTO transaksi_sampah_jenis (id_transaksi, id_multivalue)
        VALUES (?, ?)
    ");
  mysqli_stmt_bind_param($stmt, 'ii', $id_transaksi, $id_multivalue);
  return mysqli_stmt_execute($stmt);
}

function simpanSubjenis($id_jenis, $nama_subjenis, $id_satuan, $jumlah, $keterangan)
{
  global $conn;
  $stmt = mysqli_prepare($conn, "
        INSERT INTO jenis_sampah_multivalue (id_jenis, nama_subjenis, id_satuan, jumlah, keterangan)
        VALUES (?, ?, ?, ?, ?)
    ");
  mysqli_stmt_bind_param($stmt, 'isiis', $id_jenis, $nama_subjenis, $id_satuan, $jumlah, $keterangan);
  mysqli_stmt_execute($stmt);
  return mysqli_insert_id($conn);
}

function getDashboardCounts($conn)
{
  // Total Klien
  $total_klien = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM user_input")
  )['total'];

  // Ambil semua ID jenis berdasarkan nama
  $jenis = ['Organik', 'Anorganik', 'B3'];
  $counts = [];

  foreach ($jenis as $nama) {
    $query_id = mysqli_query($conn, "SELECT id_jenis FROM jenis_sampah WHERE jenis_sampah = '$nama'");
    $row = mysqli_fetch_assoc($query_id);
    $id_jenis = $row ? $row['id_jenis'] : null;

    if ($id_jenis) {
      $total = mysqli_fetch_assoc(
        mysqli_query($conn, "SELECT COUNT(*) AS total FROM jenis_sampah_multivalue WHERE id_jenis = '$id_jenis'")
      )['total'];
    } else {
      $total = 0;
    }

    $counts[strtolower($nama)] = $total;
  }

  return [
    'total_klien' => $total_klien,
    'organik' => $counts['organik'],
    'anorganik' => $counts['anorganik'],
    'b3' => $counts['b3']
  ];
}

function getPaginatedData($conn, $page = 1, $perPage = 10)
{
  $offset = ($page - 1) * $perPage;

  $query = "SELECT 
                ts.id_transaksi AS id,
                jsmv.id_multivalue,
                jsmv.nama_subjenis AS nama_sampah,
                jsmv.jumlah AS jumlah,
                s.nama_satuan AS satuan,
                js.jenis_sampah AS jenis_sampah,
                jsmv.keterangan AS keterangan
                FROM transaksi_sampah ts
                JOIN transaksi_sampah_jenis tsj ON ts.id_transaksi = tsj.id_transaksi
                JOIN jenis_sampah_multivalue jsmv ON tsj.id_multivalue = jsmv.id_multivalue
                LEFT JOIN satuan s ON jsmv.id_satuan = s.id_satuan
                LEFT JOIN jenis_sampah js ON jsmv.id_jenis = js.id_jenis
                ORDER BY ts.id_transaksi ASC
                LIMIT $offset, $perPage";

  $result = mysqli_query($conn, $query);
  $data = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  // Hitung total data (berdasarkan transaksi sampah)
  $countQuery = "SELECT COUNT(*) AS total FROM transaksi_sampah";
  $countResult = mysqli_query($conn, $countQuery);
  $totalData = mysqli_fetch_assoc($countResult)['total'];
  $totalPages = ceil($totalData / $perPage);

  return [
    'data' => $data,
    'totalPages' => $totalPages,
    'currentPage' => $page,
    'totalData' => $totalData
  ];
}

function getSampahDataByKelas($kelas = null)
{
  global $conn;

  $sql = "
        SELECT 
            DATE(ts.timestamp) as tanggal,
            js.jenis_sampah,
            SUM(jsm.jumlah) as total_jumlah
        FROM transaksi_sampah ts
        JOIN transaksi_sampah_jenis tsj ON ts.id_transaksi = tsj.id_transaksi
        JOIN jenis_sampah_multivalue jsm ON tsj.id_multivalue = jsm.id_multivalue
        JOIN jenis_sampah js ON jsm.id_jenis = js.id_jenis
        JOIN user_input u ON ts.id_user = u.id_user
        JOIN kelas k ON u.id_kelas = k.id_kelas
    ";

  if ($kelas && in_array(strtoupper($kelas), ['C1', 'C2'])) {
    $kelas = mysqli_real_escape_string($conn, $kelas);
    $sql .= " WHERE k.kelas = '$kelas' ";
  }

  $sql .= "
        GROUP BY tanggal, js.jenis_sampah
        ORDER BY tanggal ASC
    ";

  $result = mysqli_query($conn, $sql);
  $data = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  return $data;
}

function getSampahDataByUser2($id_user, $jenis_sampah = [])
{
  global $conn;

  $id_user = (int)$id_user;

  $jenis_condition = "";
  if (!empty($jenis_sampah)) {
    $jenis_list = array_map(function ($jenis) use ($conn) {
      return "'" . mysqli_real_escape_string($conn, $jenis) . "'";
    }, $jenis_sampah);
    $jenis_condition = "AND js.jenis_sampah IN (" . implode(',', $jenis_list) . ")";
  }

  $sql = "
        SELECT 
            DATE(ts.timestamp) as tanggal,
            js.jenis_sampah,
            SUM(jsm.jumlah) as total_jumlah
        FROM transaksi_sampah ts
        JOIN transaksi_sampah_jenis tsj ON ts.id_transaksi = tsj.id_transaksi
        JOIN jenis_sampah_multivalue jsm ON tsj.id_multivalue = jsm.id_multivalue
        JOIN jenis_sampah js ON jsm.id_jenis = js.id_jenis
        WHERE ts.id_user = $id_user
        $jenis_condition
        GROUP BY tanggal, js.jenis_sampah
        ORDER BY tanggal ASC
    ";

  $result = mysqli_query($conn, $sql);
  $data = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  return $data;
}

function getUsersByClass($kelas)
{
  global $conn;
  $kelas = mysqli_real_escape_string($conn, $kelas);
  $sql = "SELECT u.id_user, u.nama_user 
            FROM user_input u
            JOIN kelas k ON u.id_kelas = k.id_kelas
            WHERE k.kelas = '$kelas'
            ORDER BY u.nama_user ASC";

  $result = mysqli_query($conn, $sql);
  $users = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
  }

  return $users;
}

function getPaginatedDataByKelas($kelas = null, $page = 1, $perPage = 10)
{
  global $conn;
  $offset = ($page - 1) * $perPage;

  $query = "SELECT 
                jsmv.id_multivalue,
                ts.id_transaksi,
                ts.id_transaksi AS id,
                jsmv.nama_subjenis AS nama_sampah,
                jsmv.jumlah AS jumlah,
                s.nama_satuan AS satuan,
                js.jenis_sampah AS jenis_sampah,
                jsmv.keterangan AS keterangan
              FROM transaksi_sampah ts
              JOIN transaksi_sampah_jenis tsj ON ts.id_transaksi = tsj.id_transaksi
              JOIN jenis_sampah_multivalue jsmv ON tsj.id_multivalue = jsmv.id_multivalue
              LEFT JOIN satuan s ON jsmv.id_satuan = s.id_satuan
              LEFT JOIN jenis_sampah js ON jsmv.id_jenis = js.id_jenis
              JOIN user_input u ON ts.id_user = u.id_user
              JOIN kelas k ON u.id_kelas = k.id_kelas";

  if ($kelas && $kelas !== 'seluruh') {
    $kelas = mysqli_real_escape_string($conn, $kelas);
    $query .= " WHERE k.kelas = '$kelas'";
  }

  $query .= " ORDER BY ts.id_transaksi ASC LIMIT $offset, $perPage";

  $result = mysqli_query($conn, $query);
  $data = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  $countQuery = "SELECT COUNT(DISTINCT ts.id_transaksi) AS total
                   FROM transaksi_sampah ts
                   JOIN user_input u ON ts.id_user = u.id_user
                   JOIN kelas k ON u.id_kelas = k.id_kelas";

  if ($kelas && $kelas !== 'seluruh') {
    $countQuery .= " WHERE k.kelas = '$kelas'";
  }

  $countResult = mysqli_query($conn, $countQuery);
  $totalData = mysqli_fetch_assoc($countResult)['total'];
  $totalPages = ceil($totalData / $perPage);

  return [
    'data' => $data,
    'totalPages' => $totalPages,
    'currentPage' => $page,
    'totalData' => $totalData
  ];
}

function getPaginatedDataByUser($id_user, $page = 1, $perPage = 10)
{
  global $conn;
  $offset = ($page - 1) * $perPage;

  $query = "SELECT 
                jsmv.id_multivalue,
                ts.id_transaksi,
                ts.id_transaksi AS id,
                jsmv.nama_subjenis AS nama_sampah,
                jsmv.jumlah AS jumlah,
                s.nama_satuan AS satuan,
                js.jenis_sampah AS jenis_sampah,
                jsmv.keterangan AS keterangan
              FROM transaksi_sampah ts
              JOIN transaksi_sampah_jenis tsj ON ts.id_transaksi = tsj.id_transaksi
              JOIN jenis_sampah_multivalue jsmv ON tsj.id_multivalue = jsmv.id_multivalue
              LEFT JOIN satuan s ON jsmv.id_satuan = s.id_satuan
              LEFT JOIN jenis_sampah js ON jsmv.id_jenis = js.id_jenis
              WHERE ts.id_user = $id_user
              ORDER BY ts.id_transaksi ASC
              LIMIT $offset, $perPage";

  $result = mysqli_query($conn, $query);
  $data = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  $countQuery = "SELECT COUNT(DISTINCT ts.id_transaksi) AS total
                   FROM transaksi_sampah ts
                   WHERE ts.id_user = $id_user";

  $countResult = mysqli_query($conn, $countQuery);
  $totalData = mysqli_fetch_assoc($countResult)['total'];
  $totalPages = ceil($totalData / $perPage);

  return [
    'data' => $data,
    'totalPages' => $totalPages,
    'currentPage' => $page,
    'totalData' => $totalData
  ];
}

function getTotalKlien()
{
  global $conn;
  $sql = "SELECT COUNT(*) AS total FROM user_input";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  return $row['total'];
}

function getTotalRecordByJenis($jenis_sampah)
{
  global $conn;
  $jenis_sampah = mysqli_real_escape_string($conn, $jenis_sampah);

  $sql = "SELECT COUNT(DISTINCT tsj.id_multivalue) AS total
            FROM transaksi_sampah_jenis tsj
            JOIN jenis_sampah_multivalue jsm ON tsj.id_multivalue = jsm.id_multivalue
            JOIN jenis_sampah js ON jsm.id_jenis = js.id_jenis
            WHERE js.jenis_sampah = '$jenis_sampah'";

  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  return $row['total'];
}

function hapusMultivalueBersertaRelasi($id_multivalue)
{
  global $conn;

  // Cari id_transaksi dari id_multivalue terkait
  $query = "SELECT id_transaksi FROM transaksi_sampah_jenis WHERE id_multivalue = $id_multivalue";
  $result = mysqli_query($conn, $query);
  if (!$result || mysqli_num_rows($result) == 0) {
    return false; // tidak ditemukan
  }
  $row = mysqli_fetch_assoc($result);
  $id_transaksi = $row['id_transaksi'];

  // Hapus record di transaksi_sampah_jenis dengan id_multivalue
  $hapus1 = mysqli_query($conn, "DELETE FROM transaksi_sampah_jenis WHERE id_multivalue = $id_multivalue");

  // Hapus record di jenis_sampah_multivalue dengan id_multivalue
  $hapus2 = mysqli_query($conn, "DELETE FROM jenis_sampah_multivalue WHERE id_multivalue = $id_multivalue");

  if (!$hapus1 || !$hapus2) {
    return false; // gagal hapus
  }

  // Cek sisa record di transaksi_sampah_jenis dengan id_transaksi yang sama
  $cekSisa = mysqli_query($conn, "SELECT COUNT(*) as sisa FROM transaksi_sampah_jenis WHERE id_transaksi = $id_transaksi");
  $rowSisa = mysqli_fetch_assoc($cekSisa);
  if ($rowSisa['sisa'] == 0) {
    // Kalau sudah tidak ada, hapus juga transaksi_sampah
    mysqli_query($conn, "DELETE FROM transaksi_sampah WHERE id_transaksi = $id_transaksi");
  }

  return true;
}
