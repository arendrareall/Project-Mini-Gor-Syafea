@extends('layouts.app')

@section('title', 'Formulir Pemesanan - Mini GOR Syafea')

@section('content')
<div class="max-w-lg mx-auto px-4 py-8">
    
    <!-- Form Card Container -->
    <div class="w-full white-card rounded-3xl p-6 sm:p-8 relative overflow-hidden glow-effect-light">
        
        <!-- Header Decor Line -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-brand-600 via-indigo-600 to-blue-500"></div>

        <!-- Tombol Kembali / Back -->
        <div class="mb-4">
            <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-all">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Jadwal
            </a>
        </div>

        <!-- Header Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 border border-brand-100 mb-3 shadow-sm">
                <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">FORMULIR PEMESANAN</h1>
            <p class="text-xs font-medium text-slate-500 mt-1">Lengkapi data diri dan konfirmasi pembayaran sewa lapangan</p>
        </div>

        <!-- Form Elements -->
        <form id="orderForm" action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data" onsubmit="handleSubmitBooking(event)" class="space-y-4">
            @csrf

            <!-- Hidden Field untuk Data Booking -->
            <input type="hidden" name="booking_date" id="inputBookingDate">
            <input type="hidden" name="booking_time" id="inputBookingTime">
            <input type="hidden" name="booking_price" id="inputBookingPrice">
            <input type="hidden" name="user_type" id="inputUserType">

            <!-- Input: Nama Lengkap -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-user text-sm"></i>
                    </div>
                    <input type="text" name="full_name" id="fullName" required placeholder="Masukkan nama lengkap Anda" 
                        class="form-control-modern pl-10" value="{{ old('full_name') }}">
                </div>
                @error('full_name')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Input: No Telepon -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    No. Telepon / WhatsApp <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                    </div>
                    <input type="tel" name="phone_number" id="phone" required placeholder="08xxxxxxxxxx" 
                        class="form-control-modern pl-10" value="{{ old('phone_number') }}">
                </div>
                @error('phone_number')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Input: Alamat -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Alamat Lengkap <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 pt-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-location-dot text-sm"></i>
                    </div>
                    <textarea name="address" id="address" rows="2" required placeholder="Masukkan alamat lengkap Anda" 
                        class="form-control-modern pl-10">{{ old('address') }}</textarea>
                </div>
                @error('address')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Total Tagihan Box -->
            <div class="p-4 rounded-2xl bg-brand-50/70 border border-brand-200 flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-500 font-medium block">Total Tagihan</span>
                    <span id="displayPrice" class="text-2xl font-black text-brand-600">Rp 50.000</span>
                </div>
                <div class="text-right">
                    <span id="displayDate" class="px-3 py-1 bg-white border border-brand-200 text-brand-700 text-xs font-bold rounded-lg shadow-sm block">
                        {{ date('d-m-Y') }}
                    </span>
                    <span id="displayTime" class="text-[11px] font-semibold text-slate-500 mt-1 block">08:00 - 11:00</span>
                </div>
            </div>

            <!-- Select: Metode Bayar -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Metode Pembayaran <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-credit-card text-sm"></i>
                    </div>
                    <select name="payment_method" class="form-control-modern pl-10 pr-10 appearance-none cursor-pointer">
                        <option value="bca">Transfer Bank - BCA (123-456-7890)</option>
                        <option value="mandiri">Transfer Bank - Mandiri (987-654-3210)</option>
                        <option value="qris">QRIS (Semua E-Wallet / Mobile Banking)</option>
                        <option value="gopay">GoPay / DANA / OVO</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Upload Bukti Transfer -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Upload Bukti Transfer <span class="text-rose-500">*</span>
                </label>
                <div class="custom-file-dropzone group" onclick="document.getElementById('fileInput').click()">
                    <input type="file" name="payment_proof" id="fileInput" class="hidden" accept="image/*" onchange="updateFileName(this)">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 group-hover:text-brand-600 group-hover:border-brand-300 shadow-sm transition-all mb-2">
                        <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                    </div>
                    <p id="fileNameText" class="text-xs font-medium text-slate-600">
                        <span class="text-brand-600 font-bold">Pilih File...</span> atau seret gambar bukti bayar ke sini
                    </p>
                    <p class="text-[10px] text-slate-400 mt-1">Format gambar: JPG, PNG, WEBP (Maks. 5MB)</p>
                </div>
                @error('payment_proof')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tombol Konfirmasi -->
            <button type="submit" class="w-full mt-6 py-3.5 px-6 btn-modern-primary flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
                <i class="fa-solid fa-circle-check"></i>
                <span>KONFIRMASI PEMBAYARAN</span>
            </button>

        </form>
    </div>

</div>

<!-- Modal Success -->
<div id="successModal" class="fixed inset-0 modal-backdrop flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-3xl p-6 max-w-sm w-full text-center border border-slate-200 shadow-2xl">
        <div class="w-14 h-14 bg-emerald-100 border border-emerald-200 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-check text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">Pembayaran Berhasil!</h3>
        <p class="text-xs text-slate-500 mb-6 leading-relaxed">Pesanan Anda telah dikonfirmasi dan otomatis masuk ke dalam riwayat pemesanan sistem.</p>
        <button type="button" onclick="finishBooking()" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl shadow-md transition-all">
            Kembali ke Beranda
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let activeBooking = JSON.parse(localStorage.getItem('syafea_active_booking')) || {
        date: "{{ date('d-m-Y') }}",
        time: "08:00 - 11:00 (Paket 3 Jam)",
        price: "Rp 50.000",
        type: "Tamu (Regular)",
        isMember: false
    };

    document.getElementById('displayPrice').innerText = activeBooking.price;
    document.getElementById('displayDate').innerText = activeBooking.date;
    document.getElementById('displayTime').innerText = activeBooking.time;

    document.getElementById('inputBookingDate').value = activeBooking.date;
    document.getElementById('inputBookingTime').value = activeBooking.time;
    document.getElementById('inputBookingPrice').value = activeBooking.price;
    document.getElementById('inputUserType').value = activeBooking.type;

    const memberName = localStorage.getItem('syafea_member');
    if (memberName) {
        document.getElementById('fullName').value = memberName;
    }

    function updateFileName(input) {
        const textElement = document.getElementById('fileNameText');
        if (input.files && input.files[0]) {
            textElement.innerHTML = `<span class="text-emerald-600 font-bold"><i class="fa-solid fa-file-check mr-1"></i> ${input.files[0].name}</span>`;
        }
    }

    function handleSubmitBooking(e) {
        // Simpan data transaksi secara real-time ke localStorage juga agar sinkron dengan client
        let history = JSON.parse(localStorage.getItem('syafea_history')) || [];
        
        history.unshift({
            id: "#GOR-" + Math.floor(1000 + Math.random() * 9000),
            date: new Date().toLocaleDateString('id-ID'),
            schedule: `${activeBooking.date} (${activeBooking.time})`,
            type: activeBooking.type,
            price: activeBooking.price,
            status: activeBooking.isMember ? "Aktif / Terverifikasi" : "Pending Verifikasi"
        });

        localStorage.setItem('syafea_history', JSON.stringify(history));

        // Jika form disubmit sebagai AJAX atau simulasi tampilan:
        e.preventDefault();
        document.getElementById('successModal').classList.remove('hidden');
    }

    function finishBooking() {
        localStorage.removeItem('syafea_active_booking');
        window.location.href = "{{ route('booking.index') }}";
    }
</script>
@endsection

