@extends('layouts.applanding')
@section('content')
<section class="overflow-hidden" style="padding-top:100px;padding-bottom: 50px;">
    <div class="container">
        <div class="row" id="listkegiatan">
            @include('landingpage.kegiatan.list')
        </div>
        <input type="hidden" name="hidden_page_kegiatan" id="hidden_page_kegiatan" value="1"/>
    </div>
</section>
@endsection

<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript">
    $(document).on('click', '.pagination a', function(event){
        var urlnow = $(this).attr('href').split('/');
        event.preventDefault();
        var cekurl = $(this).attr('href');
        if (cekurl.includes("pagekegiatan")) {
            var page = $(this).attr('href').split('pagekegiatan=')[1];
            $('#hidden_page_kegiatan').val(page);
            $('li').removeClass('active');
                $(this).parent().addClass('active');
            paginationKegiatan(page);
        }
    });

    function paginationKegiatan(page) {
        $.ajax({
            url: "{{ url('/kegiatan/paginationkegiatan') }}",
            type: 'GET',
            data: {
                pagekegiatan: page
            },
            success:function(datas)
            {
                $('#listkegiatan').html('');
                $('#listkegiatan').html(datas);
            }
        });
    }

</script>
