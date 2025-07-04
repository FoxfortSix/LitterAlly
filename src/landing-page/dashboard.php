<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: signup-login.php");
  exit;
}

include(__DIR__ . '/../function.php');

$userId = $_SESSION['user_id'];
$dataChart = getSampahDataByUser($userId);
$dataTabel = getTabelSampahByUser($userId);
$satuanList = getSatuanOptions();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LitterAlly</title>
  <!--
        For more customization options, we would advise
        you to build your TailwindCSS from the source.
        https://tailwindcss.com/docs/installation
    -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css"> -->
  <link rel="stylesheet" href="../output.css">
  <!-- Small CSS to Hide elements at 1520px size -->
  <style>
    @media(max-width:1520px) {
      .left-svg {
        display: none;
      }
    }

    /* small css for the mobile nav close */
    #nav-mobile-btn.close span:first-child {
      transform: rotate(45deg);
      top: 4px;
      position: relative;
      background: #a0aec0;
    }

    #nav-mobile-btn.close span:nth-child(2) {
      transform: rotate(-45deg);
      margin-top: 0px;
      background: #a0aec0;
    }
  </style>

  <!-- CHARTS -->
  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
  <script src="../assets/js/init-alpine.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="overflow-x-hidden antialiased">
  <!-- Header Section -->
  <header class="fixed top-0 z-50 w-full bg-white shadow h-15">
    <div
      class="container flex items-center justify-center h-full max-w-6xl px-8 mx-auto sm:justify-between xl:px-0">

      <a href="./index.php" class="relative flex items-center inline-block h-5 h-full font-black leading-none">
        <svg class="w-auto h-6 text-indigo-600 fill-current" viewBox="0 0 194 116"
          xmlns="http://www.w3.org/2000/svg">
          <g fill-rule="evenodd">
            <path
              d="M96.869 0L30 116h104l-9.88-17.134H59.64l47.109-81.736zM0 116h19.831L77 17.135 67.088 0z" />
            <path d="M87 68.732l9.926 17.143 29.893-51.59L174.15 116H194L126.817 0z" />
          </g>
        </svg>
        <span class="ml-3 text-xl text-gray-800">LitterAlly<span class="text-pink-500">.</span></span>
      </a>

      <nav id="nav"
        class="absolute top-0 left-0 z-50 flex flex-col items-center justify-between hidden w-full h-64 pt-5 mt-24 text-sm text-gray-800 bg-white border-t border-gray-200 md:w-auto md:flex-row md:h-24 lg:text-base md:bg-transparent md:mt-0 md:border-none md:py-0 md:flex md:relative">
        <a href="./index.php"
          class="ml-0 mr-0 font-bold duration-100 md:ml-12 md:mr-3 lg:mr-8 transition-color hover:text-indigo-600">Home</a>
        <a href="#penambahan_data_sampah"
          class="mr-0 font-bold duration-100 md:mr-3 lg:mr-8 transition-color hover:text-indigo-600">Penambahan Data Sampah</a>
        <a href="#chart_data_sampah"
          class="mr-0 font-bold duration-100 md:mr-3 lg:mr-8 transition-color hover:text-indigo-600">Chart Sampah Anda</a>
        <a href="#tabel_data_sampah"
          class="font-bold duration-100 transition-color hover:text-indigo-600">Tabel Sampah Anda</a>
        <div class="flex flex-col block w-full font-medium border-t border-gray-200 md:hidden">
          <a href="#_" class="w-full py-2 font-bold text-center text-pink-500">Login</a>
          <a href="#_"
            class="relative inline-block w-full px-5 py-3 text-sm leading-none text-center text-white bg-indigo-700 fold-bold">Get
            Started</a>
        </div>
      </nav>

      <div class="absolute left-0 flex-col items-center justify-center hidden w-full pb-8 mt-48 border-b border-gray-200 md:relative md:w-auto md:bg-transparent md:border-none md:mt-0 md:flex-row md:p-0 md:items-end md:flex md:justify-between">

        <svg class="absolute top-0 left-0 hidden w-screen max-w-3xl -mt-64 -ml-12 lg:block"
          viewBox="0 0 818 815" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
          <defs>
            <linearGradient x1="0%" y1="0%" x2="100%" y2="100%" id="c">
              <stop stop-color="#E614F2" offset="0%" />
              <stop stop-color="#FC3832" offset="100%" />
            </linearGradient>
            <linearGradient x1="0%" y1="0%" x2="100%" y2="100%" id="f">
              <stop stop-color="#657DE9" offset="0%" />
              <stop stop-color="#1C0FD7" offset="100%" />
            </linearGradient>
            <filter x="-4.7%" y="-3.3%" width="109.3%" height="109.3%" filterUnits="objectBoundingBox"
              id="a">
              <feOffset dy="8" in="SourceAlpha" result="shadowOffsetOuter1" />
              <feGaussianBlur stdDeviation="8" in="shadowOffsetOuter1" result="shadowBlurOuter1" />
              <feColorMatrix values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.15 0" in="shadowBlurOuter1" />
            </filter>
            <filter x="-4.7%" y="-3.3%" width="109.3%" height="109.3%" filterUnits="objectBoundingBox"
              id="d">
              <feOffset dy="8" in="SourceAlpha" result="shadowOffsetOuter1" />
              <feGaussianBlur stdDeviation="8" in="shadowOffsetOuter1" result="shadowBlurOuter1" />
              <feColorMatrix values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.2 0" in="shadowBlurOuter1" />
            </filter>
            <path
              d="M160.52 108.243h497.445c17.83 0 24.296 1.856 30.814 5.342 6.519 3.486 11.635 8.602 15.12 15.12 3.487 6.52 5.344 12.985 5.344 30.815v497.445c0 17.83-1.857 24.296-5.343 30.814-3.486 6.519-8.602 11.635-15.12 15.12-6.52 3.487-12.985 5.344-30.815 5.344H160.52c-17.83 0-24.296-1.857-30.814-5.343-6.519-3.486-11.635-8.602-15.12-15.12-3.487-6.52-5.343-12.985-5.343-30.815V159.52c0-17.83 1.856-24.296 5.342-30.814 3.486-6.519 8.602-11.635 15.12-15.12 6.52-3.487 12.985-5.343 30.815-5.343z"
              id="b" />
            <path
              d="M159.107 107.829H656.55c17.83 0 24.296 1.856 30.815 5.342 6.518 3.487 11.634 8.602 15.12 15.12 3.486 6.52 5.343 12.985 5.343 30.816V656.55c0 17.83-1.857 24.296-5.343 30.815-3.486 6.518-8.602 11.634-15.12 15.12-6.519 3.486-12.985 5.343-30.815 5.343H159.107c-17.83 0-24.297-1.857-30.815-5.343-6.519-3.486-11.634-8.602-15.12-15.12-3.487-6.519-5.343-12.985-5.343-30.815V159.107c0-17.83 1.856-24.297 5.342-30.815 3.487-6.519 8.602-11.634 15.12-15.12 6.52-3.487 12.985-5.343 30.816-5.343z"
              id="e" />
          </defs>
          <g fill="none" fill-rule="evenodd" opacity=".9">
            <g transform="rotate(65 416.452 409.167)">
              <use fill="#000" filter="url(#a)" xlink:href="#b" />
              <use fill="url(#c)" xlink:href="#b" />
            </g>
            <g transform="rotate(29 421.929 414.496)">
              <use fill="#000" filter="url(#d)" xlink:href="#e" />
              <use fill="url(#f)" xlink:href="#e" />
            </g>
          </g>
        </svg>
      </div>

      <div id="nav-mobile-btn"
        class="absolute top-0 right-0 z-50 block w-6 mt-8 mr-10 cursor-pointer select-none md:hidden sm:mt-10">
        <span class="block w-full h-1 mt-2 duration-200 transform bg-gray-800 rounded-full sm:mt-1"></span>
        <span class="block w-full h-1 mt-1 duration-200 transform bg-gray-800 rounded-full"></span>
      </div>

    </div>
  </header>
  <!-- End Header Section-->

  <!-- FORM -->
  <div class="px-6 pt-40 sm:px-4 md:px-6 lg:px-8">
    <div class="container flex flex-col items-center px-4 mx-auto space-y-8 sm:px-6 md:px-8">
      <div class="flex flex-col items-center justify-center w-full h-full max-w-2xl pr-8 mx-auto text-center">
        <h2 class="text-4xl font-extrabold leading-10 tracking-tight text-gray-900 sm:text-5xl sm:leading-none md:text-6xl lg:text-5xl xl:text-6xl">
          Penambahan Data Sampah
        </h2>
        <p class="my-6 text-xl font-medium text-gray-500">
          Submit Data Sampah yang telah anda buang disini
        </p>
      </div>
      <!-- Line -->
      <div class="relative flex items-center justify-center w-full max-w-xs border border-indigo-400 sm:max-w-md md:max-w-lg">
        <div class="absolute w-1/2 border-2 border-blue-400"></div>
      </div>
      <form id="form-sampah" method="POST" class="relative flex flex-col w-full max-w-2xl px-6 py-10 space-y-4 text-white shadow-2xl sm:px-10 bg-gradient-to-br from-indigo-500 to-pink-400 rounded-xl">
        <!-- Checkbox Organik -->
        <div id="checkbox-organik" class="flex items-center w-full max-w-lg mx-auto space-x-2 md:w-3/4">
          <input type="checkbox" name="checkbox_organik" id="checkbox_organik" class="w-5 h-5 text-indigo-600 bg-white rounded">
          <label for="checkbox_organik" class="text-lg font-bold">
            Formulir Pengisian Sampah Organik
          </label>
        </div>
        <!-- FROM ORGANIK -->
        <div id="form-organik" class="z-10">
          <!-- Nama Sampah -->
          <div class="flex flex-col w-full max-w-lg mx-auto space-y-2 md:w-3/4">
            <label for="nama_sampah_organik" class="text-lg font-bold">Nama Sampah</label>
            <input type="text" name="nama_sampah_organik" id="nama_sampah_organik" class="w-full p-2 text-lg text-black bg-white rounded-xl">
          </div>
          <!-- Jumlah -->
          <div class="flex flex-col w-full max-w-lg mx-auto space-y-2 md:w-3/4">
            <label for="jumlah_organik" class="text-lg font-bold">Jumlah</label>
            <input type="text" name="jumlah_organik" id="jumlah_organik" class="w-full p-2 text-lg text-black bg-white rounded-xl">
          </div>
          <!-- Satuan -->
          <div class="flex flex-col w-full max-w-lg mx-auto space-y-2 md:w-3/4">
            <label for="satuan_organik" class="text-lg font-bold">Satuan</label>
            <select name="satuan_organik" id="satuan_organik" class="w-full p-2 text-lg text-black bg-white rounded-xl">
              <option value="">-- Pilih Satuan --</option>
              <?php foreach ($satuanList as $satuan): ?>
                <option value="<?= $satuan['id_satuan'] ?>"><?= htmlspecialchars($satuan['nama_satuan']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <!-- Keterangan -->
          <div class="flex flex-col w-full max-w-lg mx-auto space-y-2 md:w-3/4">
            <label for="keterangan_organik" class="text-lg font-bold">Keterangan</label>
            <input type="text" name="keterangan_organik" id="keterangan_organik" class="w-full h-24 p-2 text-lg text-black bg-white rounded-xl">
          </div>
        </div>
        <div id="checkbox-anorganik" class="flex items-center w-full max-w-lg mx-auto space-x-2 md:w-3/4">
          <input type="checkbox" name="checkbox_anorganik" id="checkbox_anorganik" class="w-5 h-5 text-indigo-600 bg-white rounded">
          <label for="checkbox_anorganik" class="text-lg font-bold">
            Formulir Pengisian Sampah Anorganik
          </label>
        </div>
        <!-- FROM ANORGANIK -->
        <div id="form-anorganik" class="z-10">
          <!-- Nama Sampah -->
          <div class="flex flex-col w-full max-w-lg mx-auto space-y-2 md:w-3/4">
            <label for="nama_sampah_anorganik" class="text-lg font-bold">Nama Sampah</label>
            <input type="text" name="nama_sampah_anorganik" id="nama_sampah_anorganik" class="w-full p-2 text-lg text-black bg-white rounded-xl">
          </div>
          <!-- Jumlah -->
          <div class="flex flex-col w-full max-w-lg mx-auto space-y-2 md:w-3/4">
            <label for="jumlah_anorganik" class="text-lg font-bold">Jumlah</label>
            <input type="text" name="jumlah_anorganik" id="jumlah_anorganik" class="w-full p-2 text-lg text-black bg-white rounded-xl">
          </div>
          <!-- Satuan -->
          <div class="flex flex-col w-full max-w-lg mx-auto space-y-2 md:w-3/4">
            <label for="satuan_anorganik" class="text-lg font-bold">Satuan</label>
            <select name="satuan_anorganik" id="satuan_anorganik" class="w-full p-2 text-lg text-black bg-white rounded-xl">
              <option value="">-- Pilih Satuan --</option>
              <?php foreach ($satuanList as $satuan): ?>
                <option value="<?= $satuan['id_satuan'] ?>"><?= htmlspecialchars($satuan['nama_satuan']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <!-- Keterangan -->
          <div class="flex flex-col w-full max-w-lg mx-auto space-y-2 md:w-3/4">
            <label for="keterangan_anorganik" class="text-lg font-bold">Keterangan</label>
            <input type="text" name="keterangan_anorganik" id="keterangan_anorganik" class="w-full h-24 p-2 text-lg text-black bg-white rounded-xl">
          </div>
        </div>
        <div id="checkbox-b3" class="flex items-center w-full max-w-lg mx-auto space-x-2 md:w-3/4">
          <input type="checkbox" name="checkbox_b3" id="checkbox_b3" class="w-5 h-5 text-indigo-600 bg-white rounded">
          <label for="checkbox_b3" class="text-lg font-bold">
            Formulir Pengisian Sampah B3
          </label>
        </div>
        <!-- FROM B3 -->
        <div id="form-b3" class="z-10">
          <!-- Nama Sampah -->
          <div class="flex flex-col w-full max-w-lg mx-auto space-y-2 md:w-3/4">
            <label for="nama_sampah_b3" class="text-lg font-bold">Nama Sampah</label>
            <input type="text" name="nama_sampah_b3" id="nama_sampah_b3" class="w-full p-2 text-lg text-black bg-white rounded-xl">
          </div>
          <!-- Jumlah -->
          <div class="flex flex-col w-full max-w-lg mx-auto space-y-2 md:w-3/4">
            <label for="jumlah_b3" class="text-lg font-bold">Jumlah</label>
            <input type="text" name="jumlah_b3" id="jumlah_b3" class="w-full p-2 text-lg text-black bg-white rounded-xl">
          </div>
          <!-- Satuan -->
          <div class="flex flex-col w-full max-w-lg mx-auto space-y-2 md:w-3/4">
            <label for="satuan_b3" class="text-lg font-bold">Satuan</label>
            <select name="satuan_b3" id="satuan_b3" class="w-full p-2 text-lg text-black bg-white rounded-xl">
              <option value="">-- Pilih Satuan --</option>
              <?php foreach ($satuanList as $satuan): ?>
                <option value="<?= $satuan['id_satuan'] ?>"><?= htmlspecialchars($satuan['nama_satuan']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <!-- Keterangan -->
          <div class="flex flex-col w-full max-w-lg mx-auto space-y-2 md:w-3/4">
            <label for="keterangan_b3" class="text-lg font-bold">Keterangan</label>
            <input type="text" name="keterangan_b3" id="keterangan_b3" class="w-full h-24 p-2 text-lg text-black bg-white rounded-xl">
          </div>
        </div>
        <div data-svg-wrapper class="absolute left-0 z-0 pointer-events-none bottom-10">
          <svg id="triangle-left" width="206" height="444" viewBox="0 0 206 444" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M196 191L0 0V444C58.3333 386 179.2 265.2 196 246C212.8 226.8 203 201.333 196 191Z" fill="#F33E8F" />
          </svg>
        </div>
        <div data-svg-wrapper class="absolute right-0 z-0 pointer-events-none top-10">
          <svg id="triangle-right" width="125" height="262" viewBox="0 0 125 262" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6.1809 146.486L125 261.86L125 0.000106168C89.7305 34.7024 16.5198 101.909 6.35427 113.404C-3.81122 124.9 2.00307 140.248 6.1809 146.486Z" fill="#F33E8F" />
          </svg>
        </div>
        <!-- Tombol -->
        <button class="w-full max-w-xs p-2 mx-auto text-xl font-bold text-indigo-600 transition duration-300 transform bg-white rounded-xl md:w-2/4 hover:scale-105 hover:shadow-2xl">Submit</button>
      </form>
    </div>
  </div>

  <!-- CHART SAMPAH -->
  <div id="chart_data_sampah" class="px-6 pt-40 sm:px-4 md:px-6 lg:px-8">
    <div class="container flex flex-col items-center px-4 mx-auto space-y-8 sm:px-6 md:px-8">
      <div class="flex flex-col items-center justify-center w-full h-full max-w-2xl pr-8 mx-auto text-center">
        <h2
          class="text-4xl font-extrabold leading-10 tracking-tight text-gray-900 sm:text-5xl sm:leading-none md:text-6xl lg:text-5xl xl:text-6xl">
          Chart Data Sampah Anda</h2>
        <p class="my-6 text-xl font-medium text-gray-500">
          Berikut ini adalah grafik data sampah yang telah Anda kumpulkan mulai dari sampah organik, anorganik, hingga B3
        </p>
      </div>

      <div class="relative flex items-center justify-center w-full max-w-xs border border-indigo-400 sm:max-w-md md:max-w-lg">
        <div class="absolute w-1/2 border-2 border-blue-400"></div>
      </div>

      <div class="relative p-4 bg-white rounded-lg shadow-2xl w-7/10">
        <canvas id="line"></canvas>
        <div class="flex justify-center mt-4 space-x-3 text-sm text-gray-800 dark:text-gray-800">
          <!-- Chart legend -->
          <div class="flex items-center">
            <span class="inline-block w-3 h-3 mr-1 bg-teal-500 rounded-full"></span>
            <span>Organik</span>
          </div>
          <div class="flex items-center">
            <span class="inline-block w-3 h-3 mr-1 bg-orange-400 rounded-full"></span>
            <span>Non-organik</span>
          </div>
          <div class="flex items-center">
            <span class="inline-block w-3 h-3 mr-1 bg-red-500 rounded-full"></span>
            <span>B3</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br>
  <!-- TABLE SAMPAH -->
  <!-- Tabel Data -->
  <div class="w-full max-w-5xl mx-auto overflow-x-auto shadow-lg rounded-lg">
    <table class="w-full text-sm text-left text-gray-600">
      <thead class="text-xs text-gray-700 uppercase bg-gray-100">
        <tr>
          <th class="px-4 py-3">Nama Sampah</th>
          <th class="px-4 py-3">Jumlah</th>
          <th class="px-4 py-3">Satuan</th>
          <th class="px-4 py-3">Jenis Sampah</th>
          <th class="px-4 py-3">Keterangan</th>
          <th class="px-4 py-3">Aksi</th>
        </tr>
      </thead>

      <tbody class="bg-white divide-y divide-gray-200">
        <?php
        $data = getTabelSampahByUser($userId);
        if (!empty($data)) :
          foreach ($data as $row) :
        ?>
            <tr>
              <td class="px-4 py-3"><?= htmlspecialchars($row['nama_subjenis']) ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($row['jumlah']) ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($row['nama_satuan']) ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($row['jenis_sampah']) ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($row['keterangan']) ?></td>
              <td class="px-4 py-3 space-x-2 flex items-center">
                <!-- Edit Button -->
                <a
                  href="edit.php?id_transaksi=<?= htmlspecialchars($row['id_transaksi'] ?? '') ?>&id_multivalue=<?= htmlspecialchars($row['id_multivalue'] ?? '') ?>"

                  class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 transition duration-200 rounded-lg dark:text-gray-400 hover:text-gray-100 focus:outline-none focus:shadow-outline-gray hover:bg-purple-700"
                  aria-label="Edit">
                  <svg
                    class="w-5 h-5"
                    aria-hidden="true"
                    fill="currentColor"
                    viewBox="0 0 20 20">
                    <path
                      d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                  </svg>
                </a>

                <!-- Delete Button -->
                <a
                  href="hapus.php?id_multivalue=<?= isset($row['id_multivalue']) ? htmlspecialchars($row['id_multivalue']) : '' ?>"
                  onclick="return confirm('Yakin ingin menghapus data ini?')"
                  class="btn-hapus ..."
                  aria-label="Delete">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                  </svg>
                </a>
              </td>
            </tr>
          <?php
          endforeach;
        else :
          ?>
          <tr>
            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada data sampah.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>


  <!-- FOOTER -->
  <br>
  <footer class="px-4 pt-12 pb-8 text-white bg-white border-t border-gray-200">
    <div class="container flex flex-col justify-between max-w-6xl px-4 mx-auto overflow-hidden lg:flex-row">
      <div class="w-full pl-12 mr-4 text-left lg:w-1/4 sm:text-center sm:pl-0 lg:text-left">
        <a href="./index.php" class="relative flex items-center inline-block h-5 h-full font-black leading-none">
          <svg class="w-auto h-6 text-indigo-600 fill-current" viewBox="0 0 194 116"
            xmlns="http://www.w3.org/2000/svg">
            <g fill-rule="evenodd">
              <path
                d="M96.869 0L30 116h104l-9.88-17.134H59.64l47.109-81.736zM0 116h19.831L77 17.135 67.088 0z" />
              <path d="M87 68.732l9.926 17.143 29.893-51.59L174.15 116H194L126.817 0z" />
            </g>
          </svg>
          <span class="ml-3 text-xl text-gray-800">LitterAlly<span class="text-pink-500">.</span></span>
          <p class="mt-6 mr-4 text-base font-medium text-gray-500">LitterAlly adalah Smart TPS dengan solusi digital dalam pengelolaan sampah yang efisien, transparan, dan berkelanjutan.</p>
        </a>

      </div>
      <div class="block w-full pl-10 mt-6 text-sm lg:w-3/4 sm:flex lg:mt-0">
        <ul class="flex flex-col w-full p-0 font-medium text-left text-gray-700 list-none">
          <li class="inline-block px-3 py-2 mt-5 font-bold tracking-wide text-gray-800 uppercase md:mt-0">
            Tentang Kami</li>
          <li>
            <a href="#kelebihan" class="inline-block px-3 py-2 text-gray-500 no-underline hover:text-gray-600">
              Kelebihan
            </a>

          </li>
          <li>
            <a href="#testimoni" class="inline-block px-3 py-2 text-gray-500 no-underline hover:text-gray-600">
              Testimoni
            </a>

          </li>
        </ul>


        <div class="flex flex-col w-full text-gray-700">
          <div class="inline-block px-3 py-2 mt-5 font-bold text-gray-800 uppercase md:mt-0">Ikuti Kami</div>
          <div class="flex justify-start pl-4 mt-2">
            <a class="flex items-center block mr-6 text-gray-400 no-underline hover:text-gray-600"
              target="_blank" rel="noopener noreferrer" href="#">
              <svg viewBox="0 0 24 24" class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M23.998 12c0-6.628-5.372-12-11.999-12C5.372 0 0 5.372 0 12c0 5.988 4.388 10.952 10.124 11.852v-8.384H7.078v-3.469h3.046V9.356c0-3.008 1.792-4.669 4.532-4.669 1.313 0 2.686.234 2.686.234v2.953H15.83c-1.49 0-1.955.925-1.955 1.874V12h3.328l-.532 3.469h-2.796v8.384c5.736-.9 10.124-5.864 10.124-11.853z" />
              </svg>
            </a>
            <a class="flex items-center block mr-6 text-gray-400 no-underline hover:text-gray-600"
              target="_blank" rel="noopener noreferrer" href="#">
              <svg viewBox="0 0 24 24" class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M23.954 4.569a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.691 8.094 4.066 6.13 1.64 3.161a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.061a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.937 4.937 0 004.604 3.417 9.868 9.868 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63a9.936 9.936 0 002.46-2.548l-.047-.02z" />
              </svg>
            </a>
            <a class="flex items-center block text-gray-400 no-underline hover:text-gray-600"
              target="_blank" rel="noopener noreferrer" href="#">
              <svg viewBox="0 0 24 24" class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="pt-4 pt-6 mt-10 text-center text-gray-500 border-t border-gray-100">© 2025 Smart TPS. All rights reserved. An SMBD Computer Science Group 4 Project</div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="./chart-handler-user.js"></script>
  <script src="./table-sampah.js"></script>
  <script src="./form.js"></script>
  <script src="./input.js"></script>
</body>

</html>