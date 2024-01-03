@extends('layouts.app')

@section('content')

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Daftar Hasil Lab</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-4">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Menu</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @include('backend.landingpage.menu.list')
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
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
