@extends('layouts.app')

@section('title', 'Jadwal & Pemesanan Lapangan - Mini GOR Syafea')

@section('content')
<div class="space-y-6" id="home">
    
    <!-- BANNER KHUSUS MEMBER AKTIF (HANYA TAMPIL JIKA LOGIN SEBAGAI MEMBER) -->
    <div id="memberWelcomeBanner" class="hidden bg-gradient-to-r from-tosca-700 via-cyan-800 to-navy-900 text-white p-4 sm:p-5 rounded-2xl flex-col sm:flex-row items-center justify-between gap-4 shadow-lg shadow-tosca-700/20 border border-tosca-500/30">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-white/15 text-tosca-200 flex items-center justify-center border border-white/20">
                <i class="fa-solid fa-award text-xl"></i>
            </div>
            <div>
                <h3 id="memberWelcomeTitle" class="text-sm font-bold text-white">Halo, Member Syafea!</h3>
                <p class="text-xs text-tosca-100">Hak istimewa custom jadwal & tarif diskon member telah aktif untuk akun Anda.</p>
            </div>
        </div>
        <button type="button" onclick="openHistoryModal()" class="px-4 py-2 bg-white text-tosca-700 hover:bg-slate-100 text-xs font-bold rounded-xl shadow-sm transition-all whitespace-nowrap">
            <i class="fa-solid fa-receipt mr-1"></i> Riwayat Pesanan Saya
        </button>
    </div>

    <!-- HERO SECTION DENGAN GRADASI TOSCA - BIRU - NAVY -->
    <section class="bg-gradient-to-br from-navy-950 via-slate-900 to-blue-950 text-white p-8 sm:p-12 text-center rounded-3xl relative overflow-hidden shadow-xl border border-slate-800">
        <!-- Background Lighting Orbs -->
        <div class="absolute -top-12 -left-12 w-48 h-48 bg-tosca-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-2xl mx-auto space-y-4">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-tosca-500/10 text-tosca-300 border border-tosca-500/30 text-xs font-bold tracking-wide uppercase">
                <span class="w-2 h-2 rounded-full bg-tosca-400 animate-pulse"></span> Sistem Informasi Pencatatan Penyewaan
            </div>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                PENYEWAAN LAPANGAN <br>
                <span class="bg-gradient-to-r from-tosca-400 via-cyan-400 to-blue-400 bg-clip-text text-transparent">MINI GOR SYAFEA</span>
            </h1>
            <p class="text-sm font-medium text-slate-300">
                <strong class="text-white">Jam Operasional: 08.00 - 22.00 WIB</strong> — 
                <span>Tersedia paket 3 jam hemat atau sewa per jam untuk badminton & voli.</span>
            </p>
        </div>
    </section>

    <!-- KONTROL KHUSUS MEMBER: PANEL CUSTOM JADWAL -->
    <div id="memberScheduleControl" class="hidden white-card p-5 sm:p-6 border border-tosca-200 bg-gradient-to-r from-tosca-50/50 to-cyan-50/50 glow-effect-tosca">
        <h4 class="text-sm font-bold text-navy-950 flex items-center gap-2 mb-1">
            <i class="fa-solid fa-sliders text-tosca-600"></i> Panel Custom Jadwal Khusus Member
        </h4>
        <p class="text-xs text-slate-600 mb-4">Sebagai member terdaftar, Anda memiliki hak istimewa mengatur tanggal dan durasi jam sewa secara fleksibel.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-4">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pilih Tanggal Sewa</label>
                <input type="date" id="customDate" value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:outline-none focus:border-tosca-500 focus:ring-2 focus:ring-tosca-400/20">
            </div>
            <div class="md:col-span-5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pilih Durasi / Jam Sewa</label>
                <select id="customTime" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:outline-none focus:border-tosca-500 focus:ring-2 focus:ring-tosca-400/20 cursor-pointer">
                    <option value="08:00 - 11:00 (Paket 3 Jam)">Paket 3 Jam (08:00 - 11:00)</option>
                    <option value="13:00 - 16:00 (Paket 3 Jam)">Paket 3 Jam (13:00 - 16:00)</option>
                    <option value="16:00 - 19:00 (Paket 3 Jam)">Paket 3 Jam (16:00 - 19:00)</option>
                    <option value="19:00 - 22:00 (Paket 3 Jam)">Paket 3 Jam (19:00 - 22:00)</option>
                    <option value="08:00 - 09:00 (Per Jam)">Per Jam (08:00 - 09:00)</option>
                    <option value="09:00 - 10:00 (Per Jam)">Per Jam (09:00 - 10:00)</option>
                </select>
            </div>
            <div class="md:col-span-3 flex items-end">
                <button type="button" onclick="applyCustomSchedule()" class="w-full py-2.5 bg-gradient-to-r from-tosca-500 via-cyan-600 to-blue-600 hover:opacity-95 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-md shadow-tosca-500/25 transition-all flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Terapkan Jadwal
                </button>
            </div>
        </div>
    </div>

    <!-- SECTION JADWAL TERSEDIA & FILTER TANGGAL -->
    <section id="jadwal" class="space-y-4">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
            <div>
                <h2 class="text-lg font-bold text-navy-950 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-tosca-600"></i> Ketersediaan Jadwal Lapangan
                </h2>
                <p class="text-xs text-slate-500">Pilih tanggal untuk melihat ketersediaan slot jam sewa lapangan</p>
            </div>
            
            <!-- FILTER TANGGAL TABEL -->
            <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-xl border border-slate-200 shadow-sm w-fit">
                <span class="text-xs font-bold text-slate-600 whitespace-nowrap flex items-center gap-1.5">
                    <i class="fa-regular fa-calendar text-tosca-600"></i> Tanggal Main:
                </span>
                <input type="date" id="tableDateFilter" value="{{ date('Y-m-d') }}" onchange="renderScheduleTable()" class="text-xs font-bold text-navy-950 bg-slate-50 px-2 py-1 rounded-lg border-0 focus:outline-none cursor-pointer">
            </div>
        </div>

        <!-- Tabel Jadwal -->
        <div class="white-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gradient-to-r from-slate-50 to-cyan-50/40 text-slate-600 uppercase font-bold tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4">Tanggal Sewa</th>
                            <th class="py-3.5 px-4">Slot Jam Lapangan</th>
                            <th class="py-3.5 px-4">Tarif Sewa</th>
                            <th class="py-3.5 px-4">Status Lapangan</th>
                            <th class="py-3.5 px-4 text-center">Aksi Pemesanan</th>
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
                    ? `<span class="line-through text-slate-400 text-[11px] mr-1">Rp 50.000</span> <span class="text-tosca-600 font-bold">Rp 40.000</span> <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-tosca-50 text-tosca-700 border border-tosca-200">Member</span>`
                    : `<span class="font-bold text-navy-950">Rp 50.000</span> <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">Umum</span>`;
            } else {
                priceVal = isMember ? "Rp 15.000" : "Rp 20.000";
                priceHtml = isMember 
                    ? `<span class="line-through text-slate-400 text-[11px] mr-1">Rp 20.000</span> <span class="text-tosca-600 font-bold">Rp 15.000</span> <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-tosca-50 text-tosca-700 border border-tosca-200">Member</span>`
                    : `<span class="font-bold text-navy-950">Rp 20.000</span> <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">Umum</span>`;
            }

            let statusHtml = isBooked 
                ? `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200"><span class="dot-indicator busy"></span> Penuh</span>`
                : `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-gradient-to-r from-tosca-50 to-cyan-50 text-tosca-700 border border-tosca-200"><span class="dot-indicator online"></span> Tersedia</span>`;

            let actionHtml = isBooked
                ? `<button disabled class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-400 bg-slate-100 cursor-not-allowed uppercase"><i class="fa-solid fa-lock mr-1"></i> Penuh</button>`
                : `<button onclick="bookSpecificSlot('${formattedDate}', '${time}', '${priceVal}')" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-tosca-500 via-cyan-600 to-blue-600 hover:opacity-95 shadow-sm shadow-tosca-500/25 transition-all uppercase"><i class="fa-solid fa-cart-plus mr-1"></i> PESAN SLOT</button>`;

            tbody.innerHTML += `
                <tr class="hover:bg-slate-50/80 transition-colors ${isBooked ? 'bg-slate-50/40 opacity-70' : ''}">
                    <td class="py-3.5 px-4"><div class="flex items-center gap-2"><i class="fa-regular fa-calendar-days text-tosca-600"></i><span class="font-bold text-navy-950">${formattedDate}</span></div></td>
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
