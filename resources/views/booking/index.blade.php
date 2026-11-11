@extends('layouts.app')

@section('title', 'Jadwal & Sewa Lapangan - Mini GOR Syafea')

@section('content')
<div class="container py-4" id="home">
    
    <!-- BANNER STATUS PENGUNJUNG (GUEST / MEMBER) -->
    <div id="bannerStatus" class="bg-gradient-banner text-white p-4 rounded-4 shadow-sm d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div id="bannerIcon" class="rounded-3 bg-warning bg-opacity-25 text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="fa-solid fa-user-clock fs-4"></i>
            </div>
            <div>
                <h5 id="bannerTitle" class="fw-bold mb-1 fs-6">Akses Tamu (Regular)</h5>
                <p id="bannerDesc" class="small text-white-50 mb-0">Anda dapat memesan jadwal yang tersedia dan mengecek riwayat pesanan.</p>
            </div>
        </div>
        <button type="button" onclick="openLoginModal()" id="btnActionBanner" class="btn btn-primary btn-sm fw-bold px-3 py-2 rounded-3 text-nowrap shadow-sm">
            Login / Daftar Member
        </button>
    </div>

    <!-- HERO SECTION -->
    <section class="white-card p-4 p-md-5 text-center glow-effect-light mb-4 position-relative overflow-hidden">
        <div class="position-relative" style="z-index: 2;">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-primary-subtle text-primary border border-primary-subtle small fw-bold text-uppercase mb-3">
                <span class="dot-indicator online"></span> Sistem Booking Online Lapangan
            </div>
            <h1 class="display-5 fw-black text-dark tracking-tight mb-2 fw-bold">
                SEWA LAPANGAN <br>
                <span class="bg-gradient-hero">MINI GOR SYAFEA</span>
            </h1>
            <p class="lead text-secondary mb-0 pt-1 fs-6">
                <strong class="text-dark">Buka Pukul 08.00 - 22.00 WIB</strong> — 
                <span class="text-muted">Pilih paket atau jam sesuai kebutuhan Anda.</span>
            </p>
        </div>
    </section>

    <!-- KONTROL KHUSUS MEMBER: PANEL CUSTOM JADWAL -->
    <div id="memberScheduleControl" class="d-none white-card p-4 mb-4 border border-primary-subtle bg-primary-subtle bg-opacity-10">
        <h5 class="fw-bold text-dark d-flex align-items-center gap-2 mb-2 fs-6">
            <i class="fa-solid fa-sliders text-primary"></i> Panel Custom Jadwal Member
        </h5>
        <p class="small text-secondary mb-3">Sebagai member resmi, Anda bebas menentukan tanggal dan durasi jam sewa secara fleksibel.</p>
        
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <label class="form-label small fw-bold text-uppercase text-secondary mb-1">Pilih Tanggal</label>
                <input type="date" id="customDate" value="{{ date('Y-m-d') }}" class="form-control rounded-3">
            </div>
            <div class="col-12 col-md-5">
                <label class="form-label small fw-bold text-uppercase text-secondary mb-1">Pilih Durasi / Jam</label>
                <select id="customTime" class="form-select rounded-3">
                    <option value="08:00 - 11:00 (Paket 3 Jam)">Paket 3 Jam (08:00 - 11:00)</option>
                    <option value="13:00 - 16:00 (Paket 3 Jam)">Paket 3 Jam (13:00 - 16:00)</option>
                    <option value="16:00 - 19:00 (Paket 3 Jam)">Paket 3 Jam (16:00 - 19:00)</option>
                    <option value="19:00 - 22:00 (Paket 3 Jam)">Paket 3 Jam (19:00 - 22:00)</option>
                    <option value="08:00 - 09:00 (Per Jam)">Per Jam (08:00 - 09:00)</option>
                    <option value="09:00 - 10:00 (Per Jam)">Per Jam (09:00 - 10:00)</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex align-items-end">
                <button type="button" onclick="applyCustomSchedule()" class="btn btn-primary w-100 rounded-3 py-2 fw-bold small text-uppercase shadow-sm">
                    <i class="fa-solid fa-check me-1"></i> Terapkan Jadwal
                </button>
            </div>
        </div>
    </div>

    <!-- SECTION JADWAL TERSEDIA & FILTER TANGGAL -->
    <section id="jadwal" class="mb-4">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
            <div>
                <h4 class="fw-bold text-dark d-flex align-items-center gap-2 mb-1 fs-5">
                    <i class="fa-solid fa-calendar-check text-primary"></i> Cek Ketersediaan & Jadwal
                </h4>
                <p class="text-muted small mb-0">Pilih tanggal untuk melihat ketersediaan slot jam</p>
            </div>
            
            <!-- FILTER TANGGAL TABEL -->
            <div class="d-flex align-items-center gap-2 bg-white p-2 rounded-3 border shadow-sm">
                <span class="small fw-bold text-secondary ps-2 text-nowrap">
                    <i class="fa-regular fa-calendar text-primary me-1"></i> Tanggal Main:
                </span>
                <input type="date" id="tableDateFilter" value="{{ date('Y-m-d') }}" onchange="renderScheduleTable()" class="form-control form-control-sm border-0 bg-light rounded-2 fw-bold text-dark">
            </div>
        </div>

        <!-- Tabel Jadwal Bootstrap 5 -->
        <div class="white-card overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase small text-secondary">
                        <tr>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Jam / Paket</th>
                            <th class="py-3 px-4">Harga</th>
                            <th class="py-3 px-4">Status Ketersediaan</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleTableBody" class="small">
                        <!-- Diisi secara dinamis via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>

