@extends('layouts.app')

@section('content')
@php
    ini_set('date.timezone', 'Asia/Jakarta');
@endphp
<style type="text/css">
.flatpickr-calendar.open {
    display: inline-block;
    z-index: 99999!important;
}
</style>
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Edit Registrasi Klinik</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <div class="info">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0 mt-4">
                            <form method="POST" action="/registrasi/klinik/updateklinik">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input id="jenis_lab_id" name="jenis_lab_id" class="form-control" value="1" type="hidden">
                                <input type="hidden" name="pemeriksaan_id" id="pemeriksaan_id" value="{{$data->id}}">
                               <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_waktu_kunjungan">Pilih Pasien</label>
                                            <div class="input-group mb-4">
                                                <select class="form-control" id="pasien_id" name="pasien_id" required="required">
                                                    <option value="">Pilih Pasien</option>
                                                    @foreach (App\Models\PasienTbl\PasienTbl::getDataPasien() as $val)
                                                        <option value="{{ $val->id }}" {{ ($data->pasien_id == $val->id ? "selected":"") }}>{{ $val->nama_pasien }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#mdlTambahPasien">Tambah Pasien</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_waktu_kunjungan">Pilih Tgl & Jam Kunjungan</label>
                                            <input id="tgl_waktu_kunjungan" name="tgl_waktu_kunjungan" class="form-control" type="button" value="{{ date('d-m-Y H:i', strtotime($data->tgl_waktu_kunjungan)) }}" readonly required>
                                            <input id="tglwaktukunjungan" name="tglwaktukunjungan" class="form-control" type="hidden" value="{{ date('d-m-Y H:i', strtotime($data->tgl_waktu_kunjungan)) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="total_biaya">Total Biaya Pemeriksaan</label>
                                        <div class="input-group mb-4">
                                            <input type="text" name="total_biaya" id="total_biaya" class="form-control" value="{{ number_format($data->total_biaya, 0, ',', '.') }}" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#mdlDetailTotalBiaya" onclick="getDetailTotalBiaya()">Detail Biaya</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <h4>Paket Pemeriksaan</h4>
                                    <div class="row mt-4" id="listpaketpemeriksaan">
                                        @include('registrasi.klinik.paketpemeriksaan')
                                    </div>
                                    <input type="hidden" name="hidden_page_paketpemeriksaan" id="hidden_page_paketpemeriksaan" value="1"/>
                                </div>

                                <div class="col-sm-12 mt-2">
                                    <h4>Pemeriksaan</h4>
                                    <div class="row mt-4" id="listparameterpemeriksaan">
                                        @include('registrasi.klinik.parameterpemeriksaan')
                                    </div>
                                    <input type="hidden" name="hidden_page_parameter" id="hidden_page_parameter" value="1"/>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <button class="btn btn-primary btn-block mt-3 mb-4 mr-2" id="simpanKlinik">Simpan Data</button>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="/registrasi/klinik" class="btn btn-danger btn-block mt-3 mb-4 mr-2" id="batalEditKlinik">Batal Edit</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="mdlJadwalKunjungan" tabindex="-1" role="dialog" aria-labelledby="mdlJadwalKunjunganTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlJadwalKunjunganTitle">Pilih Waktu Kunjungan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <input id="waktu_kunjungan" name="waktu_kunjungan" value="{{ date('H:i') }}" class="form-control" type="hidden" readonly required>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="tgl_kunjungan">Waktu Kunjungan</label>
                        <input id="tgl_kunjungan" name="tgl_kunjungan" value="{{ date('d-m-Y') }}" class="form-control flatpickr flatpickr-input active" type="text" onchange="waktuKunjungan()" readonly required>
                    </div>
                </div>
                <div class="col-sm-12" id="jadwal">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlDetailPemeriksaanKlinik" tabindex="-1" role="dialog" aria-labelledby="mdlJadwalKunjunganTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlDetailPemeriksaanKlinikTitle">Detail Pemeriksaan <span id="mdljenispemeriksaanklinis"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <span id="detailPemeriksaanKlinik"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlAlertKunjungan" tabindex="-1" role="dialog" aria-labelledby="mdlAlertKunjunganTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlAlertKunjunganTitle">Pemberitahuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <h5 style="color: red;">Tgl dan jam kunjungan belum terisi. Silahkan isi terlebih dahulu.</h5>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" id="tutupPemberitahuan"><i class="flaticon-cancel-12"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlAlertPasien" tabindex="-1" role="dialog" aria-labelledby="mdlAlertPasienTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlAlertPasienTitle">Pemberitahuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <h5 style="color: red;">Data pasien belum dipilih. Silahkan pilih pasien / tambah data terlebih dahulu.</h5>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" id="tutupPemberitahuan"><i class="flaticon-cancel-12"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlAlertTotalBiaya" tabindex="-1" role="dialog" aria-labelledby="mdlAlertTotalBiayaTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlAlertTotalBiayaTitle">Pemberitahuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <h5 style="color: red;">Silahkan pilih pemeriksaan terlebih dahulu.</h5>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" id="tutupPemberitahuan"><i class="flaticon-cancel-12"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlDetailTotalBiaya" tabindex="-1" role="dialog" aria-labelledby="mdlJadwalKunjunganTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlDetailTotalBiayaTitle">Detail Total Biaya</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" >
                <div class="table-responsive">
                    <table class="table table-bordered mb-4">
                        <thead>
                            <tr>
                                <th class="text-center">Pemeriksaan</th>
                                <th class="text-center">Biaya</th>
                            </tr>
                        </thead>
                        <tbody id="bodyDetailTotalBiaya">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlTambahPasien" tabindex="-1" role="dialog" aria-labelledby="mdlJadwalKunjunganTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlTambahPasienTitle">Tambah Data Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" >
                <form method="POST" action="/spr/pasien" id="pasien-form">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="nama_pasien">Nama Pasien</label>
                            <input id="nama_pasien" name="nama_pasien" class="form-control" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="nik">NIK</label>
                            <input id="nik" name="nik" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-4">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <input id="tempat_lahir" name="tempat_lahir" class="form-control" required>
                        </div>
                        <div class="col-sm-4">
                            <label for="tgl_lahir">Tgl. Lahir</label>
                            <input id="tgl_lahir" name="tgl_lahir" class="form-control flatpickr flatpickr-input active" required>
                        </div>
                        <div class="col-sm-4">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih</option>
                                <option value="1">Laki-laki</option>
                                <option value="2">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <label for="nama_pasien">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="2" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('backend/plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('backend/plugins/flatpickr/custom-flatpickr.js') }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('backend/plugins/notification/snackbar/snackbar.min.js') }}"></script>
<script>
    $(function(){
        // getPaketPemeriksaanKlinik();
    });

    var tgl_kunjungan = flatpickr(document.getElementById('tgl_kunjungan'), {
        dateFormat: "d-m-Y",
        minDate: "today",
        maxDate: new Date().fp_incr(14),
        disable: [
            function(date) {
                return (date.getDay() === 0);
            }
        ],
        locale: {
            "firstDayOfWeek": 1
        }
    });
    var tgl_lahir = flatpickr(document.getElementById('tgl_lahir'), {
        dateFormat: "d-m-Y"
    });

    $(document).on('click', '.pagination a', function(event){
        var urlnow = $(this).attr('href').split('/');
        event.preventDefault();
        var cekurl = $(this).attr('href');
        console.log(cekurl)
        if (cekurl.includes("pagepaketpemeriksaan")) {
            var page = $(this).attr('href').split('pagepaketpemeriksaan=')[1];
            $('#hidden_page_paketpemeriksaan').val(page);
            $('li').removeClass('active');
                $(this).parent().addClass('active');
            paginationPaketPemeriksaan(page);
        } else if (cekurl.includes("pageparameterpemeriksaan")) {
            var page = $(this).attr('href').split('pageparameterpemeriksaan=')[1];
            $('#hidden_page_parameterpemeriksaan').val(page);
            $('li').removeClass('active');
                $(this).parent().addClass('active');
            paginationParameterPemeriksaan(page);
        }
    });

    function paginationPaketPemeriksaan(page) {
        $.ajax({
            url:"/registrasi/klinik/paginationpaketpemeriksaan?pagepaketpemeriksaan="+page,
            success:function(datas)
            {
                $('#listpaketpemeriksaan').html('');
                $('#listpaketpemeriksaan').html(datas);
            }
        });
    }

    function paginationParameterPemeriksaan(page) {
        $.ajax({
            url:"/registrasi/klinik/paginationparameterpemeriksaan?pageparameterpemeriksaan="+page,
            success:function(datas)
            {
                $('#listparameterpemeriksaan').html('');
                $('#listparameterpemeriksaan').html(datas);
            }
        });
    }

    $('#tgl_waktu_kunjungan').on('click', function(e) {
        var token = $("input[name='_token']").val();
        $('#mdlJadwalKunjungan').modal('toggle');
        waktuKunjungan();
    });

    function getPaketPemeriksaanKlinik() {
        var token = $("input[name='_token']").val();

        $.ajax({
            type:'POST',
            url: '/registrasi/getPaketPemeriksaanKlinik',
            data: { _token:token },
            success:function(data){
                $('#paketPemeriksaanKlinik').html(data);
            }
        });
    }

    function waktuKunjungan() {
        var waktu = $("#waktu_kunjungan").val();
        var tgl = $("#tgl_kunjungan").val();
        var token = $("input[name='_token']").val();

        $.ajax({
            type:'POST',
            url: '/registrasi/getJadwalPemeriksaan',
            data: { _token:token, waktu:waktu, tgl:tgl },
            success:function(data){
                $('#jadwal').html(data);
            }
        });
    }

    $('#simpanjadwal').on('click', function(e) {
        var tgl_kunjungan = $('#tgl_kunjungan').val();
        var jam_kunjungan = $('input[name="jam_kunjungan"]:checked').val();
        var jadwalKunjungan = tgl_kunjungan+' '+jam_kunjungan+':00';
        $('#tgl_waktu_kunjungan').val(jadwalKunjungan);
        // $('#tglwaktukunjungan').val(jadwalKunjungan);
        document.getElementById('tglwaktukunjungan').value = jadwalKunjungan;
    });

    function getDetailPemeriksaanKlinik(id, arr_parameter_id) {
        var token = $("input[name='_token']").val();
        $.ajax({
            type:'POST',
            url: '/registrasi/getDetailPemeriksaanKlinik',
            data: { _token:token, id:id, arr_parameter_id:arr_parameter_id },
            success:function(data){
                $('#detailPemeriksaanKlinik').html(data);
            }
        });
    }

    function pilihPaketPemeriksaan(id) {
        var token = $("input[name='_token']").val();
        var total_biaya = $("#total_biaya").val();
        var biaya = $("#biayaPaket"+id).val();
        var aktif_paket = $("#aktifPaket"+id).val(id);
        var status = 'pilih';
        var jenis_lab_id = $("#jenis_lab_id").val();

        $("#paketTerpilih"+id).text("Terpilih");
        $("#paketTerpilih"+id).show();
        $("#pilihPaket"+id).hide();
        $("#batalPaket"+id).show();

        $.ajax({
            type:'POST',
            url: '/registrasi/hitungBiayaPaketPemeriksaan',
            data: { _token:token, paket_pemeriksaan_id:id, total_biaya:total_biaya, biaya:biaya, jenis_lab_id:jenis_lab_id, biaya:biaya, status:status },
            success:function(data){
                $('#total_biaya').val(data.total_biaya);
            }
        });
    }

    function batalPilihPaketPemeriksaan(id) {
        var token = $("input[name='_token']").val();
        var total_biaya = $("#total_biaya").val();
        var biaya = $("#biayaPaket"+id).val();
        var aktif_paket = $("#aktifPaket"+id).val('');
        var status = 'batal';
        var jenis_lab_id = $("#jenis_lab_id").val();

        $("#paketTerpilih"+id).text('');
        $("#paketTerpilih"+id).hide();
        $("#pilihPaket"+id).show();
        $("#batalPaket"+id).hide();

        $.ajax({
            type:'POST',
            url: '/registrasi/hitungBiayaPaketPemeriksaan',
            data: { _token:token, paket_pemeriksaan_id:id, total_biaya:total_biaya, biaya:biaya, jenis_lab_id:jenis_lab_id, biaya:biaya, status:status },
            success:function(data){
                $('#total_biaya').val(data.total_biaya);
            }
        });
    }

    function pilihParameterPemeriksaan(id) {
        var token = $("input[name='_token']").val();
        var total_biaya = $("#total_biaya").val();
        var biaya = $("#biayaParameter"+id).val();
        var aktif_paket = $("#aktifParameter"+id).val(id);
        var status = 'pilih';
        var jenis_lab_id = $("#jenis_lab_id").val();

        $("#parameterTerpilih"+id).text("Terpilih");
        $("#parameterTerpilih"+id).show();
        $("#pilihParameter"+id).hide();
        $("#batalParameter"+id).show();

        $.ajax({
            type:'POST',
            url: '/registrasi/hitungBiayaParameterPemeriksaan',
            data: { _token:token, id:id, total_biaya:total_biaya, biaya:biaya, jenis_lab_id:jenis_lab_id, biaya:biaya, status:status },
            success:function(data){
                $('#total_biaya').val(data.total_biaya);
                console.log(data);
            }
        });
    }

    function batalPilihParameterPemeriksaan(id) {
        var token = $("input[name='_token']").val();
        var total_biaya = $("#total_biaya").val();
        var biaya = $("#biayaParameter"+id).val();
        var aktif_paket = $("#aktifParameter"+id).val('');
        var status = 'batal';
        var jenis_lab_id = $("#jenis_lab_id").val();

        $("#parameterTerpilih"+id).text('');
        $("#parameterTerpilih"+id).hide();
        $("#pilihParameter"+id).show();
        $("#batalParameter"+id).hide();

        $.ajax({
            type:'POST',
            url: '/registrasi/hitungBiayaParameterPemeriksaan',
            data: { _token:token, id:id, total_biaya:total_biaya, biaya:biaya, jenis_lab_id:jenis_lab_id, biaya:biaya, status:status },
            success:function(data){
                $('#total_biaya').val(data.total_biaya);
                console.log(data);
            }
        });
    }

    function getDetailTotalBiaya() {
        var token = $("input[name='_token']").val();
        var jenis_lab_id = $("#jenis_lab_id").val();

        $.ajax({
            type:'POST',
            url: '/registrasi/getDetailTotalBiaya',
            data: { _token:token, jenis_lab_id:jenis_lab_id },
            success:function(data){
                $('#bodyDetailTotalBiaya').html('');
                $('#bodyDetailTotalBiaya').html(data.html);
                console.log(data);
            }
        });
    }

    $('#simpanKlinik').on('click', function(e) {
        var $form = $(this).closest('form');
        e.preventDefault();

        if ($('#tgl_waktu_kunjungan').val() == '') {
            $('#mdlAlertKunjungan').modal('toggle');
            return false;
        } else if ($('#pasien_id').val() == '') {
            $('#mdlAlertPasien').modal('toggle');
            return false;
        } else if ($('#total_biaya').val() == 0) {
            $('#mdlAlertTotalBiaya').modal('toggle');
            return false;
        } else {
            $form.trigger('submit');
        }
    });

    $('#pasien-form').on('submit', function (e) {
        e.preventDefault();

        // Serialize the form data
        var formData = $(this).serialize();

        $.ajax({
            url: '/spr/pasien',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                Snackbar.show({
                    text: data.message,
                    pos: 'top-center'
                });
                $('#pasien_id').html(data.html);
                $('#mdlTambahPasien').modal('hide');
            },
            error: function (xhr, status, error) {
                // Handle errors if any
                console.error(error);
            }
        });
    });
</script>
@endsection
