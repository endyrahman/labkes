@extends('layouts.applanding')
@section('content')
<section class="bg-half-50 d-table w-100" id="home">
</section>
<section class="section">
    <div class="container">
        <div class="row align-items-center">
            @php
                $pglayananmikrobiologi = App\Models\Landingpage\Layanankami\LayanankamiTbl::getDataLayananKami(3);
            @endphp
            <div class="col-lg-5 col-md-6 col-12">
                <img src="/storage/foto_layanan/{{$pglayananmikrobiologi->foto_layanan}}" class="img-fluid rounded" alt="">
            </div>
            <div class="col-lg-7 col-md-6 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="section-title ms-lg-4">
                    <h4 class="title mb-4">{{ $pglayananmikrobiologi->nama_layanan }}</h4>
                    <p class="text-muted">{{ $pglayananmikrobiologi->keterangan }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mt-sm-0 pt-sm-0">
                <div class="row align-items-center">
                    <div class="col-lg-7 col-md-7">
                        <div class="section-title">
                            <h5 class="mb-0">Paket Pemeriksaan</h5>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-7">
                        <div class="input-group mb-3 border rounded">
                            <input type="text" id="caripaketpemeriksaan" name="caripaketpemeriksaan" class="form-control border-0" placeholder="Cari Paket Pemeriksaan">
                            <button type="button" class="input-group-text bg-white border-0 bg-transparent" id="searchsubmit"><i class="uil uil-search"></i></button>
                        </div>
                    </div>
                </div>

                <div class="row" id="listpaketpemeriksaan">
                    @php
                        $paketpemeriksaan = App\Models\PemeriksaanTbl\PemeriksaanTbl::getPaketPemeriksaanLp();
                    @endphp
                    @foreach ($paketpemeriksaan as $val)
                    <div class="col-lg-3 mt-4">
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#panelpemeriksaan" type="button" onclick="kirimPaketPemeriksaanId({{ $val->paket_pemeriksaan_id }});" class="d-flex btn btn-soft-primary text-dark features feature-warning key-feature align-items-center p-3 rounded shadow" style="min-height: 100px;">
                            <div class="icon text-center rounded-circle me-3">
                                <i data-feather="camera" class="fea icon-ex-md"></i>
                            </div>
                            <div class="flex-1">
                                <h6 style="font-size: 12px;font-weight: 700;">{{ $val->nama_pemeriksaan }}</h6>
                            </div>
                        </a>
                    </div>
                    @endforeach

                    <div class="col-12 mt-3 text-center">
                        {{ $paketpemeriksaan->links() }}
                    </div>
                </div>
                <input type="hidden" name="hidden_page_paketpemeriksaan" id="hidden_page_paketpemeriksaan" value="1"/>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-8 col-12 pt-2 mt-sm-0 pt-sm-0">
                <div class="row align-items-center">
                    <div class="col-lg-7 col-md-7">
                        <div class="section-title">
                            <h5 class="mb-0">Pemeriksaan</h5>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-7">
                        <div class="input-group mb-3 border rounded">
                            <input type="text" id="caripemeriksaan" name="caripemeriksaan" class="form-control border-0" placeholder="Cari Pemeriksaan">
                            <button type="button" class="input-group-text bg-white border-0 bg-transparent" id="searchsubmit"><i class="uil uil-search"></i></button>
                        </div>
                    </div>
                </div>

                <div class="row" id="listpemeriksaan">
                </div>
                <input type="hidden" name="hidden_page_pemeriksaan" id="hidden_page_pemeriksaan" value="1"/>
            </div>
        </div>
    </div>
</section>

@endsection
