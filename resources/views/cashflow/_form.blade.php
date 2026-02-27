{{-- =========================================================
     FORM 2-TAB:  Tab 1 = Neraca  |  Tab 2 = Arus Kas
     ========================================================= --}}

@php
    $neracaCategories = \App\Models\CashflowReport::NERACA_CATEGORIES;
    $arusKasCategories = \App\Models\CashflowReport::CATEGORIES;

    $neracaCatColors = [
        'aset_likuid_tabungan' => '#10b981',
        'aset_likuid_others' => '#059669',
        'aset_investasi_neraca' => '#3b82f6',
        'aset_investasi_belum_cair' => '#6366f1',
        'aset_pribadi' => '#8b5cf6',
        'neraca_utang_pendek' => '#f43f5e',
        'neraca_utang_panjang' => '#dc2626',
    ];

    $arusKasCatColors = [
        'uang_masuk_tetap' => '#10b981',
        'uang_masuk_tidak_tetap' => '#059669',
        'pengeluaran_rt' => '#f59e0b',
        'pengeluaran_konsumtif' => '#f97316',
        'pengeluaran_pendidikan' => '#3b82f6',
        'gaya_hidup' => '#8b5cf6',
        'utang_pendek' => '#f43f5e',
        'utang_panjang' => '#dc2626',
        'investasi' => '#06b6d4',
        'proteksi' => '#6366f1',
    ];
@endphp

{{-- Info Header --}}
<div class="fp-card mb-4">
    <div class="fp-card-header">
        <span class="header-icon" style="background: var(--gradient-blue);"><i class="bi bi-person-lines-fill"></i></span>
        Informasi Umum
    </div>
    <div class="fp-card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label-fp">Nama</label>
                <input type="text" name="nama" class="form-control form-control-fp"
                       value="{{ old('nama', $report->nama ?? '') }}" required placeholder="Nama lengkap">
            </div>
            <div class="col-md-3">
                <label class="form-label-fp">Bulan</label>
                <select name="bulan" class="form-control form-control-fp" required>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bln)
                        <option value="{{ $bln }}" {{ old('bulan', $report->bulan ?? '') == $bln ? 'selected' : '' }}>{{ $bln }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label-fp">Tahun</label>
                <input type="text" name="tahun" class="form-control form-control-fp"
                       value="{{ old('tahun', $report->tahun ?? date('Y')) }}" required placeholder="2026">
            </div>
        </div>
    </div>
</div>

{{-- TAB NAVIGATION --}}
<div class="fp-tabs" id="formTabs">
    <button type="button" class="fp-tab active" data-tab="neraca" onclick="switchFormTab('neraca')">
        <span class="tab-num">1</span>
        <i class="bi bi-bank"></i> Neraca
    </button>
    <button type="button" class="fp-tab" data-tab="aruskas" onclick="switchFormTab('aruskas')">
        <span class="tab-num">2</span>
        <i class="bi bi-cash-stack"></i> Arus Kas
    </button>
</div>

{{-- ===================== TAB 1: NERACA ===================== --}}
<div class="tab-content-fp" id="tab-neraca">
    <div class="neraca-grid">
        {{-- LEFT: ASET --}}
        <div>
            <div class="neraca-col-header header-aset">
                <i class="bi bi-safe me-2"></i> ASET
            </div>
            @foreach($neracaCategories as $catKey => $catMeta)
                @if($catMeta['section'] === 'aset')
                    @php
                        $existing = isset($report) ? $report->itemsByCategory($catKey) : collect();
                        $items = $existing->count() > 0
                            ? $existing->map(fn($i) => ['label' => $i->label, 'amount' => $i->amount])
                            : collect($catMeta['defaults'])->map(fn($d) => ['label' => $d, 'amount' => 0]);
                    @endphp
                    <div class="cat-section" style="border-radius: 0; margin-bottom: 0; border-left: 3px solid {{ $neracaCatColors[$catKey] }};">
                        <div class="cat-section-header">
                            <div class="cat-label">
                                <span class="cat-dot" style="background: {{ $neracaCatColors[$catKey] }};"></span>
                                {{ $catMeta['label'] }}
                            </div>
                            <button type="button" class="btn btn-sm btn-add-item btn-outline-secondary"
                                    onclick="addRow(this, '{{ $catKey }}')">
                                <i class="bi bi-plus-lg"></i> Tambah
                            </button>
                        </div>
                        <div class="cat-section-body" id="section-{{ $catKey }}">
                            @foreach($items as $item)
                                <div class="item-row">
                                    <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][label]"
                                           class="form-control" value="{{ $item['label'] }}" placeholder="Nama item">
                                    <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][amount]"
                                           class="form-control input-amount" style="max-width:180px;"
                                           value="{{ $item['amount'] > 0 ? number_format($item['amount'], 0, ',', '.') : '' }}"
                                           placeholder="0" oninput="formatCurrency(this)">
                                    <button type="button" class="btn-remove-item" onclick="removeRow(this)">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- RIGHT: KEWAJIBAN --}}
        <div>
            <div class="neraca-col-header header-kewajiban">
                <i class="bi bi-credit-card me-2"></i> KEWAJIBAN (UTANG)
            </div>
            @foreach($neracaCategories as $catKey => $catMeta)
                @if($catMeta['section'] === 'kewajiban')
                    @php
                        $existing = isset($report) ? $report->itemsByCategory($catKey) : collect();
                        $items = $existing->count() > 0
                            ? $existing->map(fn($i) => ['label' => $i->label, 'amount' => $i->amount])
                            : collect($catMeta['defaults'])->map(fn($d) => ['label' => $d, 'amount' => 0]);
                    @endphp
                    <div class="cat-section" style="border-radius: 0; margin-bottom: 0; border-left: 3px solid {{ $neracaCatColors[$catKey] }};">
                        <div class="cat-section-header">
                            <div class="cat-label">
                                <span class="cat-dot" style="background: {{ $neracaCatColors[$catKey] }};"></span>
                                {{ $catMeta['label'] }}
                            </div>
                            <button type="button" class="btn btn-sm btn-add-item btn-outline-secondary"
                                    onclick="addRow(this, '{{ $catKey }}')">
                                <i class="bi bi-plus-lg"></i> Tambah
                            </button>
                        </div>
                        <div class="cat-section-body" id="section-{{ $catKey }}">
                            @foreach($items as $item)
                                <div class="item-row">
                                    <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][label]"
                                           class="form-control" value="{{ $item['label'] }}" placeholder="Nama item">
                                    <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][amount]"
                                           class="form-control input-amount" style="max-width:180px;"
                                           value="{{ $item['amount'] > 0 ? number_format($item['amount'], 0, ',', '.') : '' }}"
                                           placeholder="0" oninput="formatCurrency(this)">
                                    <button type="button" class="btn-remove-item" onclick="removeRow(this)">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

