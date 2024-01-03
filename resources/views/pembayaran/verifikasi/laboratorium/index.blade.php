@extends('layouts.app')

@section('content')
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Daftar Pembayaran</h5>
        </div>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <div class="input-group mb-4">
            <div class="input-group-prepend">
                <select class="form-control col-md-6" id="combocari" onchange="paginationGrid(1)">
                    <option value="">Pilih Pencarian</option>
                    <option value="no_registrasi">No. Registrasi</option>
                    <option value="nama_pasien">Nama Pasien</option>
                    <option value="tgl_waktu_kunjungan">Tgl. Kunjungan</option>
                </select>
                <input type="text" class="form-control col-md-8" id="pencarian" onchange="paginationGrid(1)" onkeyup="paginationGrid(1)" placeholder="Masukkan Pencarian">
            </div>
            <button class="btn btn-danger" type="button" id="hapusPencarian" onclick="hapusPencarian(1)" style="display:none;">Hapus</button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-4">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Registrasi</th>
                        <th>Nama</th>
                        <th>Jadwal Datang</th>
                        <th>Jenis Lab</th>
                        <th>Total Biaya</th>
                        <th>Status Bayar</th>
                        <th>Tgl. Registrasi</th>
                        <th class="no-content">Aksi</th>
                    </tr>
                </thead>
                <tbody id="bodyGridVerifikasiPemeriksaan">
                    @include('pembayaran.verifikasi.laboratorium.list')
                </tbody>
                <input type="hidden" name="hidden_page_gridverifikasipemeriksaan" id="hidden_page_gridverifikasipemeriksaan" value="1"/>
            </table>
        </div>
    </div>
    </div>
</div>

<div class="modal fade" id="mdlDetailPemeriksaan" tabindex="-1" role="dialog" aria-labelledby="mdlJadwalKunjunganTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlDetailPemeriksaanTitle">Detail Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" >
                <div class="form-row mb-4">
                    <div class="form-group col-md-6">
                        <label for="no_registrasi">No. Registrasi</label>
                        <input type="text" class="form-control" id="detail_no_registrasi" name="detail_no_registrasi" readonly>
                    </div>
                    <input type="hidden" class="form-control" id="detail_pemeriksaan_id" name="detail_pemeriksaan_id" readonly>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-4">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Pemeriksaan</th>
                                <th class="text-center">Biaya</th>
                            </tr>
                        </thead>
                        <tbody id="bodyDetailPemeriksaan">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
            </div>
        </div>
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
            <form method="POST" action="{{ url('/pembayaran/verifikasi/laboratorium/updateStatusBayar') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-body">
                <input type="hidden" class="form-control" name="validasi_pemeriksaan_id" id="validasi_pemeriksaan_id" readonly>
                <input type="hidden" class="form-control" name="validasi_pembayaran_id" id="validasi_pembayaran_id" readonly>
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
<script src="{{ asset('backend/plugins/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('backend/plugins/file-upload/file-upload-with-preview.min.js') }}"></script>
<script>

    function mdlDetailPemeriksaan(id, no_registrasi, jenis_lab_id, user_id) {
        var token = $("input[name='_token']").val();
        $('#mdlDetailPemeriksaan').modal('toggle');
        $('#detail_pemeriksaan_id').val(id);
        $('#detail_no_registrasi').val(no_registrasi);

        $.ajax({
            type:'POST',
            url: '{{ url("/registrasi/getDetailPemeriksaan") }}',
            data: { _token:token, id:id, jenis_lab_id:jenis_lab_id, user_id:user_id },
            success:function(data){
                $('#bodyDetailPemeriksaan').html('');
                $('#bodyDetailPemeriksaan').html(data.html);
            }
        });
    }

    function mdlBatalkanRegistrasi(id) {
        $('#mdlBatalkanRegistrasi').modal('toggle');
        $('#batal_pemeriksaan_id').val(id);
    }

    function mdlDiverifikasi(id, lab_id) {
        $('#mdlDiverifikasi').modal('toggle');
        $('#pemeriksaan_id').val(id);
    }

    function mdlUploadFormLab(id) {
        $('#mdlUploadFormLab').modal('toggle');
        $('#lab_pemeriksaan_id').val(id);
    }

    function mdlProsesLab(id) {
        $('#mdlProsesLab').modal('toggle');
        $('#proses_pemeriksaan_id').val(id);
    }

    function mdlUploadHasilLab(id) {
        $('#mdlUploadHasilLab').modal('toggle');
        $('#upload_pemeriksaan_id').val(id);
    }

    var upformLab = new FileUploadWithPreview('formLab');
    var upformHasilLab = new FileUploadWithPreview('formHasilLab');


    $(document).on('click', '.pagination a', function(event){
        var urlnow = $(this).attr('href').split('/');
        event.preventDefault();
        var cekurl = $(this).attr('href');
        console.log(cekurl)
        if (cekurl.includes("pagegridverifikasipemeriksaan")) {
            var page = $(this).attr('href').split('pagegridverifikasipemeriksaan=')[1];
            $('li').removeClass('active');
                $(this).parent().addClass('active');
            paginationGrid(page);
        }
    });

    function paginationGrid(page, combocari, pencarian) {
        $('#hidden_page_gridverifikasipemeriksaan').val(page);
        var combocari = $('#combocari').val();
        var pencarian = $('#pencarian').val();
        if (pencarian) {
            $("#hapusPencarian").show();
        } else {
            $("#hapusPencarian").hide();
        }

        $.ajax({
            url:'{{ url("/registrasi/verifikasi/paginationgridverifikasipemeriksaan") }}',
            type: 'GET',
            data: {
                pagegridverifikasipemeriksaan: page,
                combocari: combocari,
                pencarian: pencarian
            },
            success:function(datas)
            {
                $('#bodyGridVerifikasiPemeriksaan').html('');
                $('#bodyGridVerifikasiPemeriksaan').html(datas);
            }
        });
    }

    function hapusPencarian(page, combocari, pencarian) {
        var combocari = $('#combocari').val('');
        var pencarian = $('#pencarian').val('');
        paginationGrid(page, combocari, pencarian)
    }

    function mdlValidasiBayar(pemeriksaan_id) {
        var token = $("input[name='_token']").val();

        $.ajax({
            type:'POST',
            url: "{{ url('/registrasi/getDataPembayaran') }}",
            data: { _token:token, pemeriksaan_id:pemeriksaan_id },
            success:function(data) {
                $('#mdlValidasiBayar').modal('toggle');
                $('#validasi_pemeriksaan_id').val(data.pemeriksaan_id);
                $('#validasi_pembayaran_id').val(data.id);
                $('#nominal_transfer').val(data.nominal_transfer);
                $('#fotoBuktiBayar').html("<img src='/labkes/storage/bukti_bayar/"+data.bukti_bayar+"' style='max-height:85vh;'>");
            }
        });
    }
</script>
@endsection
