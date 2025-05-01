<!DOCTYPE html>
<html lang="en" data-assets-path="{{ asset('sneat/assets/assets/') }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>SIJAMU FKIP | Download Panduan Pengguna</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('sneat/assets/img/favicon/LOGO FIP.png') }}" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('sneat/assets/css/styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('sneat/assets/css/custom.css') }}" />

    <style>
        a:hover {
            color: #0d6efd;
            /* Ganti dengan warna yang kamu inginkan */
            text-decoration: underline;
            /* Opsional, untuk efek garis bawah saat hover */
        }
    </style>
</head>

<body>
    <!-- Background Video -->
    <video class="bg-video" playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
        <source src="{{ asset('sneat/assets/assets/mp4/bg2.mp4') }}" type="video/mp4" />
    </video>

    <!-- Main Content -->
    <div class="masthead">
        <div class="masthead-content text-white text-center">
            <h1 class="mb-4">Panduan Pengguna</h1>
            <p class="lead">Unduh panduan pengguna dalam format PDF.</p>
            <a href="{{ asset('file_panduanpengguna/panduanpengguna.pdf') }}" class="btn btn-primary btn-lg" target="_blank">
                <i class="fas fa-download"></i> Unduh Panduan Pengguna
            </a>
            <p style="padding-top: 50px"><a href="{{ route('auth.login') }}">Kembali Ke Halaman Login</a></p>
        </div>
    </div>
</body>

</html>
