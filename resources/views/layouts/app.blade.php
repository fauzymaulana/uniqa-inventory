<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Inventory Control System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            min-height: 100vh;
            position: sticky;
            top: 0;
        }
        .sidebar a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: all 0.3s;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
            border-left: 4px solid white;
            padding-left: 16px;
        }
        .sidebar-title {
            font-weight: bold;
            padding: 15px 20px;
            margin-top: 20px;
            text-transform: uppercase;
            font-size: 0.85rem;
        }
        .main-content {
            padding: 30px;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: none;
            margin-bottom: 20px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        .stat-card {
            text-align: center;
            padding: 20px;
        }
        .stat-card h5 {
            color: #667eea;
            font-weight: bold;
        }
        .stat-card .number {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div style="padding: 20px;">
                    <h4 style="color: white; margin-bottom: 30px;">
                        <i class="fas fa-shopping-cart"></i> Inventory
                    </h4>
                    <div style="color: rgba(255,255,255,0.9); font-size:0.9rem; margin-top:8px;">
                        <div id="currentDate"></div>
                        <div id="currentTime" style="font-weight:600;"></div>
                    </div>
                </div>

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
                    @endif

                    <div class="sidebar-title">Akun</div>
                    <a href="{{ route('profile.edit') }}" class="@if(request()->routeIs('profile.*')) active @endif">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <a href="#" onclick="document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endauth
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Sidebar live date & time
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', options);
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID');
        }
        if (document.getElementById('currentDate')) {
            updateDateTime();
            setInterval(updateDateTime, 1000);
        }
        // Register Service Worker for offline support
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/sw.js').catch(function () {});
            });
        }

        // ── Sortable Table Headers ───────────────────────────────
        (function () {
            function getCellValue(row, colIndex) {
                const cell = row.cells[colIndex];
                if (!cell) return '';
                return cell.innerText.trim();
            }

            function comparator(a, b, colIndex, asc) {
                const va = getCellValue(asc ? a : b, colIndex);
                const vb = getCellValue(asc ? b : a, colIndex);
                // Try numeric comparison (strip non-numeric except dots and commas)
                const na = parseFloat(va.replace(/[^0-9,.-]/g, '').replace(',', '.'));
                const nb = parseFloat(vb.replace(/[^0-9,.-]/g, '').replace(',', '.'));
                if (!isNaN(na) && !isNaN(nb)) return na - nb;
                return va.localeCompare(vb, 'id');
            }

            function initSortableTable(table) {
                const headers = table.querySelectorAll('thead th');
                headers.forEach(function (th, colIndex) {
                    th.style.cursor = 'pointer';
                    th.style.userSelect = 'none';
                    th.setAttribute('data-sort-dir', '');
                    // Add sort indicator span
                    if (!th.querySelector('.sort-icon')) {
                        const icon = document.createElement('span');
                        icon.className = 'sort-icon ms-1 text-muted';
                        icon.innerHTML = '&#8597;';
                        icon.style.fontSize = '0.75rem';
                        th.appendChild(icon);
                    }
                    th.addEventListener('click', function () {
                        const currentDir = th.getAttribute('data-sort-dir');
                        const asc = currentDir !== 'asc';
                        // Reset all headers
                        headers.forEach(function (h) {
                            h.setAttribute('data-sort-dir', '');
                            const ic = h.querySelector('.sort-icon');
                            if (ic) { ic.innerHTML = '&#8597;'; ic.className = 'sort-icon ms-1 text-muted'; }
                        });
                        th.setAttribute('data-sort-dir', asc ? 'asc' : 'desc');
                        const ic = th.querySelector('.sort-icon');
                        if (ic) {
                            ic.innerHTML = asc ? '&#8593;' : '&#8595;';
                            ic.className = 'sort-icon ms-1 text-primary';
                        }
                        // Sort visible rows
                        const tbody = table.querySelector('tbody');
                        if (!tbody) return;
                        const rows = Array.from(tbody.querySelectorAll('tr')).filter(function (r) {
                            return r.style.display !== 'none';
                        });
                        rows.sort(function (a, b) { return comparator(a, b, colIndex, asc); });
                        rows.forEach(function (r) { tbody.appendChild(r); });
                    });
                });
            }

            function initAllTables() {
                document.querySelectorAll('table.table').forEach(function (table) {
                    if (!table.dataset.sortInit) {
                        table.dataset.sortInit = '1';
                        initSortableTable(table);
                    }
                });
            }

            // Init on page load
            document.addEventListener('DOMContentLoaded', initAllTables);
            // Also init if tables are added later (e.g., via AJAX)
            window.initAllSortableTables = initAllTables;
        })();
    </script>
    @yield('scripts')
</body>
</html>
