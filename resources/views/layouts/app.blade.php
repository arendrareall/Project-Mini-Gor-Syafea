<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Mini GOR Syafea - Sewa Lapangan Modern')</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts & Font Awesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @yield('styles')
</head>
<body class="figma-grid min-vh-100 d-flex flex-column justify-content-between">

    <!-- Header / Navbar Utama Bootstrap 5 -->
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top shadow-sm py-2">
        <div class="container">
            <!-- Logo & Brand Name -->
            <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="{{ route('booking.index') }}">
                <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
                    <i class="fa-solid fa-volleyball fs-5"></i>
                </div>
                <div class="lh-sm">
                    <span class="d-block text-primary text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 2px;">Mini GOR</span>
                    <span class="fs-5 fw-extrabold text-dark fw-bold" style="letter-spacing: -0.5px;">SYAFEA</span>
                </div>
            </a>

            <!-- Toggle Mobile -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Nav Links -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <div class="ms-auto d-flex flex-wrap align-items-center gap-2 bg-light p-1.5 rounded-3 border">
                    <a href="{{ route('booking.index') }}" class="btn btn-sm btn-primary fw-semibold px-3 py-1.5 rounded-2 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-house"></i> Home
                    </a>
                    
                    <a href="{{ route('booking.index') }}#jadwal" class="btn btn-sm btn-light text-secondary fw-semibold px-3 py-1.5 rounded-2 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-calendar-days text-muted"></i> Jadwal
                    </a>
                    
                    <button type="button" onclick="openHistoryModal()" class="btn btn-sm btn-light text-secondary fw-semibold px-3 py-1.5 rounded-2 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-primary"></i> Riwayat Pesanan
                    </button>
                    
                    <!-- Dynamic Member Nav / Auth Status -->
                    <div id="authNavContainer" class="d-flex align-items-center gap-2 ps-2 border-start">
                        <button type="button" onclick="openLoginModal()" id="btnLoginMember" class="btn btn-sm btn-outline-primary fw-bold px-3 py-1.5 rounded-2 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-user-lock"></i> Login Member
                        </button>
                        
                        <div id="memberProfileBadge" class="d-none align-items-center gap-2 bg-success-subtle border border-success-subtle px-3 py-1 rounded-2">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-center text-center" style="width: 26px; height: 26px; font-size: 11px;">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                            <div class="lh-1 text-start">
                                <span class="d-block text-success fw-bold text-uppercase" style="font-size: 9px;">Member Aktif</span>
                                <span id="memberNameDisplay" class="small fw-bold text-dark">Member</span>
                            </div>
                            <button type="button" onclick="logoutMember()" class="btn btn-link text-muted hover-danger p-0 ms-1 text-decoration-none" title="Keluar">
                                <i class="fa-solid fa-right-from-bracket small"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Role Link: Admin & Pemilik -->
                    <div class="d-flex align-items-center gap-1 ps-2 border-start">
                        <a href="{{ route('admin.index') }}" class="btn btn-sm btn-light text-secondary px-2 py-1 rounded-2" title="Portal Admin">
                            <i class="fa-solid fa-user-gear"></i> <span class="small">Admin</span>
                        </a>
                        <a href="{{ route('owner.index') }}" class="btn btn-sm btn-light text-secondary px-2 py-1 rounded-2" title="Portal Pemilik">
                            <i class="fa-solid fa-chart-line"></i> <span class="small">Pemilik</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Alert Messages -->
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2" role="alert">
                <i class="fa-solid fa-circle-check fs-5 text-success"></i>
                <div class="fw-semibold small">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2" role="alert">
                <i class="fa-solid fa-circle-exclamation fs-5 text-danger"></i>
                <div class="fw-semibold small">{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Modal Riwayat Pemesanan (History Modal) Bootstrap 5 -->
    <div class="modal fade" id="historyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fa-solid fa-clock-rotate-left fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0">Riwayat Pemesanan Lapangan</h5>
                            <p id="historySubtitle" class="text-muted small mb-0">Daftar transaksi sewa lapangan Anda</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th>ID / Tgl Booking</th>
                                    <th>Jadwal Main</th>
                                    <th>Tipe Akun</th>
                                    <th>Total Biaya</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody">
                                <!-- Diisi via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-dark w-100 rounded-3 py-2 fw-bold text-uppercase small" data-bs-dismiss="modal">
                        Tutup Window
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Login Member Bootstrap 5 -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg p-3">
                <div class="modal-header border-0 pb-0 justify-content-end">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center pt-0">
                    <div class="bg-primary-subtle text-primary rounded-4 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 56px; height: 56px;">
                        <i class="fa-solid fa-user-shield fs-3"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Login Member Syafea</h4>
                    <p class="text-muted small mb-4">Nikmati potongan tarif khusus member resmi</p>

                    <form onsubmit="handleMemberLogin(event)" class="text-start">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-secondary">Nama / WhatsApp / Email</label>
                            <input type="text" id="inputUser" required placeholder="Contoh: Budi Santoso" class="form-control rounded-3 py-2">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-secondary">Password</label>
                            <input type="password" required placeholder="••••••••" class="form-control rounded-3 py-2">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-3 py-2.5 fw-bold text-uppercase small shadow-sm">
                            MASUK SEBAGAI MEMBER
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bootstrap 5 -->
    <footer class="bg-dark text-secondary py-4 mt-5 border-top border-secondary">
        <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-center text-md-start">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-primary text-white rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="fa-solid fa-volleyball small"></i>
                </div>
                <span class="text-white fw-bold">Mini GOR Syafea</span>
            </div>
            <p class="small text-muted mb-0">&copy; {{ date('Y') }} Mini GOR Syafea. Built with Laravel & Bootstrap 5.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Client-Side Scripting -->
    <script>
        let bsLoginModal = null;
        let bsHistoryModal = null;

        document.addEventListener('DOMContentLoaded', () => {
            const loginEl = document.getElementById('loginModal');
            const historyEl = document.getElementById('historyModal');
            if (loginEl) bsLoginModal = new bootstrap.Modal(loginEl);
            if (historyEl) bsHistoryModal = new bootstrap.Modal(historyEl);

            const savedMember = localStorage.getItem('syafea_member');
            if (savedMember) {
                activateMemberUI(savedMember);
            }
        });

        function openLoginModal() { 
            if (bsLoginModal) bsLoginModal.show(); 
        }
        function closeLoginModal() { 
            if (bsLoginModal) bsLoginModal.hide(); 
        }

        function openHistoryModal() { 
            renderHistoryModal();
            if (bsHistoryModal) bsHistoryModal.show(); 
        }
        function closeHistoryModal() { 
            if (bsHistoryModal) bsHistoryModal.hide(); 
        }

        function handleMemberLogin(e) {
            e.preventDefault();
            const inputVal = document.getElementById('inputUser').value || "Budi Santoso";
            localStorage.setItem('syafea_member', inputVal);
            activateMemberUI(inputVal);
            if (typeof renderScheduleTable === "function") {
                renderScheduleTable();
            }
            closeLoginModal();
        }

        function activateMemberUI(name) {
            const btnLogin = document.getElementById('btnLoginMember');
            const badge = document.getElementById('memberProfileBadge');
            const nameDisplay = document.getElementById('memberNameDisplay');
            if (btnLogin) btnLogin.classList.add('d-none');
            if (badge) {
                badge.classList.remove('d-none');
                badge.classList.add('d-flex');
            }
            if (nameDisplay) nameDisplay.innerText = name;

            const banner = document.getElementById('bannerStatus');
            if (banner) {
                banner.className = "bg-gradient-member text-white p-4 rounded-4 shadow-sm d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 mb-4";
                const bannerIcon = document.getElementById('bannerIcon');
                if (bannerIcon) {
                    bannerIcon.className = "rounded-3 bg-white bg-opacity-25 text-white d-flex align-items-center justify-content-center";
                    bannerIcon.innerHTML = '<i class="fa-solid fa-award fs-4"></i>';
                }
                const bannerTitle = document.getElementById('bannerTitle');
                if (bannerTitle) bannerTitle.innerText = "Halo, " + name + " (Member Aktif)";
                const bannerDesc = document.getElementById('bannerDesc');
                if (bannerDesc) bannerDesc.innerText = "Hak istimewa custom jadwal aktif + Tarif spesial Member GOR Syafea.";
                const bannerBtn = document.getElementById('btnActionBanner');
                if (bannerBtn) {
                    bannerBtn.innerText = "Lihat Riwayat Member";
                    bannerBtn.onclick = openHistoryModal;
                }
            }

            const scheduleCtrl = document.getElementById('memberScheduleControl');
            if (scheduleCtrl) scheduleCtrl.classList.remove('d-none');
        }

        function logoutMember() {
            localStorage.removeItem('syafea_member');
            location.reload();
        }

        function renderHistoryModal() {
            const tbody = document.getElementById('historyTableBody');
            const subtitle = document.getElementById('historySubtitle');
            const isMember = localStorage.getItem('syafea_member');
            
            let allHistory = JSON.parse(localStorage.getItem('syafea_history')) || [];
            let userHistory = allHistory.filter(item => {
                return isMember ? item.type.includes(isMember) : item.type.includes("Tamu");
            });

            if (subtitle) {
                subtitle.innerText = isMember 
                    ? `Daftar transaksi eksklusif milik Member: ${isMember}` 
                    : "Daftar transaksi sewa lapangan (Mode: Tamu / Regular)";
            }

            tbody.innerHTML = "";
            if (userHistory.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="py-4 text-center text-muted fst-italic">Belum ada riwayat pesanan untuk akun ini.</td></tr>`;
                return;
            }

            userHistory.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td><span class="fw-bold text-dark">${item.id}</span><br><small class="text-muted">${item.date}</small></td>
                        <td>${item.schedule}</td>
                        <td><span class="badge bg-success-subtle text-success border border-success-subtle">${item.type}</span></td>
                        <td class="fw-bold text-dark">${item.price}</td>
                        <td><span class="badge bg-success-subtle text-success">${item.status}</span></td>
                    </tr>
                `;
            });
        }
    </script>

    @yield('scripts')
</body>
</html>
