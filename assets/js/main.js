/* ============================================================
 * WARUNGKU — SCRIPT UTAMA (MAIN.JS)
 * ============================================================
 * DAFTAR ISI:
 * - GLOBAL: LAYOUT, UTILITAS & KOMPONEN UMUM
 *   - Sidebar, Modal & Format Rupiah
 *   - Filter & Pagination Tabel Otomatis
 *   - Modal Hapus Global & Export CSV
 * - MENU: AUTH (PASSWORD TOGGLE)
 * - MENU: OWNER — MANAJEMEN AKUN KASIR
 * - MENU: OWNER — DAFTAR PRODUK
 * - MENU: OWNER — KATEGORI PRODUK
 * - MENU: OWNER — RESTOCK BARANG
 * - SHARED: RIWAYAT TRANSAKSI
 * - SHARED: KASIR POS (KERANJANG & PEMBAYARAN)
 * - GLOBAL: INISIALISASI DOM
 * ============================================================ */

/* ============================================================
 * GLOBAL — LAYOUT, UTILITAS & KOMPONEN UMUM
 * ============================================================ */
function toggleSidebar() {
  const wrapper = document.getElementById("wrapper");
  if (wrapper) wrapper.classList.toggle("toggled");
}

function showModal(id) {
  const el = document.getElementById(id);
  if (el && window.bootstrap) {
    bootstrap.Modal.getOrCreateInstance(el).show();
  }
}

function formatRupiah(n) {
  return "Rp " + Math.round(Number(n) || 0).toLocaleString("id-ID");
}

function parseRupiah(text) {
  const digits = String(text || "").replace(/[^\d-]/g, "");
  return Number(digits) || 0;
}

function applyTableFilter(rowSelector, matchFn) {
  const rows = document.querySelectorAll(rowSelector);
  rows.forEach((row) => {
    row.dataset.filtered = matchFn(row) ? "1" : "0";
  });
  paginateTable(rows[0]?.closest("tbody"), 1);
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

  showModal("modalHapusGlobal");
}

