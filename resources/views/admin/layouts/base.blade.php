<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>V-Person Assistant - @yield('title')</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('/assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('/assets/css/sb-admin-2.min.css')}}" rel="stylesheet">

    

<style>
    .bg-gradient-green {
    background-color: #43a047 !important; /* hijau medium */
    background-image: linear-gradient(180deg, #388e3c 10%, #4caf50 100%) !important;
    background-size: cover;
}

</style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-green sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center logo-custom" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                </div>
                <div class="sidebar-brand-text">SPK V-Person Assistant</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('dashboard') }}">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Master Data
            </div>

            <!-- Nav Item - Data Kriteria -->
            <li class="nav-item {{ request()->is('kriteria') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('kriteria') }}">
                    <i class="bi bi-box"></i>
                    <span>Data Kriteria</span></a>
            </li>

            <!-- Nav Item - Data Sub Kriteria (Collapse) -->
            <li class="nav-item {{ request()->is('admin/subkriteria/kriteria/*') ? 'active' : '' }}">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
                    aria-controls="collapsePages">
                    <i class="bi bi-boxes"></i>
                    <span>Data Sub Kriteria</span>
                </a>
            
                <div id="collapsePages" class="collapse {{ request()->is('admin/subkriteria/kriteria/*') ? 'show' : '' }}"
                    aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        @foreach ($semua_kriteria as $kriteria)
                        {{-- Loop semua kriteria untuk ditampilkan sebagai submenu --}}
                            <a class="collapse-item {{ request()->is('admin/subkriteria/kriteria/' . $kriteria->id) ? 'active text-white' : '' }}"
                                 style="{{ request()->is('admin/subkriteria/kriteria/' . $kriteria->id) ? 'background-color: #064e03;' : '' }}"
                                href="{{ route('admin.pages.subkriteria.kriteria', $kriteria->id) }}">
                                {{ $kriteria->nama_kriteria }} (C{{ $loop->iteration }})
                            </a>
                        @endforeach
                    </div>
                </div>
            </li>            

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    let collapseMenu = document.getElementById("collapsePages");
                    let collapseToggle = document.querySelector("[data-target='#collapsePages']");
                
                    // Cek status collapse di localStorage
                    let isOpen = localStorage.getItem("collapsePages") === "open";
                
                    // Tunggu sebentar sebelum menyesuaikan tampilan (menghindari Bootstrap override)
                    setTimeout(() => {
                        if (isOpen) {
                            collapseMenu.classList.add("show");
                        } else {
                            collapseMenu.classList.remove("show");
                        }
                    }, 50); // Delay kecil untuk menghindari efek flicker
                
                    // Event listener untuk tombol collapse
                    collapseToggle.addEventListener("click", function () {
                        isOpen = !collapseMenu.classList.contains("show");
                        localStorage.setItem("collapsePages", isOpen ? "open" : "closed");
                    });
                });
                </script>        

            <!-- Nav Item - Alternatif -->
            <li class="nav-item {{ request()->is('alternatif') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('alternatif') }}">
                    <i class="bi bi-person-lines-fill"></i>
                    <span>Data Alternatif</span></a>        
            </li>

            <!-- Nav Item - Penilaian -->
            <li class="nav-item {{ request()->is('penilaian') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('penilaian') }}">
                    <i class="bi bi-list-ul"></i>
                    <span>Penilaian Alternatif</span></a>        
            </li>

            <!-- Nav Item - Perhitungan -->
            <li class="nav-item {{ request()->is('admin/perhitungan/*') ? 'active' : '' }}">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePerhitungan" aria-expanded="true"
                    aria-controls="collapsePerhitungan">
                    <i class="bi bi-calculator-fill"></i>
                    <span>Perhitungan</span>
                </a>

                <div id="collapsePerhitungan" class="collapse {{ request()->is('admin/perhitungan/*') ? 'show' : '' }}" 
                    aria-labelledby="headingPages" data-psarent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ request()->is('admin/perhitungan/dress') ? 'active' : '' }}" href="{{ route('admin.perhitungan.dress') }}">Dress</a>
                        <a class="collapse-item {{ request()->is('admin/perhitungan/blouse') ? 'active' : '' }}" href="{{ route('admin.perhitungan.blouse') }}">Blouse</a>
                        <a class="collapse-item {{ request()->is('admin/perhitungan/cardigan') ? 'active' : '' }}" href="{{ route('admin.perhitungan.cardigan') }}">Cardigan</a>
                        <a class="collapse-item {{ request()->is('admin/perhitungan/rok') ? 'active' : '' }}" href="{{ route('admin.perhitungan.rok') }}">Rok</a>
                        <a class="collapse-item {{ request()->is('admin/perhitungan/celana') ? 'active' : '' }}" href="{{ route('admin.perhitungan.celana') }}">Celana</a>
                    </div>
                </div>
            </li>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    let collapseMenu = document.getElementById("collapsePerhitungan");
                    let collapseToggle = document.querySelector("[data-target='#collapsePerhitungan']");
            
                    if (!collapseMenu || !collapseToggle) return;
            
                    // Cek status collapse di localStorage
                    let isOpen = localStorage.getItem("collapsePerhitungan") === "open";
            
                    // Tunggu sebentar sebelum menyesuaikan tampilan
                    setTimeout(() => {
                        if (isOpen) {
                            collapseMenu.classList.add("show");
                        } else {
                            collapseMenu.classList.remove("show");
                        }
                    }, 50);
            
                    // Event listener untuk toggle
                    collapseToggle.addEventListener("click", function () {
                        let willOpen = !collapseMenu.classList.contains("show");
                        localStorage.setItem("collapsePerhitungan", willOpen ? "open" : "closed");
                    });
                });
            </script>            

            <!-- Nav Item - Hasil Akhir -->
            <li class="nav-item {{ request()->is('admin/riwayat') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.riwayat.index') }}">
                    <i class="bi bi-hourglass-split"></i>
                    <span>Riwayat</span>
                </a>
            </li>

            @stack('scripts')

 

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    Admin
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('/assets/img/profile.svg') }}">
                            </a>

                           <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                
                                <!-- Profile Link -->
                                <a class="dropdown-item" href="{{ route('user.show', Auth::user()->id) }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>

                                <!-- Logout Link -->
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Apakah Anda yakin ingin keluar?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Pilih "Logout" di bawah ini jika anda siap mengakhiri sesi anda
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal" 
                    style="background-color: #90ee90; color: black; border-color: #90ee90;">
                Batal
                </button>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn" style="background-color: #064e03; border-color: #064e03; color: white;">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('/assets/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Core plugin JavaScript-->
    <script src="{{asset('/assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('/assets/js/sb-admin-2.min.js')}}"></script>

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>     

</body>

</html>