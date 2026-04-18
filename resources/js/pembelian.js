// ================= GLOBAL =================
let debounceTimer;


// ================= HELPER =================
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID').format(angka);
}

function formatTanggal(tgl) {
    let date = new Date(tgl);
    return date.toLocaleString('id-ID');
}

function highlight(text, keyword) {
    if (!keyword) return text;

    return text.replace(
        new RegExp(`(${keyword})`, 'gi'),
        '<span class="bg-warning">$1</span>'
    );
}


// ================= INDEX (SEARCH) =================
document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById('searchInput');
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');

    if (!searchInput) return;

    console.log("🔥 pembelian index aktif");

    function loadData() {

        let keyword = searchInput.value || '';
        let start = startDate?.value || '';
        let end = endDate?.value || '';

        document.getElementById('loadingIndicator')?.classList.remove('d-none');

        fetch(`/pembelian/search?search=${keyword}&startDate=${start}&endDate=${end}`)
            .then(res => res.json())
            .then(data => {

                let tbody = document.querySelector('table tbody');
                if (!tbody) return;

                tbody.innerHTML = '';

                if (!data || data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Tidak ada hasil ditemukan
                            </td>
                        </tr>
                    `;
                    return;
                }

                let rows = '';

                data.forEach(row => {
                    rows += `
                        <tr>
                            <td>${formatTanggal(row.tanggal)}</td>
                            <td>${highlight(row.supplier?.nama ?? '-', keyword)}</td>
                            <td>${highlight(row.user?.name ?? '-', keyword)}</td>
                            <td class="text-end">
                                Rp ${formatRupiah(row.total)}
                            </td>
                            <td class="text-end">
                                <a href="/pembelian/${row.id}" class="btn-soft-primary btn-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    `;
                });

                tbody.innerHTML = rows;

            })
            .finally(() => {
                document.getElementById('loadingIndicator')?.classList.add('d-none');
            });
    }

    function debounceLoad() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(loadData, 400);
    }

    searchInput.addEventListener('keyup', debounceLoad);
    startDate?.addEventListener('change', loadData);
    endDate?.addEventListener('change', loadData);
});


// ================= CREATE (TRANSAKSI) =================
document.addEventListener("DOMContentLoaded", function () {

    const addBtn = document.getElementById('addRow');

    if (!addBtn) return;

    console.log("🔥 pembelian create aktif");

    addBtn.addEventListener('click', function () {

        if (!window.products || window.products.length === 0) {
            alert("❌ Produk kosong!");
            return;
        }

        let tbody = document.getElementById('itemBody');

        if (tbody.querySelector('.text-muted')) {
            tbody.innerHTML = '';
        }

        let options = window.products.map(p => `
            <option value="${p.id}" data-harga="${p.harga_beli}">
                ${p.nama}
            </option>
        `).join('');

        let row = `
        <tr>
            <td>
                <select name="produk_id[]" class="form-select produk" required>
                    <option value="">-- Pilih Produk --</option>
                    ${options}
                </select>
            </td>

            <td>
                <input type="text" name="harga[]" class="form-control harga" readonly>
            </td>

            <td>
                <input type="number" name="qty[]" class="form-control qty" value="1" min="1" required>
            </td>

            <td>
                <input type="text" class="form-control subtotal" readonly>
            </td>

            <td class="text-end">
                <button type="button" class="btn-soft-danger btn-sm removeRow">
                    Hapus
                </button>
            </td>
        </tr>
        `;

        tbody.insertAdjacentHTML('beforeend', row);

        // animasi
        let newRow = tbody.lastElementChild;
        newRow.style.opacity = 0;
        setTimeout(() => {
            newRow.style.transition = "0.3s";
            newRow.style.opacity = 1;
        }, 50);
    });


    // ================= EVENT PRODUK =================
    document.addEventListener('change', function (e) {

        if (e.target.classList.contains('produk')) {

            let selectedId = e.target.value;

            if (!selectedId) return;

            // 🔥 CEK DUPLIKAT
            let duplicate = false;

            document.querySelectorAll('.produk').forEach(el => {
                if (el !== e.target && el.value === selectedId) {
                    duplicate = true;
                }
            });

            if (duplicate) {
                alert("Produk sudah dipilih!");
                e.target.value = '';
                return;
            }

            let selected = e.target.selectedOptions[0];
            let harga = parseFloat(selected.dataset.harga || 0);

            let row = e.target.closest('tr');

            row.querySelector('.harga').value = formatRupiah(harga);

            row.querySelector('.qty').focus();

            hitung(row);
        }

    });


    // ================= EVENT QTY =================
    document.addEventListener('input', function (e) {

        if (e.target.classList.contains('qty')) {

            let val = parseInt(e.target.value);

            if (val < 1 || isNaN(val)) {
                e.target.value = 1;
            }

            let row = e.target.closest('tr');
            hitung(row);
        }

    });


    // ================= REMOVE =================
    document.addEventListener('click', function (e) {

        if (e.target.classList.contains('removeRow')) {

            e.target.closest('tr').remove();
            updateTotal();
        }

    });


    // ================= VALIDASI SUBMIT =================
    document.getElementById('purchaseForm').addEventListener('submit', function(e) {

        let valid = true;

        document.querySelectorAll('.produk').forEach(el => {
            if (!el.value) valid = false;
        });

        if (!valid) {
            e.preventDefault();
            alert("Pilih semua produk dulu!");
        }

    });


    // ================= HITUNG =================
    function hitung(row) {

        let harga = parseFloat(row.querySelector('.harga').value.replace(/\./g, '')) || 0;
        let qty   = parseFloat(row.querySelector('.qty').value) || 0;

        let subtotal = harga * qty;

        row.querySelector('.subtotal').value = formatRupiah(subtotal);

        updateTotal();
    }


    function updateTotal() {

        let total = 0;

        document.querySelectorAll('.subtotal').forEach(el => {
            total += parseFloat(el.value.replace(/\./g, '')) || 0;
        });

        document.getElementById('totalDisplay').innerText =
            'Rp ' + formatRupiah(total);

        document.getElementById('totalInput').value = total;
    }

});