@extends('layouts.app')

@section('content')
<style type="text/css">
.flatpickr-calendar.open {
    display: inline-block;
    z-index: 99999!important;
}
</style>
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Pelaporan</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <div id="toggleAccordion">
            <div class="card">
                <div class="card-header" id="accordion1">
                    <section class="mb-0 mt-0">
                        <div role="menu" class="collapsed" data-toggle="collapse" data-target="#laporanpendapatan" aria-expanded="true" aria-controls="laporanpendapatan">
                            Pelaporan Registrasi / Pembayaran
                            <div class="icons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </div>
                        </div>
                    </section>
                </div>
                <div id="laporanpendapatan" class="collapse" aria-labelledby="accordion1" data-parent="#toggleAccordion">
                    <div class="card-body">
                        <div class="form-group row mb-1">
                            <label for="tgl_awal" class="col-sm-2 col-form-label">Tanggal</label>
                            <div class="col-sm-4">
                                <input id="tgl_awal" value="{{ date('d-m-Y') }}" class="form-control flatpickr flatpickr-input" type="text" placeholder="Select Date.." readonly="readonly">
                            </div><span style="padding-top:11px;">s/d</span>
                            <div class="col-sm-4">
                                <input id="tgl_akhir" value="{{ date('d-m-Y') }}" class="form-control flatpickr flatpickr-input" type="text" placeholder="Select Date.." readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label for="hEmail" class="col-sm-2 col-form-label">Tanggal Berdasarkan</label>
                            <div class="col-sm-5">
                                <select class="form-control" id="berdasarkan_tgl">
                                    <option value="">Pilih Berdasarkan</option>
                                    <option value="1">Tgl. Registrasi</option>
                                    <option value="2">Tgl. Pembayaran</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="hEmail" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-5">
                                <select class="form-control" id="status_bayar">
                                    <option value="">Pilih Berdasarkan</option>
                                    <option value="1">Belum Terbayar</option>
                                    <option value="2">Terbayar</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="hPassword" class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <button class="btn btn-info" onclick="pelaporanregistrasibayar()">Cetak</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('backend/plugins/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('backend/plugins/flatpickr/custom-flatpickr.js') }}"></script>
<script>
    flatpickr(document.getElementById('tgl_awal'), {
        dateFormat: "d-m-Y",
    });

    flatpickr(document.getElementById('tgl_akhir'), {
        dateFormat: "d-m-Y",
    });


    function pelaporanregistrasibayar() {
        var token = $("input[name='_token']").val();
        var tgl_awal = $("#tgl_awal").val();
        var tgl_akhir = $("#tgl_akhir").val();
        var berdasarkan_tgl = $("#berdasarkan_tgl").val();
        var status_bayar = $("#status_bayar").val();

        $.ajax({
            type:'POST',
            url: '{{ url("/spr/pelaporan/cetakpelaporanregistrasibayar") }}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { tgl_awal:tgl_awal, tgl_akhir:tgl_akhir, berdasarkan_tgl:berdasarkan_tgl, status_bayar:status_bayar },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                var blob = new Blob([response], {type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;'});
                const downloadLink = document.createElement('a');
                downloadLink.href = URL.createObjectURL(blob);
                downloadLink.download = 'pelaporanregistrasi.xlsx';
                downloadLink.click();
            },
            error: function(blob){
                console.log(blob);
            }
        });
    }
</script>
@endsection
