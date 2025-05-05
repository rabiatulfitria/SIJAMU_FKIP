<!DOCTYPE html>
<html lang="en" data-assets-path="{{ asset('sneat/assets/assets/') }}">


<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>SIJAMU FKIP | Halaman Login</title>

    <!-- Modal -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- SweetAlert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('sneat/assets/img/favicon/LOGO FIP.png') }}" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&amp;display=swap"
        rel="stylesheet" />

    <!-- Icon -->
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core theme CSS (includes Bootstrap)-->

    <a href="{{ asset('sneat/assets/assets/img/bg-mobile-fallback.jpg') }}" ></a>
    <link rel="stylesheet" href="{{ asset('sneat/assets/assets/mp4/bg2.mp4') }}" />
    <link rel="stylesheet" href="{{ asset('sneat/assets/css/demo.css') }}" />
    <script rel="stylesheet" src="{{ asset('sneat/assets/js/config.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('sneat/assets/css/styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('sneat/assets/css/custom.css') }}">
    <script rel="stylesheet" src="{{ asset('sneat/assets/js/scripts.js') }}"></script>

    <!-- helpers -->
    <script src="{{ asset('sneat/assets/vendor/js/helpers.js') }}"></script>

</head>

<body>
    @include('sweetalert::alert')

    <!-- Background Video-->
    <video class="bg-video" playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
        <source src="{{ asset('sneat/assets/assets/mp4/bg2.mp4') }}" type="video/mp4" />
    </video>
    <!-- Masthead-->
    <div class="masthead">
        <div class="masthead-content text-white">
            <div class="container-fluid px-4 px-lg-0">
                @yield('index-content')
            </div>
        </div>
    </div>
    <!-- tombol Registrasi, Panduan Pengguna, Info -->
    <div class="social-icons">
        <div class="d-flex flex-row flex-lg-column justify-content-center align-items-center h-100 mt-3 mt-lg-0">
            {{-- <a type="button" href="{{ route('auth.register') }}" class="btn btn-dark m-3" class="btn btn-dark m-3 tooltip-trigger" title="Daftar Akun Baru">
                <i class="fa-solid fa-user-plus" style="color: #fefefe"></i>
            </a> --}}
            <a href="{{ route('FilePanduanPengguna') }}" class="btn btn-dark m-3" class="btn btn-dark m-3 tooltip-trigger" title="Panduan Pengguna">
                <i class="fa-solid fa-book" style="color: #fefefe;"></i>
            </a>
            <a href="/Info" class="btn btn-dark m-3" class="btn btn-dark m-3 tooltip-trigger" title="Tentang Website Ini">
                <i class="fa-solid fa-circle-info" style="color: #fefefe;"></i>
            </a>
        </div>
    </div>

    <script>
        // Inisialisasi tooltip Bootstrap
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('.tooltip-trigger'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, { trigger: 'hover focus' });
        });
    
        // Simpan waktu klik terakhir di tombol
        const clickTimestamps = {};
    
        // Deteksi klik tombol
        document.querySelectorAll('.tooltip-trigger').forEach(btn => {
            btn.addEventListener('click', function (e) {
                const now = Date.now();
                const id = this.getAttribute('data-href');
    
                if (!clickTimestamps[id] || now - clickTimestamps[id] > 1500) {
                    // Pertama kali klik → tampilkan tooltip
                    e.preventDefault(); // cegah langsung redirect
                    clickTimestamps[id] = now;
    
                    // Tampilkan tooltip secara manual
                    const tooltip = bootstrap.Tooltip.getInstance(this);
                    tooltip.show();
    
                    // Sembunyikan tooltip setelah 1.5 detik
                    setTimeout(() => tooltip.hide(), 1500);
                } else {
                    // Klik kedua → redirect
                    window.location.href = id;
                }
            });
        });
    </script>
    


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('sneat/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/js/bootstrap.js') }}"></script>
    {{-- <script src="{{ asset('sneat/assets/vendor/js/app.js') }}"></script> --}}
    <script src="{{ asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('sneat/assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ asset('sneat/assets/js/main.js') }}"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core theme JS-->
    <script src="{{ asset('start/js/scripts.js') }}"></script>

    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <!-- * *                               SB Forms JS                               * *-->
    <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <!-- <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script> -->
</body>

</html>
