{{-- =========================================================
     FORM 2-TAB:  Tab 1 = Neraca  |  Tab 2 = Arus Kas
     Enhanced UX: collapsible, live totals, Rp prefix, keyboard nav
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

    $neracaCatIcons = [
        'aset_likuid_tabungan' => 'bi-piggy-bank',
        'aset_likuid_others' => 'bi-wallet2',
        'aset_investasi_neraca' => 'bi-bar-chart-line',
        'aset_investasi_belum_cair' => 'bi-hourglass-split',
        'aset_pribadi' => 'bi-house-heart',
        'neraca_utang_pendek' => 'bi-credit-card-2-front',
        'neraca_utang_panjang' => 'bi-calendar3-range',
    ];
    $arusKasCatIcons = [
        'uang_masuk_tetap' => 'bi-cash-coin',
        'uang_masuk_tidak_tetap' => 'bi-cash',
        'pengeluaran_rt' => 'bi-house-door',
        'pengeluaran_konsumtif' => 'bi-cart3',
        'pengeluaran_pendidikan' => 'bi-mortarboard',
        'gaya_hidup' => 'bi-emoji-sunglasses',
        'utang_pendek' => 'bi-credit-card-2-front',
        'utang_panjang' => 'bi-calendar3-range',
        'investasi' => 'bi-graph-up-arrow',
        'proteksi' => 'bi-shield-check',
    ];
@endphp

{{-- ============ STEP PROGRESS BAR ============ --}}
<div class="form-stepper mb-4">
    <div class="stepper-track">
        <div class="stepper-step active" data-step="info">
            <div class="step-circle"><i class="bi bi-person-lines-fill"></i></div>
            <span class="step-label">Info</span>
        </div>
        <div class="stepper-line" id="line-info-neraca"><div class="stepper-line-fill"></div></div>
        <div class="stepper-step" data-step="neraca">
            <div class="step-circle"><i class="bi bi-bank"></i></div>
            <span class="step-label">Neraca</span>
        </div>
        <div class="stepper-line" id="line-neraca-aruskas"><div class="stepper-line-fill"></div></div>
        <div class="stepper-step" data-step="aruskas">
            <div class="step-circle"><i class="bi bi-cash-stack"></i></div>
            <span class="step-label">Arus Kas</span>
        </div>
    </div>
</div>

{{-- ============ STEP 1: Info Header ============ --}}
<div class="fp-card mb-4" id="section-info">
    <div class="fp-card-header">
        <span class="header-icon" style="background: var(--gradient-blue);"><i class="bi bi-person-lines-fill"></i></span>
        Informasi Umum
    </div>
    <div class="fp-card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label-fp">Nama</label>
                <div class="input-group input-group-fp">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="nama" class="form-control form-control-fp"
                           value="{{ old('nama', $report->nama ?? '') }}" required placeholder="Nama lengkap"
                           data-field="info">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label-fp">Bulan</label>
                <div class="input-group input-group-fp">
                    <span class="input-group-text"><i class="bi bi-calendar-month"></i></span>
                    <select name="bulan" class="form-control form-control-fp" required data-field="info">
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bln)
                            <option value="{{ $bln }}" {{ old('bulan', $report->bulan ?? '') == $bln ? 'selected' : '' }}>{{ $bln }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label-fp">Tahun</label>
                <div class="input-group input-group-fp">
                    <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                    <input type="text" name="tahun" class="form-control form-control-fp"
                           value="{{ old('tahun', $report->tahun ?? date('Y')) }}" required placeholder="2026"
                           data-field="info">
                </div>
            </div>
        </div>
        {{-- Hint --}}
        <div class="form-hint mt-3">
            <i class="bi bi-lightbulb text-warning me-1"></i>
            <small class="text-muted">Lengkapi data di atas, lalu isi tab <strong>Neraca</strong> dan <strong>Arus Kas</strong>. Klik header kategori untuk buka/tutup. Tekan <kbd>Enter</kbd> untuk pindah ke kolom berikutnya.</small>
        </div>
    </div>
</div>

{{-- ============ TAB NAVIGATION ============ --}}
<div class="fp-tabs" id="formTabs">
    <button type="button" class="fp-tab active" data-tab="neraca" onclick="switchFormTab('neraca')">
        <span class="tab-num">1</span>
        <i class="bi bi-bank"></i> Neraca
        <span class="tab-badge" id="badge-neraca">0 item</span>
    </button>
    <button type="button" class="fp-tab" data-tab="aruskas" onclick="switchFormTab('aruskas')">
        <span class="tab-num">2</span>
        <i class="bi bi-cash-stack"></i> Arus Kas
        <span class="tab-badge" id="badge-aruskas">0 item</span>
    </button>
</div>

{{-- ===================== TAB 1: NERACA ===================== --}}
<div class="tab-content-fp" id="tab-neraca">
    <div class="neraca-grid">
        {{-- LEFT: ASET --}}
        <div>
            <div class="neraca-col-header header-aset">
                <i class="bi bi-safe me-2"></i> ASET
                <span class="col-header-total ms-auto" id="aset-grand-total">Rp 0</span>
            </div>
            @foreach($neracaCategories as $catKey => $catMeta)
                @if($catMeta['section'] === 'aset')
                    @php
                        $existing = isset($report) ? $report->itemsByCategory($catKey) : collect();
                        $items = $existing->count() > 0
                            ? $existing->map(fn($i) => ['label' => $i->label, 'amount' => $i->amount])
                            : collect($catMeta['defaults'])->map(fn($d) => ['label' => $d, 'amount' => 0]);
                        $hasValues = $items->contains(fn($i) => $i['amount'] > 0);
                    @endphp
                    <div class="cat-section cat-collapsible {{ $hasValues ? 'cat-open' : '' }}" style="border-radius: 0; margin-bottom: 0; border-left: 3px solid {{ $neracaCatColors[$catKey] }};" data-category="{{ $catKey }}" data-tab="neraca" data-section="aset">
                        <div class="cat-section-header" onclick="toggleSection(this)">
                            <div class="cat-label">
                                <span class="cat-icon-wrap" style="background: {{ $neracaCatColors[$catKey] }}15; color: {{ $neracaCatColors[$catKey] }};"><i class="bi {{ $neracaCatIcons[$catKey] }}"></i></span>
                                {{ $catMeta['label'] }}
                                <span class="cat-count-badge" id="count-{{ $catKey }}">{{ $items->count() }} item</span>
                            </div>
                            <div class="cat-header-right">
                                <span class="cat-subtotal mono" id="subtotal-{{ $catKey }}">Rp 0</span>
                                <i class="bi bi-chevron-down cat-chevron"></i>
                            </div>
                        </div>
                        <div class="cat-section-body" id="section-{{ $catKey }}">
                            @foreach($items as $item)
                                <div class="item-row" style="animation-delay: {{ $loop->index * 30 }}ms;">
                                    <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][label]"
                                           class="form-control" value="{{ $item['label'] }}" placeholder="Nama item"
                                           onkeydown="handleEnterKey(event)">
                                    <div class="input-group input-group-amount">
                                        <span class="input-group-text rp-prefix">Rp</span>
                                        <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][amount]"
                                               class="form-control input-amount"
                                               value="{{ $item['amount'] > 0 ? number_format($item['amount'], 0, ',', '.') : '' }}"
                                               placeholder="0" oninput="formatCurrency(this); updateSubtotals();"
                                               onkeydown="handleEnterKey(event)"
                                               onfocus="this.select()">
                                    </div>
                                    <div class="quick-fill-wrap">
                                        <button type="button" class="btn-quick-fill" onclick="quickFill(this, 1000000)" title="1 Juta">1jt</button>
                                        <button type="button" class="btn-quick-fill" onclick="quickFill(this, 5000000)" title="5 Juta">5jt</button>
                                        <button type="button" class="btn-quick-fill" onclick="quickFill(this, 10000000)" title="10 Juta">10jt</button>
                                    </div>
                                    <button type="button" class="btn-remove-item" onclick="removeRow(this)" title="Hapus item">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <div class="cat-section-footer">
                            <button type="button" class="btn btn-sm btn-add-item-new"
                                    onclick="addRow(this, '{{ $catKey }}')" style="--cat-color: {{ $neracaCatColors[$catKey] }};">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Item
                            </button>
                            <span class="cat-footer-total mono" id="ftotal-{{ $catKey }}">Subtotal: Rp 0</span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- RIGHT: KEWAJIBAN --}}
        <div>
            <div class="neraca-col-header header-kewajiban">
                <i class="bi bi-credit-card me-2"></i> KEWAJIBAN (UTANG)
                <span class="col-header-total ms-auto" id="kewajiban-grand-total">Rp 0</span>
            </div>
            @foreach($neracaCategories as $catKey => $catMeta)
                @if($catMeta['section'] === 'kewajiban')
                    @php
                        $existing = isset($report) ? $report->itemsByCategory($catKey) : collect();
                        $items = $existing->count() > 0
                            ? $existing->map(fn($i) => ['label' => $i->label, 'amount' => $i->amount])
                            : collect($catMeta['defaults'])->map(fn($d) => ['label' => $d, 'amount' => 0]);
                        $hasValues = $items->contains(fn($i) => $i['amount'] > 0);
                    @endphp
                    <div class="cat-section cat-collapsible {{ $hasValues ? 'cat-open' : '' }}" style="border-radius: 0; margin-bottom: 0; border-left: 3px solid {{ $neracaCatColors[$catKey] }};" data-category="{{ $catKey }}" data-tab="neraca" data-section="kewajiban">
                        <div class="cat-section-header" onclick="toggleSection(this)">
                            <div class="cat-label">
                                <span class="cat-icon-wrap" style="background: {{ $neracaCatColors[$catKey] }}15; color: {{ $neracaCatColors[$catKey] }};"><i class="bi {{ $neracaCatIcons[$catKey] }}"></i></span>
                                {{ $catMeta['label'] }}
                                <span class="cat-count-badge" id="count-{{ $catKey }}">{{ $items->count() }} item</span>
                            </div>
                            <div class="cat-header-right">
                                <span class="cat-subtotal mono" id="subtotal-{{ $catKey }}">Rp 0</span>
                                <i class="bi bi-chevron-down cat-chevron"></i>
                            </div>
                        </div>
                        <div class="cat-section-body" id="section-{{ $catKey }}">
                            @foreach($items as $item)
                                <div class="item-row" style="animation-delay: {{ $loop->index * 30 }}ms;">
                                    <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][label]"
                                           class="form-control" value="{{ $item['label'] }}" placeholder="Nama item"
                                           onkeydown="handleEnterKey(event)">
                                    <div class="input-group input-group-amount">
                                        <span class="input-group-text rp-prefix">Rp</span>
                                        <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][amount]"
                                               class="form-control input-amount"
                                               value="{{ $item['amount'] > 0 ? number_format($item['amount'], 0, ',', '.') : '' }}"
                                               placeholder="0" oninput="formatCurrency(this); updateSubtotals();"
                                               onkeydown="handleEnterKey(event)"
                                               onfocus="this.select()">
                                    </div>
                                    <div class="quick-fill-wrap">
                                        <button type="button" class="btn-quick-fill" onclick="quickFill(this, 1000000)" title="1 Juta">1jt</button>
                                        <button type="button" class="btn-quick-fill" onclick="quickFill(this, 5000000)" title="5 Juta">5jt</button>
                                        <button type="button" class="btn-quick-fill" onclick="quickFill(this, 10000000)" title="10 Juta">10jt</button>
                                    </div>
                                    <button type="button" class="btn-remove-item" onclick="removeRow(this)" title="Hapus item">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <div class="cat-section-footer">
                            <button type="button" class="btn btn-sm btn-add-item-new"
                                    onclick="addRow(this, '{{ $catKey }}')" style="--cat-color: {{ $neracaCatColors[$catKey] }};">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Item
                            </button>
                            <span class="cat-footer-total mono" id="ftotal-{{ $catKey }}">Subtotal: Rp 0</span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Navigate to next tab --}}
    <div class="text-center mt-4">
        <button type="button" class="btn btn-fp btn-fp-primary" onclick="switchFormTab('aruskas')">
            Lanjut ke Arus Kas <i class="bi bi-arrow-right ms-1"></i>
        </button>
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
                    <span class="col-header-total ms-auto" id="income-grand-total">Rp 0</span>
                </div>
                @foreach($arusKasCategories as $catKey => $catMeta)
                    @if($catMeta['group'] === 'income')
                        @php
                            $existing = isset($report) ? $report->itemsByCategory($catKey) : collect();
                            $items = $existing->count() > 0
                                ? $existing->map(fn($i) => ['label' => $i->label, 'amount' => $i->amount])
                                : collect($catMeta['defaults'])->map(fn($d) => ['label' => $d, 'amount' => 0]);
                            $hasValues = $items->contains(fn($i) => $i['amount'] > 0);
                        @endphp
                        <div class="cat-section cat-collapsible {{ $hasValues ? 'cat-open' : '' }}" style="border-radius:0; margin:0; border-left:3px solid {{ $arusKasCatColors[$catKey] }};" data-category="{{ $catKey }}" data-tab="aruskas" data-group="income">
                            <div class="cat-section-header" onclick="toggleSection(this)">
                                <div class="cat-label">
                                    <span class="cat-icon-wrap" style="background: {{ $arusKasCatColors[$catKey] }}15; color: {{ $arusKasCatColors[$catKey] }};"><i class="bi {{ $arusKasCatIcons[$catKey] }}"></i></span>
                                    {{ $catMeta['label'] }}
                                    <span class="cat-count-badge" id="count-{{ $catKey }}">{{ $items->count() }} item</span>
                                </div>
                                <div class="cat-header-right">
                                    <span class="cat-subtotal mono" id="subtotal-{{ $catKey }}">Rp 0</span>
                                    <i class="bi bi-chevron-down cat-chevron"></i>
                                </div>
                            </div>
                            <div class="cat-section-body" id="section-{{ $catKey }}">
                                @foreach($items as $item)
                                    <div class="item-row" style="animation-delay: {{ $loop->index * 30 }}ms;">
                                        <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][label]"
                                               class="form-control" value="{{ $item['label'] }}" placeholder="Nama item"
                                               onkeydown="handleEnterKey(event)">
                                        <div class="input-group input-group-amount">
                                            <span class="input-group-text rp-prefix">Rp</span>
                                            <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][amount]"
                                                   class="form-control input-amount"
                                                   value="{{ $item['amount'] > 0 ? number_format($item['amount'], 0, ',', '.') : '' }}"
                                                   placeholder="0" oninput="formatCurrency(this); updateSubtotals();"
                                                   onkeydown="handleEnterKey(event)"
                                                   onfocus="this.select()">
                                        </div>
                                        <div class="quick-fill-wrap">
                                            <button type="button" class="btn-quick-fill" onclick="quickFill(this, 1000000)" title="1 Juta">1jt</button>
                                            <button type="button" class="btn-quick-fill" onclick="quickFill(this, 5000000)" title="5 Juta">5jt</button>
                                        </div>
                                        <button type="button" class="btn-remove-item" onclick="removeRow(this)" title="Hapus item">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <div class="cat-section-footer">
                                <button type="button" class="btn btn-sm btn-add-item-new"
                                        onclick="addRow(this, '{{ $catKey }}')" style="--cat-color: {{ $arusKasCatColors[$catKey] }};">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Item
                                </button>
                                <span class="cat-footer-total mono" id="ftotal-{{ $catKey }}">Subtotal: Rp 0</span>
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
                    <span class="col-header-total ms-auto" id="expense-grand-total">Rp 0</span>
                </div>
                @foreach($arusKasCategories as $catKey => $catMeta)
                    @if($catMeta['group'] === 'expense')
                        @php
                            $existing = isset($report) ? $report->itemsByCategory($catKey) : collect();
                            $items = $existing->count() > 0
                                ? $existing->map(fn($i) => ['label' => $i->label, 'amount' => $i->amount])
                                : collect($catMeta['defaults'])->map(fn($d) => ['label' => $d, 'amount' => 0]);
                            $hasValues = $items->contains(fn($i) => $i['amount'] > 0);
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
                        <div class="cat-section cat-collapsible {{ $hasValues ? 'cat-open' : '' }}" style="border-radius:0; margin:0; border-left:3px solid {{ $arusKasCatColors[$catKey] }};" data-category="{{ $catKey }}" data-tab="aruskas" data-group="expense">
                            <div class="cat-section-header" onclick="toggleSection(this)">
                                <div class="cat-label">
                                    <span class="cat-icon-wrap" style="background: {{ $arusKasCatColors[$catKey] }}15; color: {{ $arusKasCatColors[$catKey] }};"><i class="bi {{ $arusKasCatIcons[$catKey] }}"></i></span>
                                    {{ $catMeta['label'] }}
                                    @if($ideal)
                                        <span class="ideal-badge">Ideal {{ $ideal }}</span>
                                    @endif
                                    <span class="cat-count-badge" id="count-{{ $catKey }}">{{ $items->count() }} item</span>
                                </div>
                                <div class="cat-header-right">
                                    <span class="cat-subtotal mono" id="subtotal-{{ $catKey }}">Rp 0</span>
                                    <i class="bi bi-chevron-down cat-chevron"></i>
                                </div>
                            </div>
                            <div class="cat-section-body" id="section-{{ $catKey }}">
                                @foreach($items as $item)
                                    <div class="item-row" style="animation-delay: {{ $loop->index * 30 }}ms;">
                                        <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][label]"
                                               class="form-control" value="{{ $item['label'] }}" placeholder="Nama item"
                                               onkeydown="handleEnterKey(event)">
                                        <div class="input-group input-group-amount">
                                            <span class="input-group-text rp-prefix">Rp</span>
                                            <input type="text" name="items[{{ $catKey }}][{{ $loop->index }}][amount]"
                                                   class="form-control input-amount"
                                                   value="{{ $item['amount'] > 0 ? number_format($item['amount'], 0, ',', '.') : '' }}"
                                                   placeholder="0" oninput="formatCurrency(this); updateSubtotals();"
                                                   onkeydown="handleEnterKey(event)"
                                                   onfocus="this.select()">
                                        </div>
                                        <div class="quick-fill-wrap">
                                            <button type="button" class="btn-quick-fill" onclick="quickFill(this, 500000)" title="500 Ribu">500k</button>
                                            <button type="button" class="btn-quick-fill" onclick="quickFill(this, 1000000)" title="1 Juta">1jt</button>
                                            <button type="button" class="btn-quick-fill" onclick="quickFill(this, 5000000)" title="5 Juta">5jt</button>
                                        </div>
                                        <button type="button" class="btn-remove-item" onclick="removeRow(this)" title="Hapus item">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <div class="cat-section-footer">
                                <button type="button" class="btn btn-sm btn-add-item-new"
                                        onclick="addRow(this, '{{ $catKey }}')" style="--cat-color: {{ $arusKasCatColors[$catKey] }};">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Item
                                </button>
                                <span class="cat-footer-total mono" id="ftotal-{{ $catKey }}">Subtotal: Rp 0</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Navigate back --}}
    <div class="text-center mt-4">
        <button type="button" class="btn btn-fp btn-fp-outline me-2" onclick="switchFormTab('neraca')">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Neraca
        </button>
    </div>
