import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// ================= SIDEBAR =================
window.toggleSidebar = function () {
    document.getElementById('sidebar').classList.toggle('collapsed');
};

// ================= TOOLTIP =================
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
});

// ================= PEMBELIAN =================
import './pembelian';
import './produk';