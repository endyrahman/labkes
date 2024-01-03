@extends('layouts.app')

@section('content')
@php
    ini_set('date.timezone', 'Asia/Jakarta');
@endphp
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
            <h5 class=""> Tambah Master Data Parameter Pemeriksaaan Klinik</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <div class="info mt-4">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mt-md-0 mt-4">
                            <form method="POST" action="{{ url('/master/parameterpemeriksaan/storeKlinik') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input id="jenis_lab_id" name="jenis_lab_id" class="form-control" value="1" type="hidden">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="nama_parameter">Nama Parameter</label>
                                            <input type="text" name="nama_parameter" id="nama_parameter" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="harga">Harga</label>
                                            <input type="text" name="harga" id="harga" class="form-control" required style="text-align: right;" onchange="this.value = formatCurrency(this.value);" onblur="this.value = formatCurrency(this.value);" onkeyup="this.value = formatCurrency(this.value);">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="lbljumlah">Status <span id="nameStatus"></span></label>
                                            <div class="form-group mb-2">
                                                <label class="switch s-icons s-outline s-outline-success">
                                                    <input type="checkbox" id="statusParameter" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <input type="hidden" id="status" name="status">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block mb-4 mr-2" id="simpanParameterKimia">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript">
    $('#statusParameter').on('change', function(){
        this.value = this.checked ? 1 : 0;
        $('#status').val(this.value);
        if (this.value == 1) {
            $('#nameStatus').text('Aktif').css({"color": "green", "font-weight": "bold"});

        } else {
            $('#nameStatus').text('Tidak Aktif').css({"color": "red", "font-weight": "bold"});
        }
    }).change();

    function formatCurrency(num)
    {
        if (num != "" || num != "undefined")
        {
            num = num.replace(/\$|\,00/g, '').replace(/\$|\./g, '');
            sign = (num == (num = Math.abs(num)));
            num = Math.floor(num * 100 + 0.50000000001);
            cents = num % 100;
            num = Math.floor(num / 100).toString();
            if (cents < 10)
                cents = "0" + cents;
            for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
                num = num.substring(0, num.length - (4 * i + 3)) + '.' + num.substring(num.length - (4 * i + 3));
            return (((sign) ? '' : '-') + num);
            //                    return (((sign) ? '' : '-') + num + ',' + cents);
            /*          }*/
        }
    }
</script>
@endsection
