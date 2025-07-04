document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("form-sampah");

  if (!form) {
    console.error("Element form-sampah tidak ditemukan");
    return;
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData();
    let hasChecked = false;

    // ORGANIK
    if (document.getElementById("checkbox_organik")?.checked) {
      hasChecked = true;
      formData.append("checkbox_organik", "on");
      formData.append(
        "nama_sampah_organik",
        document.getElementById("nama_sampah_organik").value
      );
      formData.append(
        "jumlah_organik",
        document.getElementById("jumlah_organik").value
      );
      formData.append(
        "satuan_organik",
        document.getElementById("satuan_organik").value
      );
      formData.append(
        "keterangan_organik",
        document.getElementById("keterangan_organik").value
      );
    }

    // ANORGANIK
    if (document.getElementById("checkbox_anorganik")?.checked) {
      hasChecked = true;
      formData.append("checkbox_anorganik", "on");
      formData.append(
        "nama_sampah_anorganik",
        document.getElementById("nama_sampah_anorganik").value
      );
      formData.append(
        "jumlah_anorganik",
        document.getElementById("jumlah_anorganik").value
      );
      formData.append(
        "satuan_anorganik",
        document.getElementById("satuan_anorganik").value
      );
      formData.append(
        "keterangan_anorganik",
        document.getElementById("keterangan_anorganik").value
      );
    }

    // B3
    if (document.getElementById("checkbox_b3")?.checked) {
      hasChecked = true;
      formData.append("checkbox_b3", "on");
      formData.append(
        "nama_sampah_b3",
        document.getElementById("nama_sampah_b3").value
      );
      formData.append("jumlah_b3", document.getElementById("jumlah_b3").value);
      formData.append("satuan_b3", document.getElementById("satuan_b3").value);
      formData.append(
        "keterangan_b3",
        document.getElementById("keterangan_b3").value
      );
    }

    if (!hasChecked) {
      alert("Pilih setidaknya satu jenis sampah untuk disubmit.");
      return;
    }

    fetch("proses_input_sampah.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.text())
      .then((text) => {
        console.log("Respon mentah dari server:\n", text);

        try {
          const response = JSON.parse(text);
          if (response.success) {
            alert("✅ Data berhasil disimpan!");
            window.location.reload();
          } else {
            alert("❌ Gagal menyimpan data: " + response.message);
          }
        } catch (err) {
          console.error(
            "❌ Respon bukan JSON. Mungkin ada error PHP di server."
          );
          console.error("Isi respon:", text);
          alert("❌ Server mengembalikan error. Lihat console untuk detail.");
        }
      })
      .catch((error) => {
        console.error("Gagal:", error);
        alert("❌ Terjadi kesalahan saat mengirim data.");
      });
  });
});