</div>
@endsection

@section('scripts')
<script>
    const standardSlots = [
        "08:00 - 11:00 (Paket 3 Jam)",
        "13:00 - 16:00 (Paket 3 Jam)",
        "16:00 - 19:00 (Paket 3 Jam)",
        "19:00 - 22:00 (Paket 3 Jam)",
        "08:00 - 09:00 (Per Jam)",
        "09:00 - 10:00 (Per Jam)"
    ];

    document.addEventListener('DOMContentLoaded', () => {
        renderScheduleTable();
    });

    function renderScheduleTable() {
        const dateInput = document.getElementById('tableDateFilter').value;
        const formattedDate = dateInput ? dateInput.split('-').reverse().join('-') : "{{ date('d-m-Y') }}";
        const tbody = document.getElementById('scheduleTableBody');
        const isMember = localStorage.getItem('syafea_member');
        let history = JSON.parse(localStorage.getItem('syafea_history')) || [];

        tbody.innerHTML = "";

        standardSlots.forEach((time) => {
            let isBooked = history.some(item => {
                return item.schedule.includes(formattedDate) && item.schedule.includes(time) && !item.status.includes('Ditolak');
            });

            let isPaket3Jam = time.includes("Paket 3 Jam");
            let priceVal, priceHtml;

            if (isPaket3Jam) {
                priceVal = isMember ? "Rp 40.000" : "Rp 50.000";
                priceHtml = isMember 
                    ? `<span class="text-decoration-line-through text-muted small me-1">Rp 50.000</span> <span class="text-success fw-bold">Rp 40.000</span> <span class="badge bg-success-subtle text-success border border-success-subtle">Member</span>`
                    : `<span class="fw-bold text-dark">Rp 50.000</span> <span class="badge bg-secondary-subtle text-secondary">Umum</span>`;
            } else {
                priceVal = isMember ? "Rp 15.000" : "Rp 20.000";
                priceHtml = isMember 
                    ? `<span class="text-decoration-line-through text-muted small me-1">Rp 20.000</span> <span class="text-success fw-bold">Rp 15.000</span> <span class="badge bg-success-subtle text-success border border-success-subtle">Member</span>`
                    : `<span class="fw-bold text-dark">Rp 20.000</span> <span class="badge bg-secondary-subtle text-secondary">Umum</span>`;
            }

            let statusHtml = isBooked 
                ? `<span class="badge bg-secondary-subtle text-secondary px-3 py-1.5 rounded-pill"><span class="dot-indicator busy"></span> Penuh</span>`
                : `<span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill"><span class="dot-indicator online"></span> Tersedia</span>`;

            let actionHtml = isBooked
                ? `<button disabled class="btn btn-sm btn-secondary disabled px-3 py-1.5 rounded-3 fw-bold small text-uppercase"><i class="fa-solid fa-lock me-1"></i> Penuh</button>`
                : `<button onclick="bookSpecificSlot('${formattedDate}', '${time}', '${priceVal}')" class="btn btn-sm btn-primary px-3 py-1.5 rounded-3 fw-bold small text-uppercase shadow-sm"><i class="fa-solid fa-cart-plus me-1"></i> PESAN SLOT</button>`;

            tbody.innerHTML += `
                <tr class="${isBooked ? 'table-light' : ''}">
                    <td class="py-3 px-4"><div class="d-flex align-items-center gap-2"><i class="fa-regular fa-calendar-days text-primary"></i><span class="fw-semibold text-dark">${formattedDate}</span></div></td>
                    <td class="py-3 px-4"><span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-2 font-monospace">${time}</span></td>
                    <td class="py-3 px-4">${priceHtml}</td>
                    <td class="py-3 px-4">${statusHtml}</td>
                    <td class="py-3 px-4 text-center">${actionHtml}</td>
                </tr>
            `;
        });
    }

    function applyCustomSchedule() {
        const selectedDate = document.getElementById('customDate').value;
        const selectedTime = document.getElementById('customTime').value;
        const formattedDate = selectedDate.split('-').reverse().join('-');
        
        const isMember = localStorage.getItem('syafea_member');
        let priceVal = selectedTime.includes("Paket 3 Jam") 
            ? (isMember ? "Rp 40.000" : "Rp 50.000") 
            : (isMember ? "Rp 15.000" : "Rp 20.000");

        if (isScheduleBooked(formattedDate, selectedTime)) {
            alert("❌ Maaf, jadwal pada tanggal dan jam tersebut sudah penuh/dipesan! Silakan pilih jam atau tanggal lain.");
            return;
        }

        document.getElementById('tableDateFilter').value = selectedDate;
        renderScheduleTable();
        bookSpecificSlot(formattedDate, selectedTime, priceVal);
    }

    function isScheduleBooked(date, time) {
        let history = JSON.parse(localStorage.getItem('syafea_history')) || [];
        return history.some(item => {
            return item.schedule.includes(date) && item.schedule.includes(time) && !item.status.includes('Ditolak');
        });
    }

    function bookSpecificSlot(date, time, price) {
        if (isScheduleBooked(date, time)) {
            alert("❌ Slot ini sudah penuh dipesan pengguna lain.");
            renderScheduleTable();
            return;
        }

        const isMember = localStorage.getItem('syafea_member');
        const type = isMember ? `Member (${isMember})` : "Tamu (Regular)";

        const bookingData = {
            date: date,
            time: time,
            price: price,
            type: type,
            isMember: !!isMember
        };
        localStorage.setItem('syafea_active_booking', JSON.stringify(bookingData));

        window.location.href = "{{ route('booking.create') }}";
    }
</script>
@endsection
