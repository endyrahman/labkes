@foreach($pemeriksaan as $val)
    @php
        $paket_pemeriksaan_id = '';
        $display = 'none';
        $displaypilih = 'inline';
        $terpilih = '';

        if (in_array($val->paket_pemeriksaan_id, $paketterpilih)) {
            $display = 'inline';
            $displaypilih = 'none';
            $paket_pemeriksaan_id = $val->paket_pemeriksaan_id;
            $terpilih = 'Terpilih';
        }
    @endphp

    <div class="card col-sm-3">
        <div class="card-body">
            <div class="text-center">
                <p class="card-user_occupation mb-1">{{ $val->nama_pemeriksaan }}</p>
                <span class="badge badge-primary mt-0 mb-1">Rp. {{ $val->total_harga }}</span>
                <span class="badge badge-success mt-0 mb-1" id="paketTerpilih{{$val->paket_pemeriksaan_id}}" style="display: {{ $display }};">
                    {{ $terpilih }}
                </span>
                <input type="hidden" name="biayaPaket[]" id="biayaPaket{{$val->paket_pemeriksaan_id}}" value="{{ $val->total_harga }}" readonly>
                <input type="hidden" name="aktifPaket[]" id="aktifPaket{{$val->paket_pemeriksaan_id}}" value="{{ $paket_pemeriksaan_id }}" readonly>
            </div>
            <div class="text-center">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-secondary" id="pilihPaket{{ $val->paket_pemeriksaan_id }}" onclick="pilihPaketPemeriksaan({{ $val->paket_pemeriksaan_id }})" style="display: {{ $displaypilih }};padding:5px 8px 5px 8px;">Pilih</button>
                    <button type="button" class="btn btn-sm btn-danger" id="batalPaket{{ $val->paket_pemeriksaan_id }}" onclick="batalPilihPaketPemeriksaan({{ $val->paket_pemeriksaan_id }})" style="display:{{ $display  }};padding:5px 8px 5px 8px;">Batal</button>
                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#mdlDetailPemeriksaanKimia" onclick="getDetailPaketPemeriksaan({{$val->paket_pemeriksaan_id}},'{{$val->arr_parameter_id}}',{{$val->lab_pemeriksaan_id}})" style="padding:5px 8px 5px 8px;">
                            Detail
                        </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
<div class="col-sm-12 mt-4">
    {{ $pemeriksaan->links() }}
</div>
