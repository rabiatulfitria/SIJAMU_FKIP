@extends('layout.sidebar')

@section('navbar')
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center">
                <!-- Tombol Kembali -->
                <a href="{{ route('BerandaSIJAMUFKIP') }}" class="btn btn-sm btn-light me-2">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h5 class="mb-0">Profil Akun Pengguna</h5>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <!-- Account -->
        {{-- <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                    <img src="{{ asset('sneat/assets/img/avatars/1.png') }}" alt="user-avatar" class="d-block rounded" height="100"
                        width="100" id="uploadedAvatar" />
                    <div class="button-wrapper">
                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                            <span class="d-none d-sm-block">Upload new photo</span>
                            <i class="bx bx-upload d-block d-sm-none"></i>
                            <input type="file" id="upload" class="account-file-input" hidden
                                accept="image/png, image/jpeg" />
                        </label>
                        <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                            <i class="bx bx-reset d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Reset</span>
                        </button>
                    </div>
                </div>
            </div> --}}
        <hr class="my-0" />
        <div class="card-body">
            <form id="formAccountSettings" method="POST" action="{{ route('profilpengguna.update', $oldData->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-fullname">NIP</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                        <input type="text" class="form-control" id="basic-icon-default-fullname" name="nip"
                            placeholder="Nomor Induk Pegawai" value="{{ $oldData->nip }}" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-fullname">Nama</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                        <input type="text" class="form-control" id="basic-icon-default-fullname" name="nama"
                            placeholder="Nama Lengkap" value="{{ $oldData->nama }}" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-company">Alamat Email</label>
                    <div class="input-group input-group-merge">
                        <span id="basic-icon-default-company2" class="input-group-text"><i
                                class="bx bx-buildings"></i></span>
                        <input type="text" id="basic-icon-default-company" class="form-control" name="email"
                            placeholder="@email.com" value="{{ $oldData->email }}" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="role">Peran Sebagai</label>
                    @php
                        $userRole = Auth::user()->role->role_name;
                    @endphp

                    @if ($userRole !== 'Admin')
                        <select class="form-select" id="role" name="role_id" required>
                            <option value="" disabled
                                {{ old('role_id', $role_id ?? null) === null ? 'selected' : '' }}>
                                Pilih Peran
                            </option>
                            @foreach ($roles as $opsi)
                                <option value="{{ $opsi->role_id }}"
                                    {{ old('role_id', $role_id ?? null) == $opsi->role_id ? 'selected' : '' }}
                                    {{ $opsi->role_name === 'Admin' ? 'disabled' : '' }}>
                                    {{ $opsi->role_name }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="role_id" value="{{ $role_id }}">
                        <div class="form-control-plaintext">{{ $userRole }}</div>
                    @endif

                </div>
                <button type="submit" class="btn btn-primary">{{ isset($users) }}Simpan Perubahan</button>
            </form>
        </div>
        <!-- /Account -->
    </div>
@endsection
