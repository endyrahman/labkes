@extends('layouts.app')

@section('content')
<div class="col-lg-4 layout-spacing">
    <div class="widget widget-four">
        <div class="widget-heading">
            <h5 class="">Laboratorium Klinik</h5>
        </div>
        <div class="widget-content">
            <div class="order-summary">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="summary-list summary-profit">
                            <div class="summery-info">
                                <div class="w-summary-details">
                                    <div class="w-summary-info text-center">
                                        <h6>Registrasi 
                                            <span class="summary-count" style="font-size:22px;">{{ $pemeriksaan->klinik }}</span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ url('/registrasi/verifikasi') }}" class="btn btn-info mt-4">Lihat Registrasi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-4 layout-spacing">
    <div class="widget widget-four">
        <div class="widget-heading">
            <h5 class="">Laboratorium Kimia</h5>
        </div>
        <div class="widget-content">
            <div class="order-summary">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="summary-list summary-profit">
                            <div class="summery-info">
                                <div class="w-summary-details">
                                    <div class="w-summary-info text-center">
                                        <h6>Registrasi 
                                            <span class="summary-count" style="font-size:22px;">{{ $pemeriksaan->kimia }}</span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ url('/registrasi/verifikasi') }}" class="btn btn-info mt-4">Lihat Registrasi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-4 layout-spacing">
    <div class="widget widget-four">
        <div class="widget-heading">
            <h5 class="">Laboratorium Mikrobiologi</h5>
        </div>
        <div class="widget-content">
            <div class="order-summary">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="summary-list summary-profit">
                            <div class="summery-info">
                                <div class="w-summary-details">
                                    <div class="w-summary-info text-center">
                                        <h6>Registrasi 
                                            <span class="summary-count" style="font-size:22px;">{{ $pemeriksaan->mikrobiologi }}</span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ url('/registrasi/verifikasi') }}" class="btn btn-info mt-4">Lihat Registrasi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-4 layout-spacing">
    <div class="widget widget-four">
        <div class="widget-heading">
            <h5 class="">Homecare</h5>
        </div>
        <div class="widget-content">
            <div class="order-summary">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="summary-list summary-expenses">
                            <div class="summery-info">
                                <div class="w-summary-details">
                                    <div class="w-summary-info text-center">
                                        <h6>Registrasi 
                                            <span class="summary-count" style="font-size:22px;">{{ $pemeriksaan->mikrobiologi }}</span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ url('/homecare/verifikasi') }}" class="btn btn-warning mt-4">Lihat Registrasi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
