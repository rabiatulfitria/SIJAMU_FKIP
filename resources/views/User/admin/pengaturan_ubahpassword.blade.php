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
            <form id="formAuthentication" action="{{ route('pengguna.ubah-password') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="current_password" class="form-label">Password Lama</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="current_password"
                            placeholder="minimal 6 karakter" required />
                        <button type="button" id="togglePassword">
                            <i class="fa fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <small id="passwordError" class="text-danger d-none"> Password minimal 6
                        karakter.</small>
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">Password Baru</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="new_password"
                            placeholder="minimal 6 karakter" required />
                        <button type="button" id="togglePassword">
                            <i class="fa fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <small id="passwordError" class="text-danger d-none"> Password minimal 6
                        karakter.</small>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                            placeholder="minimal 6 karakter" required />
                        <button type="button" id="toggleConfirmPassword">
                            <i class="fa fa-eye" id="toggleConfirmIcon"></i>
                        </button>
                    </div>
                    <small id="confirmPasswordError" class="text-danger d-none">Isikan format password yang
                        benar</small>
                </div>


                <button type="submit" class="btn btn-warning">Simpan Password</button>
            </form>

            <form action="{{ route('pengguna.reset-password') }}" method="POST"
                onsubmit="return confirm('Yakin ingin reset password?')">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-outline-secondary mt-3">
                    <i class="fas fa-undo"></i> Reset Password
                </button>
            </form>
        </div>
    </div>
@endsection
