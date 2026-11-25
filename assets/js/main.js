/* ============================================================
 * WARUNGKU - SCRIPT UTAMA (MAIN.JS)
 * ============================================================
 * File ini memuat seluruh interaksi JavaScript untuk aplikasi
 * WarungKu yang dikelompokkan berdasarkan menu/modul.
 * ============================================================ */

/* ============================================================
 * GLOBAL — LAYOUT & INISIALISASI
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
 * MENU — AUTH (LOGIN & REGISTER)
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
 * MENU — OWNER: MANAJEMEN AKUN KASIR
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

  new bootstrap.Modal(document.getElementById("modalEditKasir")).show();
}

function deleteCashier(id, name) {
  openDeleteModal({
    action: "/owner/akun-kasir",
    inputName: "user_id",
    id,
    name,
    title: "Hapus Akun Kasir?",
    typeLabel: "Akun Staf Kasir",
    note: "Riwayat transaksi kasir ini tetap tersimpan aman di sistem.",
  });
}

function filterAkunTable() {
  const query = (
    document.getElementById("akun-table-search")?.value || ""
  ).toLowerCase();
  const statusFilter =
    document.getElementById("akun-filter-status")?.value || "all";

  const rows = document.querySelectorAll(".akun-row");
  rows.forEach(function (row) {
    const name = (row.getAttribute("data-name") || "").toLowerCase();
    const status = row.getAttribute("data-status") || "";

    const matchName = name.includes(query);
    const matchStatus = statusFilter === "all" || status === statusFilter;
    row.dataset.filtered = matchName && matchStatus ? "1" : "0";
  });
  paginateTable(rows[0]?.closest("tbody"), 1);
}

/* ============================================================
 * MENU — OWNER: DAFTAR PRODUK
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
  document.getElementById("prod-status-group")?.classList.add("d-none");

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
  const prodStatus = document.getElementById("prod-is-active");
  if (prodStatus) prodStatus.value = p.IsActive ?? 1;
  document.getElementById("prod-status-group")?.classList.remove("d-none");

  const modalEl = document.getElementById("modalFormProduk");
  if (modalEl && window.bootstrap) {
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
  }
}

function openDeleteModal({ action, inputName, id, name, title, typeLabel, note }) {
  const form = document.getElementById("delete-modal-form");
  const idInput = document.getElementById("delete-modal-id");
  const titleEl = document.getElementById("delete-modal-title");
  const nameEl = document.getElementById("delete-modal-item-name");
  const typeEl = document.getElementById("delete-modal-item-type");
  const noteEl = document.getElementById("delete-modal-note");

  if (!form || !idInput) return;

  form.action = action;
  idInput.name = inputName;
  idInput.value = id;
  if (titleEl) titleEl.textContent = title;
  if (nameEl) nameEl.textContent = name;
  if (typeEl) typeEl.textContent = typeLabel;
  if (noteEl) noteEl.textContent = note;

  const modalEl = document.getElementById("modalHapusGlobal");
  if (modalEl && window.bootstrap) {
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
  }
}

function deleteProduct(id, name) {
  openDeleteModal({
    action: "/owner/produk",
    inputName: "product_id",
    id,
    name,
    title: "Hapus Data Produk?",
    typeLabel: "Katalog Produk",
    note: "Riwayat transaksi dan restock produk ini tetap tersimpan di sistem.",
  });
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
    const minStock = parseInt(row.getAttribute("data-min-stock") || "10", 10);

    const matchQuery = name.includes(query);
    const matchCat = cat === "all" || category === cat;
    let matchStock = true;
    if (stockFilter === "in_stock") matchStock = stock > minStock;
    if (stockFilter === "low_stock") matchStock = stock <= minStock;

    row.dataset.filtered = matchQuery && matchCat && matchStock ? "1" : "0";
  });
  paginateTable(rows[0]?.closest("tbody"), 1);
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

function exportTableCsv(filename) {
  const table = document.querySelector(".table-modern");
  if (!table) return;
  const rows = Array.from(table.querySelectorAll("tr")).filter(
    (r) => r.dataset.filtered !== "0"
  );
  const csv = rows
    .map((r) =>
      Array.from(r.querySelectorAll("th, td"))
        .map((c) => '"' + c.innerText.replace(/\s+/g, " ").trim().replace(/"/g, '""') + '"')
        .join(",")
    )
    .join("\n");
  const blob = new Blob(["\uFEFF" + csv], { type: "text/csv;charset=utf-8;" });
  const a = document.createElement("a");
  a.href = URL.createObjectURL(blob);
  a.download = filename;
  a.click();
}

function exportDataProduk() {
  exportTableCsv("laporan-warungku.csv");
}

function exportDataRestock() {
  exportTableCsv("riwayat-restock.csv");
}

/* ============================================================
 * MENU — OWNER: KATEGORI PRODUK
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
  document.getElementById("cat-status-group")?.classList.add("d-none");

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
  const catStatus = document.getElementById("cat-is-active");
  if (catStatus) catStatus.value = c.IsActive ?? 1;
  document.getElementById("cat-status-group")?.classList.remove("d-none");

  const modalEl = document.getElementById("modalFormKategori");
  if (modalEl && window.bootstrap) {
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
  }
}

function deleteCategory(id, name) {
  openDeleteModal({
    action: "/owner/kategori",
    inputName: "category_id",
    id,
    name,
    title: "Hapus Kategori?",
    typeLabel: "Kategori Produk",
    note: "Data riwayat produk yang pernah memakai kategori ini tetap aman.",
  });
}

function filterKategoriTable() {
  const query = (
    document.getElementById("kategori-table-search")?.value || ""
  ).toLowerCase();
  const rows = document.querySelectorAll(".kategori-row");
  rows.forEach(function (row) {
    const name = (row.getAttribute("data-name") || "").toLowerCase();
    row.dataset.filtered = name.includes(query) ? "1" : "0";
  });
  paginateTable(rows[0]?.closest("tbody"), 1);
}


/* ============================================================
 * MENU — OWNER: RESTOCK BARANG
 * ============================================================ */
