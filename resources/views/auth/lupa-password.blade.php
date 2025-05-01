<!DOCTYPE html>
<html lang="en" data-assets-path="{{ asset('sneat/assets/assets/') }}">


<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>SIJAMU FKIP | Lupa Password</title>

    <!-- Modal -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

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

    <link rel="stylesheet" href="{{ asset('sneat/assets/assets/img/bg-mobile-fallback.jpg') }}" />
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
    <style>
        .text-container {
            max-width: 400px;
            /* Batasi lebar teks */
            margin: 0 auto;
            /* Tengah secara horizontal */
            text-align: left;
            /* Rata tengah */
            line-height: 1.5;
            /* Spasi antar baris */
        }

        p.mb-5 {
            white-space: normal;
            /* Izinkan teks terpotong otomatis */
            word-wrap: break-word;
            /* Bungkus kata panjang */
        }

        a:hover {
            color: #0d6efd;
            /* Ganti dengan warna yang kamu inginkan */
            text-decoration: underline;
            /* Opsional, untuk efek garis bawah saat hover */
        }
        
        .logo-small-ss {
            width: 35px;
            height: auto;
        }
    </style>

    <!-- Background Video-->
    <video class="bg-video" playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
        <source src="{{ asset('sneat/assets/assets/mp4/bg2.mp4') }}" type="video/mp4" />
    </video>
    <!-- Masthead-->
    <div class="masthead">
        <div class="masthead-content text-white">
            <div class="">
                <p class="">
                    Cari Akun Anda<br>
                </p>

                <form id="formAuthentication" class="mb-3" action="{{ route('lupa-password.cari') }}" method="POST"
                    onsubmit="return validateForm()">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip" required />
                                <small id="nipError" class="text-danger d-none">NIP harus berupa angka dan minimal 17
                                    digit.</small>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required />
                                <small id="emailError" class="text-danger d-none">Format email tidak valid.</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button class="btn btn-primary d-grid w-100" type="submit">Cari Akun</button>
                    </div>
                    <p><a href="{{ route('auth.login') }}">Kembali Ke Halaman Sebelumnya</a></p>
                </form>
            </div>
        </div>
    </div>
    <div class="social-icons">
        <div class="d-flex flex-row flex-lg-column justify-content-center align-items-center h-100 mt-3 mt-lg-0">
            <span class="app-brand-logo demo">
                <img src="{{ asset('sneat/assets/img/favicon/Logo-kemendikbud.png') }}" class="logo-small-ss"
                    alt="">
                <img src="{{ asset('sneat/assets/img/favicon/LOGO UTM.png') }}" class="logo-small-ss" alt="">
                <img src="{{ asset('sneat/assets/img/favicon/LOGO FIP.png') }}" class="logo-small-ss" alt="">
            </span>
            <h1 style="font-size: 35px">SIJAMU FKIP</h1>
        </div>
    </div>


    <script>
        // Untuk Password
        $('#togglePassword').on('click', function() {
            const passwordInput = $('#password');
            const icon = $('#toggleIcon');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            icon.toggleClass('fa-eye fa-eye-slash');
        });

        // Untuk Konfirmasi Password
        $('#toggleConfirmPassword').on('click', function() {
            const passwordInput = $('#confirm_password');
            const icon = $('#toggleConfirmIcon');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
            icon.toggleClass('fa-eye fa-eye-slash');
        });
    </script>

<script>
    function validateForm() {
        const nip = document.getElementById("nip").value.trim();
        const email = document.getElementById("email").value.trim();

        let isValid = true;

        // Validasi NIP (hanya angka dan minimal 17 digit)
        const nipError = document.getElementById("nipError");
        if (!/^\d{17,}$/.test(nip)) {
            nipError.classList.remove("d-none");
            isValid = false;
        } else {
            nipError.classList.add("d-none");
        }

        // Validasi Email (format email standar)
        const emailError = document.getElementById("emailError");
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            emailError.classList.remove("d-none");
            isValid = false;
        } else {
            emailError.classList.add("d-none");
        }

        return isValid;
    }
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
