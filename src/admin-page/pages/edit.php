<?php
require_once __DIR__ . '/../../config.php';

// Fungsi bantu untuk ambil dan bersihkan parameter GET / POST
function safe_param($source, $key) {
    return isset($source[$key]) && trim($source[$key]) !== '' ? trim($source[$key]) : null;
}

// Ambil parameter GET dengan validasi trim kosong
$id_multivalue = isset($_GET['id_multivalue']) ? (int)$_GET['id_multivalue'] : null;
$id_transaksi = safe_param($_GET, 'id_transaksi');
$kelas = safe_param($_GET, 'kelas');
$id_user = safe_param($_GET, 'id_user');
$data_kelas = safe_param($_GET, 'data_kelas');
$page = safe_param($_GET, 'page');

if (!$id_multivalue) {
    header("Location: tabel_result.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM jenis_sampah_multivalue WHERE id_multivalue = ?");
$stmt->bind_param("i", $id_multivalue);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "Data tidak ditemukan.";
    exit();
}

$satuan = $conn->query("SELECT * FROM satuan");
$jenis = $conn->query("SELECT * FROM jenis_sampah");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil parameter POST dengan validasi
    $id_multivalue = (int) $_POST['id_multivalue'];
    $id_transaksi = safe_param($_POST, 'id_transaksi');
    $kelas = safe_param($_POST, 'kelas');
    $id_user = safe_param($_POST, 'id_user');
    $data_kelas = safe_param($_POST, 'data_kelas');
    $page = safe_param($_POST, 'page');

    $nama_subjenis = $_POST['nama_subjenis'];
    $jumlah = (float) $_POST['jumlah'];
    $id_satuan = (int) $_POST['id_satuan'];
    $id_jenis = (int) $_POST['id_jenis'];
    $keterangan = $_POST['keterangan'];

    $update = $conn->prepare("UPDATE jenis_sampah_multivalue SET nama_subjenis=?, jumlah=?, id_satuan=?, id_jenis=?, keterangan=? WHERE id_multivalue=?");
    $update->bind_param("siiisi", $nama_subjenis, $jumlah, $id_satuan, $id_jenis, $keterangan, $id_multivalue);
    $success = $update->execute();

    if (!empty($kelas) && !empty($id_user)) {
        header("Location: tabel_result.php?kelas=" . urlencode($kelas) . "&id_user=" . urlencode($id_user));
    } elseif (!empty($data_kelas)) {
        $url = "tabel_result.php?data_kelas=" . urlencode($data_kelas);
        if (!empty($page)) {
            $url .= "&page=" . urlencode($page);
        }
        header("Location: $url");
    } else {
        header("Location: tabel_result.php");
    }
    exit();
}
?>

<!-- HTML mulai -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Data Sampah</title>
  <link rel="stylesheet" href="../../output.css" />
  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
  <script src="../assets/js/init-alpine.js"></script>
</head>
<body>
  <div class="flex h-screen bg-gray-50 dark:bg-gray-900" x-data="{ isSideMenuOpen: false }" :class="{ 'overflow-hidden': isSideMenuOpen }">
    <div class="flex flex-col flex-1 w-full">
      <header class="z-10 py-4 bg-white shadow-md dark:bg-gray-800">
        <div class="container flex items-center justify-between h-full px-6 mx-auto text-purple-600 dark:text-purple-300">
          <div class="flex justify-start flex-1 lg:mr-3">
            <a href="javascript:history.back()" class="transition duration-300 hover:text-white">Kembali ke laman sebelumnya</a>
          </div>
        </div>
      </header>

      <main class="h-full pb-16 overflow-y-auto">
        <div class="container grid px-6 mx-auto">
          <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">Formulir Edit Sampah</h2>

          <form action="" method="POST" class="w-full max-w-4xl px-4 py-3 mx-auto mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <!-- Hidden inputs -->
            <input type="hidden" name="id_multivalue" value="<?= htmlspecialchars($id_multivalue) ?>">
            <input type="hidden" name="id_transaksi" value="<?= htmlspecialchars($id_transaksi) ?>">
            <input type="hidden" name="kelas" value="<?= htmlspecialchars($kelas) ?>">
            <input type="hidden" name="id_user" value="<?= htmlspecialchars($id_user) ?>">
            <input type="hidden" name="data_kelas" value="<?= htmlspecialchars($data_kelas) ?>">
            <input type="hidden" name="page" value="<?= htmlspecialchars($page) ?>">

            <!-- Nama Sampah -->
            <label class="block text-sm mt-2">
              <span class="text-gray-700 dark:text-gray-400">Nama Sampah</span>
              <input
                type="text"
                name="nama_subjenis"
                value="<?= htmlspecialchars($data['nama_subjenis']) ?>"
                required
                class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 form-input
                      focus:border-purple-400 focus:outline-none focus:shadow-outline-purple
                      dark:text-gray-300 dark:focus:shadow-outline-gray"
              />
            </label>

            <!-- Jumlah -->
            <label class="block mt-4 text-sm">
              <span class="text-gray-700 dark:text-gray-400">Jumlah</span>
              <input
                type="number"
                name="jumlah"
                step="any"
                min="0"
                value="<?= htmlspecialchars($data['jumlah']) ?>"
                required
                class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 form-input
                      focus:border-purple-400 focus:outline-none focus:shadow-outline-purple
                      dark:text-gray-300 dark:focus:shadow-outline-gray"
              />
            </label>

            <!-- Satuan -->
            <label class="block mt-4 text-sm">
              <span class="text-gray-700 dark:text-gray-400">Satuan</span>
              <select
                name="id_satuan"
                required
                class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600
                      dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none
                      focus:shadow-outline-purple dark:focus:shadow-outline-gray"
              >
                <option value="">-- Pilih Satuan --</option>
                <?php while ($s = $satuan->fetch_assoc()): ?>
                  <option value="<?= $s['id_satuan'] ?>" <?= $s['id_satuan'] == $data['id_satuan'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['nama_satuan']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </label>

            <!-- Jenis Sampah -->
            <label class="block mt-4 text-sm">
              <span class="text-gray-700 dark:text-gray-400">Jenis Sampah</span>
              <select
                name="id_jenis"
                required
                class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600
                      dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none
                      focus:shadow-outline-purple dark:focus:shadow-outline-gray"
              >
                <option value="">-- Pilih Jenis Sampah --</option>
                <?php while ($j = $jenis->fetch_assoc()): ?>
                  <option value="<?= $j['id_jenis'] ?>" <?= $j['id_jenis'] == $data['id_jenis'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($j['jenis_sampah']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </label>

            <!-- Keterangan -->
            <label class="block mt-4 text-sm">
              <span class="text-gray-700 dark:text-gray-400">Keterangan</span>
              <textarea
                name="keterangan"
                rows="3"
                class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600
                      dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none
                      focus:shadow-outline-purple dark:focus:shadow-outline-gray"
              ><?= htmlspecialchars($data['keterangan']) ?></textarea>
            </label>

            <!-- Submit -->
            <div class="flex justify-center mt-6 text-sm">
              <button
                type="submit"
                class="w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors
                      duration-150 bg-purple-600 border-transparent rounded-md max-w-3xs
                      active:bg-purple-600 hover:bg-purple-700 focus:outline-none
                      focus:shadow-outline-purple dark:focus:shadow-outline-gray"
              >
                Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>
</body>
</html>