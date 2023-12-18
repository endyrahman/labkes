@foreach($mikrobiologi as $val)
    @php
        $id = '';
        $display = 'none';
        $displaypilih = 'inline';
        $terpilih = '';

        if (in_array($val->id, $parameterterpilih)) {
            $display = 'inline';
            $displaypilih = 'none';
            $id = $val->id;
            $terpilih = 'Terpilih';
        }
    @endphp
    <div class="card col-sm-3">
        <div class="card-body">
            <div class="text-center">
                <p class="card-user_occupation mb-1">{{ $val->nama_parameter }}</p>
                <span class="badge badge-primary mt-0 mb-1">Rp. {{ number_format($val->harga, 0, ',', '.') }}</span>
                <span class="badge badge-success mt-0 mb-1" id="parameterTerpilih{{$val->id}}" style="display: {{ $display }};">
                    {{ $terpilih }}
                </span>
                <input type="hidden" name="biayaParameter[]" id="biayaParameter{{$val->id}}" value="{{ $val->harga }}" readonly>
                <input type="hidden" name="aktifParameter[]" id="aktifParameter{{$val->id}}" value="{{ $id }}" readonly>
            </div>
            <div class="text-center">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-secondary" id="pilihParameter{{ $val->id }}" onclick="pilihParameterPemeriksaan({{ $val->id }})" style="display: {{ $displaypilih }};padding:5px 8px 5px 8px;">Pilih</button>
                    <button type="button" class="btn btn-sm btn-danger" id="batalParameter{{ $val->id }}" onclick="batalPilihParameterPemeriksaan({{ $val->id }})" style="display:{{ $display  }};padding:5px 8px 5px 8px;">Batal</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
<div class="col-sm-12 mt-4">
    {{ $mikrobiologi->links() }}
</div>
