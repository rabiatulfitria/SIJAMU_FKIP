@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center" style="color: #007bff; font-size: 20px; font-weight:bold">Pengendalian</div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('sneat/assets/img/avatars/1.png') }}" alt
                            class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('sneat/assets/img/avatars/1.png') }}" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <!-- Tampilkan nama pengguna -->
                                    <span class="fw-semibold d-block">{{ Auth::User()->nama }}</span>
                                    <!-- Tampilkan role atau informasi tambahan jika perlu -->
                                    <small class="text-muted">{{ Auth::User()->role->role_name }}</small>

                                </div>
                            </div>
                        </a>
                    </li>
                    <!--<li>-->
                    <!--    <div class="dropdown-divider"></div>-->
                    <!--</li>-->
                    <!--<li>-->
                    <!--    <a class="dropdown-item" href="#">-->
                    <!--        <i class="bx bx-user me-2"></i>-->
                    <!--        <span class="align-middle">Profil Pengguna</span>-->
                    <!--    </a>-->
                    <!--</li>-->
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
@endsection

@section('content')
    <div class="card">
        <h5 class="card-header">Pengendalian Standar SPMI Perguruan Tinggi
            <!--<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"-->
            <!--    aria-expanded="false">-->
            <!--    Tahun Akademik (TA)-->
            <!--</button>-->
            <!--<ul class="dropdown-menu">-->
            <!--    <li><a class="dropdown-item" href="javascript:void(0);">2022-2023</a></li>-->
            <!--    <li><a class="dropdown-item" href="javascript:void(0);">2023-2024</a></li>-->
            <!--    <li><a class="dropdown-item" href="javascript:void(0);">2024-2025</a></li>-->
            <!--</ul>-->
        </h5>
        <div class="table text-nowrap" id="horizontal-example" style="height: 200px;">
            <table class="table table-bordered">
                <thead class="table-purple">
                    <tr>
                        <!--<th>Nama Bidang Pengaturan Standar</th>-->
                        <th>Nama Dokumen</th>
                        <th>Tahun</th>
                        <th>Program Studi</th>
                        <th>Dokumen RTM</th>
                        <th>Dokumen RTL</th>
                        @if (Auth::user() &&
                                (Auth::user()->role->role_name == 'Admin' ||
                                    Auth::user()->role->role_name == 'JMF' ||
                                    Auth::user()->role->role_name == 'Ketua Jurusan' ||
                                    Auth::user()->role->role_name == 'Koordinator Prodi'))
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($pengendalian as $row)
                        <tr>
                            {{-- <td><i></i>{{ $row->bidang_standar }}</td> --}}
                            <td><i></i>{{ $row->nama_dokumen }}</td>
                            <td><i></i>{{ $row->tahun }}</td>
                            <td><i></i>{{ $row->prodi->nama_prodi }}</td>
                            <td>

                                @if ($row->file_rtm)
                                    <a href="{{ route('dokumenpengendalian.tampil', ['id_pengendalian' => $row->id_pengendalian, 'jenis_file' => 'rtm', 'file_rtm' => basename($row->file_rtm)]) }}"
                                        class="badge bg-label-info me-1" target="_blank">
                                        <i class="bi bi-link-45deg">Buka Dokumen RTM</i>
                                    </a>
                                @else
                                    <p>Masih dalam proses</p>
                                @endif

                            </td>
                            <td>

                                @if ($row->file_rtl)
                                    <a href="{{ route('dokumenpengendalian.tampil', ['id_pengendalian' => $row->id_pengendalian, 'jenis_file' => 'rtl', 'file_rtl' => basename($row->file_rtl)]) }}"
                                        class="badge bg-label-info me-1" target="_blank">
                                        <i class="bi bi-link-45deg">Buka Dokumen RTL</i>
                                    </a>
                                @else
                                    <p>Masih dalam proses</p>
                                @endif
                            </td>
                            @if (
                                (Auth::user() && Auth::user()->role->role_name == 'Admin') ||
                                    Auth::user()->role->role_name == 'JMF' ||
                                    Auth::user()->role->role_name == 'Ketua Jurusan' ||
                                    Auth::user()->role->role_name == 'Koordinator Prodi')
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <div>
                                                <a class="dropdown-item"
                                                    href="{{ route('editDokumenPengendalian', $row->id_pengendalian) }}"><i
                                                        class="bx bx-edit-alt me-1"></i>
                                                    Ubah</a>
                                            </div>
                                            <div>
                                                <form id="delete-form-{{ $row->id_pengendalian }}" method="POST"
                                                    action="{{ route('hapusDokumenPengendalian', $row->id_pengendalian) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="dropdown-item btn btn-outline-danger"
                                                        onclick="confirmDelete({{ $row->id_pengendalian }})"><i
                                                            class="bx bx-trash me-1"></i>
                                                        Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if (Auth::user() &&
            (Auth::user()->role->role_name == 'Admin' ||
                Auth::user()->role->role_name == 'JMF' ||
                Auth::user()->role->role_name == 'Ketua Jurusan' ||
                Auth::user()->role->role_name == 'Koordinator Prodi'))
        <div class="demo-inline-spacing">
            <button type="button" class="btn btn-light"
                onclick="window.location.href='{{ route('tambahDokumenPengendalian') }}'">+
                Tambah Bentuk Pengendalian</button>
            @if (session('success'))
                <div>{{ @session('success') }}</div>
            @endif
        </div>
    @endif
@endsection
