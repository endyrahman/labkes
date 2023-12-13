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
            <h5 class=""> Registrasi Online Lab Mikrobiologi</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <div class="info mt-4">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0 mt-4">
                            <form method="POST" action="/pembayaran/storepembayaran" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input type="hidden" name="pemeriksaan_id" id="pemeriksaan_id" value="{{ $data->pemeriksaan_id }}" />
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_pendaftaran">No. Registrasi <span style="color:red;">*</span></label>
                                            <input id="no_registrasi" name="no_registrasi" class="form-control" type="text" placeholder="Nomor Registrasi" value="{{ $data->no_registrasi }}" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_pendaftaran">No. Rekening <span style="color:red;">*</span></label>
                                            <input id="no_rekening" name="no_rekening" class="form-control" type="text" placeholder="Nomor Rekening" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_pendaftaran">Nama Pemilik Rekening <span style="color:red;">*</span></label>
                                            <input id="nama_rekening" name="nama_rekening" class="form-control" type="text" placeholder="Nama Pemilik Rekening" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_pendaftaran">Asal Bank Pemilik Rekening <span style="color:red;">*</span></label>
                                            <input id="asal_bank" name="asal_bank" class="form-control" type="text" placeholder="Asal Bank Pemilik Rekening" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_pendaftaran">Tanggal Transfer <span style="color:red;">*</span></label>
                                            <input id="tgl_transfer" name="tgl_transfer" class="form-control" type="text" placeholder="Tanggal Transfer" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_pendaftaran">Nominal Transfer <span style="color:red;">*</span></label>
                                            <input id="nominal_transfer" name="nominal_transfer" class="form-control" type="text" placeholder="Nominal Transfer" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="custom-file-container" data-upload-id="buktiBayar">
                                            <label>Upload Bukti Pembayaran <span style="color:red;">*</span> <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                                            <label class="custom-file-container__custom-file" >
                                                <input type="file" class="custom-file-container__custom-file__custom-file-input" accept="image/*" id="bukti_bayar" name="bukti_bayar" required>
                                                <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                                                <span class="custom-file-container__custom-file__custom-file-control"></span>
                                            </label>
                                            <div class="custom-file-container__image-preview"></div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block mb-4 mr-2">Simpan Data</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('backend/plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('backend/plugins/flatpickr/custom-flatpickr.js') }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('backend/plugins/file-upload/file-upload-with-preview.min.js') }}"></script>
<script>
    var f2 = flatpickr(document.getElementById('tgl_transfer'), {
        dateFormat: "d-m-Y"
    });
    var upBuktiBayar = new FileUploadWithPreview('buktiBayar');
</script>
@endsection
