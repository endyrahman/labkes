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

#geocoder-container > div {
    min-width: 50%;
    margin-left: 25%;
}

.coordinates {
    background: rgba(0, 0, 0, 0.5);
    color: #fff;
    position: absolute;
    bottom: 40px;
    left: 10px;
    padding: 5px 10px;
    margin: 0;
    font-size: 11px;
    line-height: 18px;
    border-radius: 3px;
    display: none;
}
#reset {
    position: absolute;
    bottom: 20px;
    left: 20px;
    z-index: 999;
}

#location {
    position: absolute;
    top: 55px;
    right: 23px;
    z-index: 999;
}

.mapboxgl-popup {
max-width: 200px;
}
</style>
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Home Care Labkes Kota Semarang</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <div class="info mt-4">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0 mt-4">
                        <form method="POST" action="/homecare/pengguna/updateHomeCare">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input id="homecare_id" name="homecare_id" class="form-control" value="{{ $data->homecare_id }}" type="hidden">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="jenis_homecare_id">Pilih Pelayanan Home Care</label>
                                            <select class="form-control basic" id="jenis_homecare_id" name="jenis_homecare_id" required>
                                                <option value="" selected="selected">Pilih</option>
                                                <option value="1" {{ $data->jenis_homecare_id == 1 ? 'selected="selected"' : ''}}>Lab Klinik</option>
                                                <option value="2" {{ $data->jenis_homecare_id == 2 ? 'selected="selected"' : ''}}>Lab Kimia</option>
                                                <option value="3" {{ $data->jenis_homecare_id == 3 ? 'selected="selected"' : ''}}>Lab Mikrobiologi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_waktu_kunjungan">Pilih Tgl & Jam Kunjungan Lokasi</label>
                                            <input id="tgl_waktu_kunjungan" name="tgl_waktu_kunjungan" class="form-control" type="text" style="cursor: pointer;" value="{{ $data->tgl_waktu_kunjungan }}" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="nama_lengkap">Nama Lengkap</label>
                                            <input id="nama_lengkap" name="nama_lengkap" class="form-control" type="text" value="{{ $data->nama_lengkap }}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="no_hp">No. HP</label>
                                            <input id="no_hp" name="no_hp" class="form-control" type="number" value="{{ $data->no_hp }}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label for="alamat_lengkap">Alamat Lengkap</label>
                                            <textarea class="form-control" id="alamat_lengkap" name="alamat_lengkap" rows=3 required>{{ $data->alamat_lengkap }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="catatan_homecare">Catatan Homecare</label>
                                            <textarea class="form-control basic" id="catatan_homecare" name="catatan_homecare" rows='3' maxlength="1000" required>{{ $data->catatan_homecare }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" id="simpanHomeCare" class="btn btn-primary btn-block mb-4 mt-4">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="tgl_kunjungan">Tanggal Kunjungan</label>
                                <input id="tgl_kunjungan" name="tgl_kunjungan" value="{{ date('d-m-Y') }}" class="form-control flatpickr flatpickr-input active" type="text" onchange="waktuKunjungan()" readonly required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sisa_kuota">Sisa Kuota</label>
                                <input id="sisa_kuota" name="sisa_kuota" type="text" class="form-control" readonly>
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

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('backend/plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('backend/plugins/flatpickr/custom-flatpickr.js') }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script src="https://d3js.org/d3-queue.v3.min.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.2/mapbox-gl-geocoder.min.js"></script>
<link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.2/mapbox-gl-geocoder.css" type="text/css">

<script>
    var f2 = flatpickr(document.getElementById('tgl_kunjungan'), {
        dateFormat: "d-m-Y",
        minDate: "today",
        maxDate: new Date().fp_incr(7),
        disable: [
            function(date) {
                return (date.getDay() === 0 || date.getDay() === 6);
            }
        ],
        locale: {
            "firstDayOfWeek": 1
        }
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
            url: '/homecare/getJadwalPemeriksaanHomecare',
            data: { _token:token, waktu:waktu, tgl:tgl },
            success:function(data){
                $('#jadwal').html(data.html);
                $('#sisa_kuota').val(data.sisaKuota);
            }
        });
    }

    $('#simpanHomeCare').on('click', function(e) {
        var $form = $(this).closest('form');
        e.preventDefault();

        if ($('#tgl_waktu_kunjungan').val() == '') {
            $('#mdlAlertKunjungan').modal('toggle');
            return false;
        } else {
            if ($('#jenis_homecare_id').val() == '') {
                $('#mdlAlertInputan').modal('toggle');
                $('#contentPeringatan').text('Pilih pelayanan Home Care');
                return false;
            } else if ($('#nama_lengkap').val() == '') {
                $('#mdlAlertInputan').modal('toggle');
                $('#contentPeringatan').text('Lengkapi kolom nama lengkap');
                return false;
            } else if ($('#no_hp').val() == '') {
                $('#mdlAlertInputan').modal('toggle');
                $('#contentPeringatan').text('Lengkapi kolom nomor hp');
                return false;
            } else if ($('#alamat_lengkap').val() == '') {
                $('#mdlAlertInputan').modal('toggle');
                $('#contentPeringatan').text('Lengkapi kolom alamat lengkap');
                return false;
            } else if ($('#catatan_homecare').val() == '') {
                $('#mdlAlertInputan').modal('toggle');
                $('#contentPeringatan').text('Lengkapi kolom catatan');
                return false;
            } else {
                $form.trigger('submit');
            }
        }
    });
</script>
@endsection