function filterRestockTable() {
  const query = (
    document.getElementById("restock-table-search")?.value || ""
  ).toLowerCase();
  const periode =
    document.getElementById("restock-filter-periode")?.value || "all";
  const now = new Date();
  const todayStr = now.toISOString().slice(0, 10);

  const rows = document.querySelectorAll(".restock-row");
  rows.forEach(function (row) {
    const produk = (row.getAttribute("data-produk") || "").toLowerCase();
    const dateStr = row.getAttribute("data-date") || "";
    const matchProduk = produk.includes(query);
    let matchPeriode = true;

    if (periode === "today") {
      matchPeriode = dateStr === todayStr;
    } else if (periode === "7days" && dateStr) {
      const diffDays = (now - new Date(dateStr)) / 86400000;
      matchPeriode = diffDays >= 0 && diffDays <= 7;
    } else if (periode === "30days" && dateStr) {
      matchPeriode = dateStr.slice(0, 7) === todayStr.slice(0, 7);
    }

    row.dataset.filtered = matchProduk && matchPeriode ? "1" : "0";
  });
  paginateTable(rows[0]?.closest("tbody"), 1);
}

function resetRestockFilter() {
  const search = document.getElementById("restock-table-search");
  const periode = document.getElementById("restock-filter-periode");
  if (search) search.value = "";
  if (periode) periode.value = "all";
  filterRestockTable();
}


/* ============================================================
 * SHARED — RIWAYAT TRANSAKSI (FILTER & MODAL DETAIL NOTA)
 * ============================================================ */
function filterTrxTable() {
  const query = (
    document.getElementById("trx-table-search")?.value || ""
  ).toLowerCase();
  const method = document.getElementById("trx-filter-method")?.value || "all";

  const rows = document.querySelectorAll(".trx-row");
  rows.forEach(function (row) {
    const faktur = (row.getAttribute("data-faktur") || "").toLowerCase();
    const m = row.getAttribute("data-method") || "";
    const matchFaktur = faktur.includes(query);
    const matchMethod = method === "all" || m === method;
    row.dataset.filtered = matchFaktur && matchMethod ? "1" : "0";
  });
  paginateTable(rows[0]?.closest("tbody"), 1);
}

