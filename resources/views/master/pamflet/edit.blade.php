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
            <h5 class=""> Edit Pamflet</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <div class="info mt-4">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0 mt-4">
                            <form method="POST" action="{{ url('/master/pamflet/'.$data->id) }}" enctype="multipart/form-data">
                                {{csrf_field()}}
                                {{ method_field('PATCH') }}
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="urutan">Urutan Pamflet<span style="color:red;">*</span></label>
                                            <select class="form-control" id="urutan" name="urutan">
                                                @for ($i = 1; $i <= 4; $i++)
                                                    <option value="{{ $i }}" {{ $data->urutan == $i ? 'selected="selected"' : ''}} >{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="custom-file-container" data-upload-id="foto_pamflet">
                                            <label>Upload Pamflet <span style="color:red;">*</span> <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                                            <label class="custom-file-container__custom-file" >
                                                <input type="file" class="custom-file-container__custom-file__custom-file-input" accept="image/*" id="foto_pamflet" name="foto_pamflet" required>
                                                <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                                                <span class="custom-file-container__custom-file__custom-file-control"></span>
                                            </label>
                                            <div class="custom-file-container__image-preview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="foto_sebelum">Foto Sebelumnya <span style="color:red;"></span></label>
                                            <img src="{{ asset('/img/'.$data->nama_file) }}" style="width:100%;">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="lbljumlah">Status <span id="nameStatus"></span></label>
                                            <div class="form-group mb-2">
                                                <label class="switch s-icons s-outline s-outline-success">
                                                    <input type="checkbox" id="statusPamflet" {{ $data->status == 1 ? 'checked="true"' : ''}} >
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <input type="hidden" id="status" name="status" value="{{$data->status}}">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block mt-4 mb-4 mr-2">Simpan Data</button>
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
    $(document).ready(function() {
        getStatusPamflet();
    });

    var upFotoPamflet = new FileUploadWithPreview('foto_pamflet');

    $('#statusPamflet').on('change', function(){
        this.value = this.checked ? 1 : 0;
        var urutan = $('#urutan option:selected').val();
        $('#status').val(this.value);
        if (this.value == 1) {
            $('#nameStatus').text('Aktif').css({"color": "green", "font-weight": "bold"});
        } else {
            if (urutan == 1) {
                alert('Pamflet urutan 1 tidak dapat di non aktifkan');
                $('#statusPamflet').prop('checked', true);
                $('#status').val(1);
                $('#nameStatus').text('Aktif').css({"color": "green", "font-weight": "bold"});
            } else {
                $('#nameStatus').text('Tidak Aktif').css({"color": "red", "font-weight": "bold"});
            }
        }
    }).change();

    function getStatusPamflet() {
        var status = $('#status').val();
        if (status == 1) {
            $('#nameStatus').text('Aktif').css({"color": "green", "font-weight": "bold"});
        } else {
            $('#nameStatus').text('Tidak Aktif').css({"color": "red", "font-weight": "bold"});
        }
    }
</script>
@endsection
