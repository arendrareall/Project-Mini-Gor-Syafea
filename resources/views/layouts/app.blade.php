<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'id') }}" class="h-full bg-slate-50 text-slate-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Mini GOR Syafea - Sewa Lapangan Modern')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts & Font Awesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS (Laravel Asset) -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @yield('styles')
</head>
<body class="h-full font-sans figma-grid antialiased flex flex-col justify-between selection:bg-brand-600 selection:text-white">

    <!-- Header / Navbar Utama -->
    <header class="sticky top-0 z-40">
        <div class="bg-white/90 backdrop-blur-md border-b border-slate-200 px-4 py-3 shadow-sm">
            <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
                
                <!-- Logo & Brand Name -->
                <a href="{{ route('booking.index') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-brand-500/20 group-hover:scale-105 transition-transform duration-200">
                        <i class="fa-solid fa-volleyball text-xl"></i>
                    </div>
                    <div>
                        <span class="text-xs uppercase tracking-widest text-brand-600 font-bold block leading-none">Mini GOR</span>
                        <span class="text-xl font-extrabold text-slate-900 tracking-tight">SYAFEA</span>
                    </div>
                </a>

                <!-- Navigation Bar -->
                <nav class="flex flex-wrap items-center gap-1 bg-slate-100 p-1.5 rounded-xl border border-slate-200 text-xs sm:text-sm">
                    <a href="{{ route('booking.index') }}" class="px-3.5 py-2 font-semibold text-white bg-brand-600 rounded-lg shadow-sm hover:bg-brand-700 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-house text-xs"></i> Home
                    </a>
                    
                    <a href="{{ route('booking.index') }}#jadwal" class="px-3.5 py-2 font-medium text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-200/70 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-calendar-days text-xs text-slate-500"></i> Jadwal
                    </a>
                    
                    <button type="button" onclick="openHistoryModal()" class="px-3.5 py-2 font-medium text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-200/70 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-xs text-brand-600"></i> Riwayat Pesanan
                    </button>
                    
                    <!-- Dynamic Member Nav / Auth Status -->
                    <div id="authNavContainer" class="flex items-center gap-2 pl-2 border-l border-slate-300">
                        <button type="button" onclick="openLoginModal()" id="btnLoginMember" class="px-3.5 py-2 font-bold text-brand-600 bg-brand-50 hover:bg-brand-100 border border-brand-200 rounded-lg transition-all flex items-center gap-2">
                            <i class="fa-solid fa-user-lock text-xs"></i> Login Member
                        </button>
                        
                        <div id="memberProfileBadge" class="hidden items-center gap-2 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg">
                            <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                            <div class="text-left">
                                <span class="text-[10px] text-emerald-600 font-bold block uppercase leading-none">Member Aktif</span>
                                <span id="memberNameDisplay" class="text-xs font-extrabold text-slate-800">Member</span>
                            </div>
                            <button type="button" onclick="logoutMember()" class="ml-2 text-slate-400 hover:text-rose-600 text-xs" title="Keluar">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Link Navigasi Role: Admin & Pemilik -->
                    <div class="flex items-center gap-1 pl-2 border-l border-slate-300">
                        <a href="{{ route('admin.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-200/70 transition-all flex items-center gap-1.5" title="Portal Admin">
                            <i class="fa-solid fa-user-gear text-slate-500"></i> Admin
                        </a>
                        <a href="{{ route('owner.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-200/70 transition-all flex items-center gap-1.5" title="Portal Pemilik">
                            <i class="fa-solid fa-chart-line text-slate-500"></i> Pemilik
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- Alert / Flash Messages dari Laravel Session -->
    @if(session('success'))
        <div class="max-w-6xl mx-auto px-4 mt-6 w-full">
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-xl"></i>
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-6xl mx-auto px-4 mt-6 w-full">
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-xl"></i>
                    <p class="text-sm font-semibold">{{ session('error') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Main Dynamic Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Modal Riwayat Pemesanan (History Modal) -->
    <div id="historyModal" class="fixed inset-0 modal-backdrop flex items-center justify-center p-4 hidden z-50">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-2xl w-full border border-slate-200 shadow-2xl relative">
            <button type="button" onclick="closeHistoryModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-brand-50 border border-brand-100 text-brand-600 rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900">Riwayat Pemesanan Lapangan</h3>
                    <p id="historySubtitle" class="text-xs text-slate-500">Daftar transaksi sewa lapangan Anda</p>
                </div>
            </div>

            <!-- Tabel Riwayat -->
            <div class="overflow-x-auto rounded-xl border border-slate-200 max-h-64 mb-6">
                <table class="modern-table text-xs">
                    <thead>
                        <tr>
                            <th>ID / Tgl Booking</th>
                            <th>Jadwal Main</th>
                            <th>Tipe Akun</th>
                            <th>Total Biaya</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody" class="divide-y divide-slate-100 font-medium">
                        <!-- Diisi via JS atau Blade Data -->
                    </tbody>
                </table>
            </div>

            <button type="button" onclick="closeHistoryModal()" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow transition-all">
                Tutup Window
            </button>
        </div>
    </div>

    <!-- Modal Login Member -->
    <div id="loginModal" class="fixed inset-0 modal-backdrop flex items-center justify-center p-4 hidden z-50">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full border border-slate-200 shadow-2xl relative">
            <button type="button" onclick="closeLoginModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            
            <div class="text-center mb-6">
                <div class="w-12 h-12 bg-brand-50 border border-brand-100 text-brand-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <i class="fa-solid fa-user-shield text-xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900">Login Member Syafea</h3>
                <p class="text-xs text-slate-500 mt-1">Nikmati potongan tarif khusus member resmi</p>
            </div>

            <form onsubmit="handleMemberLogin(event)" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama / WhatsApp / Email</label>
                    <input type="text" id="inputUser" required placeholder="Contoh: Budi Santoso" class="form-control-modern">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" required placeholder="••••••••" class="form-control-modern">
                </div>
                <button type="submit" class="w-full py-3.5 btn-modern-primary text-xs uppercase tracking-wider">
                    MASUK SEBAGAI MEMBER
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 py-8 px-4 mt-12">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-brand-600 flex items-center justify-center text-white text-sm font-bold">
                    <i class="fa-solid fa-volleyball"></i>
                </div>
                <span class="text-white font-bold text-sm">Mini GOR Syafea</span>
            </div>
            <p class="text-xs text-slate-400">&copy; {{ date('Y') }} Mini GOR Syafea. All Rights Reserved. Built with Laravel & CSS.</p>
        </div>
    </footer>

    <!-- Shared Client Scripts -->
    <script>
        function openLoginModal() { 
            document.getElementById('loginModal').classList.remove('hidden'); 
        }
        function closeLoginModal() { 
            document.getElementById('loginModal').classList.add('hidden'); 
        }

        function openHistoryModal() { 
            renderHistoryModal();
            document.getElementById('historyModal').classList.remove('hidden'); 
        }
        function closeHistoryModal() { 
            document.getElementById('historyModal').classList.add('hidden'); 
        }

        function handleMemberLogin(e) {
            e.preventDefault();
            const inputVal = document.getElementById('inputUser').value || "Budi Santoso";
            localStorage.setItem('syafea_member', inputVal);
            activateMemberUI(inputVal);
            if (typeof renderScheduleTable === "function") {
                renderScheduleTable();
            }
            closeLoginModal();
        }

        function activateMemberUI(name) {
            const btnLogin = document.getElementById('btnLoginMember');
            const badge = document.getElementById('memberProfileBadge');
            const nameDisplay = document.getElementById('memberNameDisplay');
            if (btnLogin) btnLogin.classList.add('hidden');
            if (badge) {
                badge.classList.remove('hidden');
                badge.classList.add('flex');
            }
            if (nameDisplay) nameDisplay.innerText = name;

            const banner = document.getElementById('bannerStatus');
            if (banner) {
                banner.className = "bg-gradient-to-r from-emerald-600 to-teal-700 text-white p-4 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 shadow-md";
                document.getElementById('bannerIcon').className = "w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center border border-white/30";
                document.getElementById('bannerIcon').innerHTML = '<i class="fa-solid fa-award text-lg"></i>';
                document.getElementById('bannerTitle').innerText = "Halo, " + name + " (Member Aktif)";
                document.getElementById('bannerDesc').innerText = "Hak istimewa custom jadwal aktif + Tarif spesial Member GOR Syafea.";
                const bannerBtn = document.getElementById('btnActionBanner');
                if (bannerBtn) {
                    bannerBtn.innerText = "Lihat Riwayat Member";
                    bannerBtn.onclick = openHistoryModal;
                }
            }

            const scheduleCtrl = document.getElementById('memberScheduleControl');
            if (scheduleCtrl) scheduleCtrl.classList.remove('hidden');
        }

        function logoutMember() {
            localStorage.removeItem('syafea_member');
            location.reload();
        }

        function renderHistoryModal() {
            const tbody = document.getElementById('historyTableBody');
            const subtitle = document.getElementById('historySubtitle');
            const isMember = localStorage.getItem('syafea_member');
            
            let allHistory = JSON.parse(localStorage.getItem('syafea_history')) || [];
            let userHistory = allHistory.filter(item => {
                return isMember ? item.type.includes(isMember) : item.type.includes("Tamu");
            });

            if (subtitle) {
                subtitle.innerText = isMember 
                    ? `Daftar transaksi eksklusif milik Member: ${isMember}` 
                    : "Daftar transaksi sewa lapangan (Mode: Tamu / Regular)";
            }

            tbody.innerHTML = "";
            if (userHistory.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="py-6 text-center text-slate-400 italic">Belum ada riwayat pesanan untuk akun ini.</td></tr>`;
                return;
            }

            userHistory.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td class="font-bold text-slate-800">${item.id}<br><span class="text-[10px] text-slate-400 font-normal">${item.date}</span></td>
                        <td>${item.schedule}</td>
                        <td><span class="badge badge-member">${item.type}</span></td>
                        <td class="font-bold text-slate-900">${item.price}</td>
                        <td><span class="badge badge-success">${item.status}</span></td>
                    </tr>
                `;
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedMember = localStorage.getItem('syafea_member');
            if (savedMember) {
                activateMemberUI(savedMember);
            }
        });
    </script>

    @yield('scripts')
</body>
</html>

