console.log("chart-handler-user.js loaded");

let myChart; // simpan di global scope

document.addEventListener("DOMContentLoaded", function () {
  fetch("get_user_chart_data.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        console.error("Error:", data.error);
        return;
      }

      const canvas = document.getElementById("line");
      const ctx = canvas.getContext("2d");

      // Hancurkan chart sebelumnya jika ada
      if (myChart) {
        myChart.destroy();
      }

      // Buat chart baru dan simpan instansinya
      myChart = new Chart(ctx, {
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
              text: "Sampah Yang Telah Anda Pilah",
            },
          },
        },
      });
    });
});
