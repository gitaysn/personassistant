<!DOCTYPE html>
<html lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>Edmee Outfit</title>

    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/templatemo-topic-listing.css" rel="stylesheet">


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">
    <link rel="manifest" href="assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="assets/css/theme.css" rel="stylesheet" />
    <style>
      .step {
        transition: all 0.5s ease;
        opacity: 0;
        transform: translateX(30px);
        display: none;
      }

      .step.active {
        opacity: 1;
        transform: translateX(0);
        display: block;
      }

      .progress {
        height: 8px;
        border-radius: 4px;
        background-color: #e9ecef;
        margin-bottom: 30px;
      }

      .progress-bar {
        transition: width 0.4s ease;
      }

      /* Hover dropdown tetap muncul */
      .hover-dropdown:hover .dropdown-menu {
        display: block;
        margin-top: 0;
      }

      /* Tampilan dropdown yang lebih modern */
      .pakaian-dropdown {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        padding: 8px 0;
        border: none;
        min-width: 200px;
        transition: all 0.3s ease;
      }

      .pakaian-dropdown .dropdown-item {
        font-weight: 500;
        color: #333;
        padding: 12px 20px;
        transition: all 0.2s ease;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .pakaian-dropdown .dropdown-item:hover {
        background-color: #f5f5f5;
        color: #000;
        transform: translateX(4px);
      }

      .kategori-pakaian .nav-link {
      border-radius: 25px;
      padding: 8px 20px;
      color: #555;
      background-color: #f2f2f2;
      transition: all 0.3s ease-in-out;
      font-weight: 500;
    }

    .kategori-pakaian .nav-link.active {
      background-color: #27ae60; /* Hijau */
      color: #fff;
      box-shadow: 0 4px 10px rgba(39, 174, 96, 0.3); /* Bayangan hijau */
    }

    .kategori-pakaian .nav-link:hover:not(.active) {
      background-color: #dfe6e9;
      color: #2d3436;
    }

    </style>
  </head>


  <body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
      <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3 bg-light opacity-85" data-navbar-on-scroll="data-navbar-on-scroll">
        <div class="container"><a class="navbar-brand"><img src="{{ asset('assets/img/gallery/logo-edmee.png') }}" alt="Logo" style="width: 80px; height: auto;" />        </a>
          <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
          <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item px-2"><a class="nav-link fw-medium active" aria-current="page" href="#header">Home</a></li>
              <li class="nav-item px-2"><a class="nav-link fw-medium" href="#pilihpakaian">Pilih Pakaian</a></li>
              <li class="nav-item px-2"><a class="nav-link fw-medium" href="#daftarpakaian">Daftar Pakaian </a></li>
              <li class="nav-item px-2"><a class="nav-link fw-medium" href="#contact">Contact </a></li>
            </ul>              
          </div>
        </div>
      </nav>

      <section class="py-0" id="header">
        <div class="bg-holder d-none d-md-block" style="background-image:url(assets/img/illustrations/baju.png);background-position:right top;background-size:contain;">
        </div>
        <!--/.bg-holder-->

        <div class="bg-holder d-md-none" style="background-image:url(assets/img/illustrations/hero-bg.png);background-position:right top;background-size:contain;">
        </div>
        <!--/.bg-holder-->

        <div class="container">
          <div class="row align-items-center min-vh-75 min-vh-lg-100">
            <div class="col-md-7 col-lg-6 col-xxl-5 py-6 text-sm-start text-center">
              <h1 class="mt-6 mb-sm-4 fw-semi-bold lh-sm fs-4 fs-lg-5 fs-xl-6">Hello! <br class="d-block d-lg-block" />Selamat Datang</h1>
              <p class="mb-4 fs-1">Terima kasih telah mengunjungi situs kami! Kami siap membantu anda mencari pakaian yang sesuai dengan gaya dan kebutuhan anda!</p>
            </div>
          </div>
        </div>
      </section>

      <hr>
      
      @include('landingpage.pilihpakaian')


      <hr>

      @include('landingpage.daftarpakaian')

      <hr class="my-4">

      @include('landingpage.contact')

        <!-- Tambahkan Bootstrap Icons jika belum ada -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <!-- Bootstrap 5 JS bundle (sudah termasuk Popper) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

      <hr>

    @yield('footer') 

    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->




    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="vendors/@popperjs/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="assets/js/theme.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Chivo:wght@300;400;700;900&amp;display=swap" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil semua tautan navigasi
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

            // Tambahkan event listener untuk setiap tautan
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Hapus kelas 'active' dari semua tautan
                    navLinks.forEach(link => link.classList.remove('active'));

                    // Tambahkan kelas 'active' ke tautan yang diklik
                    this.classList.add('active');
                });
            });
        });
    </script>

  </body>

</html>