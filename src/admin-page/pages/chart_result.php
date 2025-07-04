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
    <!-- <script src="../../assets/js/init-alpine.js"></script> -->
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
              <a href="../pages/chart_data_sampah.php" class="transition duration-300 hover:text-white">
                Kembali ke laman tabel data sampah
              </a>
            </div>
          </div>
        </header>

        <main class="h-full pb-16 overflow-y-auto">
        <!-- CHART SAMPAH -->
        <div id="data_sampah" class="px-6 pt-5 sm:px-4 md:px-6 lg:px-8">
            <div class="container flex flex-col items-center px-4 mx-auto space-y-8 sm:px-6 md:px-8">
                <div class="flex flex-col items-center justify-center w-full h-full max-w-2xl pr-8 mx-auto text-center">
                    <p class="my-2 text-xl font-medium text-gray-500">
                        Data Chart:
                    </p>
                </div>
                
                <div class="relative flex items-center justify-center w-full max-w-xs border border-indigo-400 sm:max-w-md md:max-w-lg">
                    <div class="absolute w-1/2 border-2 border-blue-400"></div>
                </div>

                <div class="relative p-4 bg-white rounded-lg shadow-2xl w-7/10">
                    <canvas id="line"></canvas>
                    <div 
                        class="flex justify-center mt-4 space-x-3 text-sm text-gray-800 dark:text-gray-800">
                    </div>
                </div>
            </div>
        </div>
        </main>
      </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Ambil parameter dari URL
        const urlParams = new URLSearchParams(window.location.search);
        const kelas = urlParams.get('kelas');
        const id_user = urlParams.get('id_user');
        const jenis_sampah = urlParams.getAll('jenis_sampah[]');

        let apiUrl;
        let title;
        
        if (id_user) {
            // Permintaan perorangan
            apiUrl = `get_personal_chart_data.php?id_user=${id_user}`;
            if (jenis_sampah.length > 0) {
                apiUrl += `&jenis_sampah=${jenis_sampah.join(',')}`;
            }
            title = "Tren Sampah Perorangan";
        } else {
            // Permintaan seluruh data atau per kelas
            apiUrl = 'get_bar_chart_data.php';
            if (kelas && kelas !== 'seluruh') {
                apiUrl += `?kelas=${kelas}`;
            }
            title = `Tren Sampah (${kelas ? kelas.toUpperCase() : 'Seluruh Data'})`;
        }

        fetch(apiUrl)
            .then((response) => response.json())
            .then((data) => {
                const ctx = document.getElementById("line").getContext("2d");
                
                new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: data.labels,
                        datasets: data.datasets,
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: "bottom",
                            },
                            title: {
                                display: true,
                                text: title,
                            },
                        },
                    },
                });
            });
    });
    </script>
</body>
</html>