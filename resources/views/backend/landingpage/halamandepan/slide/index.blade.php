@extends('layouts.app')

@section('content')

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Slide</h5>
        </div>
        <a href="{{ url('/spr/landingpage/slide/create') }}" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Foto</a>
    </div>
    <div class="widget-content widget-content-area">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-4">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Foto</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @include('backend.landingpage.halamandepan.slide.list')
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript">
    $(document).on('click', '.pagination a', function(event){
        var urlnow = $(this).attr('href').split('/');
        event.preventDefault();
        var cekurl = $(this).attr('href');
        if (cekurl.includes("pageslide")) {
            var page = $(this).attr('href').split('pageslide=')[1];
            $('#hidden_page_slide').val(page);
            $('li').removeClass('active');
                $(this).parent().addClass('active');
            paginationSlide(page);
        }
    });

    function paginationSlide(page) {
        $.ajax({
            url: "{{ url('/paginationslide') }}",
            type: 'GET',
            data: {
                pageslide: page
            },
            success:function(datas)
            {
                $('#slide').html('');
                $('#slide').html(datas);
            }
        });
    }
</script>
