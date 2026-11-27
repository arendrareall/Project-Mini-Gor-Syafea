@extends('layouts.app')

@section('title', 'Formulir Pemesanan & Pembayaran - Mini GOR Syafea')

@section('content')
<div class="max-w-xl mx-auto py-4">
    
    <!-- Form Card Container -->
    <div class="white-card rounded-3xl p-6 sm:p-8 relative overflow-hidden glow-effect-light">
        
        <!-- Header Decor Line -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-brand-600 via-indigo-600 to-blue-500"></div>

        <!-- Tombol Kembali / Back -->
        <div class="mb-4">
            <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-all">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Jadwal Lapangan
            </a>
        </div>

        <!-- Header Title -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 border border-brand-100 mb-2.5 shadow-sm">
                <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">FORMULIR PEMESANAN & PEMBAYARAN</h1>
            <p class="text-xs font-medium text-slate-500 mt-1">Lengkapi data diri penyewa dan konfirmasi metode pembayaran sewa lapangan</p>
        </div>

        <!-- Form Elements -->
        <form id="orderForm" action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data" onsubmit="handleSubmitBooking(event)" class="space-y-4">
            @csrf

            <!-- Hidden Field untuk Data Booking -->
            <input type="hidden" name="booking_date" id="inputBookingDate">
            <input type="hidden" name="booking_time" id="inputBookingTime">
            <input type="hidden" name="booking_price" id="inputBookingPrice">
            <input type="hidden" name="user_type" id="inputUserType">

            <!-- Input: Nama Lengkap Penyewa -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Lengkap Penyewa <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-user text-sm"></i>
                    </div>
                    <input type="text" name="full_name" id="fullName" required placeholder="Masukkan nama lengkap penyewa" 
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-brand-600 focus:bg-white focus:ring-2 focus:ring-brand-500/20 transition-all" value="{{ old('full_name') }}">
                </div>
                @error('full_name')
                    <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Input: No Telepon Penyewa -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    No. Telepon / WhatsApp <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                    </div>
                    <input type="tel" name="phone_number" id="phone" required placeholder="Contoh: 081234567890" 
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-brand-600 focus:bg-white focus:ring-2 focus:ring-brand-500/20 transition-all" value="{{ old('phone_number') }}">
                </div>
                @error('phone_number')
                    <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Input: Alamat Penyewa -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Alamat Lengkap Penyewa <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 pt-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-location-dot text-sm"></i>
                    </div>
                    <textarea name="address" id="address" rows="2" required placeholder="Masukkan alamat lengkap penyewa" 
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-brand-600 focus:bg-white focus:ring-2 focus:ring-brand-500/20 transition-all">{{ old('address') }}</textarea>
                </div>
                @error('address')
                    <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Total Tagihan Box -->
            <div class="p-4 rounded-2xl bg-brand-50/70 border border-brand-200 flex items-center justify-between my-2">
                <div>
                    <span class="text-[11px] font-semibold text-slate-500 block mb-0.5">Total Tagihan Penyewaan</span>
                    <span id="displayPrice" class="text-2xl font-black text-brand-600">Rp 50.000</span>
                </div>
                <div class="text-right">
                    <span id="displayDate" class="inline-block px-3 py-1 rounded-lg bg-white text-brand-700 border border-brand-200 text-xs font-bold shadow-sm">
                        {{ date('d-m-Y') }}
                    </span>
                    <small id="displayTime" class="text-slate-500 block mt-1 font-mono text-[11px]">08:00 - 11:00</small>
                </div>
            </div>

            <!-- Select: Metode Bayar -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Pilihan Metode Pembayaran <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-credit-card text-sm"></i>
                    </div>
                    <select name="payment_method" id="paymentMethodSelect" onchange="handlePaymentChange(this.value)" 
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-brand-600 focus:bg-white cursor-pointer font-medium">
                        <option value="bca">Transfer Bank - BCA (123-456-7890)</option>
                        <option value="mandiri">Transfer Bank - Mandiri (987-654-3210)</option>
                        <option value="qris" selected>QRIS (Semua E-Wallet & Mobile Banking)</option>
                        <option value="gopay">E-Wallet (GoPay / DANA / OVO)</option>
                    </select>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- DETAIL PEMBAYARAN INTERAKTIF -->
            <!-- ========================================== -->
            <div id="paymentDetailContainer">
                
                <!-- 1. KOTAK SIMULASI QRIS -->
                <div id="qrisBox" class="p-5 bg-white border-2 border-rose-200 rounded-3xl text-center shadow-sm my-3 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-rose-600 text-white text-[10px] font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-qrcode"></i> QRIS STANDAR NASIONAL
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono">NMID: ID1029384756201</span>
                    </div>
                    
                    <div>
                        <h4 class="font-black text-slate-900 text-base">MINI GOR SYAFEA</h4>
                        <p class="text-[11px] text-slate-500">Sistem Pembayaran Instan Semua E-Wallet & Mobile Banking</p>
                    </div>

                    <!-- Gambar Simulasi QR Code QRIS -->
                    <div class="inline-block p-3.5 bg-white border-2 border-slate-900 rounded-2xl shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 160" width="160" height="160" class="block mx-auto">
                            <!-- Background -->
                            <rect width="160" height="160" fill="#ffffff"/>
                            <!-- Top-Left Target -->
                            <rect x="10" y="10" width="40" height="40" fill="#0f172a" rx="4"/>
                            <rect x="18" y="18" width="24" height="24" fill="#ffffff" rx="2"/>
                            <rect x="24" y="24" width="12" height="12" fill="#0f172a"/>
                            <!-- Top-Right Target -->
                            <rect x="110" y="10" width="40" height="40" fill="#0f172a" rx="4"/>
                            <rect x="118" y="18" width="24" height="24" fill="#ffffff" rx="2"/>
                            <rect x="124" y="24" width="12" height="12" fill="#0f172a"/>
                            <!-- Bottom-Left Target -->
                            <rect x="10" y="110" width="40" height="40" fill="#0f172a" rx="4"/>
                            <rect x="18" y="118" width="24" height="24" fill="#ffffff" rx="2"/>
                            <rect x="24" y="124" width="12" height="12" fill="#0f172a"/>
                            <!-- Data Bits Pattern -->
                            <rect x="60" y="15" width="8" height="8" fill="#0f172a"/>
                            <rect x="75" y="15" width="8" height="16" fill="#0f172a"/>
                            <rect x="90" y="20" width="8" height="8" fill="#0f172a"/>
                            <rect x="60" y="35" width="16" height="8" fill="#0f172a"/>
                            <rect x="85" y="35" width="12" height="12" fill="#0f172a"/>
                            <rect x="15" y="60" width="8" height="16" fill="#0f172a"/>
                            <rect x="30" y="65" width="12" height="8" fill="#0f172a"/>
                            <rect x="50" y="55" width="8" height="8" fill="#0f172a"/>
                            <rect x="65" y="60" width="12" height="12" fill="#0f172a"/>
                            <rect x="85" y="55" width="15" height="8" fill="#0f172a"/>
                            <rect x="110" y="60" width="8" height="15" fill="#0f172a"/>
                            <rect x="125" y="65" width="12" height="8" fill="#0f172a"/>
                            <rect x="140" y="55" width="8" height="8" fill="#0f172a"/>
                            <rect x="60" y="80" width="18" height="8" fill="#0f172a"/>
                            <rect x="85" y="80" width="8" height="18" fill="#0f172a"/>
                            <rect x="100" y="85" width="12" height="8" fill="#0f172a"/>
                            <rect x="120" y="80" width="8" height="8" fill="#0f172a"/>
                            <rect x="135" y="85" width="15" height="8" fill="#0f172a"/>
                            <rect x="60" y="105" width="8" height="12" fill="#0f172a"/>
                            <rect x="75" y="110" width="15" height="8" fill="#0f172a"/>
                            <rect x="100" y="105" width="8" height="8" fill="#0f172a"/>
                            <rect x="115" y="110" width="18" height="8" fill="#0f172a"/>
                            <rect x="60" y="130" width="12" height="15" fill="#0f172a"/>
                            <rect x="80" y="135" width="8" height="8" fill="#0f172a"/>
                            <rect x="95" y="125" width="15" height="12" fill="#0f172a"/>
                            <rect x="120" y="130" width="8" height="15" fill="#0f172a"/>
                            <rect x="135" y="125" width="15" height="8" fill="#0f172a"/>
                            <!-- Center Volleyball Badge -->
                            <rect x="66" y="66" width="28" height="28" fill="#ffffff" rx="6" stroke="#2563eb" stroke-width="2"/>
                            <circle cx="80" cy="80" r="9" fill="#2563eb"/>
                            <path d="M76 80 Q80 75 84 80 M80 76 Q85 80 80 84" stroke="#ffffff" stroke-width="1.5" fill="none"/>
                        </svg>
                    </div>

                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                        <span class="text-[11px] text-slate-500 block">Nominal Tagihan:</span>
                        <span id="qrisPriceDisplay" class="text-lg font-black text-rose-600">Rp 50.000</span>
                    </div>

                    <div class="bg-blue-50/70 border border-blue-200 rounded-xl p-3 text-left">
                        <div class="flex gap-2">
                            <i class="fa-solid fa-circle-info text-brand-600 text-sm mt-0.5"></i>
                            <div class="text-xs text-slate-700 space-y-0.5">
                                <strong class="text-slate-900">Petunjuk Pembayaran QRIS:</strong>
                                <p class="text-[11px] text-slate-600">Scan QR Code di atas dengan aplikasi m-Banking atau E-Wallet, simpan bukti transfer, lalu unggah pada kolom di bawah.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. KOTAK INFORMASI TRANSFER BANK -->
                <div id="bankBox" class="p-4 bg-slate-50 border border-brand-200 rounded-2xl shadow-sm my-3 hidden space-y-2">
                    <div class="flex items-center justify-between">
                        <span id="bankNameDisplay" class="px-2.5 py-1 rounded-lg bg-brand-600 text-white text-[10px] font-bold uppercase tracking-wider">TRANSFER BCA</span>
                        <button type="button" class="px-2.5 py-1 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 rounded-lg shadow-sm" onclick="copyRekening()">
                            <i class="fa-regular fa-copy mr-1"></i> <span id="btnCopyText">Salin Rekening</span>
                        </button>
                    </div>
                    <span class="text-xs text-slate-500 block">Nomor Rekening Tujuan:</span>
                    <h3 id="bankNumberDisplay" class="font-mono text-xl font-black text-slate-900">123-456-7890</h3>
                    <p class="text-xs text-slate-600">Atas Nama: <strong class="text-slate-900">Mini GOR Syafea</strong></p>
                </div>

                <!-- 3. KOTAK INFORMASI E-WALLET -->
                <div id="ewalletBox" class="p-4 bg-slate-50 border border-emerald-200 rounded-2xl shadow-sm my-3 hidden space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded-lg bg-emerald-600 text-white text-[10px] font-bold uppercase tracking-wider">E-WALLET (GOPAY / DANA / OVO)</span>
                        <button type="button" class="px-2.5 py-1 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 rounded-lg shadow-sm" onclick="copyEwallet()">
                            <i class="fa-regular fa-copy mr-1"></i> <span>Salin No. HP</span>
                        </button>
                    </div>
                    <span class="text-xs text-slate-500 block">Nomor Akun / Handphone:</span>
                    <h3 id="ewalletNumberDisplay" class="font-mono text-xl font-black text-slate-900">0812-3456-7890</h3>
                    <p class="text-xs text-slate-600">Atas Nama: <strong class="text-slate-900">Mini GOR Syafea (Official)</strong></p>
                </div>

            </div>
            <!-- ========================================== -->

            <!-- Upload Bukti Transfer -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Unggah Bukti Pembayaran <span class="text-rose-500">*</span>
                </label>
                <div class="border-2 border-dashed border-slate-300 hover:border-brand-600 rounded-2xl bg-slate-50 hover:bg-brand-50/20 p-5 text-center cursor-pointer transition-all" onclick="document.getElementById('fileInput').click()">
                    <input type="file" name="payment_proof" id="fileInput" class="hidden" accept="image/*" onchange="updateFileName(this)">
                    <div class="w-11 h-11 bg-white border border-slate-200 text-brand-600 rounded-xl inline-flex items-center justify-center mb-2 shadow-sm">
                        <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                    </div>
                    <p id="fileNameText" class="text-xs text-slate-600 mb-1">
                        <span class="text-brand-600 font-bold">Pilih Berkas...</span> atau seret bukti transfer ke sini
                    </p>
                    <span class="text-[10px] text-slate-400">Format yang didukung: JPG, PNG, WEBP (Maksimal 5MB)</span>
                </div>
                @error('payment_proof')
                    <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tombol Konfirmasi -->
            <button type="submit" class="w-full py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-brand-500/25 transition-all flex items-center justify-center gap-2 mt-4">
                <i class="fa-solid fa-circle-check text-base"></i>
                <span>KONFIRMASI & PROSES PEMBAYARAN</span>
            </button>

        </form>
    </div>

