@extends('layout.index-login-auth')

@section('index-content')
    @include('sweetalert::alert')
    {{-- @include('_partials.alert') --}}

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

        .logo-small-s {
            width: 70px;
            height: auto;
        }
    </style>

    <span class="app-brand-logo demo">
        <img src="{{ asset('sneat/assets/img/favicon/Logo-kemendikbud.png') }}" class="logo-small-s" alt="">
        <img src="{{ asset('sneat/assets/img/favicon/LOGO UTM.png') }}" class="logo-small-s" alt="">
        <img src="{{ asset('sneat/assets/img/favicon/LOGO FIP.png') }}" class="logo-small-s" alt="">
    </span>
    <h1 class="fst-bold lh-1 mb-4">SIJAMU FKIP</h1>
    <div class="text-container">
        <p class="mb-5">
            Sistem Informasi<br>
            Sistem Penjaminan Mutu Internal<br>
            Fakultas Keguruan dan Ilmu Pendidikan<br>
            Universitas Trunojoyo Madura
        </p>
    </div>

    <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan Email"
                autofocus />
        </div>
        <div class="mb-3">
            <label class="form-label" for="basic-default-password32">Password</label>
            <div class="input-group input-group-merge">
                <input type="password" id="basic-default-password32" class="form-control" name="password"
                    placeholder="Masukkan Password" aria-describedby="toggle-password-icon" />
                <span class="input-group-text cursor-pointer" id="toggle-password-icon">
                    <i class="bx bx-hide" id="toggle-password-icon-eye"></i>
                </span>
            </div>
        </div>
        <div class="mb-3">
            <button class="btn btn-primary d-grid w-100" type="submit">LOGIN</button>
            @if (session('success'))
                <div>{{ @session('success') }}</div>
            @endif
        </div>
        <a href="{{ route('lupa-password') }}">Lupa Password</a>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const passwordInput = document.getElementById('basic-default-password32');
            const toggleIcon = document.getElementById('toggle-password-icon-eye');

            if (passwordInput && toggleIcon) {
                toggleIcon.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('bx-hide');
                    this.classList.toggle('bx-show');
                });
            }
        });
    </script>
@endsection