function exportTableCsv(filename) {
  const table = document.querySelector(".table-modern, .table-responsive table");
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

function paginateTable(tbody, page = 1, perPage = 10) {
  if (!tbody) return;
  const tableWrap = tbody.closest(".table-responsive");
  const nextEl = tableWrap?.nextElementSibling;
  const footer = nextEl?.classList.contains("border-top") ? nextEl : null;

  tbody.querySelectorAll(".table-empty-filter-row").forEach((r) => r.remove());

  const allRows = Array.from(tbody.querySelectorAll("tr")).filter(
    (r) => !r.querySelector("td[colspan]")
  );
  if (allRows.length === 0) return;

  const matched = allRows.filter((r) => r.dataset.filtered !== "0");
  const totalPages = Math.ceil(matched.length / perPage);
  const current = Math.max(1, Math.min(page, totalPages || 1));

  allRows.forEach((r) => (r.style.display = "none"));
  const visible = matched.slice((current - 1) * perPage, current * perPage);
  visible.forEach((r) => (r.style.display = ""));

  if (matched.length === 0) {
    const colCount =
      tbody.closest("table")?.querySelectorAll("thead th").length || 1;
    const emptyTr = document.createElement("tr");
    emptyTr.className = "table-empty-filter-row";
    emptyTr.innerHTML = `<td colspan="${colCount}" class="text-center text-secondary py-5"><div class="small">Tidak ada data yang cocok dengan filter.</div></td>`;
    tbody.appendChild(emptyTr);
  }

  if (!footer) return;

  const countSmall = Array.from(footer.querySelectorAll("small")).find((el) =>
    el.textContent.includes("Menampilkan")
  );
  const countStrong = countSmall?.querySelector("strong");
  if (countStrong) {
    countStrong.textContent =
      totalPages > 1
        ? `${visible.length} dari ${matched.length}`
        : String(matched.length);
  }

  const headers = Array.from(
    tbody.closest("table")?.querySelectorAll("thead th") || []
  ).map((th) => th.textContent.trim().toLowerCase());

  const updateFooterSum = (labelText, colKeyword) => {
    const sumSmall = Array.from(footer.querySelectorAll("small")).find((el) =>
      el.textContent.includes(labelText)
    );
    const sumStrong = sumSmall?.querySelector("strong");
    const colIdx = headers.findIndex((h) => h.includes(colKeyword));
    if (!sumStrong || colIdx === -1) return;
    const total = matched.reduce(
      (acc, r) => acc + parseRupiah(r.cells[colIdx]?.textContent),
      0
    );
    sumStrong.textContent = formatRupiah(total);
  };

  updateFooterSum("Total Valuasi", "valuasi aset");
  updateFooterSum("Total Omzet", "total omzet");

  let nav = footer.querySelector(".table-pagination-nav");
  if (totalPages <= 1) {
    if (nav) nav.remove();
    return;
  }

  if (!nav) {
    nav = document.createElement("nav");
    nav.className =
      "table-pagination-nav" +
      (footer.classList.contains("d-flex") ? "" : " mt-2");
    footer.appendChild(nav);
  }

  const ul = document.createElement("ul");
  ul.className = "pagination pagination-sm mb-0 justify-content-center";
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

/* ============================================================
 * MENU — AUTH (PASSWORD TOGGLE)
 * ============================================================ */
function togglePasswordVisibility(input, btn) {
  if (!input || !btn) return;
  const show = input.type === "password";
  input.type = show ? "text" : "password";
  btn.innerHTML =
    '<i data-lucide="' + (show ? "eye-off" : "eye") + '" class="icon-xs"></i>';
  if (window.lucide) lucide.createIcons();
}

/* ============================================================
 * MENU — OWNER: MANAJEMEN AKUN KASIR
 * ============================================================ */
function openAddCashierModal() {
  const form = document.getElementById("form-kasir");
  if (form) form.reset();

  document.getElementById("kasir-action").value = "tambah";
  document.getElementById("kasir-id").value = "";
  document.getElementById("modalFormKasirLabel").textContent = "Tambah Akun Kasir Baru";
  document.getElementById("kasir-modal-subtitle").textContent = "Buat kredensial login untuk staf kasir";
  document.getElementById("kasir-submit-text").textContent = "Simpan Akun";
  document.getElementById("kasir-phone").required = true;
  document.getElementById("kasir-phone-req")?.classList.remove("d-none");
  document.getElementById("kasir-email-hint")?.classList.remove("d-none");

  const iconWrap = document.getElementById("kasir-modal-icon");
  if (iconWrap) {
    iconWrap.innerHTML = '<i data-lucide="user-plus" class="icon-sm"></i>';
    if (window.lucide) lucide.createIcons();
  }

  showModal("modalFormKasir");
}

function editCashier(user) {
  document.getElementById("kasir-action").value = "edit";
  document.getElementById("kasir-id").value = user.UserID;
  document.getElementById("kasir-fullname").value = user.Fullname;
  document.getElementById("kasir-username").value = user.Username;
  document.getElementById("kasir-email").value = user.Email || "";
  document.getElementById("kasir-phone").value = user.PhoneNumber || "";
  document.getElementById("modalFormKasirLabel").textContent = "Perbarui Akun Kasir";
  document.getElementById("kasir-modal-subtitle").textContent = "Edit data profil akun kasir";
  document.getElementById("kasir-submit-text").textContent = "Simpan Perubahan";
  document.getElementById("kasir-phone").required = false;
  document.getElementById("kasir-phone-req")?.classList.add("d-none");
  document.getElementById("kasir-email-hint")?.classList.add("d-none");

  const iconWrap = document.getElementById("kasir-modal-icon");
  if (iconWrap) {
    iconWrap.innerHTML = '<i data-lucide="user-cog" class="icon-sm"></i>';
    if (window.lucide) lucide.createIcons();
  }

  showModal("modalFormKasir");
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
  const query = (document.getElementById("akun-table-search")?.value || "").toLowerCase();
  const statusFilter = document.getElementById("akun-filter-status")?.value || "all";

  applyTableFilter(".akun-row", (row) => {
    const name = (row.getAttribute("data-name") || "").toLowerCase();
    const status = row.getAttribute("data-status") || "";
    return name.includes(query) && (statusFilter === "all" || status === statusFilter);
  });
}

/* ============================================================
 * MENU — OWNER: DAFTAR PRODUK
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

  showModal("modalFormProduk");
}

function editProduct(p) {
  const label = document.getElementById("modalFormProdukLabel");
  if (label) label.textContent = "Edit Data Produk";

  document.getElementById("prod-action").value = "edit";
  document.getElementById("prod-id").value = p.ProductID;
  document.getElementById("prod-name").value = p.ProductName;
  document.getElementById("prod-category").value = p.CategoryID;
  document.getElementById("prod-unit").value = p.Unit;
  document.getElementById("prod-purchase-price").value = Math.round(p.PurchasePrice);
  document.getElementById("prod-selling-price").value = Math.round(p.SellingPrice);
  document.getElementById("prod-stock").value = p.Stock;
  document.getElementById("prod-min-stock").value = p.MinimumStock;
  document.getElementById("prod-location").value = p.Location || "";
  const prodStatus = document.getElementById("prod-is-active");
  if (prodStatus) prodStatus.value = p.IsActive ?? 1;
  document.getElementById("prod-status-group")?.classList.remove("d-none");

  showModal("modalFormProduk");
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
  const query = (document.getElementById("produk-table-search")?.value || "").toLowerCase();
  const cat = document.getElementById("produk-filter-category")?.value || "all";
  const stockFilter = document.getElementById("produk-filter-stock")?.value || "all";

  applyTableFilter(".produk-row", (row) => {
    const name = (row.getAttribute("data-name") || "").toLowerCase();
    const category = row.getAttribute("data-category") || "";
    const stock = parseInt(row.getAttribute("data-stock") || "0", 10);
    const minStock = parseInt(row.getAttribute("data-min-stock") || "10", 10);

    let matchStock = true;
    if (stockFilter === "in_stock") matchStock = stock > minStock;
    if (stockFilter === "low_stock") matchStock = stock <= minStock;

    return name.includes(query) && (cat === "all" || category === cat) && matchStock;
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

  showModal("modalFormKategori");
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

  showModal("modalFormKategori");
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
  const query = (document.getElementById("kategori-table-search")?.value || "").toLowerCase();

  applyTableFilter(".kategori-row", (row) => {
    const name = (row.getAttribute("data-name") || "").toLowerCase();
    return name.includes(query);
  });
}

/* ============================================================
 * MENU — OWNER: RESTOCK BARANG
 * ============================================================ */
function filterRestockTable() {
  const query = (document.getElementById("restock-table-search")?.value || "").toLowerCase();
  const periode = document.getElementById("restock-filter-periode")?.value || "all";
  const now = new Date();
  const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, "0")}-${String(now.getDate()).padStart(2, "0")}`;

  applyTableFilter(".restock-row", (row) => {
    const produk = (row.getAttribute("data-produk") || "").toLowerCase();
    const dateStr = row.getAttribute("data-date") || "";

    let matchPeriode = true;
    if (periode === "today") {
      matchPeriode = dateStr === todayStr;
    } else if (periode === "7days" && dateStr) {
      const diffDays = (now - new Date(dateStr)) / 86400000;
      matchPeriode = diffDays >= 0 && diffDays <= 7;
    } else if (periode === "30days" && dateStr) {
      matchPeriode = dateStr.slice(0, 7) === todayStr.slice(0, 7);
    }

    return produk.includes(query) && matchPeriode;
  });
}

function resetRestockFilter() {
  const search = document.getElementById("restock-table-search");
  const periode = document.getElementById("restock-filter-periode");
  if (search) search.value = "";
  if (periode) periode.value = "all";
  filterRestockTable();
}

/* ============================================================
 * SHARED — RIWAYAT TRANSAKSI
 * ============================================================ */
function filterTrxTable() {
  const query = (document.getElementById("trx-table-search")?.value || "").toLowerCase();
  const method = document.getElementById("trx-filter-method")?.value || "all";

  applyTableFilter(".trx-row", (row) => {
    const faktur = (row.getAttribute("data-faktur") || "").toLowerCase();
    const rowMethod = row.getAttribute("data-method") || "";
    return faktur.includes(query) && (method === "all" || rowMethod === method);
  });
}

function openTrxDetail(trx) {
  document.getElementById("detail-trx-id").textContent = "#" + trx.TransactionID;
  document.getElementById("detail-trx-meta").textContent =
    (trx.TransactionDate || "") + " • Kasir: " + (trx.CashierName || "-");
  document.getElementById("detail-trx-method").textContent = trx.PaymentMethod;
  document.getElementById("detail-trx-total").textContent = formatRupiah(trx.TotalAmount);
  document.getElementById("detail-trx-paid").textContent = formatRupiah(trx.AmountPaid);
  document.getElementById("detail-trx-change").textContent = formatRupiah(trx.ChangeAmount);

  const wrap = document.getElementById("detail-trx-items");
  wrap.innerHTML = "";
  (trx.Items || []).forEach((it) => {
    const div = document.createElement("div");
    div.className = "d-flex justify-content-between align-items-center small";
    div.innerHTML = `
      <div>
        <span class="fw-semibold text-dark d-block"></span>
        <small class="text-muted"></small>
      </div>
      <span class="fw-semibold text-dark"></span>
    `;
    div.querySelector("span").textContent = it.ProductName || it.ProductID;
    div.querySelector("small").textContent =
      `${it.Quantity} ${it.Unit || "pcs"} × ${formatRupiah(it.SellingPriceAtTransaction)}`;
    div.lastElementChild.textContent = formatRupiah(it.Subtotal);
    wrap.appendChild(div);
  });

  showModal("modalDetailTrx");
}

/* ============================================================
 * SHARED — KASIR POS (KERANJANG & PEMBAYARAN)
 * ============================================================ */
let posCart = [];

function filterPosProducts() {
  const q = (document.getElementById("pos-product-search")?.value || "").toLowerCase();
  const cat = document.getElementById("pos-filter-category")?.value || "all";

  document.querySelectorAll(".pos-product-row").forEach((row) => {
    const name = row.getAttribute("data-name") || "";
    const rowCat = row.getAttribute("data-category") || "";
    row.style.display = name.includes(q) && (cat === "all" || rowCat === cat) ? "" : "none";
  });
}

function posShowStockLimitModal(item) {
  const msgEl = document.getElementById("pos-stock-limit-msg");
  if (msgEl) {
    msgEl.textContent = `Stok "${item.name}" maksimal ${item.stock} ${item.unit}.`;
  }
  showModal("modalPosStockLimit");
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

  const totalQty = posCart.reduce((s, c) => s + c.qty, 0);
  const totalPrice = posGetTotal();

  countEl.textContent = `${totalQty} item`;
  totalEl.textContent = formatRupiah(totalPrice);
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
  posCart.forEach((c, i) => {
    const row = document.createElement("div");
    row.className =
      "d-flex align-items-center justify-content-between gap-2 py-2" +
      (i < posCart.length - 1 ? " border-bottom" : "");
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
        <span class="fw-bold text-dark small text-end" style="min-width:68px;">${formatRupiah(c.price * c.qty)}</span>
      </div>
    `;
    row.querySelector(".fw-semibold").textContent = c.name;
    row.querySelector("small").textContent = `${formatRupiah(c.price)} / ${c.unit}`;
    const btns = row.querySelectorAll("button");
    btns[0].onclick = () => posUpdateQty(c.id, -1);
    btns[1].onclick = () => posUpdateQty(c.id, 1);
    listEl.appendChild(row);
  });

  syncPosCardBadges();
  posCalcChange();
}

