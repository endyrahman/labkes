@extends('layouts.app')

@section('content')

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Halaman Depan</h5>
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
                    @include('backend.landingpage.halamandepan.list')
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
