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
            <h5 class=""> Registrasi Online Lab Kimia</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <div class="info">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0">
                            <form method="POST" action="{{ url('/registrasi/kimia/storekimia') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input id="jenis_lab_id" name="jenis_lab_id" class="form-control" value="2" type="hidden">
                                <input id="tgl_pendaftaran" name="tgl_pendaftaran" value="{{ date('d-m-Y') }}" class="form-control" type="hidden" readonly required>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="tgl_waktu_kunjungan">Tanggal & Waktu Kunjungan</label>
                                            <input id="tgl_waktu_kunjungan" name="tgl_waktu_kunjungan" class="form-control" type="button" readonly required>
                                            <input id="tglwaktukunjungan" name="tglwaktukunjungan" class="form-control" type="hidden" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="jenis_sampel_id">Sampel</label>
                                            <select class="form-control basic" id="jenis_sampel_id" name="jenis_sampel_id" required>
                                                <option value="">Pilih Sampel</option>
                                                @foreach($jenisSampel as $val)
                                                    <option value="{{ $val->id }}">{{ $val->nama_sampel }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="kemasan_sampel_id">Kemasan Sampel</label>
                                            <select class="form-control basic" id="kemasan_sampel_id" name="kemasan_sampel_id" required>
                                                <option value="">Pilih Kemasan</option>
                                                <option value="1">Jerigen</option>
                                                <option value="2">Botol Steril</option>
                                                <option value="3">Media Usap Steril</option>
                                                <option value="4">Plastik Steril / Kemasan Lainnya</option>
                                                <option value="5"> - </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="volume">Volume Sampel</label>
                                            <input id="volume" name="volume" class="form-control" type="text" placeholder="Volume" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="jmlh_sampel">Jumlah Sampel</label>
                                            <input type="number" class="form-control" id="jmlh_sampel" name="jmlh_sampel" value="1" onchange="hitungjmlhsampel()" onkeyup="hitungjmlhsampel()" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="jmlh_sampel">Lokasi Sampling</label>
                                            <textarea class="form-control" id="lokasi_sampel" name="lokasi_sampel" rows="2" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="total_biaya">Total Biaya Pemeriksaan</label>
                                        <div class="input-group mb-4">
                                            <input type="text" name="total_biaya" id="total_biaya" class="form-control" value="0" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#mdlDetailTotalBiaya" onclick="getDetailTotalBiaya()">Detail Biaya</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 mt-4">
                                    <h4>Paket Pemeriksaan</h4>
                                    <div class="row mt-4" id="listpaketpemeriksaan">
                                        @include('registrasi.kimia.paketpemeriksaan')
                                    </div>
                                    <input type="hidden" name="hidden_page_paketpemeriksaan" id="hidden_page_paketpemeriksaan" value="1"/>
                                </div>

                                <div class="col-sm-12">
                                    <h4>Parameter Pemeriksaan</h4>
                                    <div class="row mt-4" id="listparameterpemeriksaan">
                                        @include('registrasi.kimia.parameterpemeriksaan')
                                    </div>
                                    <input type="hidden" name="hidden_page_parameter" id="hidden_page_parameter" value="1"/>
                                </div>

                                <div id="checklist" class="pb-3">
                                </div>
                                <button type="submit" class="btn btn-primary btn-block mb-4 mr-2" id="simpanKimia">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlJadwalKunjungan" tabindex="-1" role="dialog" aria-labelledby="mdlJadwalKunjunganTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
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
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="tgl_kunjungan">Tanggal Kunjungan</label>
                                <input id="tgl_kunjungan" name="tgl_kunjungan" value="{{ date('d-m-Y') }}" class="form-control flatpickr flatpickr-input active" type="text" onchange="waktuKunjungan()" readonly required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="kuota">Kuota</label>
                                <input id="kuota" name="kuota" type="text" class="form-control" readonly>
                            </div>
                        </div>
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

<div class="modal fade" id="mdlAlertInputan" tabindex="-1" role="dialog" aria-labelledby="mdlAlertInputanTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlAlertInputanTitle">Pemberitahuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <h5 style="color: red;" id="contentPeringatan"></h5>
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

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('backend/plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('backend/plugins/flatpickr/custom-flatpickr.js') }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script>
    var f2 = flatpickr(document.getElementById('tgl_kunjungan'), {
        dateFormat: "d-m-Y",
        minDate: "today",
        maxDate: new Date().fp_incr(30),
        disable: [
            function(date) {
                return (date.getDay() === 0);
            }
        ],
        locale: {
            "firstDayOfWeek": 1
        }
    });

    $('#jenis_pemeriksaan_id').change(function () {
        var token = $("input[name='_token']").val();
        var jenis_pemeriksaan_id = $(this).val();
        var jenis_lab_id = $('#jenis_lab_id').val();

        $.ajax({
            type:'POST',
            url: "{{ url('/registrasi/getDataJenisPemeriksaan') }}",
            data: { _token:token, jenis_pemeriksaan_id:jenis_pemeriksaan_id, jenis_lab_id:jenis_lab_id },
            success:function(data){
                $('#checklist').html(data.html);
                $('#total_biaya').val(data.biaya);
            }
        });
    });

    $('#tgl_waktu_kunjungan').on('click', function(e) {
        var token = $("input[name='_token']").val();
        var jenis_pemeriksaan_id = $(this).val();
        $('#mdlJadwalKunjungan').modal('toggle');
        waktuKunjungan();
    });

    function waktuKunjungan() {
        var waktu = $("#waktu_kunjungan").val();
        var tgl = $("#tgl_kunjungan").val();
        var token = $("input[name='_token']").val();

        $.ajax({
            type:'POST',
            url: "{{ url('/registrasi/getJadwalPemeriksaan') }}",
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
        $('#tglwaktukunjungan').val(jadwalKunjungan);
    });

    $('#kemasan_sampel_id').on('change', function(e) {
        if (this.value == 1) {
            $('#volume').val('@2 l');
        } else if (this.value == 2) {
            $('#volume').val('@250 ml');
        } else {
            $('#volume').val('');
        }
    });

    $('#simpanKimia').on('click', function(e) {
        var $form = $(this).closest('form');
        e.preventDefault();

        if ($('#tgl_waktu_kunjungan').val() == '') {
            $('#mdlAlertKunjungan').modal('toggle');
            return false;
        } else {
            if ($('#jenis_pemeriksaan_id').val() == '') {
                $('#mdlAlertInputan').modal('toggle');
                $('#contentPeringatan').text('Pilih pemeriksaan terlebih dahulu');
                return false;
            } else if ($('#kemasan_sampel_id').val() == '') {
                $('#mdlAlertInputan').modal('toggle');
                $('#contentPeringatan').text('Lengkapi kolom kemasan sampel');
                return false;
            } else if ($('#volume').val() == '') {
                $('#mdlAlertInputan').modal('toggle');
                $('#contentPeringatan').text('Lengkapi kolom volume');
                return false;
            } else if ($('#jmlh_sampel').val() == '') {
                $('#mdlAlertInputan').modal('toggle');
                $('#contentPeringatan').text('Lengkapi kolom jumlah sampel');
                return false;
            } else if (!$.trim($("#lokasi_sampel").val())) {
                $('#mdlAlertInputan').modal('toggle');
                $('#contentPeringatan').text('Lengkapi kolom lokasi sampel');
                return false;
            } else {
                $form.trigger('submit');
            }
        }
    });

    $('#jenis_pemeriksaan_id').on('change', function(e){
        var jenis_pemeriksaan_id = this.value;
        var jenis_lab_id = $('#jenis_lab_id').val();
        var token = $("input[name='_token']").val();

        $.ajax({
            type:'POST',
            url: "{{ url('/registrasi/getDataKemasan') }}",
            data: { _token:token, jenis_pemeriksaan_id:jenis_pemeriksaan_id, jenis_lab_id:jenis_lab_id },
            success:function(data){
                $('#kemasan_sampel_id').html(data.option);
                $('#volume').val(data.volume);
            }
        });
    });

    function hitungpemeriksaankimiamikro(harga,ini) {
        var operand = '';
        if ($(ini).is(':checked')) {
            var operand = 'tambah';
        } else {
            var operand = 'kurang';
        }
        var token = $("input[name='_token']").val();
        var total_biaya = $('#total_biaya').val();
        var jmlh_sampel = $('#jmlh_sampel').val();

        $.ajax({
            type:'POST',
            url: "{{ url('/registrasi/hitungPemeriksaanKimiaMikro') }}",
            data: { _token:token, total_biaya:total_biaya, operand:operand, jmlh_sampel:jmlh_sampel, harga:harga },
            success:function(data){
                $('#total_biaya').val(data.total_biaya);
            }
        });
    }

    function hitungjmlhsampel() {
        var token = $("input[name='_token']").val();
        var total_biaya = $('#total_biaya').val();
        var jmlh_sampel = $('#jmlh_sampel').val();
        var jenis_lab_id = $('#jenis_lab_id').val();

        $.ajax({
            type:'POST',
            url: "{{ url('/registrasi/hitungJumlahSampelKimiaMikro') }}",
            data: { _token:token, total_biaya:total_biaya, jmlh_sampel:jmlh_sampel, jenis_lab_id:jenis_lab_id },
            success:function(data){
                $('#total_biaya').val(data.total_biaya);
                $('#jmlh_sampel').val(data.jmlh_sampel);
            }
        });
    }

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
            url: '{{ url("/registrasi/kimia/paginationpaketpemeriksaan") }}',
            type: 'GET',
            data: {
                pagepaketpemeriksaan: page
            },
            success:function(datas)
            {
                $('#listpaketpemeriksaan').html('');
                $('#listpaketpemeriksaan').html(datas);
            }
        });
    }

    function paginationParameterPemeriksaan(page) {
        $.ajax({
            url: '{{ url("/registrasi/kimia/paginationparameterpemeriksaan") }}',
            type: 'GET',
            data: {
                pageparameterpemeriksaan: page
            },
            success:function(datas)
            {
                $('#listparameterpemeriksaan').html('');
                $('#listparameterpemeriksaan').html(datas);
            }
        });
    }

    function getDetailTotalBiaya() {
        var token = $("input[name='_token']").val();
        var jenis_lab_id = $("#jenis_lab_id").val();
        var jmlh_sampel = $("#jmlh_sampel").val();

        $.ajax({
            type:'POST',
            url: "{{ url('/registrasi/getDetailTotalBiayaKimiaMikro') }}",
            data: { _token:token, jenis_lab_id:jenis_lab_id, jmlh_sampel:jmlh_sampel },
            success:function(data){
                $('#bodyDetailTotalBiaya').html('');
                $('#bodyDetailTotalBiaya').html(data.html);
                console.log(data);
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
        var jmlh_sampel = $("#jmlh_sampel").val();

        $("#paketTerpilih"+id).text("Terpilih");
        $("#paketTerpilih"+id).show();
        $("#pilihPaket"+id).hide();
        $("#batalPaket"+id).show();

        $.ajax({
            type:'POST',
            url: "{{ url('/registrasi/hitungBiayaPaketPemeriksaanKimia') }}",
            data: { _token:token, paket_pemeriksaan_id:id, total_biaya:total_biaya, biaya:biaya, jenis_lab_id:jenis_lab_id, biaya:biaya, jmlh_sampel:jmlh_sampel, status:status },
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
        var jmlh_sampel = $("#jmlh_sampel").val();

        $("#paketTerpilih"+id).text('');
        $("#paketTerpilih"+id).hide();
        $("#pilihPaket"+id).show();
        $("#batalPaket"+id).hide();

        $.ajax({
            type:'POST',
            url: "{{ url('/registrasi/hitungBiayaPaketPemeriksaanKimia') }}",
            data: { _token:token, paket_pemeriksaan_id:id, total_biaya:total_biaya, biaya:biaya, jenis_lab_id:jenis_lab_id, biaya:biaya, jmlh_sampel:jmlh_sampel, status:status },
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
        var jmlh_sampel = $("#jmlh_sampel").val();

        $("#parameterTerpilih"+id).text("Terpilih");
        $("#parameterTerpilih"+id).show();
        $("#pilihParameter"+id).hide();
        $("#batalParameter"+id).show();

        $.ajax({
            type:'POST',
            url: "{{ url('/registrasi/hitungBiayaParameterPemeriksaanKimia') }}",
            data: { _token:token, id:id, total_biaya:total_biaya, biaya:biaya, jenis_lab_id:jenis_lab_id, biaya:biaya, jmlh_sampel:jmlh_sampel, status:status },
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
        var jmlh_sampel = $("#jmlh_sampel").val();

        $("#parameterTerpilih"+id).text('');
        $("#parameterTerpilih"+id).hide();
        $("#pilihParameter"+id).show();
        $("#batalParameter"+id).hide();

        $.ajax({
            type:'POST',
            url: "{{ url('/registrasi/hitungBiayaParameterPemeriksaanKimia') }}",
            data: { _token:token, id:id, total_biaya:total_biaya, biaya:biaya, jenis_lab_id:jenis_lab_id, biaya:biaya, jmlh_sampel:jmlh_sampel, status:status },
            success:function(data){
                $('#total_biaya').val(data.total_biaya);
                console.log(data);
            }
        });
    }

</script>
@endsection
