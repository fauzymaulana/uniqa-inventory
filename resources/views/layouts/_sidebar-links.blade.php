@auth
    @if(auth()->user()->role === 'admin')
        <div class="sidebar-title">Admin Menu</div>
        <a href="{{ route('admin.dashboard') }}" class="@if(request()->routeIs('admin.dashboard')) active @endif">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{ route('admin.products.index') }}" class="@if(request()->routeIs('admin.products.*')) active @endif">
            <i class="fas fa-box"></i> Kelola Produk
        </a>
        <a href="{{ route('admin.categories.index') }}" class="@if(request()->routeIs('admin.categories.*')) active @endif">
            <i class="fas fa-tags"></i> Kategori
        </a>

        <div class="sidebar-title">Laporan</div>
        <a href="{{ route('admin.reports.sales') }}" class="@if(request()->routeIs('admin.reports.sales')) active @endif">
            <i class="fas fa-chart-line"></i> Penjualan
        </a>
        <a href="{{ route('admin.reports.inventory') }}" class="@if(request()->routeIs('admin.reports.inventory')) active @endif">
            <i class="fas fa-warehouse"></i> Inventory
        </a>
        <a href="{{ route('admin.reports.stock-history') }}" class="@if(request()->routeIs('admin.reports.stock-history')) active @endif">
            <i class="fas fa-history"></i> Riwayat Stok
        </a>
        <a href="{{ route('admin.reports.daily') }}" class="@if(request()->routeIs('admin.reports.daily')) active @endif">
            <i class="fas fa-calendar-day"></i> Laporan Harian
        </a>
        <a href="{{ route('admin.cashiers.index') }}" class="@if(request()->routeIs('admin.cashiers.*')) active @endif">
            <i class="fas fa-users"></i> Daftar Kasir
        </a>
        <a href="{{ route('admin.expenses.index') }}" class="@if(request()->routeIs('admin.expenses.*')) active @endif">
            <i class="fas fa-money-bill-wave"></i> Pengeluaran
        </a>
        <a href="{{ route('admin.debts.index') }}" class="@if(request()->routeIs('admin.debts.*')) active @endif">
            <i class="fas fa-hand-holding-usd"></i> Riwayat Hutang
        </a>
        <a href="{{ route('admin.activity-logs.index') }}" class="@if(request()->routeIs('admin.activity-logs.*')) active @endif">
            <i class="fas fa-clipboard-list"></i> Activity Log
        </a>

        <div class="sidebar-title">Undangan &amp; Konten</div>
        <a href="{{ route('admin.invitation.index') }}" class="@if(request()->routeIs('admin.invitation.*')) active @endif">
            <i class="fas fa-envelope-open-text"></i> Undangan
        </a>
        <a href="{{ route('admin.content.index') }}" class="@if(request()->routeIs('admin.content.*')) active @endif">
            <i class="fas fa-images"></i> Konten
        </a>
    @else
        <div class="sidebar-title">Kasir Menu</div>
        <a href="{{ route('cashier.dashboard') }}" class="@if(request()->routeIs('cashier.dashboard')) active @endif">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{ route('cashier.pos') }}" class="@if(request()->routeIs('cashier.pos')) active @endif">
            <i class="fas fa-cash-register"></i> Kasir
        </a>
        <a href="{{ route('cashier.history') }}" class="@if(request()->routeIs('cashier.history')) active @endif">
            <i class="fas fa-receipt"></i> Riwayat Transaksi
        </a>
        <a href="{{ route('cashier.expenses.index') }}" class="@if(request()->routeIs('cashier.expenses.*')) active @endif">
            <i class="fas fa-money-bill-wave"></i> Pengeluaran
        </a>
        <a href="{{ route('cashier.debts.index') }}" class="@if(request()->routeIs('cashier.debts.*')) active @endif">
            <i class="fas fa-hand-holding-usd"></i> Riwayat Hutang
        </a>

        <div class="sidebar-title">Undangan &amp; Konten</div>
        <a href="{{ route('cashier.invitation.index') }}" class="@if(request()->routeIs('cashier.invitation.*')) active @endif">
            <i class="fas fa-envelope-open-text"></i> Undangan
        </a>
        <a href="{{ route('cashier.content.create') }}" class="@if(request()->routeIs('cashier.content.*')) active @endif">
            <i class="fas fa-images"></i> Konten
        </a>
    @endif

    <div class="sidebar-title">Akun</div>
    <a href="{{ route('profile.edit') }}" class="@if(request()->routeIs('profile.*')) active @endif">
        <i class="fas fa-user"></i> Profile
    </a>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>

    <div style="padding: 16px 20px; margin-top: 20px; margin-bottom: 12px; border-top: 1px solid rgba(255,255,255,0.15);">
        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
            <span style="background:rgba(255,255,255,0.15); color:rgba(255,255,255,0.9); font-size:0.72rem; font-weight:600; letter-spacing:0.05em; padding:3px 8px; border-radius:20px; border:1px solid rgba(255,255,255,0.25);">
                v{{ config('app.version') }}
            </span>
            <span style="color:rgba(255,255,255,0.5); font-size:0.72rem;">{{ config('app.name') }}</span>
        </div>
    </div>
@endauth
