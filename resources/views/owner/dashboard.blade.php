<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Pemilik - Laporan Rekap Transaksi Mini GOR Syafea</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS (Laravel Asset) -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex">

    <!-- MODAL LOGIN PEMILIK (KUNCI PRIVASI KEUANGAN) -->
    <div id="ownerLoginModal" class="fixed inset-0 modal-backdrop flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full border border-slate-200 shadow-2xl relative text-center">
            <div class="w-14 h-14 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                <i class="fa-solid fa-lock text-2xl"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 mb-1">Area Private Pemilik</h2>
            <p class="text-xs text-slate-500 mb-6">Data omzet keuangan & rekap jadwal bersifat rahasia. Masukkan kredensial pemilik GOR.</p>
            
            <form onsubmit="handleOwnerLogin(event)" class="space-y-4 text-left">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username Pemilik</label>
                    <input type="text" id="ownerUser" required placeholder="Contoh: pemilik_syafea" class="form-control-modern">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password Rahasia</label>
                    <input type="password" id="ownerPass" required placeholder="••••••••" class="form-control-modern">
                </div>
                <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-indigo-500/25 transition-all">
                    BUKA LAPORAN KEUANGAN
                </button>
            </form>
            
            <div class="mt-6 pt-4 border-t border-slate-100">
                <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda Utama
                </a>
            </div>
        </div>
    </div>

    <!-- SIDEBAR (KIRI) -->
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between hidden md:flex">
        <div>
            <div class="h-16 flex items-center px-6 border-b border-slate-200 gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-black">
                    <i class="fa-solid fa-volleyball"></i>
                </div>
                <div>
                    <span class="font-extrabold text-slate-900 tracking-tight text-sm block leading-none">MINI GOR SYAFEA</span>
                    <span class="text-[10px] font-bold text-indigo-600 tracking-wider">PEMILIK PORTAL</span>
                </div>
            </div>

            <nav class="p-4 space-y-1">
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-indigo-600 bg-indigo-50 border border-indigo-100">
                    <i class="fa-solid fa-chart-column"></i> Laporan Rekap
                </a>
            </nav>
        </div>

        <!-- NAVIGASI KEMBALI DI SIDEBAR -->
        <div class="p-4 border-t border-slate-200 space-y-2">
            <a href="{{ route('admin.index') }}" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs rounded-xl border border-blue-200 transition-all">
                <i class="fa-solid fa-user-gear"></i> Portal Admin
            </a>
            <a href="{{ route('booking.index') }}" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-all shadow-md">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Home
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT (KANAN) -->
    <main class="flex-1 flex flex-col min-w-0">
        <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('booking.index') }}" class="md:hidden px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left"></i> Home
                </a>
                <h1 class="text-base sm:text-lg font-black text-slate-900 tracking-wide uppercase">LAPORAN KEUANGAN & REKAP JADWAL</h1>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="logoutOwner()" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5" title="Kunci Kembali">
                    <i class="fa-solid fa-lock"></i> Lock Owner
                </button>
                <span class="text-xs font-bold px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-full">Private Owner Mode</span>
            </div>
        </header>

        <div class="p-6 space-y-6 flex-grow">
            
            <!-- FILTER TANGGAL -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                        <span class="text-xs font-semibold text-slate-500">Tgl Awal:</span>
                        <input type="date" value="{{ date('Y-m-01') }}" class="bg-transparent text-xs font-bold text-slate-800 focus:outline-none">
                    </div>
                    <span class="text-xs font-bold text-slate-400">s/d</span>
                    <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                        <span class="text-xs font-semibold text-slate-500">Tgl Akhir:</span>
                        <input type="date" value="{{ date('Y-m-d') }}" class="bg-transparent text-xs font-bold text-slate-800 focus:outline-none">
                    </div>
                    <button type="button" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-500/20 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-filter"></i> TAMPILKAN
                    </button>
                </div>
            </div>

            <!-- KOTAK BESAR RINGKASAN PENDAPATAN & TRANSAKSI -->
            <div class="bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 rounded-2xl p-6 text-white shadow-xl shadow-indigo-500/10 relative overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10 divide-y md:divide-y-0 md:divide-x divide-white/20">
                    <div class="pb-4 md:pb-0">
                        <span class="text-xs font-bold text-indigo-100 uppercase tracking-wider block mb-1">TOTAL PENDAPATAN (LIVE)</span>
                        <p id="totalRevenue" class="text-3xl sm:text-4xl font-black tracking-tight">Rp 0</p>
                    </div>
                    <div class="pt-4 md:pt-0 md:pl-6">
                        <span class="text-xs font-bold text-indigo-100 uppercase tracking-wider block mb-1">TOTAL TRANSAKSI</span>
                        <p id="totalTransactions" class="text-3xl sm:text-4xl font-black tracking-tight">0 Transaksi</p>
                    </div>
                </div>
            </div>

            <!-- TABEL RIWAYAT SELURUH JADWAL PESANAN (MEMBER / NON-MEMBER) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-indigo-600"></i> Rekap Seluruh Jadwal & Riwayat Pesanan (Member & Non-Member)
                    </h2>
                    <span class="text-xs text-slate-500 font-medium">Live Data</span>
                </div>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="modern-table text-xs">
                        <thead>
                            <tr>
                                <th>ID / Tgl Booking</th>
                                <th>Jadwal Main</th>
                                <th>Tipe Pemesan</th>
                                <th>Total Biaya</th>
                                <th>Status Konfirmasi</th>
                            </tr>
                        </thead>
                        <tbody id="ownerHistoryTableBody" class="divide-y divide-slate-100 font-medium">
                            <!-- Data otomatis diisi -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- GRAFIK BATANG TERURUT KRONOLOGIS -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-indigo-600"></i> Grafik Pendapatan Harian (Urut Tanggal)
                    </h2>
                    <span class="text-xs text-slate-500 font-medium">Berdasarkan Tanggal Jadwal Main</span>
                </div>
                <div class="h-64">
                    <canvas id="rekapChart"></canvas>
                </div>
            </div>

        </div>
    </main>

    <script>
        let rekapChartInstance = null;

        function handleOwnerLogin(e) {
            e.preventDefault();
            document.getElementById('ownerLoginModal').classList.add('hidden');
            loadOwnerData();
        }

        function logoutOwner() {
            document.getElementById('ownerLoginModal').classList.remove('hidden');
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
                let cleanPrice = parseInt(item.price.replace(/[^0-9]/g, '')) || 0;
                totalRev += cleanPrice;

                let scheduleText = item.schedule; 
                let matchDate = scheduleText.match(/\d{2}-\d{2}-\d{4}/); 
                let mainDate = matchDate ? matchDate[0] : item.date; 

                if (!dateMap[mainDate]) {
                    dateMap[mainDate] = 0;
                }
                dateMap[mainDate] += cleanPrice;

                tbody.innerHTML += `
                    <tr>
                        <td class="font-bold text-slate-800">${item.id}<br><span class="text-[10px] text-slate-400 font-normal">${item.date}</span></td>
                        <td>${item.schedule}</td>
                        <td><span class="badge badge-member">${item.type}</span></td>
                        <td class="font-bold text-slate-900">${item.price}</td>
                        <td><span class="badge badge-success">${item.status}</span></td>
                    </tr>
                `;
            });

            document.getElementById('totalRevenue').innerText = "Rp " + totalRev.toLocaleString('id-ID');
            document.getElementById('totalTransactions').innerText = allHistory.length + " Transaksi";

            // URUTKAN TANGGAL SECARA KRONOLOGIS (DARI TERLAMPAU KE TERBARU)
            let sortedDates = Object.keys(dateMap).sort((a, b) => {
                let partsA = a.split('-'); // [DD, MM, YYYY]
                let partsB = b.split('-');
                let dateA = new Date(partsA[2], partsA[1] - 1, partsA[0]);
                let dateB = new Date(partsB[2], partsB[1] - 1, partsB[0]);
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

        document.addEventListener('DOMContentLoaded', () => {
            loadOwnerData();
        });
    </script>
</body>
</html>

