// script.js  →  JavaScript halaman Daftar Produk
// Dibungkus DOMContentLoaded supaya aman dimuat di mana pun (head / footer layout).
document.addEventListener("DOMContentLoaded", function () {

    // ----- Pencarian & filter (jalan di browser, tanpa reload) -----
    const inputCari      = document.getElementById("cariProduk");
    const filterKategori = document.getElementById("filterKategori");
    const filterStatus   = document.getElementById("filterStatus");
    const barisKosong    = document.getElementById("barisKosong");
    const semuaBaris     = document.querySelectorAll("#tabelProduk tr[data-nama]");

    function terapkanFilter() {
      const kata     = inputCari.value.toLowerCase().trim();
      const kategori = filterKategori.value;
      const status   = filterStatus.value;
      let tampil = 0;

      semuaBaris.forEach(function (baris) {
        const cocokKata     = baris.dataset.nama.includes(kata) || baris.dataset.kode.includes(kata);
        const cocokKategori = kategori === "" || baris.dataset.kategori === kategori;
        const cocokStatus   = status === "" || baris.dataset.status === status;
        const tampilkan     = cocokKata && cocokKategori && cocokStatus;

        baris.classList.toggle("d-none", !tampilkan);
        if (tampilkan) tampil++;
      });

      document.getElementById("jumlahTampil").textContent = tampil;
      barisKosong.classList.toggle("d-none", tampil > 0);
    }

    inputCari.addEventListener("input", terapkanFilter);
    filterKategori.addEventListener("change", terapkanFilter);
    filterStatus.addEventListener("change", terapkanFilter);

    document.getElementById("resetFilter").addEventListener("click", function () {
      inputCari.value = "";
      filterKategori.value = "";
      filterStatus.value = "";
      terapkanFilter();
    });

    // ----- Tombol Edit: isi form popup dengan data baris yang diklik -----
    document.querySelectorAll(".btn-edit").forEach(function (tombol) {
      tombol.addEventListener("click", function () {
        const d = tombol.dataset;
        document.getElementById("editProductID").value    = d.productId;
        document.getElementById("editProductName").value  = d.name;
        document.getElementById("editCategoryID").value   = d.categoryId;
        document.getElementById("editUnit").value         = d.unit;
        document.getElementById("editPurchasePrice").value = d.purchase;
        document.getElementById("editSellingPrice").value = d.selling;
        document.getElementById("editStock").value        = d.stock;
        document.getElementById("editMinimumStock").value = d.min;
        document.getElementById("editExpiredDate").value  = d.expired;
        document.getElementById("editLocation").value     = d.location;
        document.getElementById("editNote").value         = d.note;
      });
    });

    // ----- Tombol Hapus: tampilkan nama produk di popup konfirmasi -----
    document.querySelectorAll(".btn-hapus").forEach(function (tombol) {
      tombol.addEventListener("click", function () {
        document.getElementById("hapusProductID").value = tombol.dataset.productId;
        document.getElementById("hapusNama").textContent = tombol.dataset.name;
      });
    });

  // ----- Ikon Lucide -----
  // Mengubah semua <i data-lucide="..."> di halaman ini menjadi ikon.
  // Dicek dulu supaya tidak error kalau library Lucide belum dimuat layout.
  if (window.lucide) {
    lucide.createIcons();
  }

});
