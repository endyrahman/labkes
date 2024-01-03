@extends('layouts.app')

@section('content')
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Daftar Pemeriksaan Lab</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <table id="dt-pembayaran-verifikasi" class="table dt-table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Registrasi</th>
                    <th>Nama</th>
                    <th>Tgl. Transfer</th>
                    <th>Bank</th>
                    <th>No. Rekening</th>
                    <th>Nominal</th>
                    <th>Status</th>
                    <th class="no-content">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $val)
                <tr>
                    <td></td>
                    <td>{{ $val->no_registrasi }}</td>
                    <td>{{ $val->nama_pasien }}</td>
                    <td>{{ date('d-m-Y', strtotime($val->tgl_transfer)) }}</td>
                    <td>{{ $val->asal_bank }}</td>
                    <td>{{ $val->no_rekening }}</td>
                    <td>{{ number_format($val->nominal_transfer, '0', ',', '.') }}</td>
                    <td>
                        @if ($val->status_bayar == 1)
                            <span class="shadow-none badge badge-info">Belum Validasi</span>
                        @elseif ($val->status_bayar == 2)
                            <span class="shadow-none badge badge-success">Lunas</span>
                        @endif
                    </td>
                    <td><a href="{{ url('/pencetakan/cetakbuktiregistrasi/'.$val->pemeriksaan_id) }}" class="bs-tooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cetak Bukti Registrasi" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a>&nbsp;
                    <a href="javascript:void(0);" onclick="mdlValidasiBayar({{$val->pemeriksaan_id}}, {{$val->pembayaran_id}}, {{$val->nominal_transfer}}, '{{$val->bukti_bayar}}' )" class="btn btn-success p-1">Validasi</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="mdlValidasiBayar" tabindex="-1" role="dialog" aria-labelledby="mdlValidasiBayarTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Validasi Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form method="POST" action="{{ url('/pembayaran/verifikasi/updateStatusBayar') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-body">
                <input type="hidden" class="form-control" name="pemeriksaan_id" id="pemeriksaan_id" readonly>
                <input type="hidden" class="form-control" name="pembayaran_id" id="pembayaran_id" readonly>
                <div class="col-sm-12 pb-3" id="fotoBuktiBayar" style="display: block; margin: auto;">

                </div>
                <div class="row pb-2">
                    <div class="col-sm-4">
                        <label class="form-label">Nominal Transfer</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="nominal_transfer" id="nominal_transfer" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Batal</button>
                <button type="submit" class="btn btn-primary" id="simpanformlab">Validasi</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<script>

    function mdlValidasiBayar(pemeriksaan_id, pembayaran_id, nominal_transfer, url) {
        $('#mdlValidasiBayar').modal('toggle');
        $('#pemeriksaan_id').val(pemeriksaan_id);
        $('#pembayaran_id').val(pembayaran_id);
        $('#nominal_transfer').val(nominal_transfer);
        $('#fotoBuktiBayar').html("<img src='/labkes/storage/bukti_bayar/"+url+"' width='600'>");
    }

</script>
@endsection
