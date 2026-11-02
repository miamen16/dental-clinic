<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title')@yield('title') · @endif Dental Clinic</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        .auth-brand {
            background: linear-gradient(135deg, var(--bs-primary) 0%, #0a3d91 100%);
        }

        .auth-brand .bi-heart-pulse {
            font-size: 4rem;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container-fluid">
        <div class="row min-vh-100">
            {{-- Branding panel (large screens only) --}}
            <div class="col-lg-6 auth-brand text-white d-none d-lg-flex flex-column justify-content-center align-items-center p-5">
                <i class="bi bi-heart-pulse mb-3"></i>
                <h1 class="display-6 fw-bold mb-2">Dental Clinic</h1>
                <p class="lead text-white-50 text-center mb-0" style="max-width: 24rem;">
                    Patients, doctors, appointments and services: all in one place.
                </p>
            </div>

            {{-- Form panel --}}
            <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center py-5 px-3">
                <div class="w-100" style="max-width: 420px;">
                    <div class="text-center mb-4 d-lg-none">
                        <i class="bi bi-heart-pulse text-primary" style="font-size: 3rem;"></i>
                        <h1 class="h4 fw-bold text-primary mt-2">Dental Clinic</h1>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 p-sm-5">
                            @yield('content')
                        </div>
                    </div>

                    <p class="text-center text-muted small mt-4 mb-0">&copy; {{ date('Y') }} Dental Clinic</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