function syncPosCardBadges() {
  document.querySelectorAll(".pos-item-card").forEach((card) => {
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
    changeEl.textContent = "Kurang " + formatRupiah(Math.abs(diff));
    changeEl.className = "fw-semibold text-danger small";
    submitBtn.disabled = true;
  } else {
    changeEl.textContent = formatRupiah(diff);
    changeEl.className = "fw-bold text-success small";
    submitBtn.disabled = false;
  }
}

/* ============================================================
 * GLOBAL — INISIALISASI DOM
 * ============================================================ */
// Paksa reload jika halaman dibuka ulang dari cache tombol Back/Forward browser
window.addEventListener("pageshow", (e) => {
  if (e.persisted) window.location.reload();
});

document.addEventListener("DOMContentLoaded", () => {
  // Cegah perubahan angka tak sengaja lewat scroll pada input number
  document.querySelectorAll("input[type=number]").forEach((el) => {
    el.addEventListener("wheel", () => el.blur());
  });

  // Pasang tombol show/hide otomatis ke semua input password
  document.querySelectorAll('input[type="password"]').forEach((input) => {
    if (input.parentElement?.querySelector(".btn-toggle-pass")) return;
    const wrap = document.createElement("div");
    wrap.className = "position-relative";
    input.parentNode.insertBefore(wrap, input);
    wrap.appendChild(input);
    input.classList.add("pe-5");

    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = "btn-toggle-pass";
    btn.title = "Tampilkan/Sembunyikan Password";
    btn.innerHTML = '<i data-lucide="eye" class="icon-xs"></i>';
    btn.addEventListener("click", () => togglePasswordVisibility(input, btn));
    wrap.appendChild(btn);
  });

  if (window.lucide) lucide.createIcons();

  // Pagination otomatis untuk tabel yang memiliki footer (.border-top)
  document.querySelectorAll(".table-responsive table tbody").forEach((tbody) => {
    const footer = tbody.closest(".table-responsive")?.nextElementSibling;
    if (footer?.classList.contains("border-top")) paginateTable(tbody, 1);
  });

  // Tutup alert notifikasi otomatis setelah 4 detik
  document.querySelectorAll(".alert-auto-dismiss").forEach((alertEl) => {
    setTimeout(() => {
      if (window.bootstrap?.Alert) {
        bootstrap.Alert.getOrCreateInstance(alertEl).close();
      }
    }, 4000);
  });

  // Auto-open modal (reset password & struk POS)
  const resetUserId = document.getElementById("reset_user_id");
  const modalReset = document.getElementById("modalResetPassword");

  if (resetUserId?.value) {
    showModal("modalNewPassword");
  } else if (modalReset?.querySelector(".alert-danger")) {
    showModal("modalResetPassword");
  }

  if (document.getElementById("modalPosReceipt")) {
    showModal("modalPosReceipt");
  }
});
