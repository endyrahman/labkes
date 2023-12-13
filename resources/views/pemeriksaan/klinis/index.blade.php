@extends('layouts.app')

@section('content')
<div class="widget-heading">
    <div class="">
        <h5 class=""> Pemeriksaan Sampel</h5>
    </div>
    <a href="/pemeriksaan/klinis/create" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Registrasi</a>
</div>
<div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
    <table id="dt-pemeriksaan-sampel" class="table dt-table-hover" style="width:100%">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Sampel</th>
                <th>Jumlah</th>
                <th>Tgl. Pengiriman</th>
                <th>Status</th>
                <th class="no-content">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Citra Bunga</td>
                <td>Jl. Merdeka Utara</td>
                <td>Darah</td>
                <td>2</td>
                <td>02-02-2022 10.00</td>
                <td><span class="shadow-none badge badge-primary">Proses Lab</span></td>
                <td><a href="javascript:void(0);" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Pendaftaran"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a></td>
            </tr>
            <tr>
                <td>Citra Bunga</td>
                <td>Jl. Merdeka Utara</td>
                <td>Urine</td>
                <td>1</td>
                <td>22-12-2021 09.00</td>
                <td><span class="shadow-none badge badge-success">Selesai / Lunas</span></td>
                <td>
                    <a href="javascript:void(0);" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Pendaftaran"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a>
                    <a href="javascript:void(0);" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Hasil Lab"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="green" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
