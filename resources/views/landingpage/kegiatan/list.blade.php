@foreach ($kegiatan as $val)
<div class="col-lg-4 col-md-6 mt-4 pt-2">
    <div class="card blog blog-primary rounded border-0 shadow">
        <div class="position-relative">
            <img src="/storage/foto_kegiatan/{{ $val->foto_kegiatan }}" class="card-img-top img-fluid mx-auto rounded-top" alt="...">
            <div class="overlay rounded-top"></div>
        </div>
        <div class="card-body content">
            <h5><a href="javascript:void(0)" class="card-title title text-dark">{{ $val->nama_kegiatan }}</a></h5>
            <div class="post-meta d-flex justify-content-between mt-3">
                <ul class="list-unstyled mb-0">
                    <li class="list-inline-item"><a href="javascript:void(0)" class="text-muted comments"><i class="uil uil-calendar-alt me-1"></i>{{ date('d-m-Y', strtotime($val->tgl_kegiatan)) }}</a></li>
                </ul>
                <a href="blog-detail.html" class="text-muted readmore">Read More <i class="uil uil-angle-right-b align-middle"></i></a>
            </div>
        </div>
        <div class="author">
            <small class="user d-block"><i class="uil uil-user"></i> Calvin Carlo</small>
            <small class="date"><i class="uil uil-calendar-alt"></i> {{ date('d-m-Y', strtotime($val->created_at)) }}</small>
        </div>
    </div>
</div>
@endforeach
<div class="col-12 mt-3 text-center">
    {{ $kegiatan->links() }}
</div>