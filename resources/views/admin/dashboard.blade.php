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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS (Laravel Asset) -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col justify-between border-t-4 border-blue-600">

    <!-- MODAL LOGIN ADMIN (PENGAMAN AREA PRIVATE) -->
    <div id="adminLoginModal" class="fixed inset-0 modal-backdrop flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full border border-slate-200 shadow-2xl relative text-center">
            <div class="w-14 h-14 bg-blue-50 border border-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                <i class="fa-solid fa-user-shield text-2xl"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 mb-1">Login Admin Panel</h2>
            <p class="text-xs text-slate-500 mb-6">Area verifikasi pesanan bersifat rahasia. Masukkan kredensial admin GOR.</p>
            
            <form onsubmit="handleAdminLogin(event)" class="space-y-4 text-left">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username Admin</label>
                    <input type="text" id="adminUser" required placeholder="Contoh: admin_syafea" class="form-control-modern">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" id="adminPass" required placeholder="••••••••" class="form-control-modern">
                </div>
                <button type="submit" class="w-full py-3.5 btn-modern-primary text-xs uppercase tracking-wider">
                    MASUK ADMIN PANEL
                </button>
            </form>
            
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Home
                </a>
                <a href="{{ route('owner.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                    Portal Pemilik &rarr;
                </a>
            </div>
        </div>
    </div>

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
                    <i class="fa-solid fa-lock"></i> Lock Admin
                </button>
                <a href="{{ route('booking.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200 hover:bg-slate-200 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Home
                </a>
                <a href="{{ route('owner.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 hover:bg-indigo-100 transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-chart-line"></i> Pemilik
                </a>
            </nav>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-10 w-full space-y-6 flex-grow">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-list text-blue-600"></i> Verifikasi Pesanan Masuk (Real-Time)
                    </h1>
                    <p class="text-xs text-slate-500 mt-1">Kelola dan verifikasi bukti transfer sewa lapangan dari pelanggan secara otomatis</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="date" value="{{ date('Y-m-d') }}" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <!-- TABEL ADMIN -->
            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="modern-table text-left">
                    <thead>
                        <tr>
                            <th>Pelanggan / Tipe</th>
                            <th>Jadwal Main</th>
                            <th>Total</th>
                            <th>Bukti Bayar</th>
                            <th>Status</th>
                            <th class="text-center">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody id="adminTableBody" class="divide-y divide-slate-100 text-sm font-medium">
                        <!-- Data render dinamis -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-4 px-6 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} Mini GOR Syafea. Admin Management Panel.
    </footer>

    <script>
        function handleAdminLogin(e) {
            e.preventDefault();
            document.getElementById('adminLoginModal').classList.add('hidden');
            loadAdminOrders();
        }

        function logoutAdmin() {
            document.getElementById('adminLoginModal').classList.remove('hidden');
        }

        function loadAdminOrders() {
            const tbody = document.getElementById('adminTableBody');
            let history = JSON.parse(localStorage.getItem('syafea_history')) || [];

            tbody.innerHTML = "";

            if (history.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 italic">Belum ada pesanan masuk dari pelanggan.</td>
                    </tr>
                `;
                return;
            }

            history.forEach((item, index) => {
                const isVerified = item.status.includes('Terverifikasi');
                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50/80">
                        <td class="py-4 px-4">
                            <div class="font-bold text-slate-900">${item.id}</div>
                            <div class="text-xs text-emerald-600 font-semibold">${item.type}</div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-slate-800 text-xs">${item.schedule}</div>
                            <div class="text-[10px] text-slate-400">Booking: ${item.date}</div>
                        </td>
                        <td class="py-4 px-4 font-bold text-slate-900">${item.price}</td>
                        <td class="py-4 px-4">
                            <button type="button" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 cursor-pointer hover:bg-blue-100" onclick="alert('Menampilkan gambar bukti transfer pelanggan.')">
                                <i class="fa-solid fa-image"></i> Lihat Bukti
                            </button>
                        </td>
                        <td class="py-4 px-4">
                            <span class="badge ${isVerified ? 'badge-success' : 'badge-warning'}">
                                <span class="dot-indicator ${isVerified ? 'online' : 'pending'}"></span>
                                ${item.status}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center space-x-1">
                            <button type="button" onclick="verifyOrder(${index}, 'Terverifikasi')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow transition-all">
                                <i class="fa-solid fa-check"></i> Terima
                            </button>
                            <button type="button" onclick="verifyOrder(${index}, 'Ditolak')" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg shadow transition-all">
                                <i class="fa-solid fa-xmark"></i> Tolak
                            </button>
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

        document.addEventListener('DOMContentLoaded', () => {
            loadAdminOrders();
        });
    </script>
</body>
</html>

