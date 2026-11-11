<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Mini GOR Syafea</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-light min-vh-100 d-flex flex-column justify-content-between border-top border-4 border-primary">

    <!-- MODAL LOGIN ADMIN (PENGAMAN AREA PRIVATE) BOOTSTRAP 5 -->
    <div class="modal fade" id="adminLoginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg p-4 text-center">
                <div class="bg-primary-subtle text-primary rounded-4 d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-user-shield fs-3"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">Login Admin Panel</h4>
                <p class="text-muted small mb-4">Area verifikasi pesanan bersifat rahasia. Masukkan kredensial admin GOR.</p>
                
                <form onsubmit="handleAdminLogin(event)" class="text-start vstack gap-3">
                    @csrf
                    <div>
                        <label class="form-label small fw-bold text-uppercase text-secondary mb-1">Username Admin</label>
                        <input type="text" id="adminUser" required placeholder="Contoh: admin_syafea" class="form-control rounded-3 py-2">
                    </div>
                    <div>
                        <label class="form-label small fw-bold text-uppercase text-secondary mb-1">Password</label>
                        <input type="password" id="adminPass" required placeholder="••••••••" class="form-control rounded-3 py-2">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2.5 fw-bold text-uppercase small shadow-sm mt-2">
                        MASUK ADMIN PANEL
                    </button>
                </form>
                
                <div class="mt-4 pt-3 border-top d-flex align-items-center justify-content-between">
                    <a href="{{ route('booking.index') }}" class="btn btn-link text-muted fw-bold small text-decoration-none p-0 d-flex align-items-center gap-1">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Home
                    </a>
                    <a href="{{ route('owner.index') }}" class="btn btn-link text-primary fw-bold small text-decoration-none p-0">
                        Portal Pemilik &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- HEADER / NAVBAR ADMIN BOOTSTRAP 5 -->
    <header class="bg-white border-bottom sticky-top shadow-sm">
        <div class="container py-2.5 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('booking.index') }}" class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center text-decoration-none shadow-sm" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-volleyball fs-5"></i>
                </a>
                <div class="lh-sm">
                    <span class="fw-bold text-dark fs-6 d-block">ADMIN PANEL</span>
                    <span class="text-primary fw-bold text-uppercase" style="font-size: 9px; letter-spacing: 1px;">MINI GOR SYAFEA</span>
                </div>
            </div>

            <nav class="d-flex align-items-center gap-2">
                <button type="button" onclick="logoutAdmin()" class="btn btn-sm btn-outline-danger fw-bold rounded-3 px-3 py-1.5 d-flex align-items-center gap-1.5" title="Kunci Kembali">
                    <i class="fa-solid fa-lock"></i> <span class="d-none d-sm-inline">Lock Admin</span>
                </button>
                <a href="{{ route('booking.index') }}" class="btn btn-sm btn-light border text-secondary fw-bold rounded-3 px-3 py-1.5 d-flex align-items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> <span class="d-none d-sm-inline">Home</span>
                </a>
                <a href="{{ route('owner.index') }}" class="btn btn-sm btn-outline-primary fw-bold rounded-3 px-3 py-1.5 d-flex align-items-center gap-1.5">
                    <i class="fa-solid fa-chart-line"></i> <span class="d-none d-sm-inline">Pemilik</span>
                </a>
            </nav>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="container py-4 flex-grow-1">
        <div class="white-card p-4 p-md-5 rounded-4 shadow-sm mb-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark d-flex align-items-center gap-2 mb-1 fs-5">
                        <i class="fa-solid fa-clipboard-list text-primary"></i> Verifikasi Pesanan Masuk (Real-Time)
                    </h4>
                    <p class="text-muted small mb-0">Kelola dan verifikasi bukti transfer sewa lapangan dari pelanggan secara otomatis</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <input type="date" value="{{ date('Y-m-d') }}" class="form-control form-control-sm rounded-3">
                </div>
            </div>

            <!-- TABEL ADMIN BOOTSTRAP 5 -->
            <div class="table-responsive rounded-3 border">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase text-secondary">
                        <tr>
                            <th class="py-3 px-3">Pelanggan / Tipe</th>
                            <th class="py-3 px-3">Jadwal Main</th>
                            <th class="py-3 px-3">Total</th>
                            <th class="py-3 px-3">Bukti Bayar</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-center">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody id="adminTableBody" class="small">
                        <!-- Data render dinamis -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="bg-white border-top py-3 text-center text-muted small">
        &copy; {{ date('Y') }} Mini GOR Syafea. Admin Management Panel.
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let bsAdminLoginModal = null;

        document.addEventListener('DOMContentLoaded', () => {
            const modalEl = document.getElementById('adminLoginModal');
            if (modalEl) {
                bsAdminLoginModal = new bootstrap.Modal(modalEl);
                bsAdminLoginModal.show();
            }
            loadAdminOrders();
        });

        function handleAdminLogin(e) {
            e.preventDefault();
            if (bsAdminLoginModal) bsAdminLoginModal.hide();
            loadAdminOrders();
        }

        function logoutAdmin() {
            if (bsAdminLoginModal) bsAdminLoginModal.show();
        }

        function loadAdminOrders() {
            const tbody = document.getElementById('adminTableBody');
            let history = JSON.parse(localStorage.getItem('syafea_history')) || [];

            tbody.innerHTML = "";

            if (history.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="py-4 text-center text-muted fst-italic">Belum ada pesanan masuk dari pelanggan.</td>
                    </tr>
                `;
                return;
            }

            history.forEach((item, index) => {
                const isVerified = item.status.includes('Terverifikasi');
                tbody.innerHTML += `
                    <tr>
                        <td class="py-3 px-3">
                            <span class="fw-bold text-dark d-block">${item.id}</span>
                            <small class="text-success fw-bold">${item.type}</small>
                        </td>
                        <td class="py-3 px-3">
                            <span class="d-block text-dark">${item.schedule}</span>
                            <small class="text-muted">Booking: ${item.date}</small>
                        </td>
                        <td class="py-3 px-3 fw-bold text-dark">${item.price}</td>
                        <td class="py-3 px-3">
                            <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 rounded-2" onclick="alert('Menampilkan gambar bukti transfer pelanggan.')">
                                <i class="fa-solid fa-image"></i> <span>Lihat Bukti</span>
                            </button>
                        </td>
                        <td class="py-3 px-3">
                            <span class="badge ${isVerified ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle'} rounded-pill px-2.5 py-1.5">
                                <span class="dot-indicator ${isVerified ? 'online' : 'pending'}"></span>
                                ${item.status}
                            </span>
                        </td>
                        <td class="py-3 px-3 text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" onclick="verifyOrder(${index}, 'Terverifikasi')" class="btn btn-success fw-bold">
                                    <i class="fa-solid fa-check me-1"></i> Terima
                                </button>
                                <button type="button" onclick="verifyOrder(${index}, 'Ditolak')" class="btn btn-danger fw-bold">
                                    <i class="fa-solid fa-xmark me-1"></i> Tolak
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        function verifyOrder(index, newStatus) {
            let history = JSON.parse(localStorage.getItem('syafea_history')) || [];
            if (history[index]) {
                history[index].status = newStatus === 'Terverifikasi' ? 'Aktif / Terverifikasi' : 'Ditolak Admin';
                localStorage.setItem('syafea_history', JSON.stringify(history));
                loadAdminOrders();
                alert(`Pesanan berhasil diubah statusnya menjadi: ${newStatus}`);
            }
        }
    </script>
</body>
</html>
