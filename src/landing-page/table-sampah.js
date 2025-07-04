document.addEventListener("DOMContentLoaded", function () {
  const tbody = document.querySelector("#tabel_data_sampah tbody");
  const prevBtn = document.getElementById("prev-btn");
  const nextBtn = document.getElementById("next-btn");

  let data = [];
  let currentPage = 1;
  const rowsPerPage = 10;

  function renderTablePage(page) {
    tbody.classList.add("opacity-0");

    setTimeout(() => {
      tbody.innerHTML = "";

      const start = (page - 1) * rowsPerPage;
      const end = start + rowsPerPage;
      const pageData = data.slice(start, end);

      pageData.forEach((row) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td class="px-4 py-2">${row.nama_subjenis || "-"}</td>
          <td class="px-4 py-2">${row.jumlah || "-"}</td>
          <td class="px-4 py-2">${row.nama_satuan || "-"}</td>
          <td class="px-4 py-2">${row.jenis_sampah || "-"}</td>
          <td class="px-4 py-2">${row.keterangan || "-"}</td>
        `;
        tbody.appendChild(tr);
      });

      // Fade in
      tbody.classList.remove("opacity-0");
      tbody.classList.add("transition-opacity", "duration-300", "opacity-100");

      // Update button states
      prevBtn.disabled = page === 1;
      nextBtn.disabled = end >= data.length;
    }, 150);
  }

  prevBtn.addEventListener("click", () => {
    if (currentPage > 1) {
      currentPage--;
      renderTablePage(currentPage);
    }
  });

  nextBtn.addEventListener("click", () => {
    if (currentPage * rowsPerPage < data.length) {
      currentPage++;
      renderTablePage(currentPage);
    }
  });

  fetch("get_table_sampah_user.php")
    .then((response) => response.json())
    .then((json) => {
      data = json;
      renderTablePage(currentPage);
    })
    .catch((error) => {
      console.error("Gagal mengambil data tabel sampah:", error);
    });
});
