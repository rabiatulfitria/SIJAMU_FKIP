@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-items d-none d-md-flex align-items-center" style="font-size: 13px">
                SI - Sistem Penjaminan Mutu Internal - Fakultas Keguruan dan Ilmu Pendidikan
            </div>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <h5 class="card-title mb-1" style="margin-right: 10px; font-size: 13px">{{ Auth::user()->nama }}</h5>

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('sneat/assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('sneat/assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::User()->nama }}</span>
                                    <small class="text-muted">{{ Auth::User()->role->role_name }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><div class="dropdown-divider"></div></li>
                    <li><a class="dropdown-item" href="{{ route('profilpengguna.edit') }}"><i class="bx bx-user me-2"></i><span class="align-middle">Profil Akun Pengguna</span></a></li>
                    <li><a class="dropdown-item" href="{{ route('pengaturan') }}"><i class="bx bx-cog me-2"></i><span class="align-middle">Pengaturan</span></a></li>
                    <li><div class="dropdown-divider"></div></li>
                    <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="bx bx-power-off me-2"></i><span class="align-middle">Log Out</span></a></li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
@endsection

@section('content')
    <!-- Mobile Header Title -->
    <div class="d-block d-md-none mb-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <strong>SI - Sistem Penjaminan Mutu Internal - FKIP</strong>
            </div>
        </div>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-3 g-3">
        @php
            $menuItems = [
                ['title' => 'Penetapan', 'desc' => 'Dokumen Terkait Standar SPMI Yang Ditetapkan Perguruan Tinggi'],
                ['title' => 'Pelaksanaan', 'desc' => 'Dokumen Terkait Pelaksanaan Standar SPMI Perguruan Tinggi'],
                ['title' => 'Evaluasi', 'desc' => 'Dokumen Terkait Evaluasi Pelaksanaan Standar SPMI Perguruan Tinggi'],
                ['title' => 'Pengendalian', 'desc' => 'Dokumen Terkait Pengendalian Standar SPMI Perguruan Tinggi'],
                ['title' => 'Peningkatan', 'desc' => 'Dokumen Terkait Peningkatan Standar SPMI Perguruan Tinggi'],
            ];
        @endphp

        @foreach ($menuItems as $item)
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                        </div>
                        <span style="color: #007bff; font-size: 18px; font-weight: bold;">{{ $item['title'] }}</span>
                        <small class="text-gray fw-semibold d-block mt-2">{{ $item['desc'] }}</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