</div>

<!-- Modal Success & Cetak Nota (Fitur 3.3 Ariel Arendra) -->
<div id="successModal" class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full border border-slate-200 shadow-2xl relative text-center">
        <div class="w-14 h-14 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
            <i class="fa-solid fa-check text-2xl"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-1">Pembayaran Berhasil Dikirim!</h3>
        <p class="text-xs text-slate-500 mb-4">Data penyewaan Anda telah dicatat ke dalam sistem dan siap diverifikasi oleh pihak pengelola.</p>
        
        <!-- Fitur 3.3 Cetak Nota Pembayaran -->
        <button type="button" onclick="printReceipt()" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2 shadow-md shadow-emerald-500/20 mb-2">
            <i class="fa-solid fa-print"></i> Cetak Nota Pembayaran
        </button>

        <button type="button" onclick="finishBooking()" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs uppercase tracking-wider transition-all">
            Kembali ke Beranda
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let lastBookingReceipt = null;

    document.addEventListener('DOMContentLoaded', () => {
        const currentMethod = document.getElementById('paymentMethodSelect').value;
        handlePaymentChange(currentMethod);
    });

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
    
    const qrisPrice = document.getElementById('qrisPriceDisplay');
    if (qrisPrice) qrisPrice.innerText = activeBooking.price;

    document.getElementById('inputBookingDate').value = activeBooking.date;
    document.getElementById('inputBookingTime').value = activeBooking.time;
    document.getElementById('inputBookingPrice').value = activeBooking.price;
    document.getElementById('inputUserType').value = activeBooking.type;

    const memberName = localStorage.getItem('syafea_member');
    if (memberName) {
        document.getElementById('fullName').value = memberName;
    }

    function handlePaymentChange(method) {
        const qrisBox = document.getElementById('qrisBox');
        const bankBox = document.getElementById('bankBox');
        const ewalletBox = document.getElementById('ewalletBox');

        const bankNameDisplay = document.getElementById('bankNameDisplay');
        const bankNumberDisplay = document.getElementById('bankNumberDisplay');

        qrisBox.classList.add('hidden');
        bankBox.classList.add('hidden');
        ewalletBox.classList.add('hidden');

        if (method === 'qris') {
            qrisBox.classList.remove('hidden');
        } else if (method === 'bca') {
            bankNameDisplay.innerText = "TRANSFER BANK BCA";
            bankNumberDisplay.innerText = "123-456-7890";
            bankBox.classList.remove('hidden');
        } else if (method === 'mandiri') {
            bankNameDisplay.innerText = "TRANSFER BANK MANDIRI";
            bankNumberDisplay.innerText = "987-654-3210";
            bankBox.classList.remove('hidden');
        } else if (method === 'gopay') {
            ewalletBox.classList.remove('hidden');
        }
    }

    function copyRekening() {
        const text = document.getElementById('bankNumberDisplay').innerText;
        navigator.clipboard.writeText(text);
        const btnText = document.getElementById('btnCopyText');
        btnText.innerText = "Tersalin!";
        setTimeout(() => { btnText.innerText = "Salin Rekening"; }, 2000);
    }

    function copyEwallet() {
        const text = document.getElementById('ewalletNumberDisplay').innerText;
        navigator.clipboard.writeText(text);
        alert("Nomor E-Wallet berhasil disalin: " + text);
    }

    function updateFileName(input) {
        const textElement = document.getElementById('fileNameText');
        if (input.files && input.files[0]) {
            textElement.innerHTML = `<span class="text-emerald-600 font-bold"><i class="fa-solid fa-file-check mr-1"></i> ${input.files[0].name}</span>`;
        }
    }

    function handleSubmitBooking(e) {
        e.preventDefault();
        
        let history = JSON.parse(localStorage.getItem('syafea_history')) || [];
        const newId = "#GOR-" + Math.floor(1000 + Math.random() * 9000);
        const todayStr = new Date().toLocaleDateString('id-ID');
        const currentName = document.getElementById('fullName').value.trim() || "Penyewa";
        const currentMethod = document.getElementById('paymentMethodSelect').value;

        const orderItem = {
            id: newId,
            date: todayStr,
            name: currentName,
            schedule: `${activeBooking.date} (${activeBooking.time})`,
            type: activeBooking.type,
            price: activeBooking.price,
            method: currentMethod,
            status: activeBooking.isMember ? "Aktif / Terverifikasi" : "Pending Verifikasi"
        };

        history.unshift(orderItem);
        localStorage.setItem('syafea_history', JSON.stringify(history));

        lastBookingReceipt = orderItem;

        const successModal = document.getElementById('successModal');
        if (successModal) {
            successModal.classList.remove('hidden');
            successModal.classList.add('flex');
        }
    }

    function printReceipt() {
        if (!lastBookingReceipt) return;
        const printWindow = window.open('', '', 'width=650,height=750');
        printWindow.document.write(`
            <html>
            <head>
                <title>Nota Pembayaran - Mini GOR Syafea</title>
                <style>
                    body { font-family: 'Inter', -apple-system, sans-serif, Arial; padding: 30px; color: #1e293b; max-width: 500px; margin: auto; }
                    .header { text-align: center; border-bottom: 2px dashed #94a3b8; padding-bottom: 16px; margin-bottom: 20px; }
                    .brand { font-size: 22px; font-weight: 900; letter-spacing: -0.5px; color: #0f172a; margin: 0; }
                    .desc { font-size: 11px; color: #64748b; margin-top: 4px; }
                    .badge { display: inline-block; padding: 4px 12px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; border-radius: 999px; font-size: 10px; font-weight: bold; margin-top: 10px; letter-spacing: 1px; }
                    .details { margin: 16px 0; }
                    .row { display: flex; justify-content: space-between; font-size: 13px; margin: 8px 0; }
                    .label { color: #64748b; }
                    .value { font-weight: bold; color: #0f172a; text-align: right; }
                    .total-box { border-top: 2px dashed #94a3b8; border-bottom: 2px dashed #94a3b8; padding: 14px 0; margin-top: 18px; display: flex; justify-content: space-between; font-size: 16px; font-weight: 900; color: #2563eb; }
                    .footer { text-align: center; margin-top: 30px; font-size: 11px; color: #94a3b8; line-height: 1.5; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1 class="brand">MINI GOR SYAFEA</h1>
                    <p class="desc">Sistem Informasi Pencatatan Penyewaan Lapangan<br>Kelompok 404 Founders - Telkom University</p>
                    <span class="badge">BUKTI RESMI TRANSAKSI PENYEWAAN</span>
                </div>
                
                <div class="details">
                    <div class="row"><span class="label">No. Transaksi</span><span class="value">${lastBookingReceipt.id}</span></div>
                    <div class="row"><span class="label">Tanggal Booking</span><span class="value">${lastBookingReceipt.date}</span></div>
                    <div class="row"><span class="label">Nama Penyewa</span><span class="value">${lastBookingReceipt.name}</span></div>
                    <div class="row"><span class="label">Tipe Akun</span><span class="value">${lastBookingReceipt.type}</span></div>
                    <div class="row"><span class="label">Jadwal Sewa</span><span class="value">${lastBookingReceipt.schedule}</span></div>
                    <div class="row"><span class="label">Metode Pembayaran</span><span class="value">${lastBookingReceipt.method.toUpperCase()}</span></div>
                    <div class="row"><span class="label">Status Verifikasi</span><span class="value" style="color:#059669;">${lastBookingReceipt.status}</span></div>
                </div>

                <div class="total-box">
                    <span>TOTAL PEMBAYARAN</span>
                    <span>${lastBookingReceipt.price}</span>
                </div>

                <div class="footer">
                    <p>Terima kasih telah melakukan pemesanan di Mini GOR Syafea.</p>
                    <p>Simpan nota ini dan tunjukkan kepada petugas saat check-in di lapangan.</p>
                </div>
                <script>window.print();<\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }

    function finishBooking() {
        localStorage.removeItem('syafea_active_booking');
        window.location.href = "{{ route('booking.index') }}";
    }
</script>
@endsection
