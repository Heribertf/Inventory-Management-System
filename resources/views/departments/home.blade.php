<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MFI GROUP - Department Selection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            background-color: #343a40;
            color: #fff;
        }

        .sidebar .nav-link {
            color: #fff;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            background-color: #495057;
        }

        .main-content {
            padding: 2rem;
        }

        .card {
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }

        .logo {
            max-width: 150px;
            height: auto;
            transition: all 0.3s ease;
        }

        @media (min-width: 768px) {
            .sidebar {
                height: 100vh;
                position: sticky;
                top: 0;
            }

            .logo {
                max-width: 180px;
            }
        }

        @media (max-width: 767px) {
            .sidebar {
                padding-bottom: 1rem;
            }

            .logo {
                max-width: 120px;
            }
        }

        @media (max-width: 575px) {
            .logo {
                max-width: 100px;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 sidebar">
                <div class="d-flex flex-column p-3">
                    <a href="#" class="mb-3 text-white text-decoration-none logo-container">
                        <img src="{{ asset('images/MFI-LOGO-02.svg') }}" alt="MFI Logo" class="logo">
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                            this.closest('form').submit();"
                                    class="nav-link text-white">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <h1 class="my-4">Select a department to continue</h1>
                <div class="row">
                  
                    @foreach ($departmentsArray as $department)
                        @switch($department)
                            @case('FR')
                                @php
                                    $departmentName = 'FR Department';
                                    $url = '/fr-dept';
                                    $icon = 'fas fa-chart-line';
                                @endphp
                            @break

                            @case('DC')
                                @php
                                    $departmentName = 'Collection & Delivery Department';
                                    $url = '/cd-dept';
                                    $icon = 'fas fa-truck';
                                @endphp
                            @break

                            @case('INS')
                                @php
                                    $departmentName = 'Installation & Uninstallation Department';
                                    $url = '/ins-dept';
                                    $icon = 'fas fa-tools';
                                @endphp
                            @break
                        @endswitch
                        <div class="col-md-6 col-lg-4 mb-4">
                            <a href="{{ $url }}" class="text-decoration-none">
                                <div class="card h-100">
                                    <div class="card-body d-flex align-items-center">
                                        <i class="{{ $icon }} fa-2x me-3"></i>
                                        <h5 class="card-title mb-0">{{ $departmentName }}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
