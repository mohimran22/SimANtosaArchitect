<div class="p-3 bg-light border-bottom">

    <div class="row">

        <div class="col-md-4">

            <label>Pekerjaan Tambahan</label>

            <select class="form-select select2 job-tambahan"
                    data-item="{{ $item->id }}">

                <option value="">
                    -- Pilih Pekerjaan --
                </option>

                @foreach($jobCategories as $job)

                    <option value="{{ $job->id }}">
                        {{ $job->nama_pekerjaan }}
                    </option>

                @endforeach

            </select>

        </div>

        <div class="col-md-auto d-flex align-items-end">

            <button type="button"
                    class="btn btn-dark btn-simpan-tambahan"
                    data-item="{{ $item->id }}">

                Tambahkan ke Kontrak

            </button>

        </div>

    </div>

</div>

        @foreach($item->tambahan as $sub)

        @php
        $progressMap = $sub->weeklyProgresses->keyBy('week_no');
        @endphp

        <tr class="table-warning">
            <td></td>

            <td>
                ↳ {{ $sub->uraian }}
            </td>

            <td>{{ $sub->satuan }}</td>

            <td>{{ $sub->volume }}</td>

            <td>
                Rp {{ number_format($sub->price,0,',','.') }}
            </td>

            <td></td>

            @foreach($weekLabels as $w)

                @php
                $prog = $progressMap[$w['week_no']] ?? null;
                @endphp

                <td>
                    <input type="number"
                        class="form-control week-vol"
                        value="{{ $prog->volume ?? '' }}">
                </td>

                <td></td>

                <td></td>

                <td>
                    <input class="form-control"
                        value="{{ $prog->just_kurang ?? 0 }}">
                </td>

                <td>
                    <input class="form-control"
                        value="{{ $prog->just_tambah ?? 0 }}">
                </td>

                <td>
                    <input class="form-control"
                        value="{{ $prog->just_baru ?? 0 }}">
                </td>

            @endforeach

            <td>0</td>
            <td>{{ $sub->volume }}</td>
            <td>Rp {{ number_format($sub->price,0,',','.') }}</td>
            <td>0</td>

        </tr>

        @endforeach