function openTrxDetail(trx) {
  const fmt = (n) => "Rp " + Math.round(Number(n) || 0).toLocaleString("id-ID");
  document.getElementById("detail-trx-id").textContent = "#" + trx.TransactionID;
  document.getElementById("detail-trx-meta").textContent =
    (trx.TransactionDate || "") + " • Kasir: " + (trx.CashierName || "-");
  document.getElementById("detail-trx-method").textContent = trx.PaymentMethod;
  document.getElementById("detail-trx-total").textContent = fmt(trx.TotalAmount);
  document.getElementById("detail-trx-paid").textContent = fmt(trx.AmountPaid);
  document.getElementById("detail-trx-change").textContent = fmt(trx.ChangeAmount);

  const wrap = document.getElementById("detail-trx-items");
  wrap.innerHTML = "";
  (trx.Items || []).forEach(function (it) {
    const div = document.createElement("div");
    div.className = "d-flex justify-content-between align-items-center small";
    div.innerHTML =
      "<div><span class=\"fw-semibold text-dark d-block\"></span><small class=\"text-muted\"></small></div><span class=\"fw-semibold text-dark\"></span>";
    div.querySelector("span").textContent = it.ProductName || it.ProductID;
    div.querySelector("small").textContent =
      it.Quantity + " " + (it.Unit || "pcs") + " × " + fmt(it.SellingPriceAtTransaction);
    div.lastElementChild.textContent = fmt(it.Subtotal);
    wrap.appendChild(div);
  });

  const modalEl = document.getElementById("modalDetailTrx");
  if (modalEl && window.bootstrap) {
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
  }
}


/* ============================================================
 * SHARED — KASIR POS (KERANJANG & KALKULATOR PEMBAYARAN)
 * ============================================================ */
let posCart = [];

function filterPosProducts() {
  const q = (
    document.getElementById("pos-product-search")?.value || ""
  ).toLowerCase();
  const cat = document.getElementById("pos-filter-category")?.value || "all";

  document.querySelectorAll(".pos-product-row").forEach(function (row) {
    const name = row.getAttribute("data-name") || "";
    const rowCat = row.getAttribute("data-category") || "";
    const matchName = name.includes(q);
    const matchCat = cat === "all" || rowCat === cat;
    row.style.display = matchName && matchCat ? "" : "none";
  });
}

function posShowStockLimitModal(item) {
  const msgEl = document.getElementById("pos-stock-limit-msg");
  const modalEl = document.getElementById("modalPosStockLimit");
  if (msgEl && modalEl && window.bootstrap) {
    msgEl.textContent = `Stok "${item.name}" maksimal ${item.stock} ${item.unit}.`;
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
  }
}

function posAddToCart(item) {
  const existing = posCart.find((c) => c.id === item.id);
  if (existing) {
    if (existing.qty >= item.stock) {
      posShowStockLimitModal(item);
      return;
    }
    existing.qty++;
  } else {
    posCart.push({ ...item, qty: 1 });
  }
  renderPosCart();
}

function posUpdateQty(id, delta) {
  const idx = posCart.findIndex((c) => c.id === id);
  if (idx === -1) return;
  const next = posCart[idx].qty + delta;
  if (next <= 0) {
    posCart.splice(idx, 1);
  } else if (next > posCart[idx].stock) {
    posShowStockLimitModal(posCart[idx]);
    return;
  } else {
    posCart[idx].qty = next;
  }
  renderPosCart();
}

function posClearCart() {
  posCart = [];
  const paidEl = document.getElementById("pos-amount-paid");
  if (paidEl) paidEl.value = "";
  renderPosCart();
}

