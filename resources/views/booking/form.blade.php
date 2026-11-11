@extends('layouts.app')

@section('title', 'Formulir Pemesanan - Mini GOR Syafea')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            
            <!-- Form Card Container -->
            <div class="white-card rounded-4 p-4 p-md-5 position-relative overflow-hidden glow-effect-light">
                
                <!-- Header Decor Line -->
                <div class="position-absolute top-0 start-0 end-0 bg-primary" style="height: 5px;"></div>

                <!-- Tombol Kembali / Back -->
                <div class="mb-4">
                    <a href="{{ route('booking.index') }}" class="btn btn-sm btn-light border text-secondary fw-bold px-3 py-1.5 rounded-3 d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Jadwal
                    </a>
                </div>

                <!-- Header Title -->
                <div class="text-center mb-4">
                    <div class="bg-primary-subtle text-primary rounded-4 d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                        <i class="fa-solid fa-file-invoice-dollar fs-4"></i>
                    </div>
                    <h3 class="fw-black text-dark mb-1 fw-bold fs-4">FORMULIR PEMESANAN</h3>
                    <p class="text-muted small mb-0">Lengkapi data diri dan konfirmasi pembayaran sewa lapangan</p>
                </div>

                <!-- Form Elements -->
                <form id="orderForm" action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data" onsubmit="handleSubmitBooking(event)" class="vstack gap-3">
                    @csrf

                    <!-- Hidden Field untuk Data Booking -->
                    <input type="hidden" name="booking_date" id="inputBookingDate">
                    <input type="hidden" name="booking_time" id="inputBookingTime">
                    <input type="hidden" name="booking_price" id="inputBookingPrice">
                    <input type="hidden" name="user_type" id="inputUserType">

                    <!-- Input: Nama Lengkap -->
                    <div>
                        <label class="form-label small fw-bold text-uppercase text-secondary mb-1">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0 rounded-start-3"><i class="fa-regular fa-user"></i></span>
                            <input type="text" name="full_name" id="fullName" required placeholder="Masukkan nama lengkap Anda" 
                                class="form-control rounded-end-3 py-2 border-start-0" value="{{ old('full_name') }}">
                        </div>
                        @error('full_name')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Input: No Telepon -->
                    <div>
                        <label class="form-label small fw-bold text-uppercase text-secondary mb-1">
                            No. Telepon / WhatsApp <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0 rounded-start-3"><i class="fa-brands fa-whatsapp"></i></span>
                            <input type="tel" name="phone_number" id="phone" required placeholder="08xxxxxxxxxx" 
                                class="form-control rounded-end-3 py-2 border-start-0" value="{{ old('phone_number') }}">
                        </div>
                        @error('phone_number')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Input: Alamat -->
                    <div>
                        <label class="form-label small fw-bold text-uppercase text-secondary mb-1">
                            Alamat Lengkap <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0 rounded-start-3 align-items-start pt-2.5"><i class="fa-solid fa-location-dot"></i></span>
                            <textarea name="address" id="address" rows="2" required placeholder="Masukkan alamat lengkap Anda" 
                                class="form-control rounded-end-3 py-2 border-start-0">{{ old('address') }}</textarea>
                        </div>
                        @error('address')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Total Tagihan Box -->
                    <div class="p-3 rounded-3 bg-primary-subtle border border-primary-subtle d-flex align-items-center justify-content-between my-2">
                        <div>
                            <span class="small text-muted d-block lh-1 mb-1">Total Tagihan</span>
                            <span id="displayPrice" class="fs-4 fw-black text-primary fw-bold">Rp 50.000</span>
                        </div>
                        <div class="text-end">
                            <span id="displayDate" class="badge bg-white text-primary border border-primary-subtle px-3 py-1.5 rounded-2 shadow-sm d-inline-block">
                                {{ date('d-m-Y') }}
                            </span>
                            <small id="displayTime" class="text-muted d-block mt-1 font-monospace">08:00 - 11:00</small>
                        </div>
                    </div>

                    <!-- Select: Metode Bayar -->
                    <div>
                        <label class="form-label small fw-bold text-uppercase text-secondary mb-1">
                            Metode Pembayaran <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0 rounded-start-3"><i class="fa-regular fa-credit-card"></i></span>
                            <select name="payment_method" id="paymentMethodSelect" onchange="handlePaymentChange(this.value)" class="form-select rounded-end-3 py-2 border-start-0 cursor-pointer">
                                <option value="bca">Transfer Bank - BCA (123-456-7890)</option>
                                <option value="mandiri">Transfer Bank - Mandiri (987-654-3210)</option>
                                <option value="qris" selected>QRIS (Semua E-Wallet / Mobile Banking)</option>
                                <option value="gopay">GoPay / DANA / OVO</option>
                            </select>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- DETAIL PEMBAYARAN INTERAKTIF (JS / SIMULASI) -->
                    <!-- ========================================== -->
                    <div id="paymentDetailContainer">
                        
                        <!-- 1. KOTAK SIMULASI QRIS (OTOMATIS MUNCUL JIKA PILIH QRIS) -->
                        <div id="qrisBox" class="p-4 bg-white border border-2 border-danger-subtle rounded-4 text-center shadow-sm my-2">
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                                <span class="badge bg-danger text-uppercase px-2.5 py-1.5 fw-bold" style="letter-spacing: 1px;">
                                    <i class="fa-solid fa-qrcode me-1"></i> QRIS STANDAR NASIONAL
                                </span>
                                <small class="text-muted font-monospace">NMID: ID1029384756201</small>
                            </div>
                            
                            <h5 class="fw-bold text-dark mb-0">MINI GOR SYAFEA</h5>
                            <small class="text-muted d-block mb-3">Sistem Pembayaran Instan Semua E-Wallet & Mobile Banking</small>

                            <!-- Gambar Simulasi QR Code QRIS -->
                            <div class="d-inline-block p-3 bg-white border-2 border-dark rounded-3 shadow-sm mb-3 position-relative">
                                <!-- SVG Realistis QR Code agar gambar tidak pernah rusak/pecah -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 160" width="160" height="160" class="d-block mx-auto">
                                    <!-- Pattern QR Code -->
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
                                    <!-- Data Bits Random Simulated Pattern -->
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
                                    <!-- Center Volley Icon Badge -->
                                    <rect x="66" y="66" width="28" height="28" fill="#ffffff" rx="6" stroke="#2563eb" stroke-width="2"/>
                                    <circle cx="80" cy="80" r="9" fill="#2563eb"/>
                                    <path d="M76 80 Q80 75 84 80 M80 76 Q85 80 80 84" stroke="#ffffff" stroke-width="1.5" fill="none"/>
                                </svg>
                            </div>

                            <div class="bg-light p-2.5 rounded-3 border mb-3">
                                <span class="small text-muted d-block">Nominal yang harus dibayar:</span>
                                <span id="qrisPriceDisplay" class="fs-5 fw-bold text-danger">Rp 50.000</span>
                            </div>

                            <div class="alert alert-info py-2 px-3 small text-start mb-0 border-0 bg-info-subtle text-info-emphasis rounded-3">
                                <div class="d-flex gap-2">
                                    <i class="fa-solid fa-circle-info fs-5 mt-0.5 text-primary"></i>
                                    <div>
                                        <strong>Petunjuk Simulasi QRIS:</strong>
                                        <p class="mb-0 small">Buka aplikasi m-Banking (BCA, Mandiri, BRI) atau E-Wallet (GoPay, DANA, OVO, ShopeePay), scan kode QR di atas, lalu screenshot bukti pembayarannya dan upload di bawah.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. KOTAK INFORMASI TRANSFER BANK (BCA / MANDIRI) -->
                        <div id="bankBox" class="p-3 bg-light border border-primary-subtle rounded-3 shadow-sm my-2 d-none">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span id="bankNameDisplay" class="badge bg-primary px-3 py-1.5 fw-bold">TRANSFER BCA</span>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0.5 px-2.5 small" onclick="copyRekening()">
                                    <i class="fa-regular fa-copy me-1"></i> <span id="btnCopyText">Salin Rekening</span>
                                </button>
                            </div>
                            <span class="small text-muted d-block mb-1">Nomor Rekening Tujuan:</span>
                            <h4 id="bankNumberDisplay" class="font-monospace fw-bold text-dark mb-1">123-456-7890</h4>
                            <small class="text-secondary">Atas Nama: <strong class="text-dark">Mini GOR Syafea</strong></small>
                        </div>

                        <!-- 3. KOTAK INFORMASI E-WALLET (GOPAY/DANA/OVO) -->
                        <div id="ewalletBox" class="p-3 bg-light border border-success-subtle rounded-3 shadow-sm my-2 d-none">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-success px-3 py-1.5 fw-bold">E-WALLET (GOPAY / DANA / OVO)</span>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0.5 px-2.5 small" onclick="copyEwallet()">
                                    <i class="fa-regular fa-copy me-1"></i> <span>Salin No. HP</span>
                                </button>
                            </div>
                            <span class="small text-muted d-block mb-1">Nomor Akun / Handphone:</span>
                            <h4 id="ewalletNumberDisplay" class="font-monospace fw-bold text-dark mb-1">0812-3456-7890</h4>
                            <small class="text-secondary">Atas Nama: <strong class="text-dark">Mini GOR Syafea (Official)</strong></small>
                        </div>

                    </div>
                    <!-- ========================================== -->

                    <!-- Upload Bukti Transfer -->
                    <div>
                        <label class="form-label small fw-bold text-uppercase text-secondary mb-1">
                            Upload Bukti Transfer <span class="text-danger">*</span>
                        </label>
                        <div class="custom-file-dropzone" onclick="document.getElementById('fileInput').click()">
                            <input type="file" name="payment_proof" id="fileInput" class="d-none" accept="image/*" onchange="updateFileName(this)">
                            <div class="bg-white border rounded-3 d-inline-flex align-items-center justify-content-center text-muted mb-2 shadow-sm" style="width: 44px; height: 44px;">
                                <i class="fa-solid fa-cloud-arrow-up fs-5 text-primary"></i>
                            </div>
                            <p id="fileNameText" class="small text-secondary mb-1">
                                <span class="text-primary fw-bold">Pilih File...</span> atau seret gambar bukti bayar ke sini
                            </p>
                            <span class="text-muted" style="font-size: 11px;">Format: JPG, PNG, WEBP (Maks. 5MB)</span>
                        </div>
                        @error('payment_proof')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tombol Konfirmasi -->
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold text-uppercase small shadow-sm d-flex align-items-center justify-content-center gap-2 mt-3">
                        <i class="fa-solid fa-circle-check fs-5"></i>
                        <span>KONFIRMASI PEMBAYARAN</span>
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>

