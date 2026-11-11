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
                            <select name="payment_method" class="form-select rounded-end-3 py-2 border-start-0 cursor-pointer">
                                <option value="bca">Transfer Bank - BCA (123-456-7890)</option>
                                <option value="mandiri">Transfer Bank - Mandiri (987-654-3210)</option>
                                <option value="qris">QRIS (Semua E-Wallet / Mobile Banking)</option>
                                <option value="gopay">GoPay / DANA / OVO</option>
                            </select>
                        </div>
                    </div>

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
