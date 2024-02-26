<section class="section pt-5 pt-sm-0 pt-md-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h1 class="mb-4">Kegiatan</h1>
                </div>
            </div>
        </div>

        <div class="row">
            @php
                $lpkegiatan = App\Models\Kegiatan\KegiatanTbl::getDataLpKegiatan();
            @endphp
            @foreach ($lpkegiatan as $val)
            <div class="col-lg-4 col-md-6 mt-4 pt-2">
                <div class="card blog blog-primary rounded border-0 shadow">
                    <div class="position-relative">
                        <img src="{{ url('/uploads/kegiatan/'.$val->foto_kegiatan) }}" class="card-img-top img-fluid mx-auto rounded-top" alt="...">
                        <div class="overlay rounded-top"></div>
                    </div>
                    <div class="card-body content">
                        <h5><a href="javascript:void(0)" class="card-title title text-dark">{{ $val->nama_kegiatan }}</a></h5>
                        <div class="post-meta d-flex justify-content-between mt-3">
                            <ul class="list-unstyled mb-0">
                                <li class="list-inline-item"><a href="javascript:void(0)" class="text-muted comments"><i class="uil uil-calendar-alt me-1"></i>{{ date('d-m-Y', strtotime($val->tgl_kegiatan)) }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="author">
                        <small class="date"><i class="uil uil-calendar-alt"></i> {{ date('d-m-Y', strtotime($val->created_at)) }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="col-lg-12 mt-4">
            {{ $lpkegiatan->appends(['combocariclosing' => Request::get('combocariclosing'), 'pencarianclosing' => Request::get('pencarianclosing')])->render() }}
        </div>
    </div>
</section>