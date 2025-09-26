<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring Pekerja - Helm Proyek</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link 
      rel="stylesheet" 
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-o9N1j7kGStK1bZ9SjeYdqLRU0p8gCmr9V6x3TM1Zl4M="
      crossorigin=""
    />

    <!-- Auto-refresh (sementara, kalau realtime sudah jalan bisa dihapus) -->
    {{-- @if (request()->routeIs('pekerjas.index'))
        <meta http-equiv="refresh" content="7">
    @endif --}}

    <style>
        body {
            background: url('{{ asset('images/bg-1.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }

        .main-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .navbar {
            background-color: rgba(25, 118, 210, 0.95) !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .btn-primary {
            background-color: #ff9800;
            border: none;
        }

        .btn-primary:hover {
            background-color: #fb8c00;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark px-4">
        <span class="navbar-brand">👷 <strong>Helm Proyek</strong></span>
        <div class="ms-auto text-white">Monitoring Keselamatan Kerja</div>
    </nav>

    <main class="py-4">
        <div class="container">
            <div class="main-card">
                @yield('content')
            </div>
        </div>
    </main>

    {{-- Panggil file JS hasil Vite --}}
    @vite('resources/js/app.js')

    {{-- Stack untuk script tambahan dari halaman child --}}
    @stack('scripts')
</body>
</html>
