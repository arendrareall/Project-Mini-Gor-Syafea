<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'id') }}" class="h-full bg-slate-50 text-slate-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistem Informasi Pencatatan Penyewaan Mini GOR Syafea')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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

    <style>
        .figma-grid {
            background-color: #f8fafc;
            background-size: 24px 24px;
            background-image: linear-gradient(to right, rgba(13, 148, 136, 0.04) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(13, 148, 136, 0.04) 1px, transparent 1px);
        }
        .white-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.03);
            border-radius: 1.25rem;
        }
        .glow-effect-tosca {
            box-shadow: 0 0 35px -5px rgba(20, 184, 166, 0.18);
        }
        .dot-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        .dot-indicator.online {
            background-color: #14b8a6;
            box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.25);
        }
        .dot-indicator.busy {
            background-color: #94a3b8;
        }
    </style>

    @yield('styles')
</head>
<body class="h-full font-sans figma-grid antialiased flex flex-col justify-between selection:bg-tosca-500 selection:text-white">

    <!-- Header / Navbar Utama Gradasi Tosca - Biru - Navy -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 py-3 shadow-sm">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            
            <!-- Logo & Brand Name -->
            <a href="{{ route('booking.index') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-tosca-500 via-cyan-600 to-blue-700 flex items-center justify-center text-white shadow-md shadow-tosca-500/25 group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-volleyball text-xl"></i>
                </div>
                <div>
                    <span class="text-[10px] uppercase tracking-widest text-tosca-600 font-extrabold block leading-none">Pencatatan Penyewaan</span>
                    <span class="text-xl font-black bg-gradient-to-r from-navy-950 via-blue-900 to-tosca-700 bg-clip-text text-transparent tracking-tight">MINI GOR SYAFEA</span>
                </div>
            </a>

            <!-- Navigation Bar Terpadu -->
            <nav class="flex flex-wrap items-center gap-2 bg-slate-100/90 p-1.5 rounded-2xl border border-slate-200">
                <a href="{{ route('booking.index') }}" class="px-3.5 py-2 text-xs font-bold text-slate-700 hover:text-navy-950 hover:bg-slate-200/80 rounded-xl transition-all flex items-center gap-2">
                    <i class="fa-solid fa-house text-xs text-tosca-600"></i> Beranda
                </a>
                
                <a href="{{ route('booking.index') }}#jadwal" class="px-3.5 py-2 text-xs font-semibold text-slate-600 hover:text-navy-950 rounded-xl hover:bg-slate-200/80 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days text-xs text-tosca-600"></i> Jadwal
                </a>
                
                <!-- Dynamic Member Auth / Nav Container -->
                <div id="authNavContainer" class="flex items-center gap-2 pl-2 border-l border-slate-300">
                    
                    <!-- Satu Tombol Utama: Portal Masuk / Login Terpadu -->
                    <button type="button" onclick="openUnifiedLoginModal()" id="btnUnifiedLogin" class="px-4 py-2 text-xs font-bold text-white bg-gradient-to-r from-tosca-500 via-cyan-600 to-blue-600 hover:opacity-95 rounded-xl shadow-md shadow-tosca-500/25 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i> Masuk Sistem GOR
                    </button>
                    
                    <!-- Badge Jika Sudah Login Member -->
                    <div id="memberProfileBadge" class="hidden items-center gap-2 bg-gradient-to-r from-tosca-50 to-cyan-50 border border-tosca-200 px-3 py-1.5 rounded-xl">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-tosca-500 to-blue-600 text-white flex items-center justify-center text-xs font-bold shadow-xs">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <div class="text-left">
                            <span class="text-[9px] text-tosca-700 font-bold block uppercase leading-none">Member Aktif</span>
                            <span id="memberNameDisplay" class="text-xs font-extrabold text-navy-950">Member</span>
                        </div>
                        <button type="button" onclick="openHistoryModal()" class="ml-1 text-tosca-600 hover:text-tosca-800 text-xs" title="Riwayat & Cetak Nota">
                            <i class="fa-solid fa-receipt"></i>
                        </button>
                        <button type="button" onclick="logoutMember()" class="text-slate-400 hover:text-rose-600 text-xs" title="Keluar Member">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </div>

                </div>
            </nav>
        </div>
    </header>

    <!-- Flash Alert Messages -->
    <div class="max-w-6xl w-full mx-auto px-4 mt-4">
        @if(session('success'))
            <div class="bg-tosca-50 border border-tosca-200 text-tosca-900 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2 text-sm font-semibold">
                    <i class="fa-solid fa-circle-check text-tosca-600 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-tosca-500 hover:text-tosca-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2 text-sm font-semibold">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="max-w-6xl w-full mx-auto px-4 py-6 flex-grow">
        @yield('content')
    </main>

    <!-- ======================================================= -->
    <!-- MODAL LOGIN TERPADU MULTI-ROLE (PORTAL MASUK SISTEM)   -->
    <!-- ======================================================= -->
    <div id="unifiedLoginModal" class="fixed inset-0 bg-navy-950/80 backdrop-blur-md hidden items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full border border-slate-200 shadow-2xl relative text-center">
            
            <!-- Tombol Tutup -->
            <button type="button" onclick="closeUnifiedLoginModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <!-- TAHAP 1: PILIHAN MASUK SEBAGAI ROLE APA -->
            <div id="loginStepSelectRole">
                <div class="w-14 h-14 bg-gradient-to-tr from-tosca-50 to-cyan-100 border border-tosca-200 text-tosca-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <i class="fa-solid fa-id-badge text-2xl"></i>
                </div>
                <h3 class="text-2xl font-black text-navy-950 mb-1">Masuk Sistem GOR</h3>
                <p class="text-xs text-slate-500 mb-6">Silakan pilih jenis hak akses pengguna Anda untuk melanjutkan</p>

                <!-- 3 Pilihan Card Role -->
                <div class="grid grid-cols-1 gap-3 text-left">
                    
                    <!-- 1. Pilihan Role: Pelanggan / Member -->
                    <button type="button" onclick="showLoginForm('member')" class="group p-4 rounded-2xl border-2 border-slate-200 hover:border-tosca-500 hover:bg-tosca-50/40 transition-all flex items-center gap-4 text-left">
                        <div class="w-12 h-12 rounded-xl bg-tosca-50 group-hover:bg-tosca-500 text-tosca-600 group-hover:text-white border border-tosca-200 flex items-center justify-center text-xl transition-all shadow-xs">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-navy-950 group-hover:text-tosca-700">Pelanggan / Member GOR</h4>
                                <span class="text-xs text-tosca-600 group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Akses khusus sewa custom jadwal & diskon tarif member</p>
                        </div>
                    </button>

                    <!-- 2. Pilihan Role: Admin Pengelola -->
                    <button type="button" onclick="showLoginForm('admin')" class="group p-4 rounded-2xl border-2 border-slate-200 hover:border-cyan-500 hover:bg-cyan-50/40 transition-all flex items-center gap-4 text-left">
                        <div class="w-12 h-12 rounded-xl bg-cyan-50 group-hover:bg-cyan-600 text-cyan-600 group-hover:text-white border border-cyan-200 flex items-center justify-center text-xl transition-all shadow-xs">
                            <i class="fa-solid fa-user-gear"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-navy-950 group-hover:text-cyan-700">Admin Pengelola</h4>
                                <span class="text-xs text-cyan-600 group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Kelola penyewaan, verifikasi pembayaran, & cek bukti bayar</p>
                        </div>
                    </button>

                    <!-- 3. Pilihan Role: Pemilik GOR -->
                    <button type="button" onclick="showLoginForm('owner')" class="group p-4 rounded-2xl border-2 border-slate-200 hover:border-blue-700 hover:bg-blue-50/40 transition-all flex items-center gap-4 text-left">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 group-hover:bg-blue-800 text-blue-700 group-hover:text-white border border-blue-200 flex items-center justify-center text-xl transition-all shadow-xs">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-navy-950 group-hover:text-blue-900">Pemilik (Owner GOR)</h4>
                                <span class="text-xs text-blue-600 group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Laporan rekap keuangan, omzet penyewaan, & grafik laba</p>
                        </div>
                    </button>

                </div>
            </div>

            <!-- TAHAP 2A: FORM LOGIN MEMBER -->
            <div id="loginFormMember" class="hidden text-left space-y-4">
                <button type="button" onclick="backToRoleSelect()" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-navy-950 mb-1">
                    <i class="fa-solid fa-arrow-left"></i> Ganti Pilihan Role
                </button>
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-tosca-50 text-tosca-600 border border-tosca-200 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-navy-950">Login Member Syafea</h4>
                        <p class="text-xs text-slate-500">Gunakan akun penyewa terdaftar Anda</p>
                    </div>
                </div>

                <form onsubmit="handleUnifiedMemberSubmit(event)" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama / WhatsApp / Email</label>
                        <input type="text" id="inputMemberName" required placeholder="Contoh: Budi Santoso" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-tosca-500 focus:bg-white focus:ring-2 focus:ring-tosca-400/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                        <input type="password" required placeholder="••••••••" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-tosca-500 focus:bg-white focus:ring-2 focus:ring-tosca-400/20">
                    </div>
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-tosca-500 via-cyan-600 to-blue-600 hover:opacity-95 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-tosca-500/25 transition-all">
                        MASUK SEBAGAI MEMBER
                    </button>
                </form>
            </div>

            <!-- TAHAP 2B: FORM LOGIN ADMIN -->
            <div id="loginFormAdmin" class="hidden text-left space-y-4">
                <button type="button" onclick="backToRoleSelect()" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-navy-950 mb-1">
                    <i class="fa-solid fa-arrow-left"></i> Ganti Pilihan Role
                </button>
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 border border-cyan-200 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-navy-950">Login Admin Pengelola</h4>
                        <p class="text-xs text-slate-500">Kredensial khusus staf pengelola GOR Syafea</p>
                    </div>
                </div>

                <form onsubmit="handleUnifiedAdminSubmit(event)" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username Admin</label>
                        <input type="text" id="inputAdminUser" required placeholder="admin_syafea" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-cyan-500 focus:bg-white focus:ring-2 focus:ring-cyan-400/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                        <input type="password" id="inputAdminPass" required placeholder="admin123" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-cyan-500 focus:bg-white focus:ring-2 focus:ring-cyan-400/20">
                    </div>
                    <div id="errorAdminMsg" class="hidden p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
                        ❌ Username atau password admin salah!
                    </div>
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-cyan-600 to-blue-700 hover:opacity-95 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-cyan-500/25 transition-all">
                        MASUK PANEL PENGELOLA
                    </button>
                </form>
            </div>

            <!-- TAHAP 2C: FORM LOGIN PEMILIK -->
            <div id="loginFormOwner" class="hidden text-left space-y-4">
                <button type="button" onclick="backToRoleSelect()" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-navy-950 mb-1">
                    <i class="fa-solid fa-arrow-left"></i> Ganti Pilihan Role
                </button>
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-800 border border-blue-200 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-navy-950">Login Pemilik GOR</h4>
                        <p class="text-xs text-slate-500">Kredensial rahasia laporan rekap keuangan</p>
                    </div>
                </div>

                <form onsubmit="handleUnifiedOwnerSubmit(event)" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username Pemilik</label>
                        <input type="text" id="inputOwnerUser" required placeholder="pemilik_syafea" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-blue-700 focus:bg-white focus:ring-2 focus:ring-blue-600/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password Rahasia</label>
                        <input type="password" id="inputOwnerPass" required placeholder="owner123" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-blue-700 focus:bg-white focus:ring-2 focus:ring-blue-600/20">
                    </div>
                    <div id="errorOwnerMsg" class="hidden p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
                        ❌ Username atau password pemilik salah!
                    </div>
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-700 via-indigo-700 to-navy-900 hover:opacity-95 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-blue-700/25 transition-all">
                        BUKA LAPORAN KEUANGAN
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Modal Riwayat Pemesanan (History Modal) -->
    <div id="historyModal" class="fixed inset-0 bg-navy-950/80 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-2xl w-full border border-slate-200 shadow-2xl relative text-left">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-tosca-50 to-cyan-100 border border-tosca-200 text-tosca-700 flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-navy-950">Riwayat Penyewaan Lapangan</h3>
                        <p id="historySubtitle" class="text-xs text-slate-500">Daftar transaksi sewa lapangan Anda</p>
                    </div>
                </div>
                <button type="button" onclick="closeHistoryModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-slate-200 max-h-80 overflow-y-auto mb-4">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-bold tracking-wider border-b border-slate-200 sticky top-0">
                        <tr>
                            <th class="py-3 px-3.5">ID / Tgl</th>
                            <th class="py-3 px-3.5">Jadwal Main</th>
                            <th class="py-3 px-3.5">Tipe Akun</th>
                            <th class="py-3 px-3.5">Total</th>
                            <th class="py-3 px-3.5">Status</th>
                            <th class="py-3 px-3.5 text-center">Nota</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody" class="divide-y divide-slate-100 font-medium text-slate-700">
                        <!-- Diisi via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer Gradasi Navy -->
    <footer class="bg-gradient-to-r from-navy-950 via-slate-900 to-navy-900 text-slate-400 py-6 border-t border-slate-800 mt-12">
        <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-3 text-center md:text-left">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-tosca-500 to-cyan-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                    <i class="fa-solid fa-volleyball"></i>
                </div>
                <span class="text-white font-bold text-sm tracking-tight">Mini GOR Syafea</span>
            </div>
            <p class="text-xs text-slate-400">&copy; {{ date('Y') }} Sistem Informasi Pencatatan Penyewaan Mini GOR Syafea. Kelompok 404 Founders.</p>
        </div>
    </footer>

    <!-- Client-Side Scripting -->
    <script>
        const CREDENTIALS = {
            admin: { user: 'admin_syafea', pass: 'admin123', redirect: "{{ route('admin.index') }}" },
            owner: { user: 'pemilik_syafea', pass: 'owner123', redirect: "{{ route('owner.index') }}" }
        };

        document.addEventListener('DOMContentLoaded', () => {
            const savedMember = localStorage.getItem('syafea_member');
            if (savedMember) {
                activateMemberUI(savedMember);
            }
        });

        // Kontrol Modal Terpadu
        function openUnifiedLoginModal() {
            backToRoleSelect();
            const modal = document.getElementById('unifiedLoginModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeUnifiedLoginModal() {
            const modal = document.getElementById('unifiedLoginModal');
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }

        function showLoginForm(role) {
            document.getElementById('loginStepSelectRole').classList.add('hidden');
            document.getElementById('loginFormMember').classList.add('hidden');
            document.getElementById('loginFormAdmin').classList.add('hidden');
            document.getElementById('loginFormOwner').classList.add('hidden');

            if (role === 'member') {
                document.getElementById('loginFormMember').classList.remove('hidden');
            } else if (role === 'admin') {
                document.getElementById('loginFormAdmin').classList.remove('hidden');
                document.getElementById('errorAdminMsg').classList.add('hidden');
            } else if (role === 'owner') {
                document.getElementById('loginFormOwner').classList.remove('hidden');
                document.getElementById('errorOwnerMsg').classList.add('hidden');
            }
        }

        function backToRoleSelect() {
            document.getElementById('loginStepSelectRole').classList.remove('hidden');
            document.getElementById('loginFormMember').classList.add('hidden');
            document.getElementById('loginFormAdmin').classList.add('hidden');
            document.getElementById('loginFormOwner').classList.add('hidden');
        }

        // Handle Login Member
        function handleUnifiedMemberSubmit(e) {
            e.preventDefault();
            const inputVal = document.getElementById('inputMemberName').value.trim() || "Budi Santoso";
            localStorage.setItem('syafea_member', inputVal);
            activateMemberUI(inputVal);
            if (typeof renderScheduleTable === "function") {
                renderScheduleTable();
            }
            closeUnifiedLoginModal();
        }

        // Handle Login Admin
        function handleUnifiedAdminSubmit(e) {
            e.preventDefault();
            const u = document.getElementById('inputAdminUser').value.trim();
            const p = document.getElementById('inputAdminPass').value;

            if (u === CREDENTIALS.admin.user && p === CREDENTIALS.admin.pass) {
                sessionStorage.setItem('admin_logged_in', 'true');
                window.location.href = CREDENTIALS.admin.redirect;
            } else {
                document.getElementById('errorAdminMsg').classList.remove('hidden');
                document.getElementById('inputAdminPass').value = '';
                document.getElementById('inputAdminPass').focus();
            }
        }

        // Handle Login Pemilik
        function handleUnifiedOwnerSubmit(e) {
            e.preventDefault();
            const u = document.getElementById('inputOwnerUser').value.trim();
            const p = document.getElementById('inputOwnerPass').value;

            if (u === CREDENTIALS.owner.user && p === CREDENTIALS.owner.pass) {
                sessionStorage.setItem('owner_logged_in', 'true');
                window.location.href = CREDENTIALS.owner.redirect;
            } else {
                document.getElementById('errorOwnerMsg').classList.remove('hidden');
                document.getElementById('inputOwnerPass').value = '';
                document.getElementById('inputOwnerPass').focus();
            }
        }

        function activateMemberUI(name) {
            const btnLogin = document.getElementById('btnUnifiedLogin');
            const badge = document.getElementById('memberProfileBadge');
            const nameDisplay = document.getElementById('memberNameDisplay');
            if (btnLogin) {
                // Setelah login, sembunyikan tombol Masuk dan ganti dengan badge member
                btnLogin.style.display = 'none';
            }
            if (badge) {
                badge.classList.remove('hidden');
                badge.classList.add('flex');
            }
            if (nameDisplay) nameDisplay.innerText = name;

            // Tampilkan banner selamat datang member di halaman index
            const memberBanner = document.getElementById('memberWelcomeBanner');
            if (memberBanner) {
                memberBanner.classList.remove('hidden');
                memberBanner.classList.add('flex');
                const title = document.getElementById('memberWelcomeTitle');
                if (title) title.innerText = "Halo, " + name + " (Member Aktif)";
            }

            const scheduleCtrl = document.getElementById('memberScheduleControl');
            if (scheduleCtrl) scheduleCtrl.classList.remove('hidden');
        }

        function logoutMember() {
            localStorage.removeItem('syafea_member');
            location.reload();
        }

        function openHistoryModal() {
            renderHistoryModal();
            const modal = document.getElementById('historyModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }
        function closeHistoryModal() {
            const modal = document.getElementById('historyModal');
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }

        function renderHistoryModal() {
            const tbody = document.getElementById('historyTableBody');
            const subtitle = document.getElementById('historySubtitle');
            const isMember = localStorage.getItem('syafea_member');
            
            let allHistory = JSON.parse(localStorage.getItem('syafea_history'));
            
            // Inisialisasi riwayat bawaan jika kosong
            if (!allHistory || allHistory.length === 0) {
                allHistory = [
                    {
                        id: "#GOR-8821",
                        date: "{{ date('d-m-Y') }}",
                        name: "Budi Santoso",
                        schedule: "{{ date('d-m-Y') }} (08:00 - 11:00)",
                        type: "Member Syafea (Budi Santoso)",
                        price: "Rp 50.000",
                        method: "QRIS",
                        status: "Aktif / Terverifikasi"
                    },
                    {
                        id: "#GOR-5432",
                        date: "{{ date('d-m-Y') }}",
                        name: "Ahmad Dani",
                        schedule: "{{ date('d-m-Y') }} (14:00 - 17:00)",
                        type: "Tamu (Regular)",
                        price: "Rp 50.000",
                        method: "TRANSFER BANK BCA",
                        status: "Aktif / Terverifikasi"
                    }
                ];
                localStorage.setItem('syafea_history', JSON.stringify(allHistory));
            }

            let userHistory = allHistory.filter(item => {
                return isMember ? ((item.type && item.type.includes(isMember)) || (item.name && item.name.includes(isMember))) : (item.type && item.type.includes("Tamu"));
            });

            // Jika filter akun ini kosong, tampilkan seluruh data agar tombol Cetak Nota selalu bisa diuji
            if (userHistory.length === 0) {
                userHistory = allHistory;
            }

            if (subtitle) {
                subtitle.innerText = isMember 
                    ? `Daftar transaksi penyewaan milik: ${isMember} (Member)` 
                    : "Daftar transaksi sewa lapangan & cetak bukti transaksi";
            }

            tbody.innerHTML = "";
            userHistory.forEach((item, idx) => {
                const isRejected = item.status && item.status.includes('Ditolak');
                const statusClass = isRejected 
                    ? "bg-rose-50 text-rose-700 border border-rose-200" 
                    : "bg-blue-50 text-blue-700 border border-blue-200";

                const printButtonHtml = isRejected ? `
                    <button type="button" disabled class="px-3 py-1.5 bg-slate-100 text-slate-400 rounded-xl text-xs font-semibold flex items-center justify-center gap-1.5 cursor-not-allowed opacity-60 mx-auto" title="Pesanan ditolak - Nota tidak dapat dicetak">
                        <i class="fa-solid fa-ban text-rose-400"></i> <span>Ditolak</span>
                    </button>
                ` : `
                    <button type="button" onclick="printReceiptDirect(${idx})" class="px-3 py-1.5 bg-gradient-to-r from-tosca-600 to-cyan-600 hover:opacity-95 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 shadow-sm transition-all" title="Cetak Nota Pembayaran">
                        <i class="fa-solid fa-print"></i> <span>Cetak Nota</span>
                    </button>
                `;

                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3 px-3.5">
                            <span class="font-bold text-navy-950">${item.id}</span>
                            <br><span class="text-[10px] text-slate-400">${item.date}</span>
                        </td>
                        <td class="py-3 px-3.5 text-slate-800 font-medium">${item.schedule}</td>
                        <td class="py-3 px-3.5">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-tosca-50 text-tosca-700 border border-tosca-200">${item.name || item.type}</span>
                        </td>
                        <td class="py-3 px-3.5 font-bold text-navy-950">${item.price}</td>
                        <td class="py-3 px-3.5">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold ${statusClass}">${item.status}</span>
                        </td>
                        <td class="py-3 px-3.5 text-center">
                            ${printButtonHtml}
                        </td>
                    </tr>
                `;
            });
        }

        function printReceiptDirect(idx) {
            const isMember = localStorage.getItem('syafea_member');
            let allHistory = JSON.parse(localStorage.getItem('syafea_history')) || [];
            let userHistory = allHistory.filter(item => {
                return isMember ? ((item.type && item.type.includes(isMember)) || (item.name && item.name.includes(isMember))) : (item.type && item.type.includes("Tamu"));
            });
            if (userHistory.length === 0) userHistory = allHistory;
            const item = userHistory[idx];
            if (!item) return;

            // Validasi: Jika status ditolak, tolak pencetakan nota
            if (item.status && item.status.includes('Ditolak')) {
                alert('❌ Mohon maaf, pesanan ini telah DITOLAK oleh admin pengelola sehingga nota pembayaran resmi tidak dapat diterbitkan.');
                return;
            }

            const printWindow = window.open('', '', 'width=650,height=750');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Nota Pembayaran - ${item.id} - Mini GOR Syafea</title>
                    <style>
                        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; padding: 30px; color: #0f172a; max-width: 500px; margin: auto; }
                        .header { text-align: center; border-bottom: 2px dashed #0d9488; padding-bottom: 16px; margin-bottom: 20px; }
                        .brand { font-size: 22px; font-weight: 900; letter-spacing: -0.5px; color: #0a1128; margin: 0; }
                        .desc { font-size: 11px; color: #64748b; margin-top: 4px; line-height: 1.4; }
                        .badge { display: inline-block; padding: 4px 12px; background: #f0fdfa; color: #0f766e; border: 1px solid #99f6e4; border-radius: 999px; font-size: 10px; font-weight: bold; margin-top: 10px; letter-spacing: 1px; }
                        .details { margin: 18px 0; }
                        .row { display: flex; justify-content: space-between; font-size: 13px; margin: 8px 0; }
                        .label { color: #64748b; font-weight: 500; }
                        .value { font-weight: bold; color: #0f172a; text-align: right; }
                        .total-box { border-top: 2px dashed #0d9488; border-bottom: 2px dashed #0d9488; padding: 14px 0; margin-top: 18px; display: flex; justify-content: space-between; font-size: 16px; font-weight: 900; color: #0d9488; }
                        .footer { text-align: center; margin-top: 28px; font-size: 11px; color: #94a3b8; line-height: 1.5; }
                        @media print {
                            body { padding: 10px; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1 class="brand">MINI GOR SYAFEA</h1>
                        <p class="desc">Sistem Informasi Pencatatan Penyewaan Lapangan<br>Kelompok 404 Founders - Telkom University Surabaya</p>
                        <span class="badge">BUKTI RESMI TRANSAKSI PENYEWAAN</span>
                    </div>
                    
                    <div class="details">
                        <div class="row"><span class="label">No. Transaksi</span><span class="value">${item.id}</span></div>
                        <div class="row"><span class="label">Tanggal Booking</span><span class="value">${item.date}</span></div>
                        <div class="row"><span class="label">Nama Penyewa</span><span class="value">${item.name || (isMember || "Penyewa")}</span></div>
                        <div class="row"><span class="label">Kategori</span><span class="value">${item.type}</span></div>
                        <div class="row"><span class="label">Jadwal Sewa</span><span class="value">${item.schedule}</span></div>
                        <div class="row"><span class="label">Metode Pembayaran</span><span class="value">${(item.method || 'QRIS').toUpperCase()}</span></div>
                        <div class="row"><span class="label">Status Verifikasi</span><span class="value" style="color:#0f766e;">${item.status}</span></div>
                        <div class="row"><span class="label">Admin Verifikator</span><span class="value" style="color:#2563eb;">${item.verified_by || 'Ariel Arendra'}</span></div>
                    </div>

                    <div class="total-box">
                        <span>TOTAL PEMBAYARAN</span>
                        <span>${item.price}</span>
                    </div>

                    <div style="margin-top: 20px; text-align: right; font-size: 11px;">
                        <div>Admin yang Bertanggung Jawab:</div>
                        <div style="font-weight: bold; margin-top: 25px; text-decoration: underline;">${item.verified_by || 'Ariel Arendra'}</div>
                        <div style="font-size: 10px; color: #94a3b8;">Admin Pengelola Mini GOR Syafea</div>
                    </div>

                    <div class="footer">
                        <p>Terima kasih telah melakukan pemesanan di Mini GOR Syafea.</p>
                        <p>Simpan nota ini dan tunjukkan kepada petugas saat check-in di lapangan.</p>
                        <p style="font-size: 10px; margin-top: 8px;">Dicetak pada: ${new Date().toLocaleString('id-ID')}</p>
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>

    @yield('scripts')
</body>
</html>
