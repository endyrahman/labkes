<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="section-title text-center mb-4 pb-2">
                    <h1 class="mb-4">Layanan Kami</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-12 mt-5 pt-4">
                <div class="features feature-primary">
                    <div class="image position-relative d-inline-block">
                        <i class="uil uil-edit-alt h2 text-primary"></i>
                    </div>

                    <div class="content mt-4">
                        <h5>Laboratorium Klinik</h5>
                        <p class="text-muted mb-0">{{ implode(' ', array_slice(explode(' ', $layanankami[0]->keterangan), 0, 15)); }}</p> <a href="{{ url('/labklinik') }}">Selengkapnya</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-12 mt-5 pt-4">
                <div class="features feature-primary">
                    <div class="image position-relative d-inline-block">
                        <i class="uil uil-vector-square h2 text-primary"></i>
                    </div>

                    <div class="content mt-4">
                        <h5>Laboratorium Kimia</h5>
                        <p class="text-muted mb-0">{{ implode(' ', array_slice(explode(' ', $layanankami[1]->keterangan), 0, 16)); }}</p> <a href="{{ url('/labkimia') }}">Selengkapnya</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-12 mt-5 pt-4">
                <div class="features feature-primary">
                    <div class="image position-relative d-inline-block">
                        <i class="uil uil-file-search-alt h2 text-primary"></i>
                    </div>

                    <div class="content mt-4">
                        <h5>Laboratorium Mikrobiologi</h5>
                        <p class="text-muted mb-0">{{ implode(' ', array_slice(explode(' ', $layanankami[2]->keterangan), 0, 20)); }}</p> <a href="{{ url('/labmikrobiologi') }}">Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