function posSetPayment(method) {
  const input = document.getElementById("pos-payment-input");
  const btnCash = document.getElementById("btn-pay-cash");
  const btnQris = document.getElementById("btn-pay-qris");
  const cashBox = document.getElementById("pos-cash-box");
  if (!input) return;

  input.value = method;
  const isQris = method === "qris";
  if (btnCash && btnQris) {
    btnCash.className = isQris
      ? "btn btn-outline-secondary bg-white fw-semibold py-2 d-flex align-items-center justify-content-center gap-1"
      : "btn btn-primary fw-semibold py-2 d-flex align-items-center justify-content-center gap-1";
    btnQris.className = isQris
      ? "btn btn-primary fw-semibold py-2 d-flex align-items-center justify-content-center gap-1"
      : "btn btn-outline-secondary bg-white fw-semibold py-2 d-flex align-items-center justify-content-center gap-1";
  }
  if (cashBox) cashBox.classList.toggle("d-none", isQris);
  posCalcChange();
}

function posGetTotal() {
  return posCart.reduce((sum, c) => sum + c.price * c.qty, 0);
}

function posParseAmount(val) {
  return Number(String(val || "").replace(/\D/g, "")) || 0;
}

function posFormatCashInput(el) {
  const num = posParseAmount(el.value);
  el.value = num > 0 ? num.toLocaleString("id-ID") : "";
  posCalcChange();
}

function posQuickCash(val) {
  const paidEl = document.getElementById("pos-amount-paid");
  if (!paidEl) return;
  const num = val === "exact" ? posGetTotal() : Number(val) || 0;
  paidEl.value = num > 0 ? num.toLocaleString("id-ID") : "";
  posCalcChange();
}

function renderPosCart() {
  const listEl = document.getElementById("pos-cart-list");
  const countEl = document.getElementById("pos-cart-count");
  const totalEl = document.getElementById("pos-total-display");
  const inputEl = document.getElementById("pos-cart-input");
  const clearBtn = document.getElementById("pos-btn-clear");
  if (!listEl) return;

  const fmt = (n) => "Rp " + Math.round(n).toLocaleString("id-ID");
  const totalQty = posCart.reduce((s, c) => s + c.qty, 0);
  const totalPrice = posGetTotal();

  countEl.textContent = `${totalQty} item`;
  totalEl.textContent = fmt(totalPrice);
  inputEl.value = JSON.stringify(posCart.map((c) => ({ id: c.id, qty: c.qty })));
  if (clearBtn) clearBtn.classList.toggle("d-none", posCart.length === 0);

  if (posCart.length === 0) {
    listEl.innerHTML =
      '<div class="text-center text-muted py-5 small"><i data-lucide="shopping-bag" class="icon-lg d-block mx-auto mb-2 opacity-50"></i>Belum ada barang dipilih</div>';
    syncPosCardBadges();
    posCalcChange();
    return;
  }

  listEl.innerHTML = "";
  posCart.forEach(function (c, i) {
    const row = document.createElement("div");
    row.className = "d-flex align-items-center justify-content-between gap-2 py-2" + (i < posCart.length - 1 ? " border-bottom" : "");
    row.innerHTML = `
      <div class="overflow-hidden flex-grow-1">
        <div class="fw-semibold text-dark small text-truncate"></div>
        <small class="text-muted" style="font-size:0.725rem;"></small>
      </div>
      <div class="d-flex align-items-center gap-2 flex-shrink-0">
        <div class="pos-qty-box">
          <button type="button" class="pos-qty-btn">−</button>
          <span class="px-2 small fw-semibold text-dark">${c.qty}</span>
          <button type="button" class="pos-qty-btn">+</button>
        </div>
        <span class="fw-bold text-dark small text-end" style="min-width:68px;">${fmt(c.price * c.qty)}</span>
      </div>
    `;
    row.querySelector(".fw-semibold").textContent = c.name;
    row.querySelector("small").textContent = `${fmt(c.price)} / ${c.unit}`;
    const btns = row.querySelectorAll("button");
    btns[0].onclick = () => posUpdateQty(c.id, -1);
    btns[1].onclick = () => posUpdateQty(c.id, 1);
    listEl.appendChild(row);
  });

  syncPosCardBadges();
  posCalcChange();
}

