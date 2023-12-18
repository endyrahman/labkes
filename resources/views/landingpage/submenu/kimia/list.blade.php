@foreach ($pemeriksaan as $val)
<div class="col-lg-3 mt-4">
    <a href="javascript:void(0)" type="button" class="d-flex btn btn-soft-primary text-dark features feature-warning key-feature align-items-center p-3 rounded shadow" style="min-height: 100px;">
        <div class="icon text-center rounded-circle me-3">
            <i data-feather="camera" class="fea icon-ex-md"></i>
        </div>
        <div class="flex-1">
            <h6 style="font-size: 12px;font-weight: 700;">{{ $val->nama_parameter }}</h6>
        </div>
    </a>
</div>
@endforeach
<div class="col-12 mt-3 text-center">
    {{ $pemeriksaan->links() }}
</div>
