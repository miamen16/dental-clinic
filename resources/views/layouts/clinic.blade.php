<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @hasSection('title')
            @yield('title') ·
        @endif Dental Clinic
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        .navbar .nav-link.active {
            background-color: rgba(255, 255, 255, .15);
            border-radius: .375rem;
        }
    </style>
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="bi bi-heart-pulse me-1"></i> Dental Clinic
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto gap-lg-1">
                    <li class="nav-item">
                        <a class="nav-link px-lg-2 @if (request()->routeIs('dashboard')) active @endif"
                            href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-lg-2 @if (request()->routeIs('patients.*')) active @endif"
                            href="{{ route('patients.index') }}">
                            <i class="bi bi-people me-1"></i> Patients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-lg-2 @if (request()->routeIs('appointments.*')) active @endif"
                            href="{{ route('appointments.index') }}">
                            <i class="bi bi-calendar-check me-1"></i> Appointments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-lg-2 @if (request()->routeIs('medical-records.*')) active @endif"
                            href="{{ route('medical-records.index') }}">
                            <i class="bi bi-file-medical me-1"></i> Records
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link px-lg-2 dropdown-toggle @if (request()->routeIs('invoices.*', 'services.*', 'taxes.*')) active @endif"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-receipt me-1"></i> Billing
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item @if (request()->routeIs('invoices.*')) active @endif"
                                    href="{{ route('invoices.index') }}">
                                    <i class="bi bi-file-earmark-text me-1"></i> Invoices
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <h6 class="dropdown-header">Pricing</h6>
                            </li>
                            <li>
                                <a class="dropdown-item @if (request()->routeIs('services.*')) active @endif"
                                    href="{{ route('services.index') }}">
                                    <i class="bi bi-clipboard2-pulse me-1"></i> Services
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item @if (request()->routeIs('taxes.*')) active @endif"
                                    href="{{ route('taxes.index') }}">
                                    <i class="bi bi-percent me-1"></i> Taxes
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link px-lg-2 dropdown-toggle @if (request()->routeIs('doctors.*', 'receptionists.*', 'users.*')) active @endif"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-gear me-1"></i> Staff
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item @if (request()->routeIs('doctors.*')) active @endif"
                                    href="{{ route('doctors.index') }}">
                                    <i class="bi bi-person-badge me-1"></i> Doctors
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item @if (request()->routeIs('receptionists.*')) active @endif"
                                    href="{{ route('receptionists.index') }}">
                                    <i class="bi bi-headset me-1"></i> Receptionists
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item @if (request()->routeIs('users.*')) active @endif"
                                    href="{{ route('users.index') }}">
                                    <i class="bi bi-person-lock me-1"></i> All Users
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

                @auth
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                                {{-- the role badge only fits next to the menu on wide screens --}}
                                <span class="badge bg-light text-primary ms-1 d-lg-none d-xl-inline">{{ ucfirst(auth()->user()->role) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-gear me-1"></i> Profile
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="mb-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-1"></i> Log Out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container py-4 flex-grow-1">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-top bg-white py-3 mt-auto">
        <div class="container small text-muted d-flex justify-content-between">
            <span>&copy; {{ date('Y') }} Dental Clinic</span>
            <span>{{ now()->format('l, F j, Y') }}</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
