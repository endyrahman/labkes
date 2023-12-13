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
            <h5 class=""> Tambah Paket Pemeriksaan</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <div class="info mt-2">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0 mt-4">
                            <form method="POST" action="/spr/master/paketpemeriksaan">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_pendaftaran">Jenis Laboratorium <span style="color:red;">*</span></label>
                                            <select class="form-control" id="lab_pemeriksaan_id" name="lab_pemeriksaan_id" required>
                                                <option value="">Pilih</option>
                                                <option value="1">Lab Klinik</option>
                                                <option value="2">Lab Kimia</option>
                                                <option value="3">Lab Mikrobiologi</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="tgl_pendaftaran">Nama Paket Pemeriksaan <span style="color:red;">*</span></label>
                                            <input id="nama_pemeriksaan" name="nama_pemeriksaan" class="form-control" type="text" placeholder="Nama Pemeriksaan" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-7" id="data_parameter_pemeriksaan">

                                    </div>
                                    <div class="col-sm-5" id="daftar_parameter_pemeriksaan">
                                        <div class="form-group">
                                            <label for="daftar_parameter">Daftar Parameter Terpilih <span style="color:red;">*</span></label>
                                            <textarea class="form-control" rows="4" id="nama_parameter" readonly> </textarea>
                                            <input id="parameter_ids" name="parameter_ids" class="form-control" type="hidden" placeholder="Nama Pemeriksaan" required>
                                        </div>
                                        <label for="lbljumlah">Status Pemeriksaan</label>
                                        <span id="nama_status" style="font-weight:bold;"></span>
                                        <div class="form-group mb-2">
                                            <label class="switch s-icons s-outline s-outline-primary mr-2">
                                                <input type="checkbox" id="statusPemeriksaan" checked>
                                                <span class="slider round"></span>
                                            </label>
                                            <input type="hidden" id="status" name="status">
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
    $('#statusPemeriksaan').on('change', function(){
        this.value = this.checked ? 1 : 0;
        $('#status').val(this.value)
        if (this.value == 1) {
            $('#nama_status').text('Aktif');
            $('#nama_status').css("color", "green");
        } else {
            $('#nama_status').text('Tidak Aktif');
            $('#nama_status').css("color", "red");
        }
    }).change();

    $('#lab_pemeriksaan_id').change(function() {
        console.log($(this).val());
        var lab_pemeriksaan_id = $(this).val();
        var token = $("input[name='_token']").val();

        $.ajax({
            type:'POST',
            url: '/master/jenispemeriksaan/getDataParameterPemeriksaan',
            data: { _token:token, lab_pemeriksaan_id:lab_pemeriksaan_id },
            success:function(data){
                $('#data_parameter_pemeriksaan').html(data.html);

            var dtmstjenisparameterpemeriksaan = $('#dt-mstjenisparameterpemeriksaan').DataTable({
                "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
            "<'table-responsive'tr>" +
            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                "oLanguage": {
                    "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                    "sInfo": "Showing page _PAGE_ of _PAGES_",
                    "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    "sSearchPlaceholder": "Search...",
                   "sLengthMenu": "Results :  _MENU_",
                },
                "stripeClasses": [],
                "lengthMenu": [10, 15, 20, 50],
                "pageLength": 10,
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 2
                } ],
                "order": [[ 1, 'asc' ]],
                "bLengthChange": false,
            });
            }
        });
    });

    function tambahparameterpemeriksaan(id,nama) {
        var parameter_ids = $('#parameter_ids').val();
        var nama_parameter = $('#nama_parameter');

        if (parameter_ids != '') {
            $("#parameter_ids").val(function() {
                return this.value + ","+id;
            });
            nama_parameter.val(nama_parameter.val()+ "," +nama);
        } else {
            $('#parameter_ids').val(id)
            nama_parameter.val(nama)
        }

        $("#tambahparameter"+id).hide();
        $("#hapusparameter"+id).show();
    }

    function hapusparameterpemeriksaan(id) {
        var parameter_ids = $('#parameter_ids').val();
        var nama_parameter = $('#nama_parameter').val();
        var lab_pemeriksaan_id = $('#lab_pemeriksaan_id option:selected').val();
        var token = $("input[name='_token']").val();

        $.ajax({
            type:'POST',
            url: '/master/jenispemeriksaan/hapusDataParameterPemeriksaan',
            data: { _token:token, parameter_ids:parameter_ids, nama_parameter:nama_parameter, id:id, lab_pemeriksaan_id:lab_pemeriksaan_id },
            success:function(data){
                $('#parameter_ids').val(data.ids);
                $('#nama_parameter').val(data.nama_parameter);
            }
        });

        $("#tambahparameter"+id).show();
        $("#hapusparameter"+id).hide();
    }
</script>
@endsection
