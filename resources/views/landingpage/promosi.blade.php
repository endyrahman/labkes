<section class="section pt-5 pt-sm-0 pt-md-4">
    <div class="container">
        <div class="row" id="lppromosi">
            @foreach ($promosi as $val)
            <div class="col-lg-4 col-md-6 mt-4 pt-2">
                <div class="card border-0 work-container work-primary work-modern position-relative d-block overflow-hidden rounded">
                    <div class="portfolio-box-img position-relative overflow-hidden">
                        <img class="item-container img-fluid mx-auto" src="/img/{{$val->nama_file}}" alt="1" />
                        <div class="overlay-work"></div>
                        <div class="content">
                            <h5 class="mb-0"><a href="portfolio-detail-one.html" class="text-white title">Mockup box with paints</a></h5>
                            <h6 class="text-white-50 tag mt-1 mb-0">Photography</h6>
                        </div>
                        <div class="icons text-center">
                            <a href="{{ url('/uploads/promosi/'.$val->nama_file) }}" class="work-icon bg-white d-inline-flex rounded-pill lightbox"><i data-feather="camera" class="fea icon-sm image-icon"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="col-lg-12 mt-4">
                {{ $promosi->appends(['combocariclosing' => Request::get('combocariclosing'), 'pencarianclosing' => Request::get('pencarianclosing')])->render() }}
            </div>
            <input type="hidden" name="hidden_page_promosi" id="hidden_page_promosi" value="1" />
        </div>
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
            }
        });
    }

</script>