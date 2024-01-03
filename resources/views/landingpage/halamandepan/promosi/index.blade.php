<section class="section pt-5 pt-sm-0 pt-md-4" id="sectionPromosi">
    <div class="container">
        <div class="row" id="lppromosi">
            @include('landingpage.halamandepan.promosi.list')
        </div>
        <input type="hidden" name="hidden_page_promosi" id="hidden_page_promosi" value="1" />
    </div>
</section>
<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript">
    $(document).on('click', '.pagination a', function(event){
        var urlnow = $(this).attr('href').split('/');
        event.preventDefault();
        var cekurl = $(this).attr('href');
        if (cekurl.includes("pagelppromosi")) {
            var page = $(this).attr('href').split('pagelppromosi=')[1];
            $('#hidden_page_promosi').val(page);
            $('li').removeClass('active');
                $(this).parent().addClass('active');
            paginationLpPromosi(page);
        }
    });

    function paginationLpPromosi(page) {
        $.ajax({
            url: "{{ url('/paginationlppromosi') }}",
            type: 'GET',
            data: {
                pagelppromosi: page
            },
            success:function(datas)
            {
                $('#lppromosi').html('');
                $('#lppromosi').html(datas);
                $('html, body').animate({ scrollTop: $('#sectionPromosi').offset().top }, 'slow');
            }
        });
    }
</script>
