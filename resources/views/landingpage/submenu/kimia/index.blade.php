@extends('layouts.applanding')
@section('content')
<section class="section">
    <div class="container">
        <div class="row align-items-center">
            @php
                $pglayananklinik = App\Models\Landingpage\Layanankami\LayanankamiTbl::getDataLayananKami(2);
            @endphp
            <div class="col-lg-5 col-md-6 col-12">
                <img src="{{ url('/storage/foto_layanan/'.$pglayananklinik->foto_layanan) }}" class="img-fluid rounded" alt="">
            </div>
            <div class="col-lg-7 col-md-6 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="section-title ms-lg-4">
                    <h4 class="title mb-4">{{ $pglayananklinik->nama_layanan }}</h4>
                    <p class="text-muted">{{ $pglayananklinik->keterangan }}</p>
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
                            <h5 class="mb-0">Pemeriksaan</h5>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-7">
                        <div class="input-group mb-3 border rounded">
                            <input type="text" id="caripemeriksaan" name="caripemeriksaan" class="form-control border-0" placeholder="Cari Pemeriksaan" onchange="calldatagrid()" onblur="calldatagrid()" onkeyup="calldatagrid()">
                            <button type="button" class="input-group-text bg-white border-0 bg-transparent" id="searchsubmit" onclick="calldatagrid()"><i class="uil uil-search"></i></button>
                        </div>
                    </div>
                </div>

                <div class="row" id="listparameterklinik">
                    @include('landingpage.submenu.kimia.list')
                </div>
                <input type="hidden" name="hidden_page_pemeriksaan" id="hidden_page_pemeriksaan" value="1"/>
            </div>
        </div>
    </div>
</section>
@endsection

<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript">
    $(document).on('click', '.pagination a', function(event){
        var urlnow = $(this).attr('href').split('/');
        event.preventDefault();
        var cekurl = $(this).attr('href');
        if (cekurl.includes("pagepemeriksaan")) {
            var page = $(this).attr('href').split('pagepemeriksaan=')[1];
            $('#hidden_page_pemeriksaan').val(page);
            var pencarian = $('#caripemeriksaan').val();
            $('li').removeClass('active');
                $(this).parent().addClass('active');
            paginationLpPemeriksaan(page, pencarian);
        }
    });

    function paginationLpPemeriksaan(page, pencarian) {
        $.ajax({
            url: "{{ url('/labkimia/paginationpemeriksaan') }}",
            type: 'GET',
            data: {
                pagepemeriksaan: page,
                pencarian: pencarian,
                laboratorium: 2
            },
            success:function(datas)
            {
                $('#listparameterklinik').html('');
                $('#listparameterklinik').html(datas);
            }
        });
    }

    function calldatagrid() {
        var pencarian = $('#caripemeriksaan').val();
        paginationLpPemeriksaan(1, pencarian);
    }

</script>
