{{-- Modal input target: tab Omset / Cost / Profit. Parameter: $year, $tabel
     Mode isi: Nominal (Rp) atau Persentase (% pertumbuhan terhadap bulan yang sama tahun lalu).
     Yang dikirim ke server selalu nominal. --}}
@php $tipeList = ['omset' => 'Omset', 'cost' => 'Cost', 'profit' => 'Profit']; @endphp

<style>
    .df-seg{display:inline-flex;border:1px solid var(--line);border-radius:999px;padding:3px;background:var(--head)}
    .df-seg button{border:0;background:transparent;padding:5px 14px;border-radius:999px;font-weight:700;font-size:13px;color:var(--muted);cursor:pointer}
    .df-seg button.on{background:var(--brand);color:#fff}
    .df-hint{display:block;margin-top:4px;font-size:12px;min-height:16px}
    .df-input input:disabled{background:var(--head);cursor:not-allowed}
</style>

<div class="modal modal-blur fade df-modal" id="modalTarget" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content df" method="POST" action="{{ route('dashboard-finance.target.store') }}" id="formTarget">
            @csrf
            <input type="hidden" name="year" value="{{ $year }}">
            <div class="modal-header">
                <h3 class="modal-title" style="font-weight:800">Target {{ $year }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <ul class="nav nav-tabs" role="tablist" style="margin-bottom:16px">
                    @foreach ($tipeList as $k => $nama)
                        <li class="nav-item">
                            <a href="#tab-target-{{ $k }}" class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" role="tab">{{ $nama }}</a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach ($tipeList as $k => $nama)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-target-{{ $k }}" data-mode="nominal">

                            {{-- pilihan mode --}}
                            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:12px">
                                <span class="text-muted" style="font-size:13px">Isi target dengan:</span>
                                <div class="df-seg">
                                    <button type="button" class="on" data-mode="nominal">Nominal (Rp)</button>
                                    <button type="button" data-mode="persen">Persentase (%)</button>
                                </div>
                            </div>

                            {{-- isi semua bulan --}}
                            <div style="margin-bottom:14px;display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                                <span class="text-muted label-sama" style="font-size:13px">Isi semua bulan sekaligus:</span>
                                <div class="df-input" style="width:190px"><span class="unit">Rp</span><input type="text" inputmode="decimal" class="target-sama" placeholder="0"></div>
                                <button type="button" class="df-btn ghost btn-samakan">Samakan</button>
                                <button type="button" class="df-btn ghost btn-bagi">Bagi rata dari total</button>
                            </div>

                            <div class="df-grid">
                                @foreach ($tabel[$k]['rows'] as $i => $r)
                                    <div class="df-cell" data-lalu="{{ (float) ($r['lalu'] ?? 0) }}">
                                        <label>{{ $r['label'] }}</label>
                                        <div class="df-input">
                                            <span class="unit">Rp</span>
                                            <input type="text" inputmode="decimal" class="target-view" placeholder="0">
                                        </div>
                                        {{-- nilai nominal yang dikirim ke server --}}
                                        <input type="hidden" class="target-bulan"
                                               name="targets[{{ $k }}][{{ $i }}]"
                                               value="{{ (int) round($r['target'] ?? 0) }}">
                                        <small class="df-hint text-muted"></small>
                                    </div>
                                @endforeach
                            </div>

                            <div style="margin-top:16px;font-weight:700">
                                Total target {{ $nama }}: <span class="total-target" style="color:var(--brand)">Rp 0</span>
                                <span class="total-growth text-muted" style="font-weight:600;font-size:13px"></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="df-btn ghost" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="df-btn">Simpan Target</button>
            </div>
        </form>
    </div>
</div>

@push('js')
<script>
    (() => {
        const TAHUN_LALU = {{ $year - 1 }};
        const rp      = n => 'Rp ' + Math.round(n).toLocaleString('id-ID');
        const digits  = s => parseInt((s || '').replace(/\D/g, '')) || 0;
        const num     = s => { const v = parseFloat((s || '').replace(/[^0-9,\-]/g, '').replace(',', '.')); return isNaN(v) ? 0 : v; };
        const fmtPct  = p => (Math.round(p * 10) / 10).toLocaleString('id-ID', {maximumFractionDigits: 1});
        const signed  = p => (p > 0 ? '+' : '') + fmtPct(p) + '%';
        const dariPct = (lalu, p) => lalu + Math.abs(lalu) * p / 100;   // pembanding minus tetap benar
        const keePct  = (n, lalu) => (n - lalu) / Math.abs(lalu) * 100;

        document.getElementById('formTarget').querySelectorAll('.tab-pane').forEach(pane => {
            const sama     = pane.querySelector('.target-sama');
            const totalEl  = pane.querySelector('.total-target');
            const growthEl = pane.querySelector('.total-growth');
            const btnBagi  = pane.querySelector('.btn-bagi');
            const labelSm  = pane.querySelector('.label-sama');
            const isPersen = () => pane.dataset.mode === 'persen';

            const cells = [...pane.querySelectorAll('.df-cell')].map(el => ({
                el,
                lalu: parseFloat(el.dataset.lalu) || 0,
                view: el.querySelector('.target-view'),
                hid:  el.querySelector('.target-bulan'),
                hint: el.querySelector('.df-hint'),
            }));

            const getNom = c => parseInt(c.hid.value) || 0;
            const setNom = (c, n) => { c.hid.value = Math.max(0, Math.round(n)); };

            const total = () => {
                const sum = cells.reduce((s, c) => s + getNom(c), 0);
                const lal = cells.reduce((s, c) => s + c.lalu, 0);
                totalEl.textContent  = rp(sum);
                growthEl.textContent = lal ? ` (${signed(keePct(sum, lal))} dari ${TAHUN_LALU})` : '';
            };

            // tulis ulang tampilan satu field sesuai mode aktif
            const render = (c, tulisInput = true) => {
                const n = getNom(c);
                if (isPersen()) {
                    c.view.disabled = !c.lalu;
                    if (tulisInput) c.view.value = c.lalu ? fmtPct(keePct(n, c.lalu)) : '';
                    c.hint.textContent = c.lalu ? '= ' + rp(n) : `Tidak ada pembanding ${TAHUN_LALU}`;
                } else {
                    c.view.disabled = false;
                    if (tulisInput) c.view.value = n ? n.toLocaleString('id-ID') : '';
                    c.hint.textContent = c.lalu && n ? `${signed(keePct(n, c.lalu))} dari ${TAHUN_LALU}` : (c.lalu ? '' : `Tidak ada pembanding ${TAHUN_LALU}`);
                }
            };

            // ketik di field bulan
            cells.forEach(c => c.view.addEventListener('input', () => {
                if (isPersen()) {
                    c.view.value = c.view.value.replace(/[^0-9,\-]/g, '');
                    setNom(c, dariPct(c.lalu, num(c.view.value)));
                    render(c, false);
                } else {
                    setNom(c, digits(c.view.value));
                    render(c, true);
                }
                total();
            }));

            // ketik di field "isi semua bulan"
            sama.addEventListener('input', () => {
                sama.value = isPersen() ? sama.value.replace(/[^0-9,\-]/g, '')
                                        : (digits(sama.value) ? digits(sama.value).toLocaleString('id-ID') : '');
            });

            pane.querySelector('.btn-samakan').onclick = () => {
                if (isPersen()) {
                    const p = num(sama.value);
                    cells.forEach(c => { if (c.lalu) { setNom(c, dariPct(c.lalu, p)); render(c); } });
                } else {
                    const v = digits(sama.value);
                    cells.forEach(c => { setNom(c, v); render(c); });
                }
                total();
            };
            btnBagi.onclick = () => {
                const v = Math.floor(digits(sama.value) / 12);
                cells.forEach(c => { setNom(c, v); render(c); });
                total();
            };

            // ganti mode Nominal <-> Persentase
            pane.querySelectorAll('.df-seg button').forEach(b => b.onclick = () => {
                pane.dataset.mode = b.dataset.mode;
                pane.querySelectorAll('.df-seg button').forEach(x => x.classList.toggle('on', x === b));
                pane.querySelectorAll('.unit').forEach(u => u.textContent = isPersen() ? '%' : 'Rp');
                labelSm.textContent = isPersen() ? `Isi semua bulan dengan pertumbuhan dari ${TAHUN_LALU}:` : 'Isi semua bulan sekaligus:';
                btnBagi.style.display = isPersen() ? 'none' : '';
                sama.value = '';
                cells.forEach(c => render(c, true));
            });

            cells.forEach(c => render(c, true));
            total();
        });
    })();
</script>
@endpush