// ================= GLOBAL STATE =================
let currentSort = '';
let currentDir = 'asc';
let debounceTimer;


// ================= CORE FUNCTIONS =================

// 🔹 Build Query Params
function buildParams() {
    const searchInput = document.getElementById('search');
    const filterStok = document.getElementById('filterStok');
    const minHarga = document.getElementById('minHarga');
    const maxHarga = document.getElementById('maxHarga');

    let params = new URLSearchParams();

    if (searchInput?.value.trim()) {
        params.append('search', searchInput.value.trim());
    }

    if (filterStok?.value) {
        params.append('stok', filterStok.value);
    }

    if (minHarga?.value) {
        params.append('min_harga', minHarga.value);
    }

    if (maxHarga?.value) {
        params.append('max_harga', maxHarga.value);
    }

    if (currentSort) {
        params.append('sort_by', currentSort);
        params.append('sort_dir', currentDir);
    }

    return params.toString();
}


// 🔹 Load Data (AJAX)
function loadData(url = null) {

    const table = document.getElementById('product-table');
    const pagination = document.getElementById('pagination-area');

    if (!table) {
        console.error("❌ TABLE TIDAK DITEMUKAN");
        return;
    }

    let params = buildParams();
    let fetchUrl = url || '/produk';

    if (params) {
        fetchUrl += (fetchUrl.includes('?') ? '&' : '?') + params;
    }

    console.log("🔥 FETCH:", fetchUrl);

    // 🔥 Loading UI
    table.innerHTML = `
        <tr>
            <td colspan="7" class="text-center py-3">
                <div class="spinner-border text-primary"></div>
                <div class="small text-muted mt-2">Memuat data...</div>
            </td>
        </tr>
    `;

    fetch(fetchUrl, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {

        let parser = new DOMParser();
        let doc = parser.parseFromString(html, 'text/html');

        let newTable = doc.querySelector('#product-table');
        let newPagination = doc.querySelector('#pagination-area');

        if (newTable) {
            table.style.opacity = 0;
            table.innerHTML = newTable.innerHTML;
        }

        if (newPagination && pagination) {
            pagination.innerHTML = newPagination.innerHTML;
        }

        setTimeout(() => {
            table.style.transition = "0.3s";
            table.style.opacity = 1;
        }, 100);

    })
    .catch(err => console.error("❌ ERROR:", err));
}


// 🔹 Update Sorting UI
function updateSortUI() {

    document.querySelectorAll('.sortable').forEach(th => {
        th.classList.remove('asc', 'desc', 'active');
    });

    let active = document.querySelector(`[data-sort="${currentSort}"]`);

    if (active) {
        active.classList.add('active', currentDir);
    }
}


// 🔹 Debounce Wrapper
function debounceLoad() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadData(), 300);
}



// ================= EVENT BINDING =================
document.addEventListener("DOMContentLoaded", function () {

    console.log("🔥 produk.js aktif (CLEAN VERSION)");

    const searchInput = document.getElementById('search');
    const filterStok = document.getElementById('filterStok');
    const minHarga = document.getElementById('minHarga');
    const maxHarga = document.getElementById('maxHarga');

    if (!searchInput) return;

    // 🔍 SEARCH
    searchInput.addEventListener('keyup', debounceLoad);

    // 🎯 FILTER
    filterStok?.addEventListener('change', () => loadData());
    minHarga?.addEventListener('input', debounceLoad);
    maxHarga?.addEventListener('input', debounceLoad);

});


// ================= GLOBAL CLICK HANDLER =================
document.addEventListener('click', function (e) {

    // 📄 PAGINATION
    const link = e.target.closest('.pagination a');
    if (link) {
        e.preventDefault();
        loadData(link.getAttribute('href'));
        return;
    }

    // 🔽 SORTING
    const th = e.target.closest('.sortable');
    if (th) {

        const field = th.dataset.sort;

        if (currentSort === field) {
            currentDir = currentDir === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort = field;
            currentDir = 'asc';
        }

        console.log("🔥 SORT:", currentSort, currentDir);

        updateSortUI();
        loadData();
    }

});