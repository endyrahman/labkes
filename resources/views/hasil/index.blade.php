@extends('layouts.app')

@section('content')
<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Daftar Hasil Lab</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <div class="input-group mb-4">
            <div class="input-group-prepend">
                <select class="form-control col-md-6" id="combocari" onchange="paginationGrid(1)">
                    <option value="">Pilih Pencarian</option>
                    <option value="no_registrasi">No. Registrasi</option>
                    <option value="tgl_waktu_kunjungan">Tgl. Kunjungan</option>
                </select>
                <input type="text" class="form-control col-md-8" id="pencarian" onchange="paginationGrid(1)" onkeyup="paginationGrid(1)" placeholder="Masukkan Pencarian">
            </div>
            <button class="btn btn-danger" type="button" id="hapusPencarian" onclick="hapusPencarian(1)" style="display:none;">Hapus</button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-4">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Registrasi</th>
                        <th>Laboratorium</th>
                        <th>Jadwal Datang</th>
                        <th class="no-content">#</th>
                    </tr>
                </thead>
                <tbody id="bodyGridKlinik">
                    @include('hasil.list')
                </tbody>
            </table>
            <input type="hidden" name="hidden_page_hasillab" id="hidden_page_hasillab" value="1"/>
        </div>
    </div>
</div>

<script>
    function countCetakDownload(pemeriksaan_id) {
        var token = $("input[name='_token']").val();

        $.ajax({
            type:'POST',
            url: "{{ url('/registrasi/countCetakDownloadHasil') }}",
            data: { _token:token, pemeriksaan_id:pemeriksaan_id },
            success:function(data){
                $('#counterHasil').text(data.cetak);
                var refreshedDataFromTheServer = getDataFromServer();

                var myTable = $('#dt-hasil').DataTable();
                myTable.clear().rows.add(refreshedDataFromTheServer).draw();
            }
        });
    }

</script>
@endsection