<!-- Modal Success Bootstrap 5 -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow-lg text-center p-4">
            <div class="modal-body p-0">
                <div class="bg-success-subtle text-success rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                    <i class="fa-solid fa-check fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Pembayaran Berhasil!</h5>
                <p class="small text-muted mb-4">Pesanan Anda telah dikonfirmasi dan otomatis masuk ke dalam riwayat pemesanan sistem.</p>
                <button type="button" onclick="finishBooking()" class="btn btn-dark w-100 rounded-3 py-2 fw-semibold small">
                    Kembali ke Beranda
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let bsSuccessModal = null;

    document.addEventListener('DOMContentLoaded', () => {
        const modalEl = document.getElementById('successModal');
        if (modalEl) bsSuccessModal = new bootstrap.Modal(modalEl);

        // Jalankan pengecekan metode pembayaran saat halaman pertama kali terbuka
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
    
    // Sinkronkan harga di kotak QRIS juga
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

    // ========================================================
    // LOGIKA JAVASCRIPT: TAMPILKAN GAMBAR QRIS / DETAIL BANK
    // ========================================================
    function handlePaymentChange(method) {
        const qrisBox = document.getElementById('qrisBox');
        const bankBox = document.getElementById('bankBox');
        const ewalletBox = document.getElementById('ewalletBox');

        const bankNameDisplay = document.getElementById('bankNameDisplay');
        const bankNumberDisplay = document.getElementById('bankNumberDisplay');

        // Sembunyikan semuanya terlebih dahulu
        qrisBox.classList.add('d-none');
        bankBox.classList.add('d-none');
        ewalletBox.classList.add('d-none');

        // Tampilkan box yang sesuai dengan pilihan dropdown
        if (method === 'qris') {
            qrisBox.classList.remove('d-none');
        } else if (method === 'bca') {
            bankNameDisplay.innerText = "TRANSFER BANK BCA";
            bankNumberDisplay.innerText = "123-456-7890";
            bankBox.classList.remove('d-none');
        } else if (method === 'mandiri') {
            bankNameDisplay.innerText = "TRANSFER BANK MANDIRI";
            bankNumberDisplay.innerText = "987-654-3210";
            bankBox.classList.remove('d-none');
        } else if (method === 'gopay') {
            ewalletBox.classList.remove('d-none');
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
            textElement.innerHTML = `<span class="text-success fw-bold"><i class="fa-solid fa-file-check me-1"></i> ${input.files[0].name}</span>`;
        }
    }

    function handleSubmitBooking(e) {
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

        e.preventDefault();
        if (bsSuccessModal) {
            bsSuccessModal.show();
        }
    }

    function finishBooking() {
        localStorage.removeItem('syafea_active_booking');
        window.location.href = "{{ route('booking.index') }}";
    }
</script>
@endsection
