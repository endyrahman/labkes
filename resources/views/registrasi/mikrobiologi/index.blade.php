@extends('layouts.app')

@section('content')

<script src="{{ asset('backend/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget widget-chart-three">
    <div class="widget-heading">
        <div class="">
            <h5 class=""> Daftar Registrasi Online Lab Mikrobiologi</h5>
        </div>
        <a href="{{ url('/registrasi/mikrobiologi/create') }}" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Registrasi</a>
    </div>
    <div class="widget-content widget-content-area br-12 pb-3 pl-3 pr-3">
        <div class="input-group mb-4">
            <div class="input-group-prepend">
                <select class="form-control col-md-6" id="combocari" onchange="paginationGrid(1)">
                    <option value="">Pilih Pencarian</option>
                    <option value="no_registrasi">No. Registrasi</option>
                    <option value="nama_sampel">Sampel</option>
                    <option value="lokasi_sampel">Lokasi Sampel</option>
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
                        <th>Sampel</th>
                        <th>Lokasi Sampel</th>
                        <th>Pemeriksaan</th>
                        <th>Jadwal Datang</th>
                        <th>Total Biaya</th>
                        <th>Pembayaran</th>
                        <th style="display:none;">Tgl. Input</th>
                        <th class="no-content">#</th>
                    </tr>
                </thead>
                <tbody id="bodyGridMikrobiologi">
                    @include('registrasi.mikrobiologi.gridmikrobiologi')
                </tbody>
            </table>
            <input type="hidden" name="hidden_page_gridmikrobiologi" id="hidden_page_gridmikrobiologi" value="1"/>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlHapusRegistrasi" tabindex="-1" role="dialog" aria-labelledby="mdlHapusRegistrasiTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Registrasi</h5>
                <button type="button" class="btn-close" data-dismiss="modal"aria-label="Close"></button>
            </div>
            <form action="{{ url('/registrasi/delete') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="pemeriksaan_id" id="pemeriksaan_id" class="form-control"></input>
                    <input type="hidden" name="lab_id" id="lab_id" value="3" class="form-control"></input>
                    <h5>Apakah anda yakin untuk hapus registrasi ini?</h5>
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

<script type="text/javascript">
    function hapusRegistrasi(id){
        $('#mdlHapusRegistrasi').modal('toggle');
        $('#pemeriksaan_id').val(id);
    }

    function getDetailPemeriksaan(id, jmlh_sampel, jenis_lab_id) {
        var token = $("input[name='_token']").val();

        $.ajax({
            type:'POST',
            url: "{{ url('/registrasi/getDetailPemeriksaan') }}",
            data: { _token:token, id:id, jmlh_sampel:jmlh_sampel, jenis_lab_id:jenis_lab_id },
            success:function(data){
                $('#bodyDetailPemeriksaan').html('');
                $('#bodyDetailPemeriksaan').html(data.html);
            }
        });
    }

    $(document).on('click', '.pagination a', function(event){
        var urlnow = $(this).attr('href').split('/');
        event.preventDefault();
        var cekurl = $(this).attr('href');
        console.log(cekurl)
        if (cekurl.includes("pagegridpemeriksaan")) {
            var page = $(this).attr('href').split('pagegridpemeriksaan=')[1];
            $('li').removeClass('active');
                $(this).parent().addClass('active');
            paginationGrid(page);
        }
    });

    function paginationGrid(page, combocari, pencarian) {
        $('#hidden_page_gridmikrobiologi').val(page);
        var combocari = $('#combocari').val();
        var pencarian = $('#pencarian').val();
        if (pencarian) {
            $("#hapusPencarian").show();
        } else {
            $("#hapusPencarian").hide();
        }

        $.ajax({
            url: '{{ url("/registrasi/mikrobiologi/paginationgridpemeriksaan") }}',
            type: 'GET',
            data: {
                pagegridpemeriksaan: page,
                combocari: combocari,
                pencarian: pencarian,
                lab_id: 3,
            },
            success:function(datas)
            {
                $('#bodyGridMikrobiologi').html('');
                $('#bodyGridMikrobiologi').html(datas);
            }
        });
    }

    function hapusPencarian(page, combocari, pencarian) {
        var combocari = $('#combocari').val('');
        var pencarian = $('#pencarian').val('');
        paginationGrid(page, combocari, pencarian)
    }
</script>
@endsection
