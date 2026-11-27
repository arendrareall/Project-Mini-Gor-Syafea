@extends('layouts.app')

@section('title', 'Jadwal & Sewa Lapangan - Mini GOR Syafea')

@section('content')
<div class="space-y-6" id="home">
    
    <!-- BANNER STATUS PENGUNJUNG (GUEST / MEMBER) -->
    <div id="bannerStatus" class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-4 sm:p-5 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 shadow-md">
        <div class="flex items-center gap-3.5">
            <div id="bannerIcon" class="w-11 h-11 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center border border-amber-500/30">
                <i class="fa-solid fa-user-clock text-xl"></i>
            </div>
            <div>
                <h3 id="bannerTitle" class="text-sm font-bold text-white">Akses Tamu (Regular)</h3>
                <p id="bannerDesc" class="text-xs text-slate-300">Anda dapat memesan jadwal yang tersedia dan mengecek riwayat pesanan.</p>
            </div>
        </div>
        <button type="button" onclick="openLoginModal()" id="btnActionBanner" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow transition-all whitespace-nowrap">
            Login / Daftar Member
        </button>
    </div>

    <!-- HERO SECTION -->
    <section class="white-card p-6 sm:p-10 text-center glow-effect-light relative overflow-hidden">
        <div class="relative z-10 max-w-2xl mx-auto space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-50 text-brand-600 border border-brand-100 text-xs font-bold tracking-wide uppercase">
                <span class="dot-indicator online"></span> Sistem Booking Online Lapangan
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                SEWA LAPANGAN <br>
                <span class="bg-gradient-to-r from-brand-600 to-indigo-600 bg-clip-text text-transparent">MINI GOR SYAFEA</span>
            </h1>
            <p class="text-sm font-medium text-slate-600">
                <strong class="text-slate-900">Buka Pukul 08.00 - 22.00 WIB</strong> — 
                <span>Pilih paket atau jam sewa sesuai kebutuhan olahraga Anda.</span>
            </p>
        </div>
    </section>

    <!-- KONTROL KHUSUS MEMBER: PANEL CUSTOM JADWAL -->
    <div id="memberScheduleControl" class="hidden white-card p-5 sm:p-6 border border-brand-200 bg-brand-50/40">
        <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2 mb-1">
            <i class="fa-solid fa-sliders text-brand-600"></i> Panel Custom Jadwal Member
        </h4>
        <p class="text-xs text-slate-600 mb-4">Sebagai member resmi, Anda bebas menentukan tanggal dan durasi jam sewa secara fleksibel.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-4">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pilih Tanggal</label>
                <input type="date" id="customDate" value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:outline-none focus:border-brand-600">
            </div>
            <div class="md:col-span-5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pilih Durasi / Jam</label>
                <select id="customTime" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:outline-none focus:border-brand-600 cursor-pointer">
                    <option value="08:00 - 11:00 (Paket 3 Jam)">Paket 3 Jam (08:00 - 11:00)</option>
                    <option value="13:00 - 16:00 (Paket 3 Jam)">Paket 3 Jam (13:00 - 16:00)</option>
                    <option value="16:00 - 19:00 (Paket 3 Jam)">Paket 3 Jam (16:00 - 19:00)</option>
                    <option value="19:00 - 22:00 (Paket 3 Jam)">Paket 3 Jam (19:00 - 22:00)</option>
                    <option value="08:00 - 09:00 (Per Jam)">Per Jam (08:00 - 09:00)</option>
                    <option value="09:00 - 10:00 (Per Jam)">Per Jam (09:00 - 10:00)</option>
                </select>
            </div>
            <div class="md:col-span-3 flex items-end">
                <button type="button" onclick="applyCustomSchedule()" class="w-full py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-md shadow-brand-500/20 transition-all flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Terapkan Jadwal
                </button>
            </div>
        </div>
    </div>

    <!-- SECTION JADWAL TERSEDIA & FILTER TANGGAL -->
    <section id="jadwal" class="space-y-4">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-brand-600"></i> Cek Ketersediaan & Jadwal
                </h2>
                <p class="text-xs text-slate-500">Pilih tanggal untuk melihat ketersediaan slot jam</p>
            </div>
            
            <!-- FILTER TANGGAL TABEL -->
            <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-xl border border-slate-200 shadow-sm w-fit">
                <span class="text-xs font-bold text-slate-600 whitespace-nowrap flex items-center gap-1.5">
                    <i class="fa-regular fa-calendar text-brand-600"></i> Tanggal Main:
                </span>
                <input type="date" id="tableDateFilter" value="{{ date('Y-m-d') }}" onchange="renderScheduleTable()" class="text-xs font-bold text-slate-900 bg-slate-50 px-2 py-1 rounded-lg border-0 focus:outline-none cursor-pointer">
            </div>
        </div>

        <!-- Tabel Jadwal Tailwind -->
        <div class="white-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-bold tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4">Tanggal</th>
                            <th class="py-3.5 px-4">Jam / Paket</th>
                            <th class="py-3.5 px-4">Harga</th>
                            <th class="py-3.5 px-4">Status Ketersediaan</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleTableBody" class="divide-y divide-slate-100 font-medium text-slate-700">
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
                    ? `<span class="line-through text-slate-400 text-[11px] mr-1">Rp 50.000</span> <span class="text-emerald-600 font-bold">Rp 40.000</span> <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Member</span>`
                    : `<span class="font-bold text-slate-900">Rp 50.000</span> <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">Umum</span>`;
            } else {
                priceVal = isMember ? "Rp 15.000" : "Rp 20.000";
                priceHtml = isMember 
                    ? `<span class="line-through text-slate-400 text-[11px] mr-1">Rp 20.000</span> <span class="text-emerald-600 font-bold">Rp 15.000</span> <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Member</span>`
                    : `<span class="font-bold text-slate-900">Rp 20.000</span> <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">Umum</span>`;
            }

            let statusHtml = isBooked 
                ? `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200"><span class="dot-indicator busy"></span> Penuh</span>`
                : `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="dot-indicator online"></span> Tersedia</span>`;

            let actionHtml = isBooked
                ? `<button disabled class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-400 bg-slate-100 cursor-not-allowed uppercase"><i class="fa-solid fa-lock mr-1"></i> Penuh</button>`
                : `<button onclick="bookSpecificSlot('${formattedDate}', '${time}', '${priceVal}')" class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-sm transition-all uppercase"><i class="fa-solid fa-cart-plus mr-1"></i> PESAN SLOT</button>`;

            tbody.innerHTML += `
                <tr class="hover:bg-slate-50/80 transition-colors ${isBooked ? 'bg-slate-50/40 opacity-70' : ''}">
                    <td class="py-3.5 px-4"><div class="flex items-center gap-2"><i class="fa-regular fa-calendar-days text-brand-600"></i><span class="font-bold text-slate-900">${formattedDate}</span></div></td>
                    <td class="py-3.5 px-4"><span class="inline-block px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-slate-800 font-mono text-[11px]">${time}</span></td>
                    <td class="py-3.5 px-4">${priceHtml}</td>
                    <td class="py-3.5 px-4">${statusHtml}</td>
                    <td class="py-3.5 px-4 text-center">${actionHtml}</td>
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
