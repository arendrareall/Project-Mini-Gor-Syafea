<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'id') }}" class="h-full bg-slate-100 text-slate-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Pemilik - Laporan Rekap Penyewaan & Pembayaran Mini GOR Syafea</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col md:flex-row">

    <!-- MODAL LOGIN PEMILIK (KUNCI PRIVASI LAPORAN REKAP) -->
    <div id="ownerLoginModal" class="fixed inset-0 bg-navy-950/80 backdrop-blur-md flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full border border-slate-200 shadow-2xl relative text-center">
            <div class="w-14 h-14 bg-gradient-to-tr from-tosca-50 to-cyan-100 border border-tosca-200 text-tosca-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-sm">
                <i class="fa-solid fa-lock text-2xl"></i>
            </div>
            <h2 class="text-2xl font-black text-navy-950 mb-1">Area Private Pemilik GOR</h2>
            <p class="text-xs text-slate-500 mb-5">Laporan rekap transaksi penyewaan & pembayaran bersifat rahasia. Masukkan kredensial pemilik GOR.</p>
            
            <form onsubmit="handleOwnerLogin(event)" class="space-y-4 text-left">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username Pemilik</label>
                    <input type="text" id="ownerUser" required placeholder="Contoh: pemilik_syafea" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-tosca-500 focus:bg-white focus:ring-2 focus:ring-tosca-400/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password Rahasia</label>
                    <input type="password" id="ownerPass" required placeholder="••••••••" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-tosca-500 focus:bg-white focus:ring-2 focus:ring-tosca-400/20">
                </div>
                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-tosca-500 via-cyan-600 to-blue-600 hover:opacity-95 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-tosca-500/25 transition-all">
                    BUKA LAPORAN REKAP PEMILIK
                </button>
            </form>
            
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-navy-950 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
                </a>
                <a href="{{ route('admin.index') }}" class="text-xs font-bold text-tosca-700 hover:underline">Portal Admin &rarr;</a>
            </div>
        </div>
    </div>

    <!-- KONTEN OWNER (TERSEMBUNYI SAMPAI LOGIN BERHASIL) -->
    <div id="ownerContent" style="display:none;" class="w-full flex flex-col md:flex-row flex-grow">

        <!-- SIDEBAR DEEP NAVY -->
        <aside class="w-full md:w-64 bg-navy-950 text-slate-300 border-r border-slate-800 flex flex-col justify-between">
            <div>
                <div class="h-16 flex items-center px-6 border-b border-slate-800/80 gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-tosca-500 via-cyan-600 to-blue-600 text-white flex items-center justify-center font-black shadow-md shadow-tosca-500/20">
                        <i class="fa-solid fa-volleyball"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-white tracking-tight text-sm block leading-none">MINI GOR SYAFEA</span>
                        <span class="text-[10px] font-bold text-tosca-400 tracking-wider">PORTAL PEMILIK</span>
                    </div>
                </div>

                <nav class="p-4 space-y-1.5">
                    <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold bg-gradient-to-r from-tosca-500/20 to-cyan-500/10 text-tosca-300 border border-tosca-500/30">
                        <i class="fa-solid fa-chart-pie text-sm text-tosca-400"></i> Rekap Penyewaan & Omzet
                    </a>
                    <a href="{{ route('booking.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:bg-slate-900 hover:text-white transition-all">
                        <i class="fa-solid fa-house text-sm text-slate-500"></i> Halaman Utama
                    </a>
                    <a href="{{ route('admin.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:bg-slate-900 hover:text-white transition-all">
                        <i class="fa-solid fa-user-gear text-sm text-slate-500"></i> Panel Pengelola
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-slate-800/80">
                <button type="button" onclick="logoutOwner()" class="w-full py-2.5 px-3 rounded-xl text-xs font-bold text-rose-300 bg-rose-500/10 border border-rose-500/20 hover:bg-rose-500/20 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-lock"></i> Kunci Portal Pemilik
                </button>
            </div>
        </aside>

        <!-- MAIN AREA -->
        <main class="flex-1 flex flex-col overflow-y-auto">
            
            <!-- TOP BAR -->
            <header class="h-16 bg-white border-b border-slate-200/80 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30">
                <h1 class="text-base sm:text-lg font-bold text-navy-950 flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-tosca-600"></i> Laporan Rekap Transaksi & Omzet Penyewaan
                </h1>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-tosca-50 to-cyan-50 text-tosca-700 border border-tosca-200">
                        <span class="w-2 h-2 rounded-full bg-tosca-500 animate-pulse"></span> Data Real-Time
                    </span>
                </div>
            </header>

            <div class="p-6 sm:p-8 space-y-6 max-w-6xl w-full">
                
                <!-- SUMMARY STATS CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    
                    <!-- Card 1: Rekap Data Pembayaran (Fitur 4.2) -->
                    <div class="bg-gradient-to-br from-navy-950 via-blue-950 to-tosca-900 rounded-3xl p-6 text-white shadow-xl shadow-navy-950/20 relative overflow-hidden border border-slate-800">
                        <div class="relative z-10">
                            <span class="text-xs font-bold uppercase tracking-wider text-tosca-300 block mb-1">Rekap Pembayaran (Omzet)</span>
                            <h2 id="totalRevenue" class="text-2xl sm:text-3xl font-black tracking-tight mb-2">Rp 0</h2>
                            <p class="text-[11px] text-tosca-100 flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check text-tosca-400"></i> Akumulasi pembayaran terverifikasi
                            </p>
                        </div>
                        <div class="absolute -right-4 -bottom-4 w-28 h-28 bg-tosca-500/20 rounded-full blur-xl pointer-events-none"></div>
                    </div>

                    <!-- Card 2: Rekap Data Penyewaan (Fitur 4.1) -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Rekap Total Penyewaan</span>
                            <h2 id="totalTransactions" class="text-2xl sm:text-3xl font-black text-navy-950 tracking-tight mb-2">0 Transaksi</h2>
                        </div>
                        <p class="text-[11px] text-slate-500 flex items-center gap-1">
                            <i class="fa-solid fa-clipboard-list text-tosca-600"></i> Seluruh data pemesanan lapangan
                        </p>
                    </div>

                    <!-- Card 3: Status Lapangan GOR -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between sm:col-span-2 lg:col-span-1">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Status Lapangan Mini GOR</span>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-3 h-3 rounded-full bg-tosca-500 animate-pulse"></span>
                                <h2 class="text-xl font-black text-navy-950">Operasional Aktif</h2>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-500">Buka setiap hari 08.00 - 22.00 WIB</p>
                    </div>

                </div>

                <!-- KONTROL FILTER TANGGAL & PILIHAN MODE GRAFIK -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-6 space-y-4">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        
                        <!-- Pilihan Rentang Tanggal (Start Date - End Date) -->
                        <div class="flex flex-wrap items-center gap-2.5">
                            <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5 mr-1">
                                <i class="fa-solid fa-calendar-week text-tosca-600"></i> Periode Laporan:
                            </span>

                            <div class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
                                <span class="text-[11px] font-bold text-slate-500">Mulai:</span>
                                <input type="date" id="filterStartDate" onchange="applyOwnerFilters()" class="text-xs font-bold text-navy-950 bg-white px-2 py-1 rounded-lg border border-slate-300 focus:outline-none focus:border-tosca-500 cursor-pointer">
                            </div>

                            <span class="text-xs font-bold text-slate-400">s/d</span>

                            <div class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
                                <span class="text-[11px] font-bold text-slate-500">Sampai:</span>
                                <input type="date" id="filterEndDate" onchange="applyOwnerFilters()" class="text-xs font-bold text-navy-950 bg-white px-2 py-1 rounded-lg border border-slate-300 focus:outline-none focus:border-tosca-500 cursor-pointer">
                            </div>

                            <!-- Tombol Shortcut Preset Contoh: 6 Mei - 6 Juni -->
                            <button type="button" onclick="setPresetMeiJuni()" class="px-3 py-1.5 text-xs font-bold text-tosca-700 hover:text-white bg-tosca-50 hover:bg-tosca-600 border border-tosca-200 rounded-xl transition-all shadow-2xs flex items-center gap-1.5" title="Contoh Preset Tanggal 6 Mei - 6 Juni">
                                <i class="fa-regular fa-calendar-check"></i> 6 Mei - 6 Juni
                            </button>

                            <button type="button" onclick="resetDateFilter()" class="px-3 py-1.5 text-xs font-bold text-slate-600 hover:text-navy-950 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all flex items-center gap-1" title="Tampilkan Semua Data">
                                <i class="fa-solid fa-arrows-rotate"></i> Reset
                            </button>
                        </div>

                        <!-- Pilihan Mode Chart: Per Hari vs Per Bulan -->
                        <div class="flex items-center gap-1.5 bg-slate-100 p-1.5 rounded-2xl border border-slate-200 self-start lg:self-auto">
                            <button type="button" id="btnModeDaily" onclick="setChartMode('daily')" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-gradient-to-r from-tosca-600 via-cyan-600 to-blue-600 text-white shadow-sm flex items-center gap-1.5">
                                <i class="fa-solid fa-calendar-day"></i> Mode Per Hari
                            </button>
                            <button type="button" id="btnModeMonthly" onclick="setChartMode('monthly')" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:text-navy-950 transition-all flex items-center gap-1.5">
                                <i class="fa-solid fa-calendar-days"></i> Mode Per Bulan
                            </button>
                        </div>

                    </div>

                    <!-- Indikator Status Filter Aktif -->
                    <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-500 gap-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-cyan-50 text-cyan-700 border border-cyan-200">
                                <i class="fa-solid fa-filter text-[10px]"></i> Status Filter
                            </span>
                            <span id="filterInfoText" class="font-medium text-slate-700">Menampilkan seluruh data transaksi</span>
                        </div>
                        <div class="text-[11px] text-slate-400">
                            Mode Visualisasi: <strong id="filterModeDisplay" class="text-tosca-700">Per Hari</strong>
                        </div>
                    </div>
                </div>

                <!-- GRAFIK REKAP OMZET -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h3 id="chartTitle" class="text-sm sm:text-base font-bold text-navy-950 flex items-center gap-2">
                                <i class="fa-solid fa-chart-simple text-tosca-600"></i> Grafik Rekap Pembayaran Sewa Lapangan
                            </h3>
                            <p id="chartSubtitle" class="text-xs text-slate-500">Visualisasi pendapatan sewa lapangan harian dari transaksi yang terverifikasi</p>
                        </div>
                        <div id="chartBadgeCount" class="text-xs font-bold text-tosca-700 bg-tosca-50 border border-tosca-200 px-3 py-1 rounded-xl w-fit">
                            0 Data Ditampilkan
                        </div>
                    </div>
                    <div class="h-72 w-full">
                        <canvas id="rekapChart"></canvas>
                    </div>
                </div>

                <!-- TABEL REKAP TRANSAKSI PENYEWAAN -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-navy-950">Daftar Rekap Transaksi Penyewaan</h3>
                            <p class="text-xs text-slate-500">Rincian data penyewaan lapangan dari seluruh pelanggan</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-600 uppercase font-bold tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="py-3.5 px-4">ID Transaksi</th>
                                    <th class="py-3.5 px-4">Nama Penyewa / Tipe</th>
                                    <th class="py-3.5 px-4">Jadwal Sewa Lapangan</th>
                                    <th class="py-3.5 px-4">Nominal</th>
                                    <th class="py-3.5 px-4">Status Verifikasi</th>
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
        let currentChartMode = 'daily'; // 'daily' atau 'monthly'
        let ownerAllHistory = [];
        let ownerFilteredHistory = [];

        const INDO_MONTHS_SHORT = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const INDO_MONTHS_LONG = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

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
            initOwnerData();
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

        // Inisialisasi Data Sampel Kaya Rentang Tanggal (Mei, Juni, Juli, dst.)
        function initOwnerData() {
            let history = JSON.parse(localStorage.getItem('syafea_history'));

            // Pastikan data mencakup variasi tanggal (misal 6 Mei - 6 Juni) agar chart interaktif
            const sampleHistory = [
                {
                    id: "#GOR-1011",
                    date: "06-05-2026",
                    name: "Budi Santoso",
                    schedule: "06-05-2026 (08:00 - 11:00)",
                    type: "Member Syafea",
                    price: "Rp 50.000",
                    method: "QRIS",
                    status: "Aktif / Terverifikasi"
                },
                {
                    id: "#GOR-1022",
                    date: "14-05-2026",
                    name: "Tim PB Djarum Muda",
                    schedule: "14-05-2026 (16:00 - 19:00)",
                    type: "Member Syafea",
                    price: "Rp 100.000",
                    method: "TRANSFER BANK BCA",
                    status: "Aktif / Terverifikasi"
                },
                {
                    id: "#GOR-1033",
                    date: "25-05-2026",
                    name: "Komunitas Voli Rungkut",
                    schedule: "25-05-2026 (19:00 - 22:00)",
                    type: "Tamu (Regular)",
                    price: "Rp 150.000",
                    method: "QRIS",
                    status: "Aktif / Terverifikasi"
                },
                {
                    id: "#GOR-1044",
                    date: "01-06-2026",
                    name: "Ahmad Dani",
                    schedule: "01-06-2026 (13:00 - 16:00)",
                    type: "Tamu (Regular)",
                    price: "Rp 50.000",
                    method: "TRANSFER BANK BCA",
                    status: "Aktif / Terverifikasi"
                },
                {
                    id: "#GOR-1055",
                    date: "06-06-2026",
                    name: "Ariel Arendra",
                    schedule: "06-06-2026 (08:00 - 11:00)",
                    type: "Member Syafea",
                    price: "Rp 100.000",
                    method: "QRIS",
                    status: "Aktif / Terverifikasi"
                },
                {
                    id: "#GOR-1066",
                    date: "18-06-2026",
                    name: "Fajar Alfian & Rekan",
                    schedule: "18-06-2026 (16:00 - 19:00)",
                    type: "Member Syafea",
                    price: "Rp 100.000",
                    method: "MANDIRI TRANSFER",
                    status: "Aktif / Terverifikasi"
                },
                {
                    id: "#GOR-1077",
                    date: "10-07-2026",
                    name: "PB Tangkas Syafea",
                    schedule: "10-07-2026 (19:00 - 22:00)",
                    type: "Member Syafea",
                    price: "Rp 150.000",
                    method: "QRIS",
                    status: "Aktif / Terverifikasi"
                },
                {
                    id: "#GOR-8821",
                    date: "{{ date('d-m-Y') }}",
                    name: "Budi Santoso",
                    schedule: "{{ date('d-m-Y') }} (08:00 - 11:00)",
                    type: "Member Syafea",
                    price: "Rp 50.000",
                    method: "QRIS",
                    status: "Aktif / Terverifikasi"
                }
            ];

            if (!history || history.length < 3) {
                history = sampleHistory;
                localStorage.setItem('syafea_history', JSON.stringify(history));
            }

            ownerAllHistory = history;
            applyOwnerFilters();
        }

        // Ekstraksi Tanggal dari Data Item Transaksi
        function parseItemDate(item) {
            let str = "";
            if (item.schedule) {
                const m = item.schedule.match(/(\d{1,4}[-/]\d{1,2}[-/]\d{1,4})/);
                if (m) str = m[1];
            }
            if (!str && item.date) {
                const m = item.date.match(/(\d{1,4}[-/]\d{1,2}[-/]\d{1,4})/);
                if (m) str = m[1];
                else str = item.date;
            }
            if (!str) return new Date();

            const p = str.split(/[-/]/);
            if (p.length === 3) {
                if (p[0].length === 4) {
                    return new Date(parseInt(p[0]), parseInt(p[1]) - 1, parseInt(p[2]));
                } else {
                    return new Date(parseInt(p[2]), parseInt(p[1]) - 1, parseInt(p[0]));
                }
            }
            const d = new Date(str);
            return isNaN(d.getTime()) ? new Date() : d;
        }

        // Format tanggal YYYY-MM-DD untuk komparasi
        function toYMD(d) {
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${day}`;
        }

        // Ganti Mode Grafik: 'daily' vs 'monthly'
        function setChartMode(mode) {
            currentChartMode = mode;
            const btnDaily = document.getElementById('btnModeDaily');
            const btnMonthly = document.getElementById('btnModeMonthly');
            const modeDisplay = document.getElementById('filterModeDisplay');

            if (mode === 'daily') {
                btnDaily.className = "px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-gradient-to-r from-tosca-600 via-cyan-600 to-blue-600 text-white shadow-sm flex items-center gap-1.5";
                btnMonthly.className = "px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:text-navy-950 transition-all flex items-center gap-1.5";
                modeDisplay.innerText = "Per Hari (Daily)";
            } else {
                btnMonthly.className = "px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-gradient-to-r from-tosca-600 via-cyan-600 to-blue-600 text-white shadow-sm flex items-center gap-1.5";
                btnDaily.className = "px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:text-navy-950 transition-all flex items-center gap-1.5";
                modeDisplay.innerText = "Per Bulan (Monthly)";
            }

            renderFilteredData();
        }

        // Preset Shortcut: 6 Mei - 6 Juni 2026
        function setPresetMeiJuni() {
            document.getElementById('filterStartDate').value = '2026-05-06';
            document.getElementById('filterEndDate').value = '2026-06-06';
            applyOwnerFilters();
        }

        // Reset Filter Tanggal
        function resetDateFilter() {
            document.getElementById('filterStartDate').value = '';
            document.getElementById('filterEndDate').value = '';
            applyOwnerFilters();
        }

        // Terapkan Filter Tanggal
        function applyOwnerFilters() {
            const startVal = document.getElementById('filterStartDate').value;
            const endVal = document.getElementById('filterEndDate').value;
            const infoText = document.getElementById('filterInfoText');

            const startDate = startVal ? new Date(startVal + 'T00:00:00') : null;
            const endDate = endVal ? new Date(endVal + 'T23:59:59') : null;

            if (startDate && endDate) {
                const sStr = `${startDate.getDate()} ${INDO_MONTHS_LONG[startDate.getMonth()]} ${startDate.getFullYear()}`;
                const eStr = `${endDate.getDate()} ${INDO_MONTHS_LONG[endDate.getMonth()]} ${endDate.getFullYear()}`;
                infoText.innerHTML = `Menampilkan periode: <strong class="text-tosca-700 font-bold">${sStr} s/d ${eStr}</strong>`;
            } else if (startDate) {
                const sStr = `${startDate.getDate()} ${INDO_MONTHS_LONG[startDate.getMonth()]} ${startDate.getFullYear()}`;
                infoText.innerHTML = `Menampilkan periode mulai: <strong class="text-tosca-700 font-bold">${sStr}</strong>`;
            } else if (endDate) {
                const eStr = `${endDate.getDate()} ${INDO_MONTHS_LONG[endDate.getMonth()]} ${endDate.getFullYear()}`;
                infoText.innerHTML = `Menampilkan periode hingga: <strong class="text-tosca-700 font-bold">${eStr}</strong>`;
            } else {
                infoText.innerText = "Menampilkan seluruh data transaksi riwayat penyewaan";
            }

            ownerFilteredHistory = ownerAllHistory.filter(item => {
                const itemD = parseItemDate(item);
                if (startDate && itemD < startDate) return false;
                if (endDate && itemD > endDate) return false;
                return true;
            });

            renderFilteredData();
        }

        // Render Data ke Stats Cards, Tabel, dan Chart
        function renderFilteredData() {
            const tbody = document.getElementById('ownerHistoryTableBody');
            tbody.innerHTML = "";

            let totalRev = 0;
            let groupMap = {}; // Key: YYYY-MM-DD (daily) atau YYYY-MM (monthly)

            ownerFilteredHistory.forEach(item => {
                const numericPrice = parseInt(item.price.replace(/[^0-9]/g, '')) || 0;
                const isVerified = item.status && item.status.includes('Terverifikasi');
                const isRejected = item.status && item.status.includes('Ditolak');

                if (isVerified) {
                    totalRev += numericPrice;
                }

                const itemDate = parseItemDate(item);
                let groupKey = "";
                let displayLabel = "";

                if (currentChartMode === 'daily') {
                    groupKey = toYMD(itemDate);
                    displayLabel = `${String(itemDate.getDate()).padStart(2, '0')} ${INDO_MONTHS_SHORT[itemDate.getMonth()]} ${itemDate.getFullYear()}`;
                } else {
                    const y = itemDate.getFullYear();
                    const m = String(itemDate.getMonth() + 1).padStart(2, '0');
                    groupKey = `${y}-${m}`;
                    displayLabel = `${INDO_MONTHS_LONG[itemDate.getMonth()]} ${y}`;
                }

                if (!groupMap[groupKey]) {
                    groupMap[groupKey] = {
                        label: displayLabel,
                        total: 0,
                        dateObj: itemDate
                    };
                }

                if (isVerified) {
                    groupMap[groupKey].total += numericPrice;
                }

                // Render Baris Tabel
                let badgeClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                if (isVerified) {
                    badgeClass = 'bg-tosca-50 text-tosca-700 border border-tosca-200';
                } else if (isRejected) {
                    badgeClass = 'bg-rose-50 text-rose-700 border border-rose-200';
                }

                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3.5 px-4 font-bold text-navy-950">${item.id}</td>
                        <td class="py-3.5 px-4">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">${item.name || item.type}</span>
                        </td>
                        <td class="py-3.5 px-4 font-medium text-slate-800">${item.schedule}</td>
                        <td class="py-3.5 px-4 font-bold text-navy-950">${item.price}</td>
                        <td class="py-3.5 px-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold ${badgeClass}">
                                ${item.status}
                            </span>
                        </td>
                    </tr>
                `;
            });

            if (ownerFilteredHistory.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400 italic">
                            <i class="fa-regular fa-calendar-xmark text-2xl block mb-1 text-slate-300"></i>
                            Tidak ada data transaksi pada rentang tanggal yang dipilih.
                        </td>
                    </tr>
                `;
            }

            // Update Statistik Ringkasan
            document.getElementById('totalRevenue').innerText = 'Rp ' + totalRev.toLocaleString('id-ID');
            document.getElementById('totalTransactions').innerText = ownerFilteredHistory.length + ' Transaksi';
            document.getElementById('chartBadgeCount').innerText = `${ownerFilteredHistory.length} Transaksi Ditemukan`;

            // Urutkan grup tanggal secara kronologis
            const sortedKeys = Object.keys(groupMap).sort((a, b) => a.localeCompare(b));
            const chartLabels = [];
            const chartValues = [];

            sortedKeys.forEach(k => {
                chartLabels.push(groupMap[k].label);
                chartValues.push(groupMap[k].total);
            });

            // Update Header Grafik
            const chartTitle = document.getElementById('chartTitle');
            const chartSubtitle = document.getElementById('chartSubtitle');
            if (currentChartMode === 'daily') {
                chartTitle.innerHTML = `<i class="fa-solid fa-chart-simple text-tosca-600"></i> Grafik Rekap Pendapatan Harian`;
                chartSubtitle.innerText = `Visualisasi pendapatan harian sesuai rentang tanggal yang dipilih`;
            } else {
                chartTitle.innerHTML = `<i class="fa-solid fa-chart-simple text-cyan-600"></i> Grafik Rekap Pendapatan Bulanan`;
                chartSubtitle.innerText = `Akumulasi total omzet per bulan dari transaksi yang terverifikasi`;
            }

            renderChart(chartLabels, chartValues);
        }

        function renderChart(labels, values) {
            const ctx = document.getElementById('rekapChart').getContext('2d');
            
            if (rekapChartInstance) {
                rekapChartInstance.destroy();
            }

            const effectiveLabels = labels.length > 0 ? labels : ['Tidak Ada Data'];
            const effectiveValues = values.length > 0 ? values : [0];

            let gradient = ctx.createLinearGradient(0, 0, 0, 320);
            if (currentChartMode === 'daily') {
                gradient.addColorStop(0, '#0d9488'); // tosca
                gradient.addColorStop(0.5, '#06b6d4'); // cyan
                gradient.addColorStop(1, '#1e3a8a'); // navy blue
            } else {
                gradient.addColorStop(0, '#2563eb'); // royal blue
                gradient.addColorStop(0.5, '#06b6d4'); // cyan
                gradient.addColorStop(1, '#0f766e'); // tosca
            }

            rekapChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: effectiveLabels,
                    datasets: [{
                        label: currentChartMode === 'daily' ? 'Omzet Harian (Rp)' : 'Omzet Bulanan (Rp)',
                        data: effectiveValues,
                        backgroundColor: gradient,
                        borderRadius: 10,
                        borderSkipped: false,
                        maxBarThickness: currentChartMode === 'daily' ? 45 : 70
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0a1128',
                            titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 10,
                            cornerRadius: 10,
                            callbacks: {
                                label: function(context) {
                                    return ' Total: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { family: 'Inter', size: 11, weight: 'bold' },
                                color: '#475569'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { family: 'Inter', size: 11 },
                                color: '#64748b',
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'jt';
                                    if (value >= 1000) return 'Rp ' + (value / 1000) + 'rb';
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