</div>

{{-- ===================== FLOATING SUMMARY BAR ===================== --}}
<div class="floating-summary" id="floatingSummary">
    <div class="container">
        <div class="summary-content">
            <div class="summary-items">
                <div class="summary-item summary-aset">
                    <span class="summary-label">Total Aset</span>
                    <span class="summary-value" id="sum-total-aset">Rp 0</span>
                </div>
                <div class="summary-divider">-</div>
                <div class="summary-item summary-utang">
                    <span class="summary-label">Total Utang</span>
                    <span class="summary-value" id="sum-total-utang">Rp 0</span>
                </div>
                <div class="summary-divider">=</div>
                <div class="summary-item summary-nw">
                    <span class="summary-label">Kekayaan Bersih</span>
                    <span class="summary-value" id="sum-net-worth">Rp 0</span>
                </div>
            </div>
            <div class="summary-actions">
                <span class="filled-count" id="filled-count">0 dari 0 terisi</span>
                <button type="submit" class="btn btn-fp btn-fp-primary btn-fp-md">
                    <i class="bi bi-check-circle me-1"></i> {{ isset($report) && $report->exists ? 'Simpan' : 'Buat Laporan' }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===================== SUBMIT (below form, full) ===================== --}}
<div class="text-center mt-4 mb-5 pb-5">
    <button type="submit" class="btn btn-fp btn-fp-primary btn-fp-lg">
        <i class="bi bi-check-circle me-2"></i> {{ isset($report) && $report->exists ? 'Simpan Perubahan' : 'Buat Laporan Financial Check Up' }}
    </button>
    <a href="{{ route('cashflow.index') }}" class="btn btn-fp btn-fp-outline btn-fp-lg ms-2">Batal</a>
