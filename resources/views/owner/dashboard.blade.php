<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Pemilik - Laporan Rekap Transaksi Mini GOR Syafea</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-light min-vh-100 d-flex flex-column flex-md-row">

    <!-- MODAL LOGIN PEMILIK (KUNCI PRIVASI KEUANGAN) BOOTSTRAP 5 -->
    <div class="modal fade" id="ownerLoginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg p-4 text-center">
                <div class="bg-primary-subtle text-primary rounded-4 d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-lock fs-3"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">Area Private Pemilik</h4>
                <p class="text-muted small mb-4">Data omzet keuangan & rekap jadwal bersifat rahasia. Masukkan kredensial pemilik GOR.</p>
                
                <form onsubmit="handleOwnerLogin(event)" class="text-start vstack gap-3">
                    @csrf
                    <div>
                        <label class="form-label small fw-bold text-uppercase text-secondary mb-1">Username Pemilik</label>
                        <input type="text" id="ownerUser" required placeholder="Contoh: pemilik_syafea" class="form-control rounded-3 py-2">
                    </div>
                    <div>
                        <label class="form-label small fw-bold text-uppercase text-secondary mb-1">Password Rahasia</label>
                        <input type="password" id="ownerPass" required placeholder="••••••••" class="form-control rounded-3 py-2">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2.5 fw-bold text-uppercase small shadow-sm mt-2">
                        BUKA LAPORAN KEUANGAN
                    </button>
                </form>
                
                <div class="mt-4 pt-3 border-top">
                    <a href="{{ route('booking.index') }}" class="btn btn-link text-muted fw-bold small text-decoration-none p-0 d-inline-flex align-items-center gap-1">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda Utama
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SIDEBAR (KIRI) BOOTSTRAP 5 -->
    <aside class="bg-white border-end d-none d-md-flex flex-column justify-content-between p-3" style="width: 260px; min-height: 100vh;">
        <div>
            <div class="d-flex align-items-center gap-2 pb-3 mb-3 border-bottom">
                <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="fa-solid fa-volleyball"></i>
                </div>
                <div class="lh-sm">
                    <span class="fw-bold text-dark fs-6 d-block">MINI GOR SYAFEA</span>
                    <span class="text-primary fw-bold text-uppercase" style="font-size: 9px; letter-spacing: 1px;">PEMILIK PORTAL</span>
                </div>
            </div>

            <nav class="nav nav-pills flex-column gap-1">
                <a href="#" class="nav-link text-secondary fw-semibold rounded-3 py-2 px-3 d-flex align-items-center gap-2.5">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>
                <a href="#" class="nav-link active fw-bold rounded-3 py-2 px-3 d-flex align-items-center gap-2.5">
                    <i class="fa-solid fa-chart-column"></i> Laporan Rekap
                </a>
            </nav>
        </div>

        <!-- NAVIGASI KEMBALI DI SIDEBAR -->
        <div class="vstack gap-2 pt-3 border-top">
            <a href="{{ route('admin.index') }}" class="btn btn-sm btn-outline-primary fw-bold rounded-3 py-2 d-flex align-items-center justify-content-center gap-2">
                <i class="fa-solid fa-user-gear"></i> Portal Admin
            </a>
            <a href="{{ route('booking.index') }}" class="btn btn-sm btn-dark fw-bold rounded-3 py-2 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Home
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT (KANAN) -->
    <div class="flex-grow-1 d-flex flex-column min-vh-100">
        
        <!-- HEADER TOP -->
        <header class="bg-white border-bottom px-4 py-3 d-flex align-items-center justify-content-between shadow-sm sticky-top">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('booking.index') }}" class="btn btn-sm btn-light border d-md-none">
                    <i class="fa-solid fa-arrow-left"></i> Home
                </a>
                <h5 class="fw-bold text-dark text-uppercase mb-0 fs-6">LAPORAN KEUANGAN & REKAP JADWAL</h5>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" onclick="logoutOwner()" class="btn btn-sm btn-outline-danger fw-bold rounded-3 px-3 py-1.5 d-flex align-items-center gap-1.5" title="Kunci Kembali">
                    <i class="fa-solid fa-lock"></i> <span class="d-none d-sm-inline">Lock Owner</span>
                </button>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 d-none d-sm-inline-block">
                    Private Owner Mode
                </span>
            </div>
        </header>

        <main class="p-4 p-md-5 vstack gap-4 flex-grow-1">
            
            <!-- FILTER TANGGAL -->
            <div class="white-card p-3 p-md-4 rounded-4 shadow-sm d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2 bg-light border rounded-3 px-3 py-1.5">
                        <span class="small text-muted fw-semibold">Tgl Awal:</span>
                        <input type="date" value="{{ date('Y-m-01') }}" class="border-0 bg-transparent small fw-bold text-dark" style="outline: none;">
                    </div>
                    <span class="small text-muted fw-bold">s/d</span>
                    <div class="d-flex align-items-center gap-2 bg-light border rounded-3 px-3 py-1.5">
                        <span class="small text-muted fw-semibold">Tgl Akhir:</span>
                        <input type="date" value="{{ date('Y-m-d') }}" class="border-0 bg-transparent small fw-bold text-dark" style="outline: none;">
                    </div>
                    <button type="button" class="btn btn-primary btn-sm rounded-3 px-4 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-filter"></i> TAMPILKAN
                    </button>
                </div>
            </div>

            <!-- KOTAK BESAR RINGKASAN PENDAPATAN & TRANSAKSI -->
            <div class="card rounded-4 border-0 text-white shadow-lg p-4 p-md-5" style="background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);">
                <div class="row g-4 align-items-center">
                    <div class="col-12 col-md-6 border-end-md">
                        <span class="small text-uppercase fw-bold text-white-50 d-block mb-1">TOTAL PENDAPATAN (LIVE)</span>
                        <h2 id="totalRevenue" class="display-6 fw-black fw-bold mb-0">Rp 0</h2>
                    </div>
                    <div class="col-12 col-md-6 ps-md-4">
                        <span class="small text-uppercase fw-bold text-white-50 d-block mb-1">TOTAL TRANSAKSI</span>
                        <h2 id="totalTransactions" class="display-6 fw-black fw-bold mb-0">0 Transaksi</h2>
                    </div>
                </div>
            </div>

            <!-- TABEL RIWAYAT SELURUH JADWAL PESANAN (MEMBER / NON-MEMBER) -->
            <div class="white-card p-4 rounded-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-0 fs-6">
                        <i class="fa-solid fa-list-check text-primary"></i> Rekap Seluruh Jadwal & Riwayat Pesanan
                    </h5>
                    <span class="badge bg-success-subtle text-success">Live Data</span>
                </div>
                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light text-uppercase text-secondary">
                            <tr>
                                <th class="py-3 px-3">ID / Tgl Booking</th>
                                <th class="py-3 px-3">Jadwal Main</th>
                                <th class="py-3 px-3">Tipe Pemesan</th>
                                <th class="py-3 px-3">Total Biaya</th>
                                <th class="py-3 px-3">Status Konfirmasi</th>
                            </tr>
                        </thead>
                        <tbody id="ownerHistoryTableBody">
                            <!-- Diisi via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- GRAFIK BATANG TERURUT KRONOLOGIS -->
            <div class="white-card p-4 rounded-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-0 fs-6">
                        <i class="fa-solid fa-chart-line text-primary"></i> Grafik Pendapatan Harian (Urut Tanggal)
                    </h5>
                    <small class="text-muted">Berdasarkan Tanggal Jadwal Main</small>
                </div>
                <div style="height: 280px;">
                    <canvas id="rekapChart"></canvas>
                </div>
            </div>

        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let bsOwnerLoginModal = null;
        let rekapChartInstance = null;

        document.addEventListener('DOMContentLoaded', () => {
            const modalEl = document.getElementById('ownerLoginModal');
            if (modalEl) {
                bsOwnerLoginModal = new bootstrap.Modal(modalEl);
                bsOwnerLoginModal.show();
            }
            loadOwnerData();
        });

        function handleOwnerLogin(e) {
            e.preventDefault();
            if (bsOwnerLoginModal) bsOwnerLoginModal.hide();
            loadOwnerData();
        }

        function logoutOwner() {
            if (bsOwnerLoginModal) bsOwnerLoginModal.show();
        }

        function loadOwnerData() {
            const tbody = document.getElementById('ownerHistoryTableBody');
            let allHistory = JSON.parse(localStorage.getItem('syafea_history')) || [];

            tbody.innerHTML = "";

            if (allHistory.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="py-4 text-center text-muted fst-italic">Belum ada data jadwal pesanan masuk.</td>
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
                        <td class="py-3 px-3">
                            <span class="fw-bold text-dark d-block">${item.id}</span>
                            <small class="text-muted">${item.date}</small>
                        </td>
                        <td class="py-3 px-3">${item.schedule}</td>
                        <td class="py-3 px-3"><span class="badge bg-primary-subtle text-primary border border-primary-subtle">${item.type}</span></td>
                        <td class="py-3 px-3 fw-bold text-dark">${item.price}</td>
                        <td class="py-3 px-3"><span class="badge bg-success-subtle text-success">${item.status}</span></td>
                    </tr>
                `;
            });

            document.getElementById('totalRevenue').innerText = "Rp " + totalRev.toLocaleString('id-ID');
            document.getElementById('totalTransactions').innerText = allHistory.length + " Transaksi";

            // Urutkan tanggal secara kronologis
            let sortedDates = Object.keys(dateMap).sort((a, b) => {
                let partsA = a.split('-'); 
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
                        backgroundColor: '#2563eb',
                        borderRadius: 6,
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
