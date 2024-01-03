@extends('layouts.app')

@section('content')

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Kegiatan</h5>
        </div>
        <a href="{{ url('/spr/landingpage/kegiatan/create') }}" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Kegiatan</a>
    </div>
    <div class="widget-content widget-content-area">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-4">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Kegiatan</th>
                        <th>Keterangan</th>
                        <th>Tgl. Kegiatan</th>
                        <th>Foto</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @include('backend.landingpage.halamandepan.kegiatan.list')
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