</div>

@push('scripts')
<script>
    // ===== Tab switching with stepper update =====
    function switchFormTab(tab) {
        document.querySelectorAll('.tab-content-fp').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.fp-tab').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + tab).style.display = 'block';
        document.querySelector('.fp-tab[data-tab="' + tab + '"]').classList.add('active');

        // Update stepper
        const steps = document.querySelectorAll('.stepper-step');
        const stepOrder = ['info', 'neraca', 'aruskas'];
        const activeIdx = stepOrder.indexOf(tab);
        steps.forEach((s, i) => {
            s.classList.toggle('active', i <= activeIdx);
            s.classList.toggle('completed', i < activeIdx);
        });
        document.querySelectorAll('.stepper-line').forEach((l, i) => {
            l.classList.toggle('line-filled', i < activeIdx);
        });

        // Smooth scroll to top of tabs
        document.getElementById('formTabs').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // ===== Collapsible sections =====
    function toggleSection(header) {
        const section = header.closest('.cat-collapsible');
        section.classList.toggle('cat-open');
    }

    // Expand all / collapse all per column
    function expandAll(container) {
        container.querySelectorAll('.cat-collapsible').forEach(s => s.classList.add('cat-open'));
    }
    function collapseAll(container) {
        container.querySelectorAll('.cat-collapsible').forEach(s => s.classList.remove('cat-open'));
    }

    // ===== Add row (enhanced with animation) =====
    function addRow(btn, category) {
        const catSection = btn.closest('.cat-collapsible');
        if (!catSection.classList.contains('cat-open')) {
            catSection.classList.add('cat-open');
        }
        const section = document.getElementById('section-' + category);
        const idx = section.querySelectorAll('.item-row').length;
        const row = document.createElement('div');
        row.className = 'item-row item-row-enter';
        row.innerHTML = `
            <input type="text" name="items[${category}][${idx}][label]"
                   class="form-control" placeholder="Nama item baru"
                   onkeydown="handleEnterKey(event)">
            <div class="input-group input-group-amount">
                <span class="input-group-text rp-prefix">Rp</span>
                <input type="text" name="items[${category}][${idx}][amount]"
                       class="form-control input-amount"
                       placeholder="0" oninput="formatCurrency(this); updateSubtotals();"
                       onkeydown="handleEnterKey(event)"
                       onfocus="this.select()">
            </div>
            <div class="quick-fill-wrap">
                <button type="button" class="btn-quick-fill" onclick="quickFill(this, 500000)" title="500 Ribu">500k</button>
                <button type="button" class="btn-quick-fill" onclick="quickFill(this, 1000000)" title="1 Juta">1jt</button>
                <button type="button" class="btn-quick-fill" onclick="quickFill(this, 5000000)" title="5 Juta">5jt</button>
            </div>
            <button type="button" class="btn-remove-item" onclick="removeRow(this)" title="Hapus item">
                <i class="bi bi-x"></i>
            </button>
        `;
        section.appendChild(row);
        // Scroll into view & focus
        requestAnimationFrame(() => {
            row.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            row.querySelector('input').focus();
            row.classList.remove('item-row-enter');
        });
        updateCounts();
    }

    // ===== Remove row (with animation) =====
    function removeRow(btn) {
        const row = btn.closest('.item-row');
        const section = row.parentElement;
        row.classList.add('item-row-exit');
        row.addEventListener('animationend', () => {
            row.remove();
            // Re-index
            section.querySelectorAll('.item-row').forEach((r, i) => {
                r.querySelectorAll('input').forEach(inp => {
                    inp.name = inp.name.replace(/\[\d+\]/, `[${i}]`);
                });
            });
            updateSubtotals();
            updateCounts();
        }, { once: true });
    }

    // ===== Format currency =====
    function formatCurrency(input) {
        let val = input.value.replace(/[^0-9]/g, '');
        if (val === '') { input.value = ''; return; }
        input.value = parseInt(val).toLocaleString('id-ID');
    }

    // ===== Quick fill amounts =====
    function quickFill(btn, amount) {
        const row = btn.closest('.item-row');
        const amtInput = row.querySelector('.input-amount');
        amtInput.value = amount.toLocaleString('id-ID');
        amtInput.classList.add('input-flash');
        setTimeout(() => amtInput.classList.remove('input-flash'), 400);
        updateSubtotals();
    }

    // ===== Parse formatted number =====
    function parseAmount(str) {
        if (!str) return 0;
        return parseInt(str.replace(/[^0-9]/g, '')) || 0;
    }

    // ===== Update subtotals per category =====
    function updateSubtotals() {
        const allSections = document.querySelectorAll('.cat-collapsible');
        let totalAset = 0, totalKewajiban = 0, totalIncome = 0, totalExpense = 0;
        let filledCount = 0, totalCount = 0;

        allSections.forEach(sec => {
            const cat = sec.dataset.category;
            const amounts = sec.querySelectorAll('.input-amount');
            let catTotal = 0;
            amounts.forEach(inp => {
                const val = parseAmount(inp.value);
                catTotal += val;
                totalCount++;
                if (val > 0) filledCount++;
            });

            // Update category subtotal
            const subtotalEl = document.getElementById('subtotal-' + cat);
            const ftotalEl = document.getElementById('ftotal-' + cat);
            if (subtotalEl) subtotalEl.textContent = 'Rp ' + catTotal.toLocaleString('id-ID');
            if (ftotalEl) ftotalEl.textContent = 'Subtotal: Rp ' + catTotal.toLocaleString('id-ID');

            // Highlight when has value
            if (subtotalEl) subtotalEl.classList.toggle('has-value', catTotal > 0);

            // Accumulate grand totals
            if (sec.dataset.section === 'aset') totalAset += catTotal;
            if (sec.dataset.section === 'kewajiban') totalKewajiban += catTotal;
            if (sec.dataset.group === 'income') totalIncome += catTotal;
            if (sec.dataset.group === 'expense') totalExpense += catTotal;
        });

        // Update grand totals in headers
        const asetEl = document.getElementById('aset-grand-total');
        const kwjEl = document.getElementById('kewajiban-grand-total');
        const incEl = document.getElementById('income-grand-total');
        const expEl = document.getElementById('expense-grand-total');
        if (asetEl) asetEl.textContent = 'Rp ' + totalAset.toLocaleString('id-ID');
        if (kwjEl) kwjEl.textContent = 'Rp ' + totalKewajiban.toLocaleString('id-ID');
        if (incEl) incEl.textContent = 'Rp ' + totalIncome.toLocaleString('id-ID');
        if (expEl) expEl.textContent = 'Rp ' + totalExpense.toLocaleString('id-ID');

        // Floating summary
        const sumAset = document.getElementById('sum-total-aset');
        const sumUtang = document.getElementById('sum-total-utang');
        const sumNw = document.getElementById('sum-net-worth');
        const filledEl = document.getElementById('filled-count');
        if (sumAset) sumAset.textContent = 'Rp ' + totalAset.toLocaleString('id-ID');
        if (sumUtang) sumUtang.textContent = 'Rp ' + totalKewajiban.toLocaleString('id-ID');
        const nw = totalAset - totalKewajiban;
        if (sumNw) {
            sumNw.textContent = (nw < 0 ? '-' : '') + 'Rp ' + Math.abs(nw).toLocaleString('id-ID');
            sumNw.classList.toggle('text-danger', nw < 0);
            sumNw.classList.toggle('text-success', nw >= 0);
        }
        if (filledEl) filledEl.textContent = filledCount + ' dari ' + totalCount + ' terisi';

        // Tab badges
        updateTabBadges();
    }

    // ===== Update item counts =====
    function updateCounts() {
        document.querySelectorAll('.cat-collapsible').forEach(sec => {
            const cat = sec.dataset.category;
            const count = sec.querySelectorAll('.item-row').length;
            const countEl = document.getElementById('count-' + cat);
            if (countEl) countEl.textContent = count + ' item';
        });
        updateTabBadges();
    }

    // ===== Tab badges (filled count) =====
    function updateTabBadges() {
        ['neraca', 'aruskas'].forEach(tab => {
            let filled = 0;
            document.querySelectorAll(`.cat-collapsible[data-tab="${tab}"] .input-amount`).forEach(inp => {
                if (parseAmount(inp.value) > 0) filled++;
            });
            const badge = document.getElementById('badge-' + tab);
            if (badge) {
                badge.textContent = filled + ' terisi';
                badge.classList.toggle('badge-has-data', filled > 0);
            }
        });
    }

    // ===== Keyboard navigation: Enter → next field =====
    function handleEnterKey(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const inputs = Array.from(document.querySelectorAll(
                '.tab-content-fp:not([style*="display: none"]):not([style*="display:none"]) .item-row input:not([type=hidden])'
            ));
            const idx = inputs.indexOf(e.target);
            if (idx >= 0 && idx < inputs.length - 1) {
                inputs[idx + 1].focus();
                if (inputs[idx + 1].classList.contains('input-amount')) {
                    inputs[idx + 1].select();
                }
            }
        }
    }

    // ===== Show/hide floating summary on scroll =====
    function handleFloatingBar() {
        const bar = document.getElementById('floatingSummary');
        const tabs = document.getElementById('formTabs');
        if (!bar || !tabs) return;
        const tabsRect = tabs.getBoundingClientRect();
        if (tabsRect.top < -100) {
            bar.classList.add('summary-visible');
        } else {
            bar.classList.remove('summary-visible');
        }
    }
    window.addEventListener('scroll', handleFloatingBar, { passive: true });

    // ===== Init: compute all subtotals on page load =====
    document.addEventListener('DOMContentLoaded', () => {
        updateSubtotals();
        updateCounts();

        // Auto-open first empty category on create
        const firstClosed = document.querySelector('.cat-collapsible:not(.cat-open)');
        if (firstClosed) firstClosed.classList.add('cat-open');
    });
</script>
@endpush
