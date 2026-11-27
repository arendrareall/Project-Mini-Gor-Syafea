<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'id') }}" class="h-full bg-slate-100 text-slate-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Pemilik - Laporan Rekap Transaksi Mini GOR Syafea</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col md:flex-row">

    <!-- MODAL LOGIN PEMILIK (KUNCI PRIVASI KEUANGAN) -->
    <div id="ownerLoginModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-md flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full border border-slate-200 shadow-2xl relative text-center">
            <div class="w-14 h-14 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-sm">
                <i class="fa-solid fa-lock text-2xl"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 mb-1">Area Private Pemilik</h2>
            <p class="text-xs text-slate-500 mb-5">Data omzet keuangan & rekap jadwal bersifat rahasia. Masukkan kredensial pemilik GOR.</p>
            
            <form onsubmit="handleOwnerLogin(event)" class="space-y-4 text-left">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username Pemilik</label>
                    <input type="text" id="ownerUser" required placeholder="Contoh: pemilik_syafea" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-indigo-600 focus:bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password Rahasia</label>
                    <input type="password" id="ownerPass" required placeholder="••••••••" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-indigo-600 focus:bg-white">
                </div>
                <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-indigo-500/25 transition-all">
                    BUKA LAPORAN KEUANGAN
                </button>
            </form>
            
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
                </a>
                <a href="{{ route('admin.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Portal Admin &rarr;</a>
            </div>
        </div>
    </div>

    <!-- KONTEN OWNER (TERSEMBUNYI SAMPAI LOGIN BERHASIL) -->
    <div id="ownerContent" style="display:none;" class="w-full flex flex-col md:flex-row flex-grow">

        <!-- SIDEBAR (KIRI) -->
        <aside class="w-full md:w-64 bg-white border-r border-slate-200 flex flex-col justify-between">
            <div>
                <div class="h-16 flex items-center px-6 border-b border-slate-200 gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black shadow-md shadow-indigo-500/20">
                        <i class="fa-solid fa-volleyball"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-slate-900 tracking-tight text-sm block leading-none">MINI GOR SYAFEA</span>
                        <span class="text-[10px] font-bold text-indigo-600 tracking-wider">PEMILIK PORTAL</span>
                    </div>
                </div>

                <nav class="p-4 space-y-1">
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        <i class="fa-solid fa-chart-pie text-sm"></i> Dashboard & Omzet
                    </a>
                    <a href="{{ route('booking.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all">
                        <i class="fa-solid fa-house text-sm text-slate-400"></i> Halaman Utama (Home)
                    </a>
                    <a href="{{ route('admin.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all">
                        <i class="fa-solid fa-user-gear text-sm text-slate-400"></i> Panel Admin
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-slate-200">
                <button type="button" onclick="logoutOwner()" class="w-full py-2.5 px-3 rounded-xl text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 hover:bg-rose-100 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-lock"></i> Kunci Portal Pemilik
                </button>
            </div>
        </aside>

        <!-- MAIN AREA -->
        <main class="flex-1 flex flex-col overflow-y-auto">
            
            <!-- TOP BAR -->
            <header class="h-16 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30">
                <h1 class="text-base sm:text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-indigo-600"></i> Laporan Rekap Keuangan & Jadwal
                </h1>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Live Data
                    </span>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6 max-w-6xl w-full">
                
                <!-- SUMMARY STATS CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    
                    <!-- Card 1: Total Omzet -->
                    <div class="bg-gradient-to-tr from-indigo-700 to-blue-600 rounded-3xl p-6 text-white shadow-lg shadow-indigo-500/20 relative overflow-hidden">
                        <div class="relative z-10">
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-200 block mb-1">Total Pendapatan</span>
                            <h2 id="totalRevenue" class="text-2xl sm:text-3xl font-black tracking-tight mb-2">Rp 0</h2>
                            <p class="text-[11px] text-indigo-100 flex items-center gap-1">
                                <i class="fa-solid fa-circle-check text-emerald-400"></i> Akumulasi dari pesanan terverifikasi
                            </p>
                        </div>
                        <div class="absolute -right-4 -bottom-4 w-28 h-28 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                    </div>

                    <!-- Card 2: Total Transaksi -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Total Transaksi</span>
                            <h2 id="totalTransactions" class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2">0 Pesanan</h2>
                        </div>
                        <p class="text-[11px] text-slate-500 flex items-center gap-1">
                            <i class="fa-solid fa-clipboard-list text-indigo-600"></i> Semua status pemesanan
                        </p>
                    </div>

                    <!-- Card 3: Status Lapangan -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between sm:col-span-2 lg:col-span-1">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Status Operasional</span>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                                <h2 class="text-xl font-black text-slate-900">GOR Buka</h2>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-500">Operasional reguler 08.00 - 22.00 WIB</p>
                    </div>

                </div>

                <!-- GRAFIK OMZET -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-chart-simple text-indigo-600"></i> Grafik Pendapatan Berdasarkan Tanggal
                            </h3>
                            <p class="text-xs text-slate-500">Rekap pemasukan harian yang tercatat di sistem</p>
                        </div>
                    </div>
                    <div class="h-64 w-full">
                        <canvas id="rekapChart"></canvas>
                    </div>
                </div>

                <!-- TABEL DETAIL SEMUA TRANSAKSI -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Rekap Transaksi Penyewaan</h3>
                            <p class="text-xs text-slate-500">Data riwayat transaksi dari semua pelanggan</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-500 uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="py-3.5 px-4">ID Transaksi</th>
                                    <th class="py-3.5 px-4">Tipe Pemesan</th>
                                    <th class="py-3.5 px-4">Jadwal Sewa</th>
                                    <th class="py-3.5 px-4">Nominal</th>
                                    <th class="py-3.5 px-4">Status</th>
                                </tr>
                            </thead>
                            <tbody id="ownerHistoryTableBody" class="divide-y divide-slate-100 font-medium text-slate-700">
                                <!-- Diisi via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </main>

    </div><!-- end #ownerContent -->

    <script>
        const OWNER_CREDENTIALS = { username: 'pemilik_syafea', password: 'owner123' };

        let rekapChartInstance = null;

        document.addEventListener('DOMContentLoaded', () => {
            const isLoggedIn = sessionStorage.getItem('owner_logged_in') === 'true';
            if (isLoggedIn) {
                showOwnerContent();
            } else {
                document.getElementById('ownerContent').style.display = 'none';
                document.getElementById('ownerLoginModal').style.display = 'flex';
            }
        });

        function handleOwnerLogin(e) {
            e.preventDefault();
            const user = document.getElementById('ownerUser').value.trim();
            const pass = document.getElementById('ownerPass').value;

            if (user === OWNER_CREDENTIALS.username && pass === OWNER_CREDENTIALS.password) {
                sessionStorage.setItem('owner_logged_in', 'true');
                showOwnerContent();
            } else {
                let errEl = document.getElementById('ownerLoginError');
                if (!errEl) {
                    errEl = document.createElement('div');
                    errEl.id = 'ownerLoginError';
                    errEl.className = 'p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold mt-2';
                    document.querySelector('#ownerLoginModal form').appendChild(errEl);
                }
                errEl.textContent = '❌ Username atau password salah!';
                document.getElementById('ownerPass').value = '';
                document.getElementById('ownerPass').focus();
            }
        }

        function showOwnerContent() {
            document.getElementById('ownerLoginModal').style.display = 'none';
            document.getElementById('ownerContent').style.display = 'flex';
            loadOwnerData();
        }

        function logoutOwner() {
            sessionStorage.removeItem('owner_logged_in');
            document.getElementById('ownerContent').style.display = 'none';
            document.getElementById('ownerUser').value = '';
            document.getElementById('ownerPass').value = '';
            const errEl = document.getElementById('ownerLoginError');
            if (errEl) errEl.remove();
            document.getElementById('ownerLoginModal').style.display = 'flex';
        }

        function loadOwnerData() {
            const tbody = document.getElementById('ownerHistoryTableBody');
            let allHistory = JSON.parse(localStorage.getItem('syafea_history')) || [];

            tbody.innerHTML = "";

            if (allHistory.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-400 italic">Belum ada data jadwal pesanan masuk.</td>
                    </tr>
                `;
                document.getElementById('totalRevenue').innerText = "Rp 0";
                document.getElementById('totalTransactions').innerText = "0 Transaksi";
                updateChart({});
                return;
            }

            let totalRev = 0;
            let dateMap = {};

            allHistory.forEach(item => {
                const numericPrice = parseInt(item.price.replace(/[^0-9]/g, '')) || 0;
                const isVerified = item.status.includes('Terverifikasi');

                if (isVerified) {
                    totalRev += numericPrice;
                }

                let bookingDate = item.schedule.split(' ')[0] || item.date;
                if (!dateMap[bookingDate]) {
                    dateMap[bookingDate] = 0;
                }
                if (isVerified) {
                    dateMap[bookingDate] += numericPrice;
                }

                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3.5 px-4 font-bold text-slate-900">${item.id}</td>
                        <td class="py-3.5 px-4"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">${item.type}</span></td>
                        <td class="py-3.5 px-4 font-medium text-slate-800">${item.schedule}</td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">${item.price}</td>
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold ${isVerified ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'}">
                                ${item.status}
                            </span>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('totalRevenue').innerText = 'Rp ' + totalRev.toLocaleString('id-ID');
            document.getElementById('totalTransactions').innerText = allHistory.length + ' Transaksi';

            // Urutkan tanggal secara kronologis untuk grafik
            let sortedDates = Object.keys(dateMap).sort((a, b) => {
                let partsA = a.split('-');
                let partsB = b.split('-');
                let dateA = new Date(partsA[2] || partsA[0], (partsA[1] || 1) - 1, partsA[0] || partsA[2]);
                let dateB = new Date(partsB[2] || partsB[0], (partsB[1] || 1) - 1, partsB[0] || partsB[2]);
                return dateA - dateB;
            });

            let sortedDateMap = {};
            sortedDates.forEach(date => {
                sortedDateMap[date] = dateMap[date];
            });

            updateChart(sortedDateMap);
        }

        function updateChart(dateMap) {
            const labels = Object.keys(dateMap);
            const dataValues = Object.values(dateMap);

            const chartLabels = labels.length > 0 ? labels : ['Belum Ada Data'];
            const chartData = dataValues.length > 0 ? dataValues : [0];

            const ctx = document.getElementById('rekapChart').getContext('2d');
            
            if (rekapChartInstance) {
                rekapChartInstance.destroy();
            }

            rekapChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: chartData,
                        backgroundColor: '#4f46e5',
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) { return 'Rp ' + (value / 1000) + 'rb'; }
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
