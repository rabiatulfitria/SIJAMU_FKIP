@extends('layout.index-login-auth')

@section('index-content')
@include('sweetalert::alert')
@include('_partials.alert')

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
    </style>

    <span class="app-brand-logo demo">
        <img src="{{ asset('sneat/assets/img/favicon/Logo-kemendikbud.png') }}" class="logo-small" alt="">
        <img src="{{ asset('sneat/assets/img/favicon/LOGO UTM.png') }}" class="logo-small" alt="">
        <img src="{{ asset('sneat/assets/img/favicon/LOGO FIP.png') }}" class="logo-small" alt="">
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
                <div class="mb-3 form-password-toggle">
            <div class="d-flex justify-content-between">
                <label class="form-label" for="basic-default-password32">Password</label>
            </div>
            <div class="input-group input-group-merge">
                <input type="password" id="basic-default-password32" class="form-control" name="password"
                    placeholder="Masukkan Password" aria-describedby="basic-default-password" />
                <span class="input-group-text cursor-pointer" id="basic-default-password"><i class="bx bx-hide"
                        style="z-index: 15"></i></span>
            </div>
        </div>
        </div>
        <div class="mb-3">
            <button class="btn btn-primary d-grid w-100" type="submit">LOGIN</button>
        </div>
    </form>
@endsection