{{-- ===================== TAB 2: ARUS KAS ===================== --}}
<div class="tab-content-fp" id="tab-aruskas" style="display:none;">
    <div class="row g-4">
        {{-- LEFT: UANG MASUK --}}
        <div class="col-lg-5">
            <div class="fp-card">
                <div class="fp-card-header" style="background: linear-gradient(135deg, #059669, #10b981); color:#fff; border:0;">
                    <i class="bi bi-arrow-down-circle me-2"></i> DOMPET UANG MASUK
                </div>
                @foreach($arusKasCategories as $catKey => $catMeta)
                    @if($catMeta['group'] === 'income')
                        @php
                            $existing = isset($report) ? $report->itemsByCategory($catKey) : collect();
                            $items = $existing->count() > 0
                                ? $existing->map(fn($i) => ['label' => $i->label, 'amount' => $i->amount])
                                : collect($catMeta['defaults'])->map(fn($d) => ['label' => $d, 'amount' => 0]);
                        @endphp
                        <div class="cat-section" style="border-radius:0; margin:0; border-left:3px solid {{ $arusKasCatColors[$catKey] }};">
                            <div class="cat-section-header">
                                <div class="cat-label">
                                    <span class="cat-dot" style="background:{{ $arusKasCatColors[$catKey] }};"></span>
                                    {{ $catMeta['label'] }}
                                </div>
                                <button type="button" class="btn btn-sm btn-add-item btn-outline-secondary"
                                        onclick="addRow(this, '{{ $catKey }}')">
                                    <i class="bi bi-plus-lg"></i> Tambah
                                </button>
                            </div>
                            <div class="cat-section-body" id="section-{{ $catKey }}">
                                @foreach($items as $item)
                                    <div class="item-row">
                                        <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][label]"
                                               class="form-control" value="{{ $item['label'] }}" placeholder="Nama item">
                                        <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][amount]"
                                               class="form-control input-amount" style="max-width:160px;"
                                               value="{{ $item['amount'] > 0 ? number_format($item['amount'], 0, ',', '.') : '' }}"
                                               placeholder="0" oninput="formatCurrency(this)">
                                        <button type="button" class="btn-remove-item" onclick="removeRow(this)">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- RIGHT: UANG KELUAR --}}
        <div class="col-lg-7">
            <div class="fp-card">
                <div class="fp-card-header" style="background: linear-gradient(135deg, #dc2626, #f43f5e); color:#fff; border:0;">
                    <i class="bi bi-arrow-up-circle me-2"></i> DOMPET UANG KELUAR
                </div>
                @foreach($arusKasCategories as $catKey => $catMeta)
                    @if($catMeta['group'] === 'expense')
                        @php
                            $existing = isset($report) ? $report->itemsByCategory($catKey) : collect();
                            $items = $existing->count() > 0
                                ? $existing->map(fn($i) => ['label' => $i->label, 'amount' => $i->amount])
                                : collect($catMeta['defaults'])->map(fn($d) => ['label' => $d, 'amount' => 0]);
                            $idealMap = [
                                'pengeluaran_rt' => null,
                                'pengeluaran_konsumtif' => '40%',
                                'pengeluaran_pendidikan' => '10%',
                                'gaya_hidup' => '5%',
                                'utang_pendek' => '15%',
                                'utang_panjang' => '15%',
                                'investasi' => '10%',
                                'proteksi' => '5%',
                            ];
                            $ideal = $idealMap[$catKey] ?? null;
                        @endphp
                        <div class="cat-section" style="border-radius:0; margin:0; border-left:3px solid {{ $arusKasCatColors[$catKey] }};">
                            <div class="cat-section-header">
                                <div class="cat-label">
                                    <span class="cat-dot" style="background:{{ $arusKasCatColors[$catKey] }};"></span>
                                    {{ $catMeta['label'] }}
                                    @if($ideal)
                                        <span class="badge bg-light text-dark border" style="font-size:.65rem; font-weight:600;">Ideal: {{ $ideal }}</span>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-add-item btn-outline-secondary"
                                        onclick="addRow(this, '{{ $catKey }}')">
                                    <i class="bi bi-plus-lg"></i> Tambah
                                </button>
                            </div>
                            <div class="cat-section-body" id="section-{{ $catKey }}">
                                @foreach($items as $item)
                                    <div class="item-row">
                                        <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][label]"
                                               class="form-control" value="{{ $item['label'] }}" placeholder="Nama item">
                                        <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][amount]"
                                               class="form-control input-amount" style="max-width:160px;"
                                               value="{{ $item['amount'] > 0 ? number_format($item['amount'], 0, ',', '.') : '' }}"
                                               placeholder="0" oninput="formatCurrency(this)">
                                        <button type="button" class="btn-remove-item" onclick="removeRow(this)">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ===================== SUBMIT ===================== --}}
