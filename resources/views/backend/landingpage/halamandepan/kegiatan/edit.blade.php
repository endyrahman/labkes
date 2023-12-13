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
            <h5 class=""> Tambah Kegiatan</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <div class="info mt-2">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0">
                            <form method="POST" action="/spr/landingpage/kegiatan/{{$kegiatan->id}}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="form-group">
                                            <label for="nama_kegiatan">Nama Kegiatan</label>
                                            <input id="nama_kegiatan" name="nama_kegiatan" class="form-control" type="text" value="{{ $kegiatan->nama_kegiatan }}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="lokasi">Lokasi Kegiatan</label>
                                            <input id="lokasi" name="lokasi" class="form-control" type="text" value="{{ $kegiatan->lokasi }}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="tgl_kegiatan">Tanggal Kegiatan</label>
                                            <input id="tgl_kegiatan" name="tgl_kegiatan" value="{{ date('d-m-Y', strtotime($kegiatan->tgl_kegiatan)) }}" class="form-control flatpickr flatpickr-input active" type="text" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="jmlh_sampel">Keterangan Kegiatan</label>
                                            <textarea class="form-control" id="keterangan" name="keterangan" required>{{ $kegiatan->keterangan }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="custom-file-container" data-upload-id="fotoKegiatan">
                                            <label>Upload Foto Kegiatan <span style="color:red;">*</span> <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                                            <label class="custom-file-container__custom-file" >
                                                <input type="file" class="custom-file-container__custom-file__custom-file-input" accept="image/*" id="foto_kegiatan" name="foto_kegiatan">
                                                <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                                                <span class="custom-file-container__custom-file__custom-file-control"></span>
                                            </label>
                                            <div class="custom-file-container__image-preview"></div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block mb-4 mr-2" id="simpanEditKegiatan">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('backend/plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('backend/plugins/flatpickr/custom-flatpickr.js') }}"></script>
<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('backend/plugins/file-upload/file-upload-with-preview.min.js') }}"></script>
<script>
    var f2 = flatpickr(document.getElementById('tgl_kegiatan'), {
        dateFormat: "d-m-Y",
    });
    var upFotoKegiatan = new FileUploadWithPreview('fotoKegiatan');
</script>
@endsection
