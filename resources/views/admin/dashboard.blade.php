<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'id') }}" class="h-full bg-slate-50 text-slate-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Pengelola - Sistem Informasi Pencatatan Penyewaan Mini GOR Syafea</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        tosca: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                        },
                        navy: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#0a1128',
                        }
                    }
                }
            }
        }
    </script>
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        .dot-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        .dot-indicator.online {
            background-color: #14b8a6;
            box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.25);
        }
        .dot-indicator.pending {
            background-color: #f59e0b;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col justify-between border-t-4 border-tosca-500">

    <!-- MODAL LOGIN ADMIN PENGELOLA (PENGAMAN AREA PRIVATE) -->
    <div id="adminLoginModal" class="fixed inset-0 bg-navy-950/80 backdrop-blur-md flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full border border-slate-200 shadow-2xl relative text-center">
            <div class="w-14 h-14 bg-gradient-to-tr from-tosca-50 to-cyan-100 border border-tosca-200 text-tosca-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-sm">
                <i class="fa-solid fa-user-shield text-2xl"></i>
            </div>
            <h2 class="text-2xl font-black text-navy-950 mb-1">Login Admin Pengelola</h2>
            <p class="text-xs text-slate-500 mb-5">Area verifikasi pembayaran & kelola penyewaan bersifat rahasia. Masukkan kredensial pengelola GOR.</p>
            
            <form onsubmit="handleAdminLogin(event)" class="space-y-4 text-left">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Petugas Admin yang Bertugas</label>
                    <input type="text" id="adminStaffName" required placeholder="Contoh: Ariel Arendra" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-tosca-500 focus:bg-white focus:ring-2 focus:ring-tosca-400/20">
                    <span class="text-[10px] text-slate-400 mt-0.5 block">*Nama ini akan terkunci sebagai verifikator resmi dan tercetak di nota.</span>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username Admin</label>
                    <input type="text" id="adminUser" required placeholder="Contoh: admin_syafea" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-tosca-500 focus:bg-white focus:ring-2 focus:ring-tosca-400/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" id="adminPass" required placeholder="••••••••" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-tosca-500 focus:bg-white focus:ring-2 focus:ring-tosca-400/20">
                </div>
                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-tosca-500 via-cyan-600 to-blue-600 hover:opacity-95 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-tosca-500/25 transition-all">
                    MASUK PANEL PENGELOLA
                </button>
            </form>
            
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-navy-950 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
                </a>
                <a href="{{ route('owner.index') }}" class="text-xs font-bold text-tosca-700 hover:underline">Portal Pemilik &rarr;</a>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI LOGOUT ADMIN (WAJIB PASSWORD DEMI KEAMANAN) -->
    <div id="adminLogoutModal" class="fixed inset-0 bg-navy-950/80 backdrop-blur-md hidden items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl p-6 sm:p-7 max-w-sm w-full border border-slate-200 shadow-2xl relative text-center space-y-4">
            <div class="w-12 h-12 bg-rose-50 border border-rose-200 text-rose-600 rounded-2xl flex items-center justify-center mx-auto shadow-xs">
                <i class="fa-solid fa-user-shield text-xl"></i>
            </div>
            <div>
                <h3 class="text-base font-black text-navy-950">Konfirmasi Keluar Petugas Admin</h3>
                <p class="text-xs text-slate-500 mt-1">Petugas: <strong id="adminLogoutStaffName" class="text-navy-950"></strong>.<br>Masukkan password admin untuk memverifikasi penutupan sesi dinas.</p>
            </div>
            <form onsubmit="handleConfirmAdminLogout(event)" class="space-y-3 text-left">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password Admin</label>
                    <input type="password" id="inputAdminLogoutPass" required placeholder="admin123" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-cyan-500 focus:bg-white focus:ring-2 focus:ring-cyan-400/20">
                    <span class="text-[10px] text-slate-400 mt-0.5 block">*Default: <strong>admin123</strong></span>
                </div>
                <div id="errorAdminLogoutMsg" class="hidden p-2 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-[11px] font-semibold">
                    ❌ Password admin salah! Sesi petugas tetap aktif demi audit.
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <button type="button" onclick="closeAdminLogoutModal()" class="w-1/2 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="w-1/2 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-rose-600 to-rose-700 hover:opacity-95 shadow-md shadow-rose-600/25 transition-all">
                        Tutup Sesi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- KONTEN ADMIN (TERSEMBUNYI SAMPAI LOGIN BERHASIL) -->
    <div id="adminContent" style="display:none;" class="flex-grow flex flex-col justify-between">

        <!-- HEADER / NAVBAR ADMIN -->
        <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-sm">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('booking.index') }}" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-tosca-500 via-cyan-600 to-blue-700 text-white flex items-center justify-center font-black text-xl shadow-md shadow-tosca-500/20 hover:opacity-95 transition-all">
                        <i class="fa-solid fa-volleyball"></i>
                    </a>
                    <div>
                        <span class="font-extrabold text-navy-950 tracking-tight text-lg block leading-none">PANEL PENGELOLA</span>
                        <span class="text-[10px] font-bold text-tosca-600 tracking-widest uppercase">MINI GOR SYAFEA</span>
                    </div>
                </div>

                <nav class="flex items-center gap-2">
                    <!-- Badge Petugas Admin Terkunci Sesuai Login (Anti-Kecurangan) -->
                    <div class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-cyan-900 bg-cyan-50 border border-cyan-200 flex items-center gap-1.5 shadow-2xs">
                        <i class="fa-solid fa-user-shield text-cyan-600"></i>
                        <span>Petugas: <strong id="adminNameDisplay" class="text-navy-950">Ariel Arendra</strong></span>
                    </div>
                    <button type="button" onclick="logoutAdmin()" class="px-3.5 py-2 rounded-xl text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 transition-all flex items-center gap-1.5" title="Kunci Kembali">
                        <i class="fa-solid fa-lock"></i> <span class="hidden sm:inline">Kunci Panel</span>
                    </button>
                    <a href="{{ route('booking.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200 hover:bg-slate-200 transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-left"></i> <span class="hidden sm:inline">Beranda</span>
                    </a>
                    <a href="{{ route('owner.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold text-tosca-700 bg-tosca-50 border border-tosca-100 hover:bg-tosca-100 transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-chart-line"></i> <span class="hidden sm:inline">Portal Pemilik</span>
                    </a>
                </nav>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 w-full space-y-6 flex-grow">
            
            <!-- Quick Stats Admin -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase text-slate-500 block mb-0.5">Total Daftar Penyewaan</span>
                        <h3 id="statTotalOrders" class="text-2xl font-black text-navy-950">0</h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-tosca-50 to-cyan-100 text-tosca-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase text-amber-600 block mb-0.5">Menunggu ACC Admin</span>
                        <div class="flex items-baseline gap-2">
                            <h3 id="statPendingOrders" class="text-2xl font-black text-amber-600">0</h3>
                            <span id="statMemberPendingSubtext" class="text-[11px] text-amber-700 font-extrabold"></span>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase text-tosca-700 block mb-0.5">Pembayaran Terverifikasi (ACC)</span>
                        <h3 id="statVerifiedOrders" class="text-2xl font-black text-tosca-700">0</h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-tosca-50 text-tosca-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Penyewaan & Verifikasi -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h1 class="text-xl font-bold text-navy-950 flex items-center gap-2">
                                <i class="fa-solid fa-clipboard-list text-tosca-600"></i> Kelola Penyewaan & Verifikasi Pembayaran
                            </h1>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-amber-50 text-amber-800 border border-amber-300">
                                <i class="fa-solid fa-crown text-amber-500"></i> Prioritas: Booking Member Tampil Paling Atas
                            </span>
                        </div>
                        <p class="text-xs text-slate-500">Seluruh booking wajib di-ACC oleh admin. Pesanan Member otomatis diposisikan paling atas untuk diprioritaskan.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="date" value="{{ date('Y-m-d') }}" class="px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:outline-none">
                    </div>
                </div>

                <!-- TABEL ADMIN -->
                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 uppercase font-bold tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3.5 px-4">Penyewa / Tipe</th>
                                <th class="py-3.5 px-4">Jadwal Main</th>
                                <th class="py-3.5 px-4">Total Biaya</th>
                                <th class="py-3.5 px-4">Bukti Pembayaran</th>
                                <th class="py-3.5 px-4">Status Pembayaran</th>
                                <th class="py-3.5 px-4 text-center">Aksi Verifikasi & Cetak Nota</th>
                            </tr>
                        </thead>
                        <tbody id="adminTableBody" class="divide-y divide-slate-100 font-medium text-slate-700">
                            <!-- Diisi via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <!-- FOOTER -->
        <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} Sistem Informasi Pencatatan Penyewaan Mini GOR Syafea. Panel Admin Pengelola.
        </footer>

    </div><!-- end #adminContent -->

    <script>
        const ADMIN_CREDENTIALS = { username: 'admin_syafea', password: 'admin123', name: 'Ariel Arendra' };

        document.addEventListener('DOMContentLoaded', () => {
            const isLoggedIn = sessionStorage.getItem('admin_logged_in') === 'true';
            if (isLoggedIn) {
                showAdminContent();
            } else {
                document.getElementById('adminContent').style.display = 'none';
                document.getElementById('adminLoginModal').style.display = 'flex';
            }
        });

        function handleAdminLogin(e) {
            e.preventDefault();
            const staffName = document.getElementById('adminStaffName').value.trim() || 'Ariel Arendra';
            const user = document.getElementById('adminUser').value.trim();
            const pass = document.getElementById('adminPass').value;

            if (user === ADMIN_CREDENTIALS.username && pass === ADMIN_CREDENTIALS.password) {
                sessionStorage.setItem('admin_logged_in', 'true');
                sessionStorage.setItem('admin_staff_name', staffName);
                showAdminContent();
            } else {
                let errEl = document.getElementById('loginError');
                if (!errEl) {
                    errEl = document.createElement('div');
                    errEl.id = 'loginError';
                    errEl.className = 'p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold mt-2';
                    document.querySelector('#adminLoginModal form').appendChild(errEl);
                }
                errEl.textContent = '❌ Username atau password salah!';
                document.getElementById('adminPass').value = '';
                document.getElementById('adminPass').focus();
            }
        }

        function getActiveAdminName() {
            return sessionStorage.getItem('admin_staff_name') || 'Ariel Arendra';
        }

        function updateAdminNameUI() {
            const el = document.getElementById('adminNameDisplay');
            if (el) el.innerText = getActiveAdminName();
        }

        function showAdminContent() {
            document.getElementById('adminLoginModal').style.display = 'none';
            document.getElementById('adminContent').style.display = 'flex';
            updateAdminNameUI();
            loadAdminOrders();
        }

        function logoutAdmin() {
            openAdminLogoutModal();
        }

        function openAdminLogoutModal() {
            const modal = document.getElementById('adminLogoutModal');
            const passInput = document.getElementById('inputAdminLogoutPass');
            const nameEl = document.getElementById('adminLogoutStaffName');
            const errEl = document.getElementById('errorAdminLogoutMsg');
            if (nameEl) nameEl.innerText = getActiveAdminName();
            if (errEl) errEl.classList.add('hidden');
            if (passInput) passInput.value = '';
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                if (passInput) passInput.focus();
            }
        }

        function closeAdminLogoutModal() {
            const modal = document.getElementById('adminLogoutModal');
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }

        function handleConfirmAdminLogout(e) {
            e.preventDefault();
            const pass = document.getElementById('inputAdminLogoutPass').value;
            const errEl = document.getElementById('errorAdminLogoutMsg');

            if (pass === ADMIN_CREDENTIALS.password) {
                const staffName = getActiveAdminName();
                sessionStorage.removeItem('admin_logged_in');
                sessionStorage.removeItem('admin_staff_name');
                closeAdminLogoutModal();
                document.getElementById('adminContent').style.display = 'none';
                document.getElementById('adminUser').value = '';
                document.getElementById('adminPass').value = '';
                const staffInput = document.getElementById('adminStaffName');
                if (staffInput) staffInput.value = '';
                const errLoginEl = document.getElementById('loginError');
                if (errLoginEl) errLoginEl.remove();
                document.getElementById('adminLoginModal').style.display = 'flex';
                alert(`✅ Petugas ${staffName} berhasil keluar dan menutup sesi dinas dengan aman.`);
            } else {
                if (errEl) errEl.classList.remove('hidden');
                document.getElementById('inputAdminLogoutPass').value = '';
                document.getElementById('inputAdminLogoutPass').focus();
            }
        }

        function loadAdminOrders() {
            const tbody = document.getElementById('adminTableBody');
            let history = JSON.parse(localStorage.getItem('syafea_history'));

            // Inisialisasi data sampel awal jika penyimpanan masih kosong
            if (!history || history.length === 0) {
                history = [
                    {
                        id: "#GOR-8821",
                        date: "{{ date('d-m-Y') }}",
                        name: "Budi Santoso",
                        schedule: "{{ date('d-m-Y') }} (08:00 - 11:00)",
                        type: "Member Syafea (Budi Santoso)",
                        price: "Rp 40.000",
                        method: "QRIS",
                        status: "Pending Verifikasi", // Default awal wajib menunggu ACC admin
                        isMember: true,
                        verified_by: null
                    },
                    {
                        id: "#GOR-5432",
                        date: "{{ date('d-m-Y') }}",
                        name: "Ahmad Dani",
                        schedule: "{{ date('d-m-Y') }} (14:00 - 17:00)",
                        type: "Tamu (Regular)",
                        price: "Rp 50.000",
                        method: "TRANSFER BANK BCA",
                        status: "Pending Verifikasi",
                        isMember: false,
                        verified_by: null
                    }
                ];
                localStorage.setItem('syafea_history', JSON.stringify(history));
            }

            tbody.innerHTML = "";

            let pendingCount = 0;
            let memberPendingCount = 0;
            let verifiedCount = 0;

            // Prioritas Urutan Tampil:
            // 1. Member yang berstatus Pending Verifikasi (Prioritas Utama Paling Atas)
            // 2. Tamu yang berstatus Pending Verifikasi
            // 3. Member yang sudah Terverifikasi / Di-ACC
            // 4. Tamu yang sudah Terverifikasi / Di-ACC
            // 5. Pesanan Ditolak / Lainnya
            const getPriorityRank = (item) => {
                const isMember = !!(item.isMember || (item.type && item.type.toLowerCase().includes('member')));
                const isPending = !!(item.status && item.status.toLowerCase().includes('pending'));
                const isVerified = !!(item.status && item.status.includes('Terverifikasi'));

                if (isMember && isPending) return 1; // Prioritas Tertinggi: Member Menunggu ACC (Paling Atas)
                if (isPending) return 2;              // Tamu Menunggu ACC
                if (isMember && isVerified) return 3; // Member Selesai di-ACC
                if (isVerified) return 4;             // Tamu Selesai di-ACC
                return 5;                             // Ditolak / Lainnya
            };

            const sortedItems = history.map((item, originalIndex) => ({ item, originalIndex }))
                .sort((a, b) => {
                    const rankA = getPriorityRank(a.item);
                    const rankB = getPriorityRank(b.item);
                    if (rankA !== rankB) {
                        return rankA - rankB; // Rank terkecil (1) muncul paling atas
                    }
                    return a.originalIndex - b.originalIndex;
                });

            sortedItems.forEach(({ item, originalIndex }) => {
                const isMember = !!(item.isMember || (item.type && item.type.toLowerCase().includes('member')));
                const isPending = !!(item.status && item.status.toLowerCase().includes('pending'));
                const isVerified = !!(item.status && item.status.includes('Terverifikasi'));
                const isRejected = !!(item.status && item.status.includes('Ditolak'));

                if (isVerified) verifiedCount++;
                else if (isPending) {
                    pendingCount++;
                    if (isMember) memberPendingCount++;
                }

                // Row styling: Highlight khusus jika Member Pending
                let rowClass = 'hover:bg-slate-50 transition-colors';
                if (isMember && isPending) {
                    rowClass = 'bg-amber-50/40 hover:bg-amber-50/70 border-l-4 border-l-amber-500 transition-colors';
                } else if (isPending) {
                    rowClass = 'bg-slate-50/30 hover:bg-slate-50 border-l-4 border-l-cyan-500 transition-colors';
                }

                // Badge status
                let statusBadgeHtml = '';
                if (isVerified) {
                    statusBadgeHtml = `
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-tosca-50 text-tosca-700 border border-tosca-200">
                            <span class="dot-indicator online"></span> Sudah Di-ACC
                        </span>
                        ${item.verified_by ? `<span class="block text-[10px] text-slate-400 mt-0.5">Admin: ${item.verified_by}</span>` : ''}
                    `;
                } else if (isRejected) {
                    statusBadgeHtml = `
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="dot-indicator offline"></span> Ditolak
                        </span>
                        ${item.verified_by ? `<span class="block text-[10px] text-rose-400 mt-0.5">${item.verified_by}</span>` : ''}
                    `;
                } else {
                    statusBadgeHtml = `
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                            <span class="dot-indicator pending"></span> Menunggu ACC
                        </span>
                    `;
                }

                // Tombol aksi ACC / Verifikasi
                let actionButtonsHtml = '';
                if (isPending) {
                    actionButtonsHtml = `
                        <div class="inline-flex rounded-xl shadow-xs overflow-hidden border border-slate-200" role="group">
                            <button type="button" onclick="verifyOrder(${originalIndex}, 'Terverifikasi')" class="px-3 py-1.5 bg-gradient-to-r from-tosca-600 to-cyan-600 hover:opacity-95 text-white font-bold text-xs flex items-center gap-1.5 transition-all shadow-sm" title="ACC & Verifikasi Pembayaran">
                                <i class="fa-solid fa-circle-check"></i> ACC / Terima
                            </button>
                            <button type="button" onclick="verifyOrder(${originalIndex}, 'Ditolak')" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border-l border-slate-200 font-bold text-xs flex items-center gap-1 transition-colors" title="Tolak Pesanan">
                                <i class="fa-solid fa-xmark"></i> Tolak
                            </button>
                        </div>
                    `;
                } else if (isVerified) {
                    actionButtonsHtml = `
                        <div class="inline-flex items-center gap-1.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200">
                                <i class="fa-solid fa-check-double mr-1 text-emerald-600"></i> Ter-ACC
                            </span>
                            <button type="button" onclick="verifyOrder(${originalIndex}, 'Ditolak')" class="text-[10px] text-slate-400 hover:text-rose-600 underline font-semibold" title="Batalkan ACC">Batal</button>
                        </div>
                    `;
                } else {
                    actionButtonsHtml = `
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200">
                            <i class="fa-solid fa-ban mr-1 text-rose-500"></i> Ditolak
                        </span>
                    `;
                }

                // Tombol cetak nota (jika ditolak, dinonaktifkan)
                const printButtonHtml = isRejected ? `
                    <button type="button" disabled class="px-3 py-1.5 bg-slate-100 text-slate-400 font-bold text-xs rounded-xl border border-slate-200 flex items-center gap-1.5 cursor-not-allowed opacity-60" title="Pesanan berstatus Ditolak tidak dapat mencetak nota">
                        <i class="fa-solid fa-ban text-rose-400"></i>
                        <span>Nota Dibatalkan</span>
                    </button>
                ` : `
                    <button type="button" onclick="printAdminReceipt(${originalIndex})" class="px-3 py-1.5 bg-gradient-to-r from-slate-100 to-cyan-50 hover:from-cyan-100 hover:to-blue-100 text-slate-800 hover:text-navy-950 font-bold text-xs rounded-xl border border-slate-300 hover:border-cyan-300 flex items-center gap-1.5 transition-all shadow-xs" title="Cetak Nota Transaksi Resmi">
                        <i class="fa-solid fa-print text-cyan-600"></i>
                        <span>Cetak Nota</span>
                    </button>
                `;

                tbody.innerHTML += `
                    <tr class="${rowClass}">
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="font-bold text-navy-950">${item.id}</span>
                                ${isMember ? `
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-xs uppercase tracking-wider ${isPending ? 'animate-pulse' : ''}">
                                        <i class="fa-solid fa-crown text-[9px]"></i> Prioritas Member
                                    </span>
                                ` : `
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">Tamu</span>
                                `}
                            </div>
                            <span class="text-xs font-bold ${isMember ? 'text-amber-900' : 'text-slate-800'} flex items-center gap-1">
                                <i class="${isMember ? 'fa-solid fa-user-check text-amber-500' : 'fa-regular fa-user text-slate-400'}"></i>
                                ${item.name || item.type}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="block text-slate-800 font-medium">${item.schedule}</span>
                            <span class="text-[10px] text-slate-400">Tgl Booking: ${item.date}</span>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-navy-950">${item.price}</td>
                        <td class="py-3.5 px-4">
                            <button type="button" class="px-2.5 py-1 text-xs font-semibold text-tosca-700 bg-tosca-50 border border-tosca-200 rounded-lg hover:bg-tosca-100 flex items-center gap-1" onclick="alert('Bukti pembayaran pelanggan terlampir: ${item.method || 'QRIS'}')">
                                <i class="fa-solid fa-image"></i> <span>Lihat Bukti</span>
                            </button>
                        </td>
                        <td class="py-3.5 px-4">
                            ${statusBadgeHtml}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                ${actionButtonsHtml}
                                ${printButtonHtml}
                            </div>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('statTotalOrders').innerText = history.length;
            document.getElementById('statPendingOrders').innerText = pendingCount;
            const subtextEl = document.getElementById('statMemberPendingSubtext');
            if (subtextEl) {
                subtextEl.innerText = memberPendingCount > 0 ? `(${memberPendingCount} Member Prioritas)` : '';
            }
            document.getElementById('statVerifiedOrders').innerText = verifiedCount;
        }

        function verifyOrder(index, newStatus) {
            let history = JSON.parse(localStorage.getItem('syafea_history')) || [];
            if (history[index]) {
                const adminName = getActiveAdminName();
                history[index].status = newStatus === 'Terverifikasi' ? 'Aktif / Terverifikasi' : 'Ditolak Admin';
                history[index].verified_by = newStatus === 'Terverifikasi' ? adminName : `${adminName} (Ditolak)`;
                history[index].verified_at = new Date().toLocaleString('id-ID');
                localStorage.setItem('syafea_history', JSON.stringify(history));
                loadAdminOrders();
                alert(`Status pesanan berhasil diperbarui: ${newStatus}\nPenanggung Jawab: ${adminName}`);
            }
        }

        // Fitur 3.3 Cetak Nota Pembayaran (Sisi Admin)
        function printAdminReceipt(index) {
            let history = JSON.parse(localStorage.getItem('syafea_history')) || [];
            const item = history[index];
            if (!item) return;

            const adminName = getActiveAdminName();

            // Validasi: Jika status ditolak, tidak dapat mencetak nota
            if (item.status && item.status.includes('Ditolak')) {
                alert('❌ Pesanan ini telah DITOLAK oleh admin. Nota transaksi tidak dapat diterbitkan atau dicetak!');
                return;
            }

            const printWindow = window.open('', '', 'width=650,height=750');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Nota Transaksi - ${item.id} - Mini GOR Syafea</title>
                    <style>
                        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; padding: 30px; color: #0f172a; max-width: 520px; margin: auto; }
                        .header { text-align: center; border-bottom: 2px dashed #0d9488; padding-bottom: 16px; margin-bottom: 20px; }
                        .brand { font-size: 22px; font-weight: 900; letter-spacing: -0.5px; color: #0a1128; margin: 0; }
                        .desc { font-size: 11px; color: #64748b; margin-top: 4px; line-height: 1.4; }
                        .badge { display: inline-block; padding: 4px 12px; background: #f0fdfa; color: #0f766e; border: 1px solid #99f6e4; border-radius: 999px; font-size: 10px; font-weight: bold; margin-top: 10px; letter-spacing: 1px; }
                        .details { margin: 18px 0; }
                        .row { display: flex; justify-content: space-between; font-size: 13px; margin: 8px 0; }
                        .label { color: #64748b; font-weight: 500; }
                        .value { font-weight: bold; color: #0f172a; text-align: right; }
                        .total-box { border-top: 2px dashed #0d9488; border-bottom: 2px dashed #0d9488; padding: 14px 0; margin-top: 18px; display: flex; justify-content: space-between; font-size: 16px; font-weight: 900; color: #0d9488; }
                        .footer { text-align: center; margin-top: 28px; font-size: 11px; color: #94a3b8; line-height: 1.5; }
                        .sign { margin-top: 24px; text-align: right; font-size: 12px; }
                        .sign-name { font-weight: bold; text-decoration: underline; margin-top: 45px; }
                        @media print {
                            body { padding: 10px; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1 class="brand">MINI GOR SYAFEA</h1>
                        <p class="desc">Sistem Informasi Pencatatan Penyewaan Lapangan<br>Panel Administrasi & Pengelola Lapangan</p>
                        <span class="badge">NOTA RESMI TRANSAKSI (SALINAN ADMIN)</span>
                    </div>
                    
                    <div class="details">
                        <div class="row"><span class="label">No. Transaksi</span><span class="value">${item.id}</span></div>
                        <div class="row"><span class="label">Tanggal Booking</span><span class="value">${item.date}</span></div>
                        <div class="row"><span class="label">Nama Penyewa</span><span class="value">${item.name || item.type}</span></div>
                        <div class="row"><span class="label">Kategori Akun</span><span class="value">${item.type}</span></div>
                        <div class="row"><span class="label">Jadwal Sewa</span><span class="value">${item.schedule}</span></div>
                        <div class="row"><span class="label">Metode Pembayaran</span><span class="value">${(item.method || 'QRIS').toUpperCase()}</span></div>
                        <div class="row"><span class="label">Status Verifikasi</span><span class="value" style="color:#0f766e;">${item.status}</span></div>
                        <div class="row"><span class="label">Admin Verifikator</span><span class="value" style="color:#2563eb;">${item.verified_by || adminName}</span></div>
                        <div class="row"><span class="label">Admin Pencetak</span><span class="value">${adminName} (Admin Pengelola)</span></div>
                    </div>

                    <div class="total-box">
                        <span>TOTAL PEMBAYARAN</span>
                        <span>${item.price}</span>
                    </div>

                    <div class="sign">
                        <div>Admin yang Bertanggung Jawab,</div>
                        <div class="sign-name">${item.verified_by || adminName}</div>
                        <div style="font-size: 10px; color: #94a3b8;">Admin Pengelola Mini GOR Syafea</div>
                    </div>

                    <div class="footer">
                        <p>Dokumen ini adalah bukti transaksi resmi sah yang dicetak dari Sistem Mini GOR Syafea.</p>
                        <p>Dicetak pada: ${new Date().toLocaleString('id-ID')}</p>
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>
</body>
</html>
