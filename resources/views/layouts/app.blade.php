<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Inventory Control System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 240px;
            --sidebar-grad: linear-gradient(160deg, #667eea 0%, #764ba2 100%);
            --topbar-h: 56px;
        }
        body { background-color: #f0f2f5; font-size: 0.9rem; overflow-x: hidden; }

        /* Topbar mobile */
        .topbar {
            position: fixed; top:0; left:0; right:0; height: var(--topbar-h);
            background: var(--sidebar-grad); z-index: 1040;
            display: flex; align-items: center; padding: 0 16px; gap: 12px;
            color: white; box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .topbar .brand-name { font-weight:700; font-size:1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; flex:1; }
        .topbar .topbar-time { font-size:.75rem; opacity:.85; white-space:nowrap; }
        @media (min-width:992px) { .topbar { display:none !important; } }

        /* Sidebar */
        .sidebar {
            background: var(--sidebar-grad); color: white; width: var(--sidebar-w);
            position: fixed; top:0; left:0; bottom:0; z-index: 1035;
            display: flex; flex-direction: column; overflow-y: auto;
            transition: transform 0.3s ease;
        }
        @media (max-width:991.98px) {
            .sidebar { transform: translateX(-100%); z-index:1055; box-shadow:4px 0 20px rgba(0,0,0,.3); }
            .sidebar.sidebar-open { transform: translateX(0); }
        }
        .sidebar-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1050; }
        .sidebar-backdrop.show { display:block; }

        /* Sidebar header */
        .sidebar-header { padding:20px 18px 14px; border-bottom:1px solid rgba(255,255,255,.12); flex-shrink:0; position:relative; }
        .sidebar-header .brand { font-weight:700; font-size:1.05rem; color:white; display:flex; align-items:center; gap:8px; }
        .sidebar-header .datetime { color:rgba(255,255,255,.8); font-size:.75rem; margin-top:6px; }
        .sidebar-close-btn {
            display:none; position:absolute; top:10px; right:10px;
            background:rgba(255,255,255,.15); border:none; color:white;
            border-radius:6px; width:30px; height:30px; font-size:1rem;
            cursor:pointer; align-items:center; justify-content:center;
        }
        @media (max-width:991.98px) { .sidebar-close-btn { display:flex; } }

        /* Sidebar nav */
        .sidebar-nav { flex:1; padding:8px 0; }
        .sidebar-section-title {
            color:rgba(255,255,255,.55); font-size:.68rem; font-weight:700;
            letter-spacing:.08em; text-transform:uppercase; padding:14px 18px 4px;
        }
        .sidebar-link {
            display:flex; align-items:center; gap:10px; padding:9px 18px;
            color:rgba(255,255,255,.82); text-decoration:none; font-size:.875rem;
            transition:background .2s,color .2s; border-left:3px solid transparent;
        }
        .sidebar-link i { width:16px; text-align:center; flex-shrink:0; }
        .sidebar-link:hover, .sidebar-link.active {
            background:rgba(255,255,255,.18); color:white; border-left-color:white;
        }

        /* Sidebar footer */
        .sidebar-footer { padding:12px 18px; border-top:1px solid rgba(255,255,255,.12); flex-shrink:0; }
        .sidebar-version { font-size:.7rem; color:rgba(255,255,255,.5); display:flex; align-items:center; gap:6px; }
        .sidebar-version .badge-version {
            background:rgba(255,255,255,.15); color:rgba(255,255,255,.85); font-weight:600;
            padding:2px 7px; border-radius:20px; border:1px solid rgba(255,255,255,.2); font-size:.68rem;
        }

        /* Layout wrapper */
        .app-wrapper { display:flex; min-height:100vh; width:100%; }
        .app-sidebar-placeholder { display:none !important; }

        /* Main Content */
        .main-content { 
            flex: 1; 
            min-width: 0; 
            padding: 28px 24px; 
            position: relative;
            z-index: 1;
            margin-left: 0;
            width: 100%;
        }
        @media (min-width:992px) { 
            .main-content { 
                margin-left: var(--sidebar-w); 
                width: calc(100% - var(--sidebar-w));
            } 
        }
        @media (max-width:991.98px) { .main-content { padding:72px 14px 24px; margin-left: 0; width: 100%; } }
        @media (max-width:575.98px)  { .main-content { padding:68px 10px 20px; } }

        /* Cards */
        .card { box-shadow:0 1px 4px rgba(0,0,0,.07); border:none; border-radius:10px; margin-bottom:20px; }
        .card-header { border-radius:10px 10px 0 0 !important; }

        /* Buttons */
        .btn-primary { background:var(--sidebar-grad); border:none; }
        .btn-primary:hover { background:linear-gradient(160deg,#764ba2 0%,#667eea 100%); }

        /* Stat Cards */
        .stat-card { text-align:center; padding:4px; }
        .stat-card h5 { color:#667eea; font-weight:600; font-size:.9rem; }
        .stat-card .number { font-size:1.6rem; font-weight:700; color:#333; }
        @media (max-width:575.98px) { .stat-card .number { font-size:1.2rem; } h2 { font-size:1.2rem; } }

        .table-responsive { border-radius:8px; }
    </style>
    @yield('styles')
</head>
<body>

    {{-- Mobile Topbar --}}
    @auth
    <div class="topbar d-lg-none">
        <button class="btn btn-link text-white p-0 fs-5" id="sidebarToggleBtn" aria-label="Toggle menu">
            <i class="fas fa-bars"></i>
        </button>
        <span class="brand-name"><i class="fas fa-shopping-cart me-1"></i> Inventory</span>
        <span class="topbar-time" id="topbarTime"></span>
    </div>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    @endauth

    <div class="app-wrapper">
        {{-- Sidebar --}}
        <aside class="sidebar" id="appSidebar">
            <button class="sidebar-close-btn" id="sidebarCloseBtn"><i class="fas fa-times"></i></button>

            <div class="sidebar-header">
                <div class="brand"><i class="fas fa-shopping-cart"></i> Inventory</div>
                <div class="datetime">
                    <div id="currentDate"></div>
                    <div id="currentTime" style="font-weight:600;"></div>
                </div>
            </div>

            <nav class="sidebar-nav">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <div class="sidebar-section-title">Admin Menu</div>
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link @if(request()->routeIs('admin.dashboard')) active @endif">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="sidebar-link @if(request()->routeIs('admin.products.*')) active @endif">
                            <i class="fas fa-box"></i> Kelola Produk
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="sidebar-link @if(request()->routeIs('admin.categories.*')) active @endif">
                            <i class="fas fa-tags"></i> Kategori
                        </a>

                        <div class="sidebar-section-title">Laporan</div>
                        <a href="{{ route('admin.reports.sales') }}" class="sidebar-link @if(request()->routeIs('admin.reports.sales')) active @endif">
                            <i class="fas fa-chart-line"></i> Penjualan
                        </a>
                        <a href="{{ route('admin.reports.inventory') }}" class="sidebar-link @if(request()->routeIs('admin.reports.inventory')) active @endif">
                            <i class="fas fa-warehouse"></i> Inventory
                        </a>
                        <a href="{{ route('admin.reports.stock-history') }}" class="sidebar-link @if(request()->routeIs('admin.reports.stock-history')) active @endif">
                            <i class="fas fa-history"></i> Riwayat Stok
                        </a>
                        <a href="{{ route('admin.reports.daily') }}" class="sidebar-link @if(request()->routeIs('admin.reports.daily')) active @endif">
                            <i class="fas fa-calendar-day"></i> Laporan Harian
                        </a>
                        <a href="{{ route('admin.cashiers.index') }}" class="sidebar-link @if(request()->routeIs('admin.cashiers.*')) active @endif">
                            <i class="fas fa-users"></i> Daftar Kasir
                        </a>
                        <a href="{{ route('admin.expenses.index') }}" class="sidebar-link @if(request()->routeIs('admin.expenses.*')) active @endif">
                            <i class="fas fa-money-bill-wave"></i> Pengeluaran
                        </a>
                        <a href="{{ route('admin.debts.index') }}" class="sidebar-link @if(request()->routeIs('admin.debts.*')) active @endif">
                            <i class="fas fa-hand-holding-usd"></i> Riwayat Hutang
                        </a>
                        <a href="{{ route('admin.activity-logs.index') }}" class="sidebar-link @if(request()->routeIs('admin.activity-logs.*')) active @endif">
                            <i class="fas fa-clipboard-list"></i> Activity Log
                        </a>

                        <div class="sidebar-section-title">Undangan &amp; Konten</div>
                        <a href="{{ route('admin.invitation.index') }}" class="sidebar-link @if(request()->routeIs('admin.invitation.*')) active @endif">
                            <i class="fas fa-envelope-open-text"></i> Undangan
                        </a>
                        <a href="{{ route('admin.content.index') }}" class="sidebar-link @if(request()->routeIs('admin.content.*')) active @endif">
                            <i class="fas fa-images"></i> Konten
                        </a>
                    @else
                        <div class="sidebar-section-title">Kasir Menu</div>
                        <a href="{{ route('cashier.dashboard') }}" class="sidebar-link @if(request()->routeIs('cashier.dashboard')) active @endif">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                        <a href="{{ route('cashier.pos') }}" class="sidebar-link @if(request()->routeIs('cashier.pos')) active @endif">
                            <i class="fas fa-cash-register"></i> Kasir
                        </a>
                        <a href="{{ route('cashier.history') }}" class="sidebar-link @if(request()->routeIs('cashier.history')) active @endif">
                            <i class="fas fa-receipt"></i> Riwayat Transaksi
                        </a>
                        <a href="{{ route('cashier.expenses.index') }}" class="sidebar-link @if(request()->routeIs('cashier.expenses.*')) active @endif">
                            <i class="fas fa-money-bill-wave"></i> Pengeluaran
                        </a>
                        <a href="{{ route('cashier.debts.index') }}" class="sidebar-link @if(request()->routeIs('cashier.debts.*')) active @endif">
                            <i class="fas fa-hand-holding-usd"></i> Riwayat Hutang
                        </a>

                        <div class="sidebar-section-title">Undangan &amp; Konten</div>
                        <a href="{{ route('cashier.invitation.index') }}" class="sidebar-link @if(request()->routeIs('cashier.invitation.*')) active @endif">
                            <i class="fas fa-envelope-open-text"></i> Undangan
                        </a>
                        <a href="{{ route('cashier.content.create') }}" class="sidebar-link @if(request()->routeIs('cashier.content.*')) active @endif">
                            <i class="fas fa-images"></i> Konten
                        </a>
                    @endif

                    <div class="sidebar-section-title">Akun</div>
                    <a href="{{ route('profile.edit') }}" class="sidebar-link @if(request()->routeIs('profile.*')) active @endif">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <a href="#" class="sidebar-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                @endauth
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-version">
                    <span class="badge-version">v{{ config('app.version') }}</span>
                    <span>{{ config('app.name') }}</span>
                </div>
            </div>
        </aside>

        <div class="app-sidebar-placeholder d-none d-lg-block"></div>

        <main class="main-content">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateDateTime() {
            const now = new Date();
            const opts = { weekday:'short', year:'numeric', month:'short', day:'numeric' };
            const d = now.toLocaleDateString('id-ID', opts), t = now.toLocaleTimeString('id-ID');
            const el1 = document.getElementById('currentDate'); if(el1) el1.textContent = d;
            const el2 = document.getElementById('currentTime'); if(el2) el2.textContent = t;
            const tb  = document.getElementById('topbarTime');  if(tb)  tb.textContent  = t;
        }
        updateDateTime(); setInterval(updateDateTime, 1000);

        const sidebar   = document.getElementById('appSidebar');
        const backdrop  = document.getElementById('sidebarBackdrop');
        const toggleBtn = document.getElementById('sidebarToggleBtn');
        const closeBtn  = document.getElementById('sidebarCloseBtn');
        function openSidebar()  { if(!sidebar) return; sidebar.classList.add('sidebar-open');    if(backdrop) backdrop.classList.add('show');    document.body.style.overflow='hidden'; }
        function closeSidebar() { if(!sidebar) return; sidebar.classList.remove('sidebar-open'); if(backdrop) backdrop.classList.remove('show'); document.body.style.overflow=''; }
        if(toggleBtn) toggleBtn.addEventListener('click', openSidebar);
        if(closeBtn)  closeBtn.addEventListener('click', closeSidebar);
        if(backdrop)  backdrop.addEventListener('click', closeSidebar);
        window.addEventListener('resize', function(){ if(window.innerWidth >= 992) closeSidebar(); });

        if('serviceWorker' in navigator) {
            window.addEventListener('load', function(){ navigator.serviceWorker.register('/sw.js').catch(function(){}); });
        }

        (function(){
            function getCellValue(row,ci){ const c=row.cells[ci]; return c?c.innerText.trim():''; }
            function comparator(a,b,ci,asc){
                const va=getCellValue(asc?a:b,ci), vb=getCellValue(asc?b:a,ci);
                const na=parseFloat(va.replace(/[^0-9,.-]/g,'').replace(',','.')), nb=parseFloat(vb.replace(/[^0-9,.-]/g,'').replace(',','.'));
                if(!isNaN(na)&&!isNaN(nb)) return na-nb;
                return va.localeCompare(vb,'id');
            }
            function initSortableTable(table){
                const headers=table.querySelectorAll('thead th');
                headers.forEach(function(th,ci){
                    th.style.cursor='pointer'; th.style.userSelect='none'; th.setAttribute('data-sort-dir','');
                    if(!th.querySelector('.sort-icon')){ const s=document.createElement('span'); s.className='sort-icon ms-1 text-muted'; s.innerHTML='&#8597;'; s.style.fontSize='.75rem'; th.appendChild(s); }
                    th.addEventListener('click',function(){
                        const asc=th.getAttribute('data-sort-dir')!=='asc';
                        headers.forEach(function(h){ h.setAttribute('data-sort-dir',''); const ic=h.querySelector('.sort-icon'); if(ic){ic.innerHTML='&#8597;';ic.className='sort-icon ms-1 text-muted';} });
                        th.setAttribute('data-sort-dir',asc?'asc':'desc');
                        const ic=th.querySelector('.sort-icon'); if(ic){ic.innerHTML=asc?'&#8593;':'&#8595;';ic.className='sort-icon ms-1 text-primary';}
                        const tbody=table.querySelector('tbody'); if(!tbody) return;
                        const rows=Array.from(tbody.querySelectorAll('tr')).filter(r=>r.style.display!=='none');
                        rows.sort((a,b)=>comparator(a,b,ci,asc));
                        rows.forEach(r=>tbody.appendChild(r));
                    });
                });
            }
            function initAllTables(){ document.querySelectorAll('table.table').forEach(function(t){ if(!t.dataset.sortInit){t.dataset.sortInit='1';initSortableTable(t);} }); }
            document.addEventListener('DOMContentLoaded', initAllTables);
            window.initAllSortableTables = initAllTables;
        })();
    </script>
    @yield('scripts')
</body>
</html>
