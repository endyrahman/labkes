@extends('layouts.app')

@section('content')
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Daftar Hasil Lab</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <table id="dt-registrasi-sampel" class="table dt-table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Registrasi</th>
                    <th>Lokasi</th>
                    <th>Jadwal Datang</th>
                    <th>Status Lab</th>
                    <th>Status Bayar</th>
                    <th class="no-content">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $val)
                <tr>
                    <td></td>
                    <td>{{ $val->no_registrasi }}</td>
                    <td>{{ $val->lokasi_sampel }}</td>
                    <td>{{ date('d-m-Y H:i', strtotime($val->tgl_waktu_kunjungan)) }}</td>
                    <td>
                        <span class="shadow-none badge badge-success">Selesai</span>
                    </td>
                    <td>
                        <span class="shadow-none badge badge-success">{{ $val->nm_status_bayar }}</span>
                    </td>
                    <td>
                        @if ($val->status == 4)
                            <a href="{{ url('/storage/hasil_lab/'.$val->fileLaboratoriumHasilPemeriksaan) }}" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Hasil Lab" target="_blank" onclick="return countCetakDownload({{$val->pemeriksaan_id}});"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script>

    function countCetakDownload(pemeriksaan_id) {
        var token = $("input[name='_token']").val();

        $.ajax({
            type:'POST',
            url: '/pemeriksaan/countCetakDownloadHasil',
            data: { _token:token, pemeriksaan_id:pemeriksaan_id },
            success:function(data){
                $('#checklist').html(data);
            }
        });
    }

</script>
@endsection