<div class="text-center mt-4 mb-3">
    <button type="submit" class="btn btn-fp btn-fp-primary btn-fp-lg">
        <i class="bi bi-check-circle me-2"></i> {{ isset($report) && $report->exists ? 'Simpan Perubahan' : 'Buat Laporan Financial Check Up' }}
    </button>
    <a href="{{ route('cashflow.index') }}" class="btn btn-fp btn-fp-outline btn-fp-lg ms-2">Batal</a>
</div>

@push('scripts')
<script>
    // ===== Tab switching =====
    function switchFormTab(tab) {
        document.querySelectorAll('.tab-content-fp').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.fp-tab').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + tab).style.display = 'block';
        document.querySelector('.fp-tab[data-tab="' + tab + '"]').classList.add('active');
    }

    // ===== Add row =====
    function addRow(btn, category) {
        const section = document.getElementById('section-' + category);
        const idx = section.querySelectorAll('.item-row').length;
        const row = document.createElement('div');
        row.className = 'item-row';
        row.innerHTML = `
            <input type="text" name="items[${category}][${idx}][label]"
                   class="form-control" placeholder="Nama item baru">
            <input type="text" name="items[${category}][${idx}][amount]"
                   class="form-control input-amount" style="max-width:180px;"
                   placeholder="0" oninput="formatCurrency(this)">
            <button type="button" class="btn-remove-item" onclick="removeRow(this)">
                <i class="bi bi-x"></i>
            </button>
        `;
        section.appendChild(row);
        row.querySelector('input').focus();
    }

    // ===== Remove row =====
    function removeRow(btn) {
        const row = btn.closest('.item-row');
        const section = row.parentElement;
        row.remove();
        // Re-index
        section.querySelectorAll('.item-row').forEach((r, i) => {
            r.querySelectorAll('input').forEach(inp => {
                inp.name = inp.name.replace(/\[\d+\]/, `[${i}]`);
            });
        });
    }

    // ===== Format currency =====
    function formatCurrency(input) {
        let val = input.value.replace(/[^0-9]/g, '');
        if (val === '') { input.value = ''; return; }
        input.value = parseInt(val).toLocaleString('id-ID');
    }
</script>
@endpush
