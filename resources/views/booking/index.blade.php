@extends('layouts.app')

@section('title', 'Jadwal & Sewa Lapangan - Mini GOR Syafea')

@section('content')
<div class="max-w-6xl w-full mx-auto px-4 py-8 space-y-8" id="home">
    
    <!-- BANNER STATUS PENGUNJUNG (GUEST / MEMBER) -->
    <div id="bannerStatus" class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-4 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 shadow-md">
        <div class="flex items-center gap-3">
            <div id="bannerIcon" class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center border border-amber-500/30">
                <i class="fa-solid fa-user-clock text-lg"></i>
            </div>
            <div>
                <h3 id="bannerTitle" class="text-sm font-bold">Akses Tamu (Regular)</h3>
                <p id="bannerDesc" class="text-xs text-slate-300">Anda dapat memesan jadwal yang tersedia dan mengecek riwayat pesanan.</p>
            </div>
        </div>
        <button type="button" onclick="openLoginModal()" id="btnActionBanner" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow transition-all whitespace-nowrap">
            Login / Daftar Member
        </button>
    </div>

    <!-- HERO SECTION -->
    <section class="relative rounded-3xl white-card p-8 md:p-14 text-center glow-effect-light overflow-hidden">
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-brand-50 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 space-y-4 max-w-2xl mx-auto">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-50 border border-brand-200 text-brand-700 text-xs font-semibold uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-brand-600 animate-pulse"></span> Sistem Booking Online Lapangan
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                SEWA LAPANGAN <br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-brand-600 via-indigo-600 to-blue-700">MINI GOR SYAFEA</span>
            </h1>
            <p class="text-lg text-slate-600 font-medium pt-1">
                <strong class="text-slate-800">Buka Pukul 08.00 - 22.00 WIB</strong> 
                <span class="text-slate-500 text-base font-normal">Pilih paket atau jam sesuai kebutuhan Anda.</span>
            </p>
        </div>
    </section>

    <!-- KONTROL KHUSUS MEMBER: PANEL CUSTOM JADWAL -->
    <div id="memberScheduleControl" class="hidden white-card p-6 rounded-2xl border border-brand-200 bg-brand-50/40 space-y-4">
        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-sliders text-brand-600"></i> Panel Custom Jadwal Member
        </h3>
        <p class="text-xs text-slate-600">Sebagai member, Anda bebas merancang tanggal dan jam sewa sesuai kebutuhan Anda.</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Tanggal</label>
                <input type="date" id="customDate" value="{{ date('Y-m-d') }}" class="form-control-modern">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Durasi / Jam</label>
                <select id="customTime" class="form-control-modern">
                    <option value="08:00 - 11:00 (Paket 3 Jam)">Paket 3 Jam (08:00 - 11:00)</option>
                    <option value="13:00 - 16:00 (Paket 3 Jam)">Paket 3 Jam (13:00 - 16:00)</option>
                    <option value="16:00 - 19:00 (Paket 3 Jam)">Paket 3 Jam (16:00 - 19:00)</option>
                    <option value="19:00 - 22:00 (Paket 3 Jam)">Paket 3 Jam (19:00 - 22:00)</option>
                    <option value="08:00 - 09:00 (Per Jam)">Per Jam (08:00 - 09:00)</option>
                    <option value="09:00 - 10:00 (Per Jam)">Per Jam (09:00 - 10:00)</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="button" onclick="applyCustomSchedule()" class="w-full py-2.5 btn-modern-primary text-xs uppercase tracking-wider">
                    <i class="fa-solid fa-check mr-1"></i> Terapkan Jadwal
                </button>
            </div>
        </div>
    </div>

    <!-- SECTION JADWAL TERSEDIA & FILTER TANGGAL -->
    <section id="jadwal" class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-brand-600"></i> Cek Ketersediaan & Jadwal
                </h2>
                <p class="text-sm text-slate-500">Pilih tanggal untuk melihat ketersediaan slot jam</p>
            </div>
            
            <!-- FILTER TANGGAL TABEL -->
            <div class="flex items-center gap-2 bg-white p-2 rounded-2xl border border-slate-200 shadow-sm">
                <span class="text-xs font-bold text-slate-600 pl-2">
                    <i class="fa-regular fa-calendar text-brand-600 mr-1"></i> Tanggal Main:
                </span>
                <input type="date" id="tableDateFilter" value="{{ date('Y-m-d') }}" onchange="renderScheduleTable()" class="px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-brand-600">
            </div>
        </div>

        <!-- Tabel Jadwal -->
        <div class="white-card rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam / Paket</th>
                            <th>Harga</th>
                            <th>Status Ketersediaan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleTableBody" class="divide-y divide-slate-200 text-sm">
                        <!-- Data slot dinamis -->
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
                    ? `<span class="line-through text-slate-400 text-xs mr-1">Rp 50.000</span> <span class="text-emerald-600 font-extrabold">Rp 40.000</span> <span class="badge badge-member">Member</span>`
                    : `Rp 50.000 <span class="badge badge-slate">Umum</span>`;
            } else {
                priceVal = isMember ? "Rp 15.000" : "Rp 20.000";
                priceHtml = isMember 
                    ? `<span class="line-through text-slate-400 text-xs mr-1">Rp 20.000</span> <span class="text-emerald-600 font-extrabold">Rp 15.000</span> <span class="badge badge-member">Member</span>`
                    : `Rp 20.000 <span class="badge badge-slate">Umum</span>`;
            }

            let statusHtml = isBooked 
                ? `<span class="badge badge-slate"><span class="dot-indicator busy"></span> Penuh</span>`
                : `<span class="badge badge-success"><span class="dot-indicator online"></span> Tersedia</span>`;

            let actionHtml = isBooked
                ? `<button disabled class="px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-200 border border-slate-300 rounded-xl cursor-not-allowed inline-flex items-center justify-center gap-2"><i class="fa-solid fa-lock text-xs"></i> Penuh</button>`
                : `<button onclick="bookSpecificSlot('${formattedDate}', '${time}', '${priceVal}')" class="px-5 py-2 text-xs font-bold uppercase tracking-wider text-white bg-brand-600 hover:bg-brand-700 active:scale-95 rounded-xl shadow-md shadow-brand-600/20 transition-all inline-flex items-center justify-center gap-2"><i class="fa-solid fa-cart-plus"></i> PESAN SLOT</button>`;

            tbody.innerHTML += `
                <tr class="${isBooked ? 'bg-slate-50/50' : ''}">
                    <td class="font-medium text-slate-800"><div class="flex items-center gap-2"><i class="fa-regular fa-calendar-days text-brand-600"></i><span>${formattedDate}</span></div></td>
                    <td class="font-semibold text-slate-900"><span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-lg border border-slate-200">${time}</span></td>
                    <td class="font-bold text-slate-900">${priceHtml}</td>
                    <td>${statusHtml}</td>
                    <td class="text-center">${actionHtml}</td>
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

        // Redirect ke route pemesanan Laravel
        window.location.href = "{{ route('booking.create') }}";
    }
</script>
@endsection

