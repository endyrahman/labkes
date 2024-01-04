@extends('layouts.app')

@section('content')
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Daftar Registrasi Online Labkes</h5>
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
                        <th>Nama Pengguna</th>
                        <th>Jadwal Datang</th>
                        <th>Jenis Lab</th>
                        <th>Total Biaya</th>
                        <th>Status Bayar</th>
                        <th>Tgl. Registrasi</th>
                        <th class="no-content">Aksi</th>
                    </tr>
                </thead>
                <tbody id="bodyGridVerifikasiPemeriksaan">
                    @include('registrasi.verifikasi.gridverifikasipemeriksaan')
                </tbody>
                <input type="hidden" name="hidden_page_gridverifikasipemeriksaan" id="hidden_page_gridverifikasipemeriksaan" value="1"/>
            </table>
        </div>
    </div>
    </div>
</div>

<div class="modal fade" id="mdlUploadFormLab" tabindex="-1" role="dialog" aria-labelledby="mdlUploadFormLabTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlUploadFormLabTitle">Upload </h5>
            </div>

            <form method="POST" action="{{ url('/registrasi/verifikasi/updateStatusLab') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="row">
                    <input id="lab_pemeriksaan_id" name="lab_pemeriksaan_id" class="form-control" type="hidden" placeholder="" required>
                    <input id="status_lab" name="status_lab" class="form-control" type="hidden" value="3" placeholder="" required>
                    <div class="col-sm-12">
                        <div class="custom-file-container" data-upload-id="formLab">
                            <label>Upload Form Laboratorium <span style="color:red;">*</span> <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                            <label class="custom-file-container__custom-file" >
                                <input type="file" class="custom-file-container__custom-file__custom-file-input" accept="image/*, application/pdf" id="fileLabPemeriksaan" name="fileLabPemeriksaan" required>
                                <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                                <span class="custom-file-container__custom-file__custom-file-control"></span>
                            </label>
                            <div class="custom-file-container__image-preview"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                <button type="submit" class="btn btn-primary" id="simpanformlab">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlProsesLab" tabindex="-1" role="dialog" aria-labelledby="mdlProsesLabTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlUploadFormLabTitle">Proses Lab Penerimaan Sampel </h5>
            </div>

            <form method="POST" action="{{ url('/registrasi/verifikasi/updateStatusLab') }}">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="row">
                    <input id="proses_pemeriksaan_id" name="proses_pemeriksaan_id" class="form-control" type="hidden" placeholder="" required>
                    <input id="status_lab" name="status_lab" class="form-control" type="hidden" value="3" placeholder="" required>
                </div>
                <div class="row pb-2">
                    <div class="col-sm-4">
                        <label class="form-label">No. Pelanggan</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="no_pelanggan_silkes" id="no_pelanggan_silkes" placeholder="No. Pelanggan Silkes">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label class="form-label">No. Sampel</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="no_sampel_silkes" id="no_sampel_silkes" placeholder="No. Sampel Silkes">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                <button type="submit" class="btn btn-primary" id="simpanformlab">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlUploadHasilLab" tabindex="-1" role="dialog" aria-labelledby="mdlJadwalKunjunganTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mdlJadwalKunjunganTitle">Upload Hasil Laboratorium</h5>
            </div>
            <form method="POST" action="{{ url('/registrasi/verifikasi/updateStatusLab') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="form-row mb-2">
                    <div class="form-group col-md-12">
                        <label for="no_registrasi">No. Silkes</label>
                        <input type="text" class="form-control" name="no_pelanggan_silkes" id="no_pelanggan_silkes" required placeholder="No. Pelanggan Silkes">
                        <input type="hidden" class="form-control" name="upload_pemeriksaan_id" id="upload_pemeriksaan_id" required>
                    </div>
                </div>
                <div class="form-row mb-4">
                    <div class="form-group col-md-12">
                        <label for="no_registrasi">No. Sampel</label>
                        <input type="text" class="form-control" name="no_sampel_silkes" id="no_sampel_silkes" required placeholder="No. Sampel Silkes">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="custom-file-container" data-upload-id="formHasilLab">
                            <label>Upload Hasil Laboratorium <span style="color:red;">*</span> <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                            <label class="custom-file-container__custom-file" >
                                <input type="file" class="custom-file-container__custom-file__custom-file-input" accept="image/*, application/pdf" id="fileLabHasilPemeriksaan" name="fileLabHasilPemeriksaan" required>
                                <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                                <span class="custom-file-container__custom-file__custom-file-control"></span>
                            </label>
                            <div class="custom-file-container__image-preview"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                <button type="submit" class="btn btn-primary" id="simpanhasillab">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlBatalkanRegistrasi" tabindex="-1" role="dialog" aria-labelledby="mdlBatalkanRegistrasiTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Batalkan Registrasi</h5>
                <button type="button" class="btn-close" data-dismiss="modal"aria-label="Close"></button>
            </div>
            <form action="{{ url('/registrasi/verifikasi/batal') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="batal_pemeriksaan_id" id="batal_pemeriksaan_id" class="form-control"></input>
                    <h5>Apakah anda yakin untuk membatalkan registrasi ini?</h5>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Keterangan Batal</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Ya</button>
                </div>
            </form>
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
                    <div class="form-group col-md-6">
                        <label for="no_registrasi">Nama Pengguna</label>
                        <input type="text" class="form-control" id="detail_nama_pengguna" name="detail_nama_pengguna" readonly>
                    </div>
                    <input type="hidden" class="form-control" id="detail_pemeriksaan_id" name="detail_pemeriksaan_id" readonly>
                </div>
                <div class="form-row mb-4">
                    <div class="form-group col-md-6">
                        <label for="no_registrasi">No. Registrasi</label>
                        <input type="text" class="form-control" id="detail_no_registrasi" name="detail_no_registrasi" readonly>
                    </div>
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
                $('#detail_nama_pengguna').val(data.nama_lengkap);
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
            url: '{{ url("/registrasi/verifikasi/paginationgridverifikasipemeriksaan?pagegridverifikasipemeriksaan") }}',
            type: 'GET',
            data: {
                pagegridverifikasipemeriksaan: page,
                combocari: combocari,
                pencarian: pencarian,
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
            url: '{{ url("/registrasi/getDataPembayaran") }}',
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
