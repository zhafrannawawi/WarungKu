/* ============================================================
 * WARUNGKU - SCRIPT UTAMA (MAIN.JS)
 * ============================================================
 * File ini memuat seluruh interaksi JavaScript untuk aplikasi
 * WarungKu yang dikelompokkan berdasarkan menu/modul.
 * ============================================================ */

/* ============================================================
 * 1. GLOBAL & LAYOUT
 * ============================================================
 * Digunakan untuk fungsionalitas umum seperti navigasi sidebar
 * responsif di perangkat mobile dan tablet.
 * ============================================================ */
function toggleSidebar() {
  const wrapper = document.getElementById("wrapper");
  if (wrapper) wrapper.classList.toggle("toggled");
}

document.querySelectorAll("input[type=number]").forEach(function (el) {
  el.addEventListener("wheel", function () {
    this.blur();
  });
});

/* ============================================================
 * 2. MENU: LOGIN & AUTENTIKASI
 * ============================================================
 * Menangani toggle visibilitas password (show/hide) dan
 * inisialisasi modal verifikasi reset password.
 * ============================================================ */
function togglePasswordVisibility() {
  var input = document.getElementById("user_password");
  var icon = document.getElementById("eye-icon");
  if (!input || !icon) return;

  if (input.type === "password") {
    input.type = "text";
    icon.setAttribute("data-lucide", "eye-off");
  } else {
    input.type = "password";
    icon.setAttribute("data-lucide", "eye");
  }
  if (window.lucide) {
    lucide.createIcons();
  }
}

// Inisialisasi otomatis saat DOM siap
document.addEventListener("DOMContentLoaded", function () {
  if (window.lucide) {
    lucide.createIcons();
  }

  // Buka modal password baru jika reset_user_id terisi
  var resetUserId = document.getElementById("reset_user_id");
  if (resetUserId && resetUserId.value && window.bootstrap) {
    var modalNewPass = document.getElementById("modalNewPassword");
    if (modalNewPass) new bootstrap.Modal(modalNewPass).show();
    return;
  }

  // Buka modal reset password jika terdapat error verifikasi
  var modalReset = document.getElementById("modalResetPassword");
  if (
    modalReset &&
    modalReset.querySelector(".alert-danger") &&
    window.bootstrap
  ) {
    new bootstrap.Modal(modalReset).show();
  }
});

/* ============================================================
 * 3. MENU: OWNER - MANAJEMEN AKUN KASIR
 * ============================================================
 * Menangani pembukaan modal edit, modal hapus kasir dengan data
 * dinamis, dan pencarian/filter real-time pada tabel staf kasir.
 * ============================================================ */
function bukaModalEdit(user) {
  document.getElementById("edit_user_id").value = user.UserID;
  document.getElementById("edit_fullname").value = user.Fullname;
  document.getElementById("edit_username").value = user.Username;
  document.getElementById("edit_email").value = user.Email || "";
  document.getElementById("edit_phone").value = user.PhoneNumber || "";
  document.getElementById("edit_is_active").value = user.IsActive;

  var statusGroup = document.getElementById("edit_status_group");
  if (statusGroup) {
    statusGroup.style.display = user.Role === "owner" ? "none" : "block";
  }

  new bootstrap.Modal(document.getElementById("modalEditKasir")).show();
}

function filterAkunTable() {
  var input = document.getElementById("akun-table-search").value.toLowerCase();
  var roleFilter = document.getElementById("akun-filter-role").value;
  var statusFilter = document.getElementById("akun-filter-status").value;
  var rows = document.querySelectorAll("#akun-table-body tr");
  rows.forEach(function (row) {
    var text = row.textContent.toLowerCase();
    var matchText = text.indexOf(input) > -1;
    var matchRole = roleFilter === "all" || text.indexOf(roleFilter) > -1;
    var matchStatus =
      statusFilter === "all" ||
      (statusFilter === "active" &&
        text.indexOf("aktif") > -1 &&
        text.indexOf("non-aktif") === -1) ||
      (statusFilter === "inactive" && text.indexOf("non-aktif") > -1);
    row.style.display = matchText && matchRole && matchStatus ? "" : "none";
  });
}

/* ============================================================
 * 4. MENU: OWNER - PRODUK, KATEGORI & RESTOCK
 * ============================================================
 * Form submission dan modal handling untuk katalog produk,
 * kategori barang, dan pencatatan restock barang.
 * ============================================================ */