function syncPosCardBadges() {
  document.querySelectorAll(".pos-item-card").forEach(function (card) {
    const found = posCart.find((c) => c.id === card.dataset.productId);
    const add = card.querySelector(".pos-item-add");
    card.classList.toggle("in-cart", !!found);
    if (add) add.innerHTML = found ? found.qty : '<i data-lucide="plus" class="icon-xs"></i>';
  });
  if (window.lucide) lucide.createIcons();
}

function posCalcChange() {
  const total = posGetTotal();
  const method = document.getElementById("pos-payment-input")?.value || "cash";
  const paidVal = posParseAmount(document.getElementById("pos-amount-paid")?.value);
  const changeEl = document.getElementById("pos-change-display");
  const submitBtn = document.getElementById("pos-submit-btn");
  if (!changeEl || !submitBtn) return;

  const fmt = (n) => "Rp " + Math.round(n).toLocaleString("id-ID");

  if (total <= 0) {
    changeEl.textContent = "Rp 0";
    changeEl.className = "fw-semibold text-muted small";
    submitBtn.disabled = true;
    return;
  }

  if (method === "qris") {
    changeEl.textContent = "Rp 0 (QRIS)";
    changeEl.className = "fw-semibold text-primary small";
    submitBtn.disabled = false;
    return;
  }

  if (paidVal <= 0) {
    changeEl.textContent = "Rp 0";
    changeEl.className = "fw-semibold text-muted small";
    submitBtn.disabled = true;
    return;
  }

  const diff = paidVal - total;
  if (diff < 0) {
    changeEl.textContent = "Kurang " + fmt(Math.abs(diff));
    changeEl.className = "fw-semibold text-danger small";
    submitBtn.disabled = true;
  } else {
    changeEl.textContent = fmt(diff);
    changeEl.className = "fw-bold text-success small";
    submitBtn.disabled = false;
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const receiptModal = document.getElementById("modalPosReceipt");
  if (receiptModal && window.bootstrap) {
    new bootstrap.Modal(receiptModal).show();
  }
});


/* ============================================================
 * GLOBAL — PAGINATION TABEL OTOMATIS (10 BARIS / HALAMAN)
 * ============================================================ */
function paginateTable(tbody, page = 1, perPage = 10) {
  if (!tbody) return;
  const card = tbody.closest(".card-modern");
  const footer = card?.querySelector(".border-top.rounded-bottom");
  if (!footer) return;

  const allRows = Array.from(tbody.querySelectorAll("tr"));
  const matched = allRows.filter((r) => r.dataset.filtered !== "0");
  const totalPages = Math.ceil(matched.length / perPage);
  const current = Math.max(1, Math.min(page, totalPages || 1));

  allRows.forEach((r) => (r.style.display = "none"));
  matched
    .slice((current - 1) * perPage, current * perPage)
    .forEach((r) => (r.style.display = ""));

  let nav = footer.querySelector(".table-pagination-nav");
  if (totalPages <= 1) {
    if (nav) nav.remove();
    return;
  }

  if (!nav) {
    nav = document.createElement("nav");
    nav.className = "table-pagination-nav";
    footer.appendChild(nav);
  }

  const ul = document.createElement("ul");
  ul.className = "pagination pagination-sm mb-0";
  const addBtn = (label, target, active = false, disabled = false) => {
    const li = document.createElement("li");
    li.className = `page-item${active ? " active" : ""}${disabled ? " disabled" : ""}`;
    const a = document.createElement("a");
    a.className = "page-link";
    a.href = "#";
    a.textContent = label;
    a.onclick = (e) => {
      e.preventDefault();
      if (!disabled && !active) paginateTable(tbody, target, perPage);
    };
    li.appendChild(a);
    ul.appendChild(li);
  };

  addBtn("‹", current - 1, false, current === 1);
  for (let p = 1; p <= totalPages; p++) addBtn(String(p), p, p === current);
  addBtn("›", current + 1, false, current === totalPages);
  nav.innerHTML = "";
  nav.appendChild(ul);
}

document.addEventListener("DOMContentLoaded", function () {
  document
    .querySelectorAll(".card-modern .table-modern tbody")
    .forEach((tbody) => paginateTable(tbody, 1));
});
