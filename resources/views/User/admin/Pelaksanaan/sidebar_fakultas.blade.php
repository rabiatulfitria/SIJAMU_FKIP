@extends('layout.sidebar')

@section('navbar')
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center" style="color: #007bff; font-size: 20px; font-weight:bold">Pelaksanaan</div>

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
    <div>
        <div class="nav-align-top mb-4">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a type="button" class="nav-link" href="{{ route('pelaksanaan.prodi') }}">
                        Prodi
                    </a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link active" href="{{ route('pelaksanaan.fakultas') }}">
                        Fakultas
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane show active" id="navs-top-home" role="tabpanel">
                    <div class="row">
                        <!-- Sub Standar -->
                        <div class="col-lg-4">
                            <div class="card overflow-hidden mb-4" style="height: 300px;">
                                <div class="card-body left shadow-lg" id="vertical-example">
                                    <div class="section-title"><b style="font-size: 13px">Strategi Pencapaian</b></div>
                                    <ul>
                                        <li class="menu-itemm active" style="font-size: 12px"><a href="javascript:void(0);" onclick="showTable('DatatablesRenstraProgramStudinya')">Renstra Fakultas</a>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li class="menu-itemm" style="font-size: 12px"><a href="javascript:void(0);" onclick="showTable('DatatablesLaporanKinerjaFakultas')">Laporan Kinerja Fakultas</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--/ Sub Standar -->

                        <!-- Tabel Unggah Dokumen Sub Standar -->
                        <div class="col-lg-8">
                            <div class="card overflow-hidden mb-4" style="height: 300px">
                                <div class="card-body right" id="both-scrollbars-example">
                                    @yield('tabel-unggah-dokumen')
                                </div>
                            </div>
                        </div>
                        <!--/ Tabel Unggah Dokumen Sub Standar -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        //fungsi menu aktif
        document.addEventListener("DOMContentLoaded", function() {
            const menuItems = document.querySelectorAll(".menu-itemm a");

            menuItems.forEach((item) => {
                item.addEventListener("click", function() {
                    menuItems.forEach((el) => el.parentElement.classList.remove("active"));

                    // class 'active' untuk item yang di klik
                    this.parentElement.classList.add("active");
                });
            });
        });
    </script>
@endsection