function openAddProductModal() {
  const form = document.getElementById("form-produk");
  if (form) form.reset();
  const label = document.getElementById("modalFormProdukLabel");
  if (label) label.textContent = "Tambah Produk Baru";

  const action = document.getElementById("prod-action");
  if (action) action.value = "tambah";
  const prodId = document.getElementById("prod-id");
  if (prodId) prodId.value = "";

  const modalEl = document.getElementById("modalFormProduk");
  if (modalEl && window.bootstrap) {
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
  }
}

function editProduct(p) {
  const label = document.getElementById("modalFormProdukLabel");
  if (label) label.textContent = "Edit Data Produk";

  document.getElementById("prod-action").value = "edit";
  document.getElementById("prod-id").value = p.ProductID;
  document.getElementById("prod-name").value = p.ProductName;
  document.getElementById("prod-category").value = p.CategoryID;
  document.getElementById("prod-unit").value = p.Unit;
  document.getElementById("prod-purchase-price").value = Math.round(
    p.PurchasePrice,
  );
  document.getElementById("prod-selling-price").value = Math.round(
    p.SellingPrice,
  );
  document.getElementById("prod-stock").value = p.Stock;
  document.getElementById("prod-min-stock").value = p.MinimumStock;
  document.getElementById("prod-location").value = p.Location || "";

  const modalEl = document.getElementById("modalFormProduk");
  if (modalEl && window.bootstrap) {
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
  }
}

function deleteProduct(id, name) {
  if (confirm(`Apakah Anda yakin ingin menghapus produk "${name}"?`)) {
    const deleteId = document.getElementById("delete-product-id");
    const formDelete = document.getElementById("form-delete-produk");
    if (deleteId && formDelete) {
      deleteId.value = id;
      formDelete.submit();
    }
  }
}

function filterProdukTable() {
  const query = (
    document.getElementById("produk-table-search")?.value || ""
  ).toLowerCase();
  const cat = document.getElementById("produk-filter-category")?.value || "all";
  const stockFilter =
    document.getElementById("produk-filter-stock")?.value || "all";

  const rows = document.querySelectorAll(".produk-row");
  rows.forEach(function (row) {
    const name = (row.getAttribute("data-name") || "").toLowerCase();
    const category = row.getAttribute("data-category") || "";
    const stock = parseInt(row.getAttribute("data-stock") || "0", 10);

    const matchQuery = name.includes(query);
    const matchCat = cat === "all" || category === cat;
    let matchStock = true;
    if (stockFilter === "in_stock") matchStock = stock > 10;
    if (stockFilter === "low_stock") matchStock = stock <= 10;

    row.style.display = matchQuery && matchCat && matchStock ? "" : "none";
  });
}

function resetProdukFilter() {
  const search = document.getElementById("produk-table-search");
  const cat = document.getElementById("produk-filter-category");
  const stock = document.getElementById("produk-filter-stock");
  if (search) search.value = "";
  if (cat) cat.value = "all";
  if (stock) stock.value = "all";
  filterProdukTable();
}

function exportDataProduk() {
  alert("Fitur ekspor inventaris produk (.xlsx)...");
}

/* ============================================================
 * 5. MENU: OWNER - KATEGORI PRODUK
 * ============================================================ */
function openAddCategoryModal() {
  const form = document.getElementById("form-kategori");
  if (form) form.reset();
  const label = document.getElementById("modalFormKategoriLabel");
  if (label) label.textContent = "Tambah Kategori Baru";

  const action = document.getElementById("cat-action");
  if (action) action.value = "tambah";
  const catId = document.getElementById("cat-id");
  if (catId) catId.value = "";

  const modalEl = document.getElementById("modalFormKategori");
  if (modalEl && window.bootstrap) {
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
  }
}

function editCategory(c) {
  const label = document.getElementById("modalFormKategoriLabel");
  if (label) label.textContent = "Edit Data Kategori";

  document.getElementById("cat-action").value = "edit";
  document.getElementById("cat-id").value = c.CategoryID;
  document.getElementById("cat-name").value = c.CategoryName;

  const modalEl = document.getElementById("modalFormKategori");
  if (modalEl && window.bootstrap) {
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
  }
}

function deleteCategory(id, name) {
  if (confirm(`Apakah Anda yakin ingin menghapus kategori "${name}"?`)) {
    const deleteId = document.getElementById("delete-category-id");
    const formDelete = document.getElementById("form-delete-kategori");
    if (deleteId && formDelete) {
      deleteId.value = id;
      formDelete.submit();
    }
  }
}

function filterKategoriTable() {
  const query = (
    document.getElementById("kategori-table-search")?.value || ""
  ).toLowerCase();
  const rows = document.querySelectorAll(".kategori-row");
  rows.forEach(function (row) {
    const name = (row.getAttribute("data-name") || "").toLowerCase();
    row.style.display = name.includes(query) ? "" : "none";
  });
}
