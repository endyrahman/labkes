@foreach ($promosi as $val)
<div class="col-lg-4 col-md-6 mt-4 pt-2">
    <div class="card border-0 work-container work-primary work-modern position-relative d-block overflow-hidden rounded">
        <div class="portfolio-box-img position-relative overflow-hidden">
            <img class="item-container img-fluid mx-auto" src="{{ url('/img/'.$val->nama_file) }}" alt="1" />
            <div class="overlay-work"></div>
            <div class="content">
                <h5 class="mb-0"><a href="portfolio-detail-one.html" class="text-white title">Mockup box with paints</a></h5>
                <h6 class="text-white-50 tag mt-1 mb-0">Photography</h6>
            </div>
            <div class="icons text-center">
                <a href="{{ url('/img/'.$val->nama_file) }}" class="work-icon bg-white d-inline-flex rounded-pill lightbox"><i data-feather="camera" class="fea icon-sm image-icon"></i></a>
            </div>
        </div>
    </div>
</div>
@endforeach
<div class="col-lg-12 mt-4">
    {{ $promosi->appends(['combocariclosing' => Request::get('combocariclosing'), 'pencarianclosing' => Request::get('pencarianclosing')])->render() }}
</div>
