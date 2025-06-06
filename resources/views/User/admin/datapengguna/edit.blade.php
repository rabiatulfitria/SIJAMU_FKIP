@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-flex align-item-center" style="margin-left: 15px;">Ubah Data Pengguna</div>
        </div>
    @endsection

    @section('content')
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('updateDataPengguna', $oldData->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-fullname">NIP</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                            class="bx bx-user"></i></span>
                                    <input type="text" class="form-control" id="basic-icon-default-fullname"
                                        name="nip" placeholder="Nomor Induk Pegawai" value="{{ $oldData->nip }}" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-fullname">Nama</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                            class="bx bx-user"></i></span>
                                    <input type="text" class="form-control" id="basic-icon-default-fullname"
                                        name="nama" placeholder="Nama Lengkap" value="{{ $oldData->nama }}" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-company">Alamat Email</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-company2" class="input-group-text"><i
                                            class="bx bx-buildings"></i></span>
                                    <input type="text" id="basic-icon-default-company" class="form-control"
                                        name="email" placeholder="@email.com" value="{{ $oldData->email }}" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-password32">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="basic-default-password32" class="form-control"
                                        name="password" value="{{ $oldData->password }}" placeholder="Masukkan Password"
                                        aria-describedby="toggle-password-icon" />
                                    <span class="input-group-text cursor-pointer" id="toggle-password-icon">
                                        <i class="bx bx-hide" id="toggle-password-icon-eye"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="role">Peran Sebagai</label>
                                <select class="form-select" id="role" name="role_id" required>
                                    <option value="" disabled {{ $role_id == null ? 'selected' : '' }}>
                                        Pilih Peran
                                    </option>
                                    @foreach ($roles as $opsi)
                                        <option value="{{ $opsi->role_id }}"
                                            {{ $role_id == $opsi->role_id ? 'selected' : '' }}>
                                            {{ $opsi->role_name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                            <button type="submit" class="btn btn-primary">{{ isset($users) }}Simpan Perubahan</button>
                            <button type="reset" class="btn btn-outline-secondary"
                                onclick="window.location.href='/DataPengguna'">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
