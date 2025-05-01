@extends('layout.sidebar')

@section('navbar')
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center">
                <!-- Tombol Kembali -->
                <a href="{{ route('pengaturan') }}" class="btn btn-sm btn-light me-2">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h5 class="mb-0">Ubah Password</h5>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('pengguna.ubah-password') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="current_password" class="form-label">Password Lama</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">Password Baru</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-warning">Simpan Password</button>
        </form>

        <form action="{{ route('pengguna.reset-password') }}" method="POST" onsubmit="return confirm('Yakin ingin reset password?')">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-outline-secondary mt-3">
                <i class="fas fa-undo"></i> Reset Password
            </button>
        </form>        
    </div>
</div>
@endsection
