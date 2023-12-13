@extends('layouts.app')

@section('content')
<div class="widget-heading">
    <div class="">
        <h5 class=""> Daftar Registrasi Online Labkes Kimia</h5>
    </div>
    <a href="/pemeriksaan/kimia/create" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Registrasi</a>
</div>
<div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
    <table id="dt-pemeriksaan-sampel" class="table dt-table-hover" style="width:100%">
        <thead>
            <tr>
                <th>No. Registrasi</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Sampel</th>
                <th>Jumlah</th>
                <th>Jadwal Datang</th>
                <th>Status</th>
                <th class="no-content">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $val)
            <tr>
                <td>{{ $val->no_registrasi }}</td>
                <td>{{ $val->nama_lengkap }}</td>
                <td>{{ $val->alamat_lengkap }}</td>
                <td>{{ $val->nama_sampel }}</td>
                <td>{{ $val->jmlh_sampel }}</td>
                <td>{{ date('d-m-Y H:i:s', strtotime($val->tgl_waktu_kunjungan)) }}</td>
                <td><span class="shadow-none badge badge-primary">Proses Lab</span></td>
                <td><a href="{{ url('/pencetakan/cetakbuktiregistrasi/'.$val->pemeriksaan_id) }}" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Pendaftaran" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
