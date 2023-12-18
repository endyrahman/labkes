<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Register Online Labkes Kota Semarang</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('backend/assets/img/favicon.ico') }}"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('backend/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/plugins/font-icons/fontawesome/css/fontawesome.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/authentication/form-2.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/css/forms/theme-checkbox-radio.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/css/forms/switches.css') }}">

    <link href="{{ asset('backend/plugins/flatpickr/flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/plugins/noUiSlider/nouislider.min.css') }}" rel="stylesheet" type="text/css">
    <!-- END THEME GLOBAL STYLES -->

    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link href="{{ asset('backend/assets/css/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/plugins/flatpickr/custom-flatpickr.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/plugins/noUiSlider/custom-nouiSlider.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/plugins/bootstrap-range-Slider/bootstrap-slider.css') }}" rel="stylesheet" type="text/css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" /> -->
</head>
<body class="form">
    <div class="form-container outer">
        <div class="form-form">
            <div class="form-container">
                <div class="form-content">
                    <h1 class="">Pendaftaran Baru</h1>
                    <p class="signup-link register">Sudah mempunyai akun ? <a href="/login">Log in</a></p>
                    <form class="text-left" method="POST" action="/pendaftaran/storependaftaran">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="form">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="jenis_pelanggan">Jenis Pelanggan <span style="color:red;">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                                            </span>
                                        </div>
                                        <select id="jenis_pelanggan" name="jenis_pelanggan" class="form-control basic" required>
                                            <option value=""> Pilih</option>
                                            <option value="1">Pribadi</option>
                                            <option value="2">Perusahaan/Instansi</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Nama <span style="color:red;">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                            </span>
                                        </div>
                                        <input id="nama_lengkap" name="nama_lengkap" type="text" class="form-control" placeholder="Nama" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Alamat Lengkap <span style="color:red;">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="input-group mb-4">
                                        <textarea id="alamat_lengkap" name="alamat_lengkap" class="form-control" placeholder="Alamat Lengkap" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="nik">NIK KTP / NPWP <span style="color:red;">*</span><span class="errornik" style="color: red; display: none"></span></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                            </span>
                                        </div>
                                        <input type="text" id="nik" name="nik"  class="form-control basic" maxlength="16" placeholder="NIK KTP / NPWP" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="no_hp">No. HP <span style="color:red;">*</span><span class="errornohp" style="color: red; display: none"></span></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-smartphone"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                                            </span>
                                        </div>
                                        <input type="text" id="no_hp" name="no_hp"  class="form-control basic" maxlength="13" placeholder="No. HP (No. Whatsapp)" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Email <span class="erroremail" style="color: red; display: none"></span></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-at-sign"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                                            </span>
                                        </div>
                                        <input id="email" name="email" type="email" value="" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="passwordlbl">Password <span style="color:red;">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-key"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
                                            </span>
                                        </div>
                                        <input id="password" name="password" type="password" class="form-control" placeholder="Password" autocomplete="off" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div><br/>
                            <div class="field-wrapper terms_condition">
                                <div class="n-chk">
                                    <label class="new-control new-checkbox checkbox-primary">
                                      <input type="checkbox" class="new-control-input" id="verifikasi_daftar" name="verifikasi_daftar" required>
                                      <span class="new-control-indicator"></span><span>Saya menyatakan data di atas diisi dengan benar dan sesuai</span>
                                    </label>
                                </div>
                            </div><br/>
                            <div class="d-sm-flex justify-content-between">
                                <div class="field-wrapper">
                                    <button type="submit" class="btn btn-primary" value="">Simpan Data</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('backend/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('backend/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('backend/assets/js/authentication/form-2.js') }}"></script>    <!-- DATE PICKER -->
    <script src="{{ asset('backend/assets/js/scrollspyNav.js') }}"></script>
    <script src="{{ asset('backend/plugins/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('backend/plugins/noUiSlider/nouislider.min.js') }}"></script>

    <script src="{{ asset('backend/plugins/flatpickr/custom-flatpickr.js') }}"></script>
    <script src="{{ asset('backend/plugins/noUiSlider/custom-nouiSlider.js') }}"></script>
    <script src="{{ asset('backend/plugins/bootstrap-range-Slider/bootstrap-rangeSlider.js') }}"></script>
    <script src="{{ asset('backend/plugins/bootstrap-maxlength/bootstrap-maxlength.js') }}"></script>
    <script src="{{ asset('backend/plugins/bootstrap-maxlength/custom-bs-maxlength.js') }}"></script>
    <script src="{{ asset('backend/assets/js/authentication/form-2.js') }}"></script>
    <script src="{{ asset('backend/assets/js/authentication/form-1.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous"></script> -->
    <script>
    $(document).ready(function() {
        $("#nik").bind("keypress", function (e) {
            var keyCode = e.which ? e.which : e.keyCode

            if (!(keyCode >= 48 && keyCode <= 57)) {
                $(".errornohp").text(" Hanya Angka (0 - 9)");
                $(".errornik").css("display", "inline");
                return false;
            }else{
                $(".errornik").css("display", "none");
                $(".errornohp").text("");
            }
        });

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

        var tgl_lahir = flatpickr(document.getElementById('tgl_lahir'), {
            dateFormat: "d-m-Y"
        });

        // $('#tgl_lahir').datetimepicker({
        //     format: "DD-MM-YYYY",
        //     useCurrent: false
        // })

        $('#nik').maxlength();
        $('#no_hp').maxlength();
    });

    $('#no_hp').change(function () {
        var token = $("input[name='_token']").val();
        var no_hp = $(this).val();

        $.ajax({
            type:'POST',
            url: '/pendaftaran/ceknohp',
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

    $('#nik').change(function () {
        var token = $("input[name='_token']").val();
        var nik = $(this).val();

        $.ajax({
            type:'POST',
            url: '/pendaftaran/ceknik',
            data: { _token:token, nik:nik },
            success:function(data){
                if (data.status == '200') {
                    $(".errornik").css("display", "inline");
                    $(".errornik").text(" NIK sudah terdaftar");
                    $("#nik").focus();
                } else {
                    $(".errornik").css("display", "none");
                    $(".errornik").text("");
                }
            }
        });
    });

    $('#email').change(function () {
        var token = $("input[name='_token']").val();
        var email = $(this).val();

        $.ajax({
            type:'POST',
            url: '/pendaftaran/cekemail',
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

    $('#jenis_pelanggan').change(function () {
        var token = $("input[name='_token']").val();
        var jp = $(this).val();

        if (jp == 2) {
            $('#tempat_lahir, #tgl_lahir, #jenis_kelamin').removeAttr("required");
            $('#lbltempat_lahir, #lbltgl_lahir, #lbljenis_kelamin').text("");
        } else {
            $('#tempat_lahir, #tgl_lahir, #jenis_kelamin').attr("required");
            $('#lbltempat_lahir, #lbltgl_lahir, #lbljenis_kelamin').text("*");
        }
    });

    $('#toggle-password').click(function () {
        var x = document.getElementById("password");

        if (x.type === "text") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    });
    </script>
</body>
</html>