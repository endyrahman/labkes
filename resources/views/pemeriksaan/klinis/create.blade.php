@extends('layouts.app')

@section('content')
@php
    ini_set('date.timezone', 'Asia/Jakarta');
@endphp
<div class="widget-heading">
    <div class="">
        <h5 class=""> Registrasi Klinik</h5>
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
                                <label for="nama_lengkap">Tempat/Tanggal Lahir</label>
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
                                @else
                                    Perempuan
                                @endif
                            </div>
                        </div>
                        <hr>
                        <form method="POST" action="/pemeriksaan/klinis/storeklinis">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="jenis_sampel">Jenis Sampel</label>
                                        <select class="form-control basic" id="jenis_sampel" name="jenis_sampel">
                                            <option selected="selected">Pilih</option>
                                            <option value="1">Darah</option>
                                            <option value="2">Urin</option>
                                            <option value="3">Feses</option>
                                            <option value="4">Dahak</option>
                                            <option value="5">Slide</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="waktu_kirim">Tanggal & Waktu Pengiriman Sampel</label>
                                        <input id="waktu_kirim" value="{{ date('d-m-Y H:i') }}" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date..">
                                    </div>
                                </div>
                            </div>
                           <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="jmlh_sampel">Jumlah Sampel</label>
                                        <input type="text" class="form-control" id="jmlh_sampel" name="jmlh_sampel">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary btn-block mb-4 mr-2">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('backend/plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('backend/plugins/flatpickr/custom-flatpickr.js') }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    var f2 = flatpickr(document.getElementById('waktu_kirim'), {
        enableTime: true,
        dateFormat: "d-m-Y H:i"
    });
</script>
@endsection
