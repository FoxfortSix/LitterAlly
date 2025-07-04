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
    <script src="./chart_data_sampah.js"></script>
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
              <a href="../index.php" class="transition duration-300 hover:text-white">
                Kembali ke laman utama
              </a>
            </div>

          </div>
        </header>

        <main class="h-full pb-16 overflow-y-auto">
          <div class="container grid px-6 mx-auto">
            <!-- Title Manajemen Sampah -->
            <h2
              class="my-6 text-3xl font-semibold text-gray-700 dark:text-gray-200"
            >
              Chart Data Sampah
            </h2>

            <!-- PEMILIHAN DATA SAMPAH -->
            <div class="flex flex-col w-full mx-auto space-y-7 lg:w-1/2">

              <!-- Judul -->
              <div class="flex flex-col space-y-3 text-center">
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                  Pemilihan Data Sampah
                </h2>
                <p class="text-gray-600 dark:text-gray-400">
                  Silahkan pilih data mana saja yang anda inginkan
                </p>
              </div>

            <!-- OPTION PEMILIHAN SECARA PERORANGAN -->
            <label class="inline-flex items-center text-sm font-semibold dark:text-gray-400">
              <input type="checkbox" id="checkbox-data-perorangan" class="text-purple-600 form-checkbox" />
              <span class="ml-2">Pilih Secara Perorangan</span>
            </label>

<!-- FORM MANAJEMEN SAMPAH PERORANGAN -->
<form id="form-perorangan" action="chart_result.php" method="GET" class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">

    <!-- Kelas -->
    <div class="mt-4 text-sm">
        <span class="text-gray-700 dark:text-gray-400">
            Pilih Kelas
        </span>
        <div class="mt-2">
            <label class="inline-flex items-center text-gray-600 dark:text-gray-400">
                <input
                    type="radio"
                    class="text-purple-600 kelas-radio form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                    name="kelas"
                    value="C1"
                    required
                />
                <span class="ml-2">Kelas C1</span>
            </label>
            <label class="inline-flex items-center ml-6 text-gray-600 dark:text-gray-400">
                <input
                    type="radio"
                    class="text-purple-600 kelas-radio form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                    name="kelas"
                    value="C2"
                    required
                />
                <span class="ml-2">Kelas C2</span>
            </label>
        </div>
    </div>

    <!-- Nama -->
    <label class="block mt-4 text-sm">
        <span class="text-gray-700 dark:text-gray-400">
            Pilih Nama
        </span>
        <select
            id="nama-user-select"
            name="id_user"
            class="block w-full p-2 mt-1 text-sm rounded-lg dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
            required
            disabled
        >
            <option value="">Pilih kelas terlebih dahulu</option>
        </select>
    </label>

    <!-- Button -->
    <div class="flex justify-center">
        <button type="submit" class="px-4 py-2 mt-3 text-sm font-medium leading-5 text-white transition-colors duration-150 transform bg-purple-600 border border-transparent rounded-lg hover:scale-105 hover:shadow-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Submit
        </button>
    </div>
</form>

            <!-- Ubah form menjadi -->
            <form id="form-seluruh-data" class="flex flex-col mb-10 space-y-7" action="chart_result.php" method="GET">
                <!-- Radio: Seluruh Data -->
                <div class="flex px-4 py-3 text-sm bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <label class="flex items-center dark:text-gray-400">
                        <input
                            type="radio"
                            name="kelas"
                            value="seluruh"
                            class="text-purple-600 form-radio focus:outline-none"
                            checked
                        />
                        <span class="ml-2 text-lg font-semibold">Seluruh Data</span>
                    </label>
                </div>

                <!-- Radio: Kelas C1 -->
                <div class="flex px-4 py-3 text-sm bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <label class="flex items-center dark:text-gray-400">
                        <input
                            type="radio"
                            name="kelas"
                            value="c1"
                            class="text-purple-600 form-radio focus:outline-none"
                        />
                        <span class="ml-2 text-lg font-semibold">Kelas C1</span>
                    </label>
                </div>

                <!-- Radio: Kelas C2 -->
                <div class="flex px-4 py-3 text-sm bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <label class="flex items-center dark:text-gray-400">
                        <input
                            type="radio"
                            name="kelas"
                            value="c2"
                            class="text-purple-600 form-radio focus:outline-none"
                        />
                        <span class="ml-2 text-lg font-semibold">Kelas C2</span>
                    </label>
                </div>

                <!-- Button -->
                <div class="flex justify-center">
                    <button type="submit" class="w-2/5 px-4 py-2 mt-3 text-sm font-medium leading-5 text-white transition-colors duration-150 transform bg-purple-600 border border-transparent rounded-lg hover:scale-105 hover:shadow-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        Submit
                    </button>
                </div>
            </form>
            </div>

          </div>
        </main>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./chart_data_sampah.js"></script>
    <script>
    $(document).ready(function() {
        // Ketika radio kelas diubah
        $('.kelas-radio').change(function() {
            var kelas = $(this).val();
            $('#nama-user-select').prop('disabled', false).html('<option value="">Memuat data...</option>');
            
            $.ajax({
                url: 'get_users_by_class.php',
                type: 'GET',
                data: { kelas: kelas },
                dataType: 'json',
                success: function(users) {
                    var options = '<option value="">Pilih nama</option>';
                    $.each(users, function(index, user) {
                        options += '<option value="' + user.id_user + '">' + user.nama_user + '</option>';
                    });
                    $('#nama-user-select').html(options);
                },
                error: function() {
                    $('#nama-user-select').html('<option value="">Gagal memuat data</option>');
                }
            });
        });
    });
    </script>
  </body>
</html>