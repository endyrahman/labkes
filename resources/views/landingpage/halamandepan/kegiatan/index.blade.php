<section class="section pt-5 pt-sm-0 pt-md-4" id="sectionKegiatan">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h1 class="mb-4">Kegiatan</h1>
                </div>
            </div>
        </div>

        <div class="row" id="hdkegiatan">
            @include('landingpage.halamandepan.kegiatan.list')
        </div>
        <input type="hidden" name="hidden_page_kegiatan" id="hidden_page_kegiatan" value="1" />
    </div>
</section>
<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript">
    $(document).on('click', '.pagination a', function(event){
        var urlnow = $(this).attr('href').split('/');
        event.preventDefault();
        var cekurl = $(this).attr('href');
        if (cekurl.includes("pagehdkegiatan")) {
            var page = $(this).attr('href').split('pagehdkegiatan=')[1];
            $('#hidden_page_kegiatan').val(page);
            $('li').removeClass('active');
                $(this).parent().addClass('active');
            paginationHdKegiatan(page);
        }
    });

    function paginationHdKegiatan(page) {
        $.ajax({
            url:"/paginationhdkegiatan?pagehdkegiatan="+page,
            success:function(datas)
            {
                $('#hdkegiatan').html('');
                $('#hdkegiatan').html(datas);
                $('html, body').animate({ scrollTop: $('#sectionKegiatan').offset().top }, 'slow');
            }
        });
    }
</script>
