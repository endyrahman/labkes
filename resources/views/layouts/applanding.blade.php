<!doctype html>
<html lang="en" dir="ltr">

    <head>
        <meta charset="utf-8">
        <title>Labkes Kota Semarang</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Pemeriksaan kesehatan laboratorium klinik, laboratorium kimia, laboratorium mikrobiologi">
        <meta name="keywords" content="Laboratorium Kesehatan Kota Semarang">

        <link rel="shortcut icon" href="{{ asset('landingpage/assets/images/favicon.ico') }}">
        <link href="{{ asset('landingpage/assets/libs/tiny-slider/tiny-slider.css') }}" rel="stylesheet">
        <link href="{{ asset('landingpage/assets/libs/tobii/css/tobii.min.css') }}" rel="stylesheet">
        <link href="{{ asset('landingpage/assets/css/bootstrap.css') }}" id="bootstrap-style" class="theme-opt" rel="stylesheet" type="text/css">
        <link href="{{ asset('landingpage/assets/libs/@mdi/font/css/materialdesignicons.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('landingpage/assets/libs/@iconscout/unicons/css/line.css') }}" type="text/css" rel="stylesheet">
        <link href="{{ asset('landingpage/assets/css/style.css') }}" id="color-opt" class="theme-opt" rel="stylesheet" type="text/css">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <style type="text/css">
            .bg-half-50 {
                padding: 50px 0;
                background-size: cover !important;
                -ms-flex-item-align: center;
                  align-self: center;
                position: relative !important;
                background-position: center center;
            }
        </style>
    </head>

    <body>
        <header id="topnav" class="defaultscroll sticky">
            <div class="container">
                <a class="logo" href="index.html" style="display:none;">
                    <img src="{{ asset('landingpage/assets/images/logo-dark.png') }}" height="24" class="logo-light-mode" alt="">
                    <img src="{{ asset('landingpage/assets/images/logo-light.png') }}" height="24" class="logo-dark-mode" alt="">
                </a>

                <div class="menu-extras">
                    <div class="menu-item">
                        <a class="navbar-toggle" id="isToggle" onclick="toggleMenu()">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                    </div>
                </div>

                <ul class="buy-button list-inline mb-0">
                    <li class="list-inline-item ps-1 mb-0">
                        <div class="dropdown dropdown-primary">
                            <a href="javascript:void(0);" type="button" data-bs-toggle="modal" data-bs-target="#login-popup"  class="btn btn-pills btn-primary">Login</a>
                        </div>
                    </li>
                    <li class="list-inline-item ps-1 mb-0">
                        <div class="dropdown dropdown-primary">
                            <a href="javascript:void(0);" type="button" data-bs-toggle="modal" data-bs-target="#daftar-popup" class="btn btn-pills btn-success">Daftar</a>
                        </div>
                    </li>
                </ul>

                <div id="navigation">
                    @include('landingpage.menu')
                </div>
            </div>
        </header>

        @if (Request::is(['/']))
            @include('landingpage.header')

            @include('landingpage.layanankami')

            @include('landingpage.halamandepan.promosi.index')

            @include('landingpage.halamandepan.kegiatan.index')
        @else
            @yield('content');
        @endif

        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="footer-py-60 footer-border">
                            <div class="row">
                                <div class="col-lg-4 col-12 mb-0 mb-md-4 pb-0 pb-md-2">
                                    <a href="#" class="logo-footer" style="display: none;">
                                        <img src="{{ asset('landingpage/assets/images/logo-light.png') }}" height="24" alt="">
                                    </a>
                                    <p class="mt-4">Untuk informasi lebih lanjut dapat menfollow akun media sosial kami</p>
                                    <ul class="list-unstyled social-icon foot-social-icon mb-0 mt-4">
                                        <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i data-feather="instagram" class="fea icon-sm fea-social"></i></a></li>
                                    </ul>
                                </div>

                                <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                                    <h5 class="footer-head">Layanan Kami</h5>
                                    <ul class="list-unstyled footer-list mt-4">
                                    @foreach (App\Models\Landingpage\MenuMdl::getMenu() as $val)
                                        @if ($val->level == 1)
                                            @foreach ($val->sub_menu as $valsubmenu)
                                                <li><a href="{{ url($valsubmenu->url) }}" class="text-foot"><i class="uil uil-angle-right-b me-1"></i> {{ $valsubmenu->nama_menu }}</a></li>
                                            @endforeach
                                        @endif
                                    @endforeach
                                    </ul>
                                </div>

                                <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                                    <h5 class="footer-head">Menu Lainnya</h5>
                                    <ul class="list-unstyled footer-list mt-4">
                                        <li><a href="javascript:void(0)" class="text-foot"><i class="uil uil-angle-right-b me-1"></i> Promosi</a></li>
                                        <li><a href="javascript:void(0)" class="text-foot"><i class="uil uil-angle-right-b me-1"></i> Kegiatan</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-py-30 footer-bar">
                <div class="container text-center">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <div class="text-sm-start">
                                <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Labkes Kota Semarang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <div class="modal fade" id="login-popup" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog  modal-md modal-dialog-centered">
                <div class="modal-content rounded shadow border-0">
                    <div class="modal-body p-4">
                        <div class="container-fluid px-0">
                            <div class="row align-items-center g-0">
                                <div class="col-lg-12 col-md-12">
                                    <form class="login-form p-4" method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label">No. Whatsapp / Email <span class="text-danger">*</span></label>
                                                    <div class="form-icon position-relative">
                                                        <i data-feather="user" class="fea icon-sm icons"></i>
                                                        <input type="text" class="form-control ps-5" placeholder="No. Whatsapp / Email" name="username" required="">
                                                    </div>
                                                </div>
                                            </div><!--end col-->
    
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                                    <div class="form-icon position-relative">
                                                        <i data-feather="key" class="fea icon-sm icons"></i>
                                                        <input type="password" name="password" class="form-control ps-5" placeholder="Password" required="" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div><!--end col-->
    
                                            <div class="col-lg-12">
                                                <div class="d-flex justify-content-between">
                                                    <p class="forgot-pass mb-2"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#lupa-popup" class="text-dark fw-bold">Lupa password ?</a></p>
                                                </div>
                                            </div><!--end col-->
    
                                            <div class="col-lg-12 mb-0">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary">Login</button>
                                                </div>
                                            </div>
    
                                            <div class="col-12 text-center">
                                                <p class="mb-0 mt-3"><small class="text-dark me-2">Belum punya akun ?</small><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#daftar-popup" class="text-dark fw-bold">Pendaftaran akun baru</a></p>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="daftar-popup" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog  modal-md modal-dialog-centered">
                <div class="modal-content rounded shadow border-0">
                    <div class="modal-header">
                        <h5 class="modal-title">Pendaftaran Baru</h5>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid px-0">
                            <div class="row align-items-center g-0">
                                <div class="col-lg-12 col-md-12">
                                    <form class="daftar-form p-4 pt-0" method="POST" action="{{ url('/pendaftaran/storependaftaran') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Jenis Pengguna <span class="text-danger">*</span></label>
                                                    <div class="form-icon position-relative">
                                                        <select id="jenis_pelanggan" name="jenis_pelanggan" class="form-control basic" required>
                                                            <option value=""> Pilih</option>
                                                            <option value="1">Pribadi</option>
                                                            <option value="2">Perusahaan/Instansi</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                                    <div class="form-icon position-relative">
                                                        <input id="nama_lengkap" name="nama_lengkap" type="text" class="form-control ps-3" placeholder="Nama Lengkap" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                                    <div class="form-icon position-relative">
                                                        <textarea id="alamat_lengkap" name="alamat_lengkap" class="form-control" placeholder="Alamat Lengkap" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label">No. Whatsapp<span class="text-danger"> *</span><span class="errornohp" style="color: red; display: none"></span></label>
                                                    <div class="form-icon position-relative">
                                                        <input type="text" class="form-control ps-3" placeholder="No. Whatsapp" id="no_hp" name="no_hp" required="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Email <span class="erroremail" style="color: red; display: none"></span></label>
                                                    <div class="form-icon position-relative">
                                                        <input type="email" class="form-control ps-3" placeholder="Email" id="email" name="email">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                                    <div class="form-icon position-relative">
                                                        <input type="password" name="password" class="form-control ps-3" placeholder="Password" required="" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="col-lg-12 mt-2">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary">Daftar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="lupa-popup" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog  modal-lg modal-dialog-centered">
                <div class="modal-content rounded shadow border-0">
                    <div class="modal-body p-0">
                        <div class="container-fluid px-0">
                            <div class="row align-items-center g-0">
                                <div class="col-lg-6 col-md-5">
                                    <img src="{{ asset('landingpage/assets/images/user/recovery.svg') }}" class="img-fluid" alt="">
                                </div><!--end col-->

                                <div class="col-lg-6 col-md-7">
                                    <form class="login-form p-4" method="POST" action="{{ url('/lupa-password') }}">
                                        @csrf
                                        <h4 class="title mb-4">Lupa Password</h4>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label">No. Whatsapp <span class="text-danger">*</span></label>
                                                    <div class="form-icon position-relative">
                                                        <i data-feather="user" class="fea icon-sm icons"></i>
                                                        <input type="text" class="form-control ps-5" placeholder="No. Whatsapp" name="no_hp" required="">
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="col-lg-12 mb-0">
                                                <div class="d-grid">
                                                    <button class="btn btn-primary">Kirim</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top fs-5"><i data-feather="arrow-up" class="fea icon-sm icons align-middle"></i></a>

        <script src="{{ asset('landingpage/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('landingpage/assets/libs/tiny-slider/min/tiny-slider.js') }}"></script>
        <script src="{{ asset('landingpage/assets/libs/tobii/js/tobii.min.js') }}"></script>
        <script src="{{ asset('landingpage/assets/libs/feather-icons/feather.min.js') }}"></script>
        <script src="{{ asset('landingpage/assets/js/plugins.init.js') }}"></script>
        <script src="{{ asset('landingpage/assets/js/easy_background.js') }}"></script>
        <script src="{{ asset('landingpage/assets/js/app.js') }}"></script>

        <script>
            var arrslide = <?php if (isset($arrslide)) { echo json_encode($arrslide); } ?>;

            easy_background("#home",
                {
                    slide: arrslide,
                    delay: [2000, 2000, 2000]
                }
            );

            $("#no_hp").bind("keypress", function (e) {
                var keyCode = e.which ? e.which : e.keyCode

                if (!(keyCode >= 48 && keyCode <= 57)) {
                    $(".errornohp").text(" Hanya Angka (0 - 9)");
                    $(".errornohp").css("display", "inline");
                    return false;
                }else{
                    $(".errornohp").css("display", "none");
                    $(".errornohp").text("");
                }
            });

            $('#no_hp').change(function () {
                var token = $("input[name='_token']").val();
                var no_hp = $(this).val();

                $.ajax({
                    type:'POST',
                    url: '{{ url("/pendaftaran/ceknohp") }}',
                    data: { _token:token, no_hp:no_hp },
                    success:function(data){
                        if (data.status == '200') {
                            $(".errornohp").css("display", "inline");
                            $(".errornohp").text(" Nomor HP sudah terdaftar");
                            $("#no_hp").focus();
                        } else {
                            $(".errornohp").css("display", "none");
                            $(".errornohp").text("");
                        }
                    }
                });
            });

            $('#email').change(function () {
                var token = $("input[name='_token']").val();
                var email = $(this).val();

                $.ajax({
                    type:'POST',
                    url: '{{ url("/pendaftaran/cekemail") }}',
                    data: { _token:token, email:email },
                    success:function(data){
                        if (data.status == '200') {
                            $(".erroremail").css("display", "inline");
                            $(".erroremail").text(" Email sudah terdaftar");
                            $("#email").focus();
                        } else {
                            $(".erroremail").css("display", "none");
                            $(".erroremail").text("");
                        }
                    }
                });
            });
        </script>
    </body>
</html>