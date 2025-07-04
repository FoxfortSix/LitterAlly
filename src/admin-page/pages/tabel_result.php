<?php
require_once '../../function.php';

if (isset($_GET['id_user'])) {
    // Mode perorangan
    $id_user = (int)$_GET['id_user'];
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $per_page = 10;

    $result = getPaginatedDataByUser($id_user, $page, $per_page);

    $dataSampah = $result['data'];
    $totalPages = $result['totalPages'];
    $current_page = $result['currentPage'];
    $totalData = $result['totalData'];

    // Ambil nama user untuk ditampilkan di judul
    $userQuery = mysqli_query($conn, "SELECT nama_user FROM user_input WHERE id_user = $id_user");
    $userName = mysqli_fetch_assoc($userQuery)['nama_user'] ?? 'Unknown User';
} else {
    // Mode per kelas (seluruh data, c1, c2)
    $kelas = isset($_GET['data_kelas']) ? $_GET['data_kelas'] : 'seluruh';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $per_page = 10;

    $result = getPaginatedDataByKelas($kelas, $page, $per_page);

    $dataSampah = $result['data'];
    $totalPages = $result['totalPages'];
    $current_page = $result['currentPage'];
    $totalData = $result['totalData'];
}
?>

<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Charts - Windmill Dashboard</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../../output.css">
    <script
      src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
      defer
    ></script>
    <script src="../../assets/js/init-alpine.js"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css"
    />
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"
      defer
    ></script>
    <!-- <script src="../../assets/js/charts-lines.js" defer></script>
    <script src="../../assets/js/charts-pie.js" defer></script>
    <script src="../../assets/js/charts-bars.js" defer></script> -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <script src="./chart_data_sampah.js"></script> -->
  </head>
  <body>
    <div
      class="flex h-screen bg-gray-50 dark:bg-gray-900"
      :class="{ 'overflow-hidden': isSideMenuOpen}"
    >

    <div class="flex flex-col flex-1">
    <header class="z-10 py-4 bg-white shadow-md dark:bg-gray-800">
      <div
        class="container flex items-center justify-between h-full px-6 mx-auto text-purple-600 dark:text-purple-300 "
      >
        <!-- Search input -->
        <div class="flex justify-start flex-1 lg:mr-3">
          <a href="../pages/tabel_data_sampah.php" class="transition duration-300 hover:text-white">
            Kembali ke laman tabel data sampah
          </a>
        </div>
      </div>
    </header>

    <main class="h-full pb-16 overflow-y-auto">
      <div class="w-full max-w-6xl mx-auto mt-10 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
          <table class="w-full whitespace-no-wrap rounded-t-xl">
            <thead>
              <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-4 py-3">ID Transaksi</th>
                <th class="px-4 py-3">Nama Sampah</th>
                <th class="px-4 py-3">Jumlah</th>
                <th class="px-4 py-3">Satuan</th>
                <th class="px-4 py-3">Jenis Sampah</th>
                <th class="px-4 py-3">Keterangan</th>
                <th class="px-4 py-3">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <?php foreach ($dataSampah as $row): ?>
                <?php
                  // Gunakan id_multivalue dan id_transaksi yang valid dari $row
                  $id_multivalue = isset($row['id_multivalue']) ? $row['id_multivalue'] : '';
                  $id_transaksi = isset($row['id_transaksi']) ? $row['id_transaksi'] : '';
                ?>
                <tr class="text-gray-700 dark:text-gray-400">
                  <td class="px-4 py-3"><?= htmlspecialchars($id_multivalue) ?></td>
                  <td class="px-4 py-3"><?= htmlspecialchars($row['nama_sampah']) ?></td>
                  <td class="px-4 py-3"><?= htmlspecialchars($row['jumlah']) ?></td>
                  <td class="px-4 py-3"><?= htmlspecialchars($row['satuan']) ?></td>
                  <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                      <?= htmlspecialchars($row['jenis_sampah']) ?>
                    </span>
                  </td>
                  <td class="px-4 py-3"><?= htmlspecialchars($row['keterangan']) ?></td>
                  <td class="px-4 py-3">
                    <div class="flex items-center space-x-4 text-sm">
                      <!-- Edit Button -->
                      <a
                        class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 transition duration-200 rounded-lg dark:text-gray-400 hover:text-gray-100 focus:outline-none focus:shadow-outline-gray hover:bg-purple-700"
                        aria-label="Edit"
                        href="./edit.php?id_multivalue=<?= urlencode($id_multivalue) ?>
                          &id_transaksi=<?= urlencode($id_transaksi) ?>
                          &kelas=<?= urlencode($_GET['kelas'] ?? '') ?>
                          &id_user=<?= urlencode($_GET['id_user'] ?? '') ?>
                          &data_kelas=<?= urlencode($_GET['data_kelas'] ?? '') ?>
                          &page=<?= urlencode($_GET['page'] ?? '') ?>"
                      >
                        <svg
                          class="w-5 h-5"
                          aria-hidden="true"
                          fill="currentColor"
                          viewBox="0 0 20 20"
                        >
                          <path
                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
                          ></path>
                        </svg>
                      </a>

                      <!-- Delete Button -->
                      <a
                        onclick="return confirm('Yakin ingin menghapus data ini?')"
                        class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 transition duration-200 rounded-lg dark:text-gray-400 hover:text-gray-100 focus:outline-none focus:shadow-outline-gray hover:bg-purple-700"
                        aria-label="Delete"
                        href="./delete.php?id_multivalue=<?= urlencode($id_multivalue) ?>&id_transaksi=<?= urlencode($id_transaksi) ?>"
                      >
                        <svg
                          class="w-5 h-5"
                          aria-hidden="true"
                          fill="currentColor"
                          viewBox="0 0 20 20"
                        >
                          <path
                            fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                            clip-rule="evenodd"
                          ></path>
                        </svg>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

          <!-- PAGINATION -->
          <div class="grid w-full max-w-6xl px-4 py-3 mb-10 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t rounded-b-xl dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
            <span class="flex items-center col-span-3">
              Showing <?= ($current_page - 1) * $per_page + 1 ?> - 
              <?= min($current_page * $per_page, $totalData) ?> of <?= $totalData ?>
            </span>
            <span class="col-span-2"></span>
            
            <!-- Pagination Links -->
            <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
              <nav aria-label="Table navigation">
                <ul class="inline-flex items-center">

                  <!-- Tombol First -->
                  <li>
                    <?php if ($current_page > 1): ?>
                      <a href="?<?= isset($id_user) ? "id_user=$id_user" : "data_kelas=$kelas" ?>&page=1"
                        class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple"
                        aria-label="First">
                        << First
                      </a>
                    <?php else: ?>
                      <span class="px-3 py-1 rounded-md opacity-50 cursor-not-allowed"><< First</span>
                    <?php endif; ?>
                  </li>


                  <!-- Tombol Previous -->
                  <li>
                    <?php if ($current_page > 1): ?>
                      <a href="?<?= isset($id_user) ? "id_user=$id_user" : "data_kelas=$kelas" ?>&page=<?= $current_page - 1 ?>"
                        class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-purple"
                        aria-label="Previous">
                        <svg aria-hidden="true" class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                          <path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" fill-rule="evenodd"></path>
                        </svg>
                      </a>
                    <?php else: ?>
                      <span class="px-3 py-1 rounded-md rounded-l-lg opacity-50 cursor-not-allowed">
                        <svg aria-hidden="true" class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                          <path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" fill-rule="evenodd"></path>
                        </svg>
                      </span>
                    <?php endif; ?>
                  </li>

                  <!-- Nomor Halaman -->
                  <?php 
                  $start_page = max(1, $current_page - 2);
                  $end_page = min($totalPages, $current_page + 2);

                  if ($start_page > 1) {
                      echo '<li><span class="px-3 py-1">...</span></li>';
                  }

                  for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <li>
                      <a href="?<?= isset($id_user) ? "id_user=$id_user" : "data_kelas=$kelas" ?>&page=<?= $i ?>"
                        class="<?= $i === $current_page ? 'text-white bg-purple-600 border border-r-0 border-purple-600' : '' ?> px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                        <?= $i ?>
                      </a>
                    </li>
                  <?php endfor; 

                  if ($end_page < $totalPages) {
                      echo '<li><span class="px-3 py-1">...</span></li>';
                  }
                  ?>

                  <!-- Tombol Next -->
                  <li>
                    <?php if ($current_page < $totalPages): ?>
                      <a href="?<?= isset($id_user) ? "id_user=$id_user" : "data_kelas=$kelas" ?>&page=<?= $current_page + 1 ?>"
                        class="px-3 py-1 rounded-md rounded-r-lg focus:outline-none focus:shadow-outline-purple"
                        aria-label="Next">
                        <svg class="w-4 h-4 fill-current" aria-hidden="true" viewBox="0 0 20 20">
                          <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" fill-rule="evenodd"></path>
                        </svg>
                      </a>
                    <?php else: ?>
                      <span class="px-3 py-1 rounded-md rounded-r-lg opacity-50 cursor-not-allowed">
                        <svg class="w-4 h-4 fill-current" aria-hidden="true" viewBox="0 0 20 20">
                          <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" fill-rule="evenodd"></path>
                        </svg>
                      </span>
                    <?php endif; ?>
                  </li>

                  <!-- Tombol Last -->
                  <li>
                    <?php if ($current_page < $totalPages): ?>
                      <a href="?<?= isset($id_user) ? "id_user=$id_user" : "data_kelas=$kelas" ?>&page=<?= $totalPages ?>"
                        class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple"
                        aria-label="Last">
                        Last >>
                      </a>
                    <?php else: ?>
                      <span class="px-3 py-1 rounded-md opacity-50 cursor-not-allowed">Last >></span>
                    <?php endif; ?>
                  </li>
                </ul>
              </nav>
            </span>
          </div>
        </div>
      </div>
    </main>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </body>
</html>