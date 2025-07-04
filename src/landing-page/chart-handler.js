console.log("chart-handler.js loaded");
document.addEventListener("DOMContentLoaded", function () {
  fetch("get_bar_chart_data.php")
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
              text: "Tren Sampah Berdasarkan Jenis",
            },
          },
        },
      });
    });
});
