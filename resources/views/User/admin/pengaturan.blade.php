@extends('layout.sidebar')
@section('navbar')
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center">
                <!-- Tombol Kembali -->
                <a href="{{ route('BerandaSIJAMUFKIP') }}" class="btn btn-sm btn-light me-2">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h5 class="mb-0">Pengaturan</h5>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="list-group list-group-flush">
    <!-- Item: Ubah Password -->
    <a href="{{ route('pengguna.form-ubah-password') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
        Ubah Password
        <i class="fas fa-chevron-right text-muted"></i>
    </a>
    <!-- Item: lainnya -->
</div>
@endsection
