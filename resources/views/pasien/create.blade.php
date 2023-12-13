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
                            <form method="POST" action="/pasienbaru" id="pasien-form">
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
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('backend/plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('backend/plugins/flatpickr/custom-flatpickr.js') }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script>
    var tgl_lahir = flatpickr(document.getElementById('tgl_lahir'), {
        dateFormat: "d-m-Y"
    });
</script>
@endsection
