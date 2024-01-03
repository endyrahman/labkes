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
<div class="widget-heading">
    <div class="">
        <h5 class=""> Registrasi Online Lab Mikrobiologi</h5>
    </div>
</div>
<div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
    <div class="info mt-4">
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0 mt-4">
                        <h4>Info Data Pelanggan</h4>
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="nama_lengkap">Nama Lengkap</label>
                            </div>
                            <div class="col-sm-6">
                                :&nbsp;&nbsp;&nbsp; {{ Auth::user()->nama_lengkap }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="nama_lengkap">Alamat Lengkap</label>
                            </div>
                            <div class="col-sm-6">
                                :&nbsp;&nbsp;&nbsp; {{ Auth::user()->alamat_lengkap }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="nama_lengkap">Tempat/Tanggal Lahir</label>
                            </div>
                            <div class="col-sm-6">
                                :&nbsp;&nbsp;&nbsp; {{ Auth::user()->tempat_lahir }}, {{ date('d-m-Y', strtotime(Auth::user()->tgl_lahir)) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="nama_lengkap">Usia</label>
                            </div>
                            <div class="col-sm-6">
                                :&nbsp;&nbsp;&nbsp;
                                @php
                                    $date1=date_create(Auth::user()->tgl_lahir);
                                    $date2=date_create(date('Y-m-d'));
                                    $diff=date_diff($date1,$date2);
                                    echo $diff->format("%Y Tahun %m Bulan %d Hari");
                                @endphp
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="nama_lengkap">Jenis Kelamin</label>
                            </div>
                            <div class="col-sm-6">
                                :&nbsp;&nbsp;&nbsp;
                                @if (Auth::user()->jenis_kelamin == 1)
                                    Laki-laki
                                @elseif (Auth::user()->jenis_kelamin == 2)
                                    Perempuan
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <hr>
                        <form method="POST" action="/pemeriksaan/mikrobiologi/storemikrobiologi">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input id="jenis_lab_id" name="jenis_lab_id" class="form-control" value="3" type="hidden">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="jenis_sampel_id">Jenis Sampel</label>
                                        <select class="form-control basic" id="jenis_sampel_id" name="jenis_sampel_id" required>
                                            <option selected="selected">Pilih</option>
                                            <option value="1">Air Minum</option>
                                            <option value="2">Air Higiene Sanitasi</option>
                                            <option value="3">Air Badan Air</option>
                                            <option value="4">Makanan</option>
                                            <option value="5">Minuman</option>
                                            <option value="6">Usap</option>
                                            <option value="7">Air Limbah</option>
                                            <option value="8">Jajanan Anak Sekolah</option>
                                            <option value="9">Darah</option>
                                            <option value="10">Urin</option>
                                            <option value="11">Feses</option>
                                            <option value="12">Dahak</option>
                                            <option value="13">Lingkungan</option>
                                            <option value="14">Udara Luar Ruangan</option>
                                            <option value="15">Udara Dalam Ruangan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="tgl_pendaftaran">Tanggal Pendaftaran</label>
                                        <input id="tgl_pendaftaran" name="tgl_pendaftaran" value="{{ date('d-m-Y') }}" class="form-control" type="text" readonly required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="tgl_waktu_kunjungan">Pilih Tanggal & Waktu Kunjungan</label>
                                        <input id="tgl_waktu_kunjungan" name="tgl_waktu_kunjungan" class="form-control" type="text" readonly required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="kemasan_sampel_id">Kemasan Sampel</label>
                                        <select class="form-control basic" id="kemasan_sampel_id" name="kemasan_sampel_id" required>
                                            <option selected="selected">Pilih</option>
                                            <option value="1">Jerigen</option>
                                            <option value="2">Botol Steril</option>
                                            <option value="3">Media Usap Steril</option>
                                            <option value="4">Plastik</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="volume">Volume Sampel</label>
                                        <input id="volume" name="volume" class="form-control" type="text" placeholder="Volume" required>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="jmlh_sampel">Jumlah Sampel</label>
                                        <input type="text" class="form-control" id="jmlh_sampel" name="jmlh_sampel" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="jmlh_sampel">Lokasi Sampling</label>
                                        <textarea class="form-control" id="lokasi_sampel" name="lokasi_sampel" required>
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="jenis_pemeriksaan_id">Pilih Pemeriksaan</label>
                                        <select class="form-control basic" id="jenis_pemeriksaan_id" name="jenis_pemeriksaan_id" required>
                                            <option value="null" selected="selected">Pilih</option>
                                            @foreach($jenis as $val)
                                                <option value="{{ $val->id }}">{{ $val->nama_pemeriksaan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="checklist" class="pb-3">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mb-4 mr-2">Simpan Data</button>
                        </div>
                    </form>
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
                    <div class="form-group">
                        <label for="tgl_kunjungan">Waktu Kunjungan</label>
                        <input id="tgl_kunjungan" name="tgl_kunjungan" value="{{ date('d-m-Y') }}" class="form-control flatpickr flatpickr-input active" type="text" onchange="waktuKunjungan()" readonly required>
                    </div>
                </div>
                <div class="col-sm-12" id="jadwal">

                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                <button type="button" class="btn btn-primary" id="simpanjadwal" data-dismiss="modal">Save</button>
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

    $('#jenis_pemeriksaan_id').change(function () {
        var token = $("input[name='_token']").val();
        var jenis_pemeriksaan_id = $(this).val();
        var jenis_lab_id = $('#jenis_lab_id').val();

        $.ajax({
            type:'POST',
            url: "{{ url('/pemeriksaan/getDataJenisPemeriksaan') }}",
            data: { _token:token, jenis_pemeriksaan_id:jenis_pemeriksaan_id, jenis_lab_id:jenis_lab_id },
            success:function(data){
                $('#checklist').html(data);
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
            url: '/pemeriksaan/getJadwalPemeriksaan',
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

</script>
@endsection
