@extends('layouts.app')

@section('content')
<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Promosi</h5>
        </div>
        <a href="/spr/landingpage/promosi/create" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Promosi</a>
    </div>
    <div class="widget-content widget-content-area">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-4">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tgl. Input</th>
                        <th>Status</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @include('backend.landingpage.halamandepan.promosi.list')
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
