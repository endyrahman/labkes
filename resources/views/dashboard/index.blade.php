@extends('layouts.app')

@section('content')
<div class="col-lg-12 layout-spacing">
    <div class="widget widget-four">
        <div class="widget-heading">
            <h5 class="">Dashboard</h5>
        </div>
        <div class="widget-content">
            <div class="order-summary">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="summary-list summary-profit">
                            <div class="summery-info">
                                <div class="w-summary-details">
                                    <div class="w-summary-info">
                                        <h5>Laboratorium Klinik</h5>
                                    </div>
                                    <a href="{{ url('/registrasi/klinik/create') }}" class="btn btn-dark mt-4">Registrasi Online</a>
                                    <a href="{{ url('/registrasi/klinik') }}" class="btn btn-info mt-4">Lihat Registrasi</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="summary-list summary-profit">
                            <div class="summery-info">
                                <div class="w-summary-details">
                                    <div class="w-summary-info">
                                        <h5>Laboratorium Kimia</h5>
                                    </div>
                                    <a href="{{ url('/registrasi/kimia/create') }}" class="btn btn-dark mt-4">Registrasi Online</a>
                                    <a href="{{ url('/registrasi/kimia') }}" class="btn btn-info mt-4">Lihat Registrasi</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="summary-list summary-profit">
                            <div class="summery-info">
                                <div class="w-summary-details">
                                    <div class="w-summary-info">
                                        <h5>Laboratorium Mikrobiologi</h5>
                                    </div>
                                    <a href="{{ url('/registrasi/mikrobiologi/create') }}" class="btn btn-dark mt-4">Registrasi Online</a>
                                    <a href="{{ url('/registrasi/mikrobiologi') }}" class="btn btn-info mt-4">Lihat Registrasi</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-4">
                        <div class="summary-list summary-profit">
                            <div class="summery-info">
                                <div class="w-summary-details">
                                    <div class="w-summary-info">
                                        <h5>Homecare</h5>
                                    </div>
                                    <a href="{{ url('/registrasi/homecare/create') }}" class="btn btn-dark mt-4">Registrasi Homecare</a>
                                    <a href="{{ url('/registrasi/homecare') }}" class="btn btn-info mt-4">Lihat Registrasi</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
