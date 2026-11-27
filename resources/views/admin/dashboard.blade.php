<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'id') }}" class="h-full bg-slate-50 text-slate-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Mini GOR Syafea</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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
            background-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }
        .dot-indicator.pending {
            background-color: #f59e0b;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col justify-between border-t-4 border-blue-600">

    <!-- MODAL LOGIN ADMIN (PENGAMAN AREA PRIVATE) -->
    <div id="adminLoginModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-md flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full border border-slate-200 shadow-2xl relative text-center">
            <div class="w-14 h-14 bg-blue-50 border border-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-sm">
                <i class="fa-solid fa-user-shield text-2xl"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 mb-1">Login Admin Panel</h2>
            <p class="text-xs text-slate-500 mb-5">Area verifikasi pesanan bersifat rahasia. Masukkan kredensial admin GOR.</p>
            
            <form onsubmit="handleAdminLogin(event)" class="space-y-4 text-left">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username Admin</label>
                    <input type="text" id="adminUser" required placeholder="Contoh: admin_syafea" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" id="adminPass" required placeholder="••••••••" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:bg-white">
                </div>
                <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-blue-500/25 transition-all">
                    MASUK ADMIN PANEL
                </button>
            </form>
            
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Home
                </a>
                <a href="{{ route('owner.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Portal Pemilik &rarr;</a>
            </div>
        </div>
    </div>

    <!-- KONTEN ADMIN (TERSEMBUNYI SAMPAI LOGIN BERHASIL) -->
    <div id="adminContent" style="display:none;" class="flex-grow flex flex-col justify-between">

        <!-- HEADER / NAVBAR ADMIN -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('booking.index') }}" class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-xl shadow-md shadow-blue-500/20 hover:bg-blue-700 transition-all">
                        <i class="fa-solid fa-volleyball"></i>
                    </a>
                    <div>
                        <span class="font-extrabold text-slate-900 tracking-tight text-lg block leading-none">ADMIN PANEL</span>
                        <span class="text-[10px] font-bold text-blue-600 tracking-widest uppercase">MINI GOR SYAFEA</span>
                    </div>
                </div>

                <nav class="flex items-center gap-2">
                    <button type="button" onclick="logoutAdmin()" class="px-3.5 py-2 rounded-xl text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 transition-all flex items-center gap-1.5" title="Kunci Kembali">
                        <i class="fa-solid fa-lock"></i> <span class="hidden sm:inline">Lock Admin</span>
                    </button>
                    <a href="{{ route('booking.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200 hover:bg-slate-200 transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-left"></i> <span class="hidden sm:inline">Home</span>
                    </a>
                    <a href="{{ route('owner.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold text-blue-700 bg-blue-50 border border-blue-100 hover:bg-blue-100 transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-chart-line"></i> <span class="hidden sm:inline">Pemilik</span>
                    </a>
                </nav>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 w-full space-y-6 flex-grow">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-clipboard-list text-blue-600"></i> Verifikasi Pesanan Masuk (Real-Time)
                        </h1>
                        <p class="text-xs text-slate-500 mt-1">Kelola dan verifikasi bukti pembayaran sewa lapangan dari pelanggan</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="date" value="{{ date('Y-m-d') }}" class="px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:outline-none">
                    </div>
                </div>

                <!-- TABEL ADMIN -->
                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase font-bold tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3.5 px-4">Pelanggan / Tipe</th>
                                <th class="py-3.5 px-4">Jadwal Main</th>
                                <th class="py-3.5 px-4">Total</th>
                                <th class="py-3.5 px-4">Bukti Bayar</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4 text-center">Aksi Verifikasi</th>
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
            &copy; {{ date('Y') }} Mini GOR Syafea. Admin Management Panel.
        </footer>

    </div><!-- end #adminContent -->

    <script>
        const ADMIN_CREDENTIALS = { username: 'admin_syafea', password: 'admin123' };

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
            const user = document.getElementById('adminUser').value.trim();
            const pass = document.getElementById('adminPass').value;

            if (user === ADMIN_CREDENTIALS.username && pass === ADMIN_CREDENTIALS.password) {
                sessionStorage.setItem('admin_logged_in', 'true');
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

        function showAdminContent() {
            document.getElementById('adminLoginModal').style.display = 'none';
            document.getElementById('adminContent').style.display = 'flex';
            loadAdminOrders();
        }

        function logoutAdmin() {
            sessionStorage.removeItem('admin_logged_in');
            document.getElementById('adminContent').style.display = 'none';
            document.getElementById('adminUser').value = '';
            document.getElementById('adminPass').value = '';
            const errEl = document.getElementById('loginError');
            if (errEl) errEl.remove();
            document.getElementById('adminLoginModal').style.display = 'flex';
        }

        function loadAdminOrders() {
            const tbody = document.getElementById('adminTableBody');
            let history = JSON.parse(localStorage.getItem('syafea_history')) || [];

            tbody.innerHTML = "";

            if (history.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="py-6 text-center text-slate-400 italic">Belum ada pesanan masuk dari pelanggan.</td>
                    </tr>
                `;
                return;
            }

            history.forEach((item, index) => {
                const isVerified = item.status.includes('Terverifikasi');
                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3.5 px-4">
                            <span class="font-bold text-slate-900 block">${item.id}</span>
                            <span class="text-[11px] text-emerald-600 font-bold">${item.type}</span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="block text-slate-800 font-medium">${item.schedule}</span>
                            <span class="text-[10px] text-slate-400">Booking: ${item.date}</span>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">${item.price}</td>
                        <td class="py-3.5 px-4">
                            <button type="button" class="px-2.5 py-1 text-xs font-semibold text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 flex items-center gap-1" onclick="alert('Menampilkan gambar bukti transfer pelanggan.')">
                                <i class="fa-solid fa-image"></i> <span>Lihat Bukti</span>
                            </button>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold ${isVerified ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'}">
                                <span class="dot-indicator ${isVerified ? 'online' : 'pending'}"></span>
                                ${item.status}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="inline-flex rounded-xl shadow-sm overflow-hidden border border-slate-200" role="group">
                                <button type="button" onclick="verifyOrder(${index}, 'Terverifikasi')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1 transition-colors">
                                    <i class="fa-solid fa-check"></i> Terima
                                </button>
                                <button type="button" onclick="verifyOrder(${index}, 'Ditolak')" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs flex items-center gap-1 transition-colors border-l border-rose-500">
                                    <i class="fa-solid fa-xmark"></i> Tolak
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
