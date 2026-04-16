<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventory Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --ims-ink: #132238;
            --ims-accent: #127369;
            --ims-accent-soft: #d7f2ec;
            --ims-bg: #f5f7fb;
            --ims-card: #ffffff;
            --ims-border: #d8dee8;
        }

        body {
            background:
                radial-gradient(circle at top right, rgba(18, 115, 105, 0.12), transparent 24rem),
                linear-gradient(180deg, #f8fbff 0%, var(--ims-bg) 100%);
            color: var(--ims-ink);
            min-height: 100vh;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .hero-card,
        .surface-card {
            background: var(--ims-card);
            border: 1px solid var(--ims-border);
            border-radius: 1rem;
            box-shadow: 0 1rem 2rem rgba(19, 34, 56, 0.05);
        }

        .hero-card {
            background: linear-gradient(135deg, #0f172a 0%, #123b52 45%, #127369 100%);
            color: #fff;
            overflow: hidden;
        }

        .hero-card::after {
            content: "";
            position: absolute;
            inset: auto -8rem -8rem auto;
            width: 16rem;
            height: 16rem;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }

        .metric-card {
            border: 0;
            border-radius: 1rem;
            height: 100%;
        }

        .metric-card .metric-value {
            font-size: 1.75rem;
            font-weight: 700;
        }

        .table > :not(caption) > * > * {
            vertical-align: middle;
        }

        .img-thumb {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 0.75rem;
            background: #eef3f8;
        }

        .badge-soft {
            background: var(--ims-accent-soft);
            color: var(--ims-accent);
            border: 1px solid rgba(18, 115, 105, 0.2);
        }

        .nav-pills .nav-link.active {
            background-color: var(--ims-accent);
        }
    </style>
    @stack('styles')
</head>
<body>
    @auth
        <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
            <div class="container py-2">
                <a class="navbar-brand text-uppercase" href="{{ route('dashboard') }}">Sharon Inventory</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('catalog.*') ? 'active' : '' }}" href="{{ route('catalog.index') }}">Products</a></li>
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">Categories</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.warehouse-locations.*') ? 'active' : '' }}" href="{{ route('admin.warehouse-locations.index') }}">Locations</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">Manage Products</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.stock-movements.*') ? 'active' : '' }}" href="{{ route('admin.stock-movements.index') }}">Stock History</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}" href="{{ route('admin.activity-logs.index') }}">Activity</a></li>
                        @endif
                    </ul>

                    <div class="d-flex align-items-center gap-3">
                        <div class="text-end small">
                            <div class="fw-semibold">{{ auth()->user()->name }}</div>
                            <div class="text-body-secondary">{{ auth()->user()->role?->name }}</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-dark btn-sm">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    @endauth

    <main class="py-4">
        <div class="container">
            @include('partials.flash')
            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
