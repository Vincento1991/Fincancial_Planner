@extends('layouts.app')

@section('title', $report->nama . ' — Financial Check Up')

@php
    $fmt = fn($v) => 'Rp ' . number_format($v, 0, ',', '.');
    $neracaCats = \App\Models\CashflowReport::NERACA_CATEGORIES;
    $arusKasCats = \App\Models\CashflowReport::CATEGORIES;
@endphp

@section('content')
{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1" style="letter-spacing:-.5px;">
            <i class="bi bi-clipboard-data text-primary me-2"></i>{{ $report->nama }}
        </h4>
        <p class="text-muted mb-0" style="font-size:.875rem;">
            Financial Check Up — {{ $report->bulan }} {{ $report->tahun }}
        </p>
    </div>
    <div class="d-flex gap-2 no-print">
        <a href="{{ route('cashflow.edit', $report) }}" class="btn btn-fp btn-fp-outline">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('cashflow.print', $report) }}" class="btn btn-fp btn-fp-outline" target="_blank">
            <i class="bi bi-printer me-1"></i> Print
        </a>
        <a href="{{ route('cashflow.index') }}" class="btn btn-fp btn-fp-outline">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

{{-- TAB NAVIGATION --}}
<div class="fp-tabs no-print" id="showTabs">
    <button type="button" class="fp-tab active" data-tab="neraca" onclick="switchShowTab('neraca')">
        <span class="tab-num">1</span>
        <i class="bi bi-bank"></i> Neraca
    </button>
    <button type="button" class="fp-tab" data-tab="aruskas" onclick="switchShowTab('aruskas')">
        <span class="tab-num">2</span>
        <i class="bi bi-cash-stack"></i> Arus Kas
    </button>
    <button type="button" class="fp-tab" data-tab="fcu" onclick="switchShowTab('fcu')">
        <span class="tab-num">3</span>
        <i class="bi bi-graph-up-arrow"></i> Hasil FCU
    </button>
</div>

{{-- ================================================================
     TAB 1: NERACA KEUANGAN PRIBADI
     ================================================================ --}}
<div class="show-tab-content" id="show-neraca">

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #059669, #10b981);"><i class="bi bi-safe2"></i></div>
                <div class="stat-label">Total Aset</div>
                <div class="stat-value" style="color: #059669;">{{ $fmt($report->total_aset) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #dc2626, #f43f5e);"><i class="bi bi-credit-card"></i></div>
                <div class="stat-label">Total Kewajiban</div>
                <div class="stat-value" style="color: #dc2626;">{{ $fmt($report->total_kewajiban) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: var(--gradient-blue);"><i class="bi bi-piggy-bank"></i></div>
                <div class="stat-label">Kekayaan Bersih</div>
                <div class="stat-value" style="color: {{ $report->kekayaan_bersih >= 0 ? '#059669' : '#dc2626' }};">{{ $fmt($report->kekayaan_bersih) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);"><i class="bi bi-percent"></i></div>
                <div class="stat-label">Rasio Utang/Aset</div>
                <div class="stat-value">{{ $report->total_aset > 0 ? round(($report->total_kewajiban / $report->total_aset) * 100, 1) : 0 }}%</div>
                <div class="stat-sub">Ideal ≤ 50%</div>
            </div>
        </div>
    </div>

    {{-- Detail Table --}}
    <div class="neraca-grid">
        {{-- LEFT: ASET --}}
        <div class="fp-card">
            <div class="neraca-col-header header-aset">
                <i class="bi bi-safe me-2"></i> ASET
            </div>

            {{-- Aset Likuid --}}
            <div class="neraca-section-label"><i class="bi bi-wallet2 me-1"></i> Aset Likuid</div>

            {{-- Tabungan --}}
            <div class="neraca-section-label" style="padding-left:1.5rem; font-size:.72rem; background:#fbfcfd;">Tabungan</div>
            @foreach($report->itemsByCategory('aset_likuid_tabungan') as $item)
                <div class="neraca-item">
                    <span style="color:#475569;"><span style="color:#94a3b8; margin-right:.5rem;">&#9642;</span>{{ $item->label }}</span>
                    <span class="mono" style="font-family:'JetBrains Mono',monospace; font-weight:500; font-size:.82rem;">{{ $fmt($item->amount) }}</span>
                </div>
            @endforeach
            <div class="neraca-subtotal">
                <span>A. Sub Total</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace;">{{ $fmt($report->sub_total_tabungan) }}</span>
            </div>

            {{-- Others --}}
            <div class="neraca-section-label" style="padding-left:1.5rem; font-size:.72rem; background:#fbfcfd;">Others</div>
            @foreach($report->itemsByCategory('aset_likuid_others') as $item)
                <div class="neraca-item">
                    <span style="color:#475569;"><span style="color:#94a3b8; margin-right:.5rem;">&#9642;</span>{{ $item->label }}</span>
                    <span class="mono" style="font-family:'JetBrains Mono',monospace; font-weight:500; font-size:.82rem;">{{ $fmt($item->amount) }}</span>
                </div>
            @endforeach
            <div class="neraca-subtotal">
                <span>B. Sub Total</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace;">{{ $fmt($report->sub_total_others) }}</span>
            </div>
            <div class="neraca-subtotal" style="background:#e0f2fe;">
                <span style="font-weight:800;">C. Total Aset Likuid (A+B)</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace; font-weight:800; color:#0284c7;">{{ $fmt($report->total_aset_likuid) }}</span>
            </div>

            {{-- Aset Investasi --}}
            <div class="neraca-section-label"><i class="bi bi-graph-up me-1"></i> Aset Investasi</div>
            @foreach($report->itemsByCategory('aset_investasi_neraca') as $item)
                <div class="neraca-item">
                    <span style="color:#475569;"><span style="color:#94a3b8; margin-right:.5rem;">&#9642;</span>{{ $item->label }}</span>
                    <span class="mono" style="font-family:'JetBrains Mono',monospace; font-weight:500; font-size:.82rem;">{{ $fmt($item->amount) }}</span>
                </div>
            @endforeach
            <div class="neraca-subtotal">
                <span>D. Sub Total</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace;">{{ $fmt($report->sub_total_aset_investasi) }}</span>
            </div>

            {{-- Aset Investasi Belum Dicairkan --}}
            <div class="neraca-section-label"><i class="bi bi-lock me-1"></i> Aset Investasi Belum Dicairkan</div>
            @foreach($report->itemsByCategory('aset_investasi_belum_cair') as $item)
                <div class="neraca-item">
                    <span style="color:#475569;"><span style="color:#94a3b8; margin-right:.5rem;">&#9642;</span>{{ $item->label }}</span>
                    <span class="mono" style="font-family:'JetBrains Mono',monospace; font-weight:500; font-size:.82rem;">{{ $fmt($item->amount) }}</span>
                </div>
            @endforeach
            <div class="neraca-subtotal">
                <span>Sub Total</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace;">{{ $fmt($report->sub_total_aset_belum_cair) }}</span>
            </div>
            <div class="neraca-subtotal" style="background:#e0f2fe;">
                <span style="font-weight:800;">E. Total Aset Investasi</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace; font-weight:800; color:#0284c7;">{{ $fmt($report->total_aset_investasi_neraca) }}</span>
            </div>

            {{-- Aset Penggunaan Pribadi --}}
            <div class="neraca-section-label"><i class="bi bi-house me-1"></i> Aset Penggunaan Pribadi</div>
            @foreach($report->itemsByCategory('aset_pribadi') as $item)
                <div class="neraca-item">
                    <span style="color:#475569;"><span style="color:#94a3b8; margin-right:.5rem;">&#9642;</span>{{ $item->label }}</span>
                    <span class="mono" style="font-family:'JetBrains Mono',monospace; font-weight:500; font-size:.82rem;">{{ $fmt($item->amount) }}</span>
                </div>
            @endforeach
            <div class="neraca-subtotal" style="background:#e0f2fe;">
                <span style="font-weight:800;">F. Total Aset Penggunaan Pribadi</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace; font-weight:800; color:#0284c7;">{{ $fmt($report->total_aset_pribadi) }}</span>
            </div>

            {{-- GRAND TOTAL ASET --}}
            <div class="neraca-grand" style="background:#dcfce7;">
                <span style="color:#166534;">G. TOTAL ASET (C+E+F)</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace; font-weight:800; color:#166534; font-size:.95rem;">{{ $fmt($report->total_aset) }}</span>
            </div>
        </div>

        {{-- RIGHT: KEWAJIBAN --}}
        <div class="fp-card">
            <div class="neraca-col-header header-kewajiban">
                <i class="bi bi-credit-card me-2"></i> KEWAJIBAN (UTANG)
            </div>

            {{-- Utang Jangka Pendek --}}
            <div class="neraca-section-label"><i class="bi bi-clock me-1"></i> Kewajiban (Utang) Jangka Pendek</div>
            @foreach($report->itemsByCategory('neraca_utang_pendek') as $item)
                <div class="neraca-item">
                    <span style="color:#475569;"><span style="color:#94a3b8; margin-right:.5rem;">&#9642;</span>{{ $item->label }}</span>
                    <span class="mono" style="font-family:'JetBrains Mono',monospace; font-weight:500; font-size:.82rem;">{{ $fmt($item->amount) }}</span>
                </div>
            @endforeach
            <div class="neraca-subtotal">
                <span>H. Total Utang Jk. Pendek</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace;">{{ $fmt($report->total_neraca_utang_pendek) }}</span>
            </div>

            {{-- Utang Jangka Panjang --}}
            <div class="neraca-section-label"><i class="bi bi-calendar3 me-1"></i> Kewajiban (Utang) Jangka Panjang</div>
            @foreach($report->itemsByCategory('neraca_utang_panjang') as $item)
                <div class="neraca-item">
                    <span style="color:#475569;"><span style="color:#94a3b8; margin-right:.5rem;">&#9642;</span>{{ $item->label }}</span>
                    <span class="mono" style="font-family:'JetBrains Mono',monospace; font-weight:500; font-size:.82rem;">{{ $fmt($item->amount) }}</span>
                </div>
            @endforeach
            <div class="neraca-subtotal">
                <span>I. Total Utang Jk. Panjang</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace;">{{ $fmt($report->total_neraca_utang_panjang) }}</span>
            </div>

            {{-- TOTAL KEWAJIBAN --}}
            <div class="neraca-grand" style="background:#fef2f2;">
                <span style="color:#991b1b;">J. TOTAL KEWAJIBAN (H+I)</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace; font-weight:800; color:#991b1b; font-size:.95rem;">{{ $fmt($report->total_kewajiban) }}</span>
            </div>

            {{-- Kekayaan Bersih --}}
            <div class="neraca-section-label" style="margin-top: 1rem; background: transparent; border: none;"><i class="bi bi-trophy me-1"></i> Nilai Kekayaan Bersih</div>
            <div class="neraca-grand" style="background: {{ $report->kekayaan_bersih >= 0 ? '#dcfce7' : '#fef2f2' }};">
                <span style="font-weight:800; color: {{ $report->kekayaan_bersih >= 0 ? '#166534' : '#991b1b' }};">K. KEKAYAAN BERSIH (G−J)</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace; font-weight:800; font-size:1rem; color: {{ $report->kekayaan_bersih >= 0 ? '#166534' : '#991b1b' }};">{{ $fmt($report->kekayaan_bersih) }}</span>
            </div>

            {{-- Verifikasi --}}
            <div class="neraca-grand" style="background:#f8fafc; border-top:1px dashed #e2e8f0;">
                <span style="color:#64748b; font-weight:600; font-size:.82rem;">Total Hutang + Kekayaan Bersih</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace; font-weight:700; font-size:.85rem; color:#64748b;">{{ $fmt($report->total_kewajiban + $report->kekayaan_bersih) }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     TAB 2: ARUS KAS
     ================================================================ --}}
<div class="show-tab-content" id="show-aruskas" style="display:none;">

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card stat-income">
                <div class="stat-icon" style="background: linear-gradient(135deg, #059669, #10b981);"><i class="bi bi-arrow-down-circle"></i></div>
                <div class="stat-label">Total Uang Masuk</div>
                <div class="stat-value" style="color: #059669;">{{ $fmt($report->total_uang_masuk) }}</div>
                <div class="stat-sub">Tetap: {{ $fmt($report->total_uang_masuk_tetap) }} + Tidak Tetap: {{ $fmt($report->total_uang_masuk_tidak_tetap) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card stat-expense">
                <div class="stat-icon" style="background: linear-gradient(135deg, #dc2626, #f43f5e);"><i class="bi bi-arrow-up-circle"></i></div>
                <div class="stat-label">Total Uang Keluar</div>
                <div class="stat-value" style="color: #dc2626;">{{ $fmt($report->total_uang_keluar) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card stat-net">
                <div class="stat-icon" style="background: var(--gradient-blue);"><i class="bi bi-wallet2"></i></div>
                <div class="stat-label">Nilai Bersih Dompet</div>
                <div class="stat-value" style="color: {{ $report->nilai_bersih >= 0 ? '#059669' : '#dc2626' }};">{{ $fmt($report->nilai_bersih) }}</div>
                <div class="stat-sub">
                    <span class="status-badge badge-{{ $report->nilai_bersih > 0 ? 'sehat' : ($report->nilai_bersih == 0 ? 'waspada' : 'bahaya') }}">
                        <span class="pulse-dot"></span> {{ $report->getStatusLabel() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- LEFT: Detail Uang Masuk --}}
        <div class="col-lg-5">
            <div class="fp-card">
                <div class="fp-card-header" style="background: linear-gradient(135deg, #059669, #10b981); color:#fff; border:0;">
                    <i class="bi bi-arrow-down-circle me-2"></i> Detail Uang Masuk
                </div>
                @foreach(['uang_masuk_tetap', 'uang_masuk_tidak_tetap'] as $catKey)
                    <div class="detail-section">
                        <div class="detail-section-header">
                            <span style="color:#10b981;">&#9679;</span> {{ $arusKasCats[$catKey]['label'] }}
                        </div>
                        @foreach($report->itemsByCategory($catKey) as $item)
                            @if($item->amount > 0)
                            <div class="detail-item">
                                <span class="item-name"><span class="item-dot" style="background:#10b981;"></span>{{ $item->label }}</span>
                                <span class="item-val">{{ $fmt($item->amount) }}</span>
                            </div>
                            @endif
                        @endforeach
                        <div class="detail-subtotal">
                            <span>{{ $catKey === 'uang_masuk_tetap' ? 'A' : 'B' }}. Total</span>
                            <span class="sub-val">{{ $fmt($report->categoryTotal($catKey)) }}</span>
                        </div>
                    </div>
                @endforeach
                <div class="detail-grand" style="background:#ecfdf5; color:#059669;">
                    <span>C. TOTAL UANG MASUK (A+B)</span>
                    <span class="grand-val">{{ $fmt($report->total_uang_masuk) }}</span>
                </div>
            </div>
        </div>

        {{-- RIGHT: Detail Uang Keluar --}}
        <div class="col-lg-7">
            <div class="fp-card">
                <div class="fp-card-header" style="background: linear-gradient(135deg, #dc2626, #f43f5e); color:#fff; border:0;">
                    <i class="bi bi-arrow-up-circle me-2"></i> Detail Uang Keluar
                    <span class="ms-auto d-flex gap-3" style="font-size:.72rem; text-transform:uppercase; letter-spacing:.5px;">
                        <span>Ideal</span><span>Realita</span>
                    </span>
                </div>

                @php
                    $expenseOrder = [
                        ['cats' => ['pengeluaran_rt'], 'sub' => 'D. Total Pengeluaran RT', 'letter' => null, 'ideal' => null, 'total' => $report->total_pengeluaran_rt],
                        ['cats' => ['pengeluaran_konsumtif'], 'sub' => 'Sub Total Konsumtif', 'letter' => null, 'ideal' => 40, 'total' => $report->total_pengeluaran_konsumtif],
                        ['cats' => ['pengeluaran_pendidikan'], 'sub' => 'E. Total Pengeluaran Pendidikan', 'letter' => null, 'ideal' => 10, 'total' => $report->total_pendidikan],
                    ];
                @endphp

                {{-- Pengeluaran RT --}}
                <div class="detail-section">
                    <div class="detail-section-header"><span style="color:#f59e0b;">&#9679;</span> Pengeluaran Rumah Tangga</div>
                    @foreach($report->itemsByCategory('pengeluaran_rt') as $item)
                        @if($item->amount > 0)
                        <div class="detail-item">
                            <span class="item-name"><span class="item-dot" style="background:#f59e0b;"></span>{{ $item->label }}</span>
                            <span class="item-val">{{ $fmt($item->amount) }}</span>
                        </div>
                        @endif
                    @endforeach
                    <div class="detail-subtotal">
                        <span>D. Total Pengeluaran RT</span>
                        <span class="sub-val">{{ $fmt($report->total_pengeluaran_rt) }}</span>
                    </div>
                </div>

                {{-- Pengeluaran Konsumtif --}}
                <div class="detail-section">
                    <div class="detail-section-header"><span style="color:#f97316;">&#9679;</span> Pengeluaran Konsumtif</div>
                    @foreach($report->itemsByCategory('pengeluaran_konsumtif') as $item)
                        @if($item->amount > 0)
                        <div class="detail-item">
                            <span class="item-name"><span class="item-dot" style="background:#f97316;"></span>{{ $item->label }}</span>
                            <span class="item-val">{{ $fmt($item->amount) }}</span>
                        </div>
                        @endif
                    @endforeach
                    @php $pctKonsumtif = $report->pct($report->total_pengeluaran_konsumtif); @endphp
                    <div class="detail-subtotal">
                        <span>Sub Total Konsumtif</span>
                        <span class="sub-val">
                            {{ $fmt($report->total_pengeluaran_konsumtif) }}
                            <span class="pct-chip {{ $pctKonsumtif <= 40 ? 'chip-ok' : 'chip-bad' }}">
                                {{ $report->statusIcon($pctKonsumtif, 40) }} {{ $pctKonsumtif }}% / 40%
                            </span>
                        </span>
                    </div>
                </div>

                {{-- Pendidikan --}}
                <div class="detail-section">
                    <div class="detail-section-header"><span style="color:#3b82f6;">&#9679;</span> Pengeluaran Pendidikan</div>
                    @foreach($report->itemsByCategory('pengeluaran_pendidikan') as $item)
                        @if($item->amount > 0)
                        <div class="detail-item">
                            <span class="item-name"><span class="item-dot" style="background:#3b82f6;"></span>{{ $item->label }}</span>
                            <span class="item-val">{{ $fmt($item->amount) }}</span>
                        </div>
                        @endif
                    @endforeach
                    @php $pctPendidikan = $report->pct($report->total_pendidikan); @endphp
                    <div class="detail-subtotal">
                        <span>E. Total Pendidikan</span>
                        <span class="sub-val">
                            {{ $fmt($report->total_pendidikan) }}
                            <span class="pct-chip {{ $pctPendidikan <= 10 ? 'chip-ok' : 'chip-bad' }}">
                                {{ $report->statusIcon($pctPendidikan, 10) }} {{ $pctPendidikan }}% / 10%
                            </span>
                        </span>
                    </div>
                </div>

                {{-- Total Pengeluaran Tetap --}}
                <div class="detail-subtotal" style="background:#fef3c7;">
                    <span style="font-weight:800;">TOTAL PENGELUARAN TETAP</span>
                    <span class="sub-val" style="font-weight:800;">
                        {{ $fmt($report->total_pengeluaran_tetap) }}
                        @php $pctTetap = $report->pct($report->total_pengeluaran_tetap); @endphp
                        <span class="pct-chip {{ $pctTetap <= 50 ? 'chip-ok' : 'chip-bad' }}">{{ $pctTetap }}% / 50%</span>
                    </span>
                </div>

                {{-- Gaya Hidup --}}
                <div class="detail-section">
                    <div class="detail-section-header"><span style="color:#8b5cf6;">&#9679;</span> Pengeluaran Gaya Hidup</div>
                    @foreach($report->itemsByCategory('gaya_hidup') as $item)
                        @if($item->amount > 0)
                        <div class="detail-item">
                            <span class="item-name"><span class="item-dot" style="background:#8b5cf6;"></span>{{ $item->label }}</span>
                            <span class="item-val">{{ $fmt($item->amount) }}</span>
                        </div>
                        @endif
                    @endforeach
                    @php $pctGH = $report->pct($report->total_gaya_hidup); @endphp
                    <div class="detail-subtotal">
                        <span>Sub Total Gaya Hidup</span>
                        <span class="sub-val">
                            {{ $fmt($report->total_gaya_hidup) }}
                            <span class="pct-chip {{ $pctGH <= 5 ? 'chip-ok' : 'chip-bad' }}">
                                {{ $report->statusIcon($pctGH, 5) }} {{ $pctGH }}% / 5%
                            </span>
                        </span>
                    </div>
                </div>

                {{-- Total Pengeluaran Konsumtif All --}}
                <div class="detail-subtotal" style="background:#fde68a;">
                    <span style="font-weight:800;">TOTAL PENGELUARAN KONSUMTIF</span>
                    <span class="sub-val" style="font-weight:800;">
                        {{ $fmt($report->total_pengeluaran_konsumtif_all) }}
                        @php $pctAll = $report->pct($report->total_pengeluaran_konsumtif_all); @endphp
                        <span class="pct-chip {{ $pctAll <= 55 ? 'chip-ok' : 'chip-bad' }}">{{ $pctAll }}% / 55%</span>
                    </span>
                </div>

                {{-- Utang Jangka Pendek --}}
                <div class="detail-section">
                    <div class="detail-section-header"><span style="color:#f43f5e;">&#9679;</span> Utang Jangka Pendek</div>
                    @foreach($report->itemsByCategory('utang_pendek') as $item)
                        @if($item->amount > 0)
                        <div class="detail-item">
                            <span class="item-name"><span class="item-dot" style="background:#f43f5e;"></span>{{ $item->label }}</span>
                            <span class="item-val">{{ $fmt($item->amount) }}</span>
                        </div>
                        @endif
                    @endforeach
                    @php $pctUP = $report->pct($report->total_utang_pendek); @endphp
                    <div class="detail-subtotal">
                        <span>G. Total Utang Jk. Pendek</span>
                        <span class="sub-val">
                            {{ $fmt($report->total_utang_pendek) }}
                            <span class="pct-chip {{ $pctUP <= 15 ? 'chip-ok' : 'chip-bad' }}">{{ $pctUP }}% / 15%</span>
                        </span>
                    </div>
                </div>

                {{-- Utang Jangka Panjang --}}
                <div class="detail-section">
                    <div class="detail-section-header"><span style="color:#dc2626;">&#9679;</span> Utang Jangka Panjang</div>
                    @foreach($report->itemsByCategory('utang_panjang') as $item)
                        @if($item->amount > 0)
                        <div class="detail-item">
                            <span class="item-name"><span class="item-dot" style="background:#dc2626;"></span>{{ $item->label }}</span>
                            <span class="item-val">{{ $fmt($item->amount) }}</span>
                        </div>
                        @endif
                    @endforeach
                    @php $pctUPJ = $report->pct($report->total_utang_panjang); @endphp
                    <div class="detail-subtotal">
                        <span>H. Total Utang Jk. Panjang</span>
                        <span class="sub-val">
                            {{ $fmt($report->total_utang_panjang) }}
                            <span class="pct-chip {{ $pctUPJ <= 15 ? 'chip-ok' : 'chip-bad' }}">{{ $pctUPJ }}% / 15%</span>
                        </span>
                    </div>
                </div>

                {{-- Total Utang --}}
                @php $pctUtang = $report->pct($report->total_utang); @endphp
                <div class="detail-subtotal" style="background:#fecaca;">
                    <span style="font-weight:800;">TOTAL PENGELUARAN UTANG</span>
                    <span class="sub-val" style="font-weight:800;">
                        {{ $fmt($report->total_utang) }}
                        <span class="pct-chip {{ $pctUtang <= 30 ? 'chip-ok' : 'chip-bad' }}">{{ $pctUtang }}% / 30%</span>
                    </span>
                </div>

                {{-- Investasi --}}
                <div class="detail-section">
                    <div class="detail-section-header"><span style="color:#06b6d4;">&#9679;</span> Investasi / Tabungan</div>
                    @foreach($report->itemsByCategory('investasi') as $item)
                        @if($item->amount > 0)
                        <div class="detail-item">
                            <span class="item-name"><span class="item-dot" style="background:#06b6d4;"></span>{{ $item->label }}</span>
                            <span class="item-val">{{ $fmt($item->amount) }}</span>
                        </div>
                        @endif
                    @endforeach
                    @php $pctInv = $report->pct($report->total_investasi); @endphp
                    <div class="detail-subtotal">
                        <span>J. Total Investasi</span>
                        <span class="sub-val">
                            {{ $fmt($report->total_investasi) }}
                            <span class="pct-chip {{ $pctInv >= 10 ? 'chip-ok' : 'chip-bad' }}">{{ $pctInv }}% / 10%</span>
                        </span>
                    </div>
                </div>

                {{-- Proteksi --}}
                <div class="detail-section">
                    <div class="detail-section-header"><span style="color:#6366f1;">&#9679;</span> Proteksi / Asuransi</div>
                    @foreach($report->itemsByCategory('proteksi') as $item)
                        @if($item->amount > 0)
                        <div class="detail-item">
                            <span class="item-name"><span class="item-dot" style="background:#6366f1;"></span>{{ $item->label }}</span>
                            <span class="item-val">{{ $fmt($item->amount) }}</span>
                        </div>
                        @endif
                    @endforeach
                    @php $pctPro = $report->pct($report->total_proteksi); @endphp
                    <div class="detail-subtotal">
                        <span>K. Total Proteksi</span>
                        <span class="sub-val">
                            {{ $fmt($report->total_proteksi) }}
                            <span class="pct-chip {{ $pctPro >= 5 ? 'chip-ok' : 'chip-bad' }}">{{ $pctPro }}% / 5%</span>
                        </span>
                    </div>
                </div>

                {{-- GRAND TOTALS --}}
                <div class="detail-grand" style="background:#fef2f2; color:#dc2626;">
                    <span>M. TOTAL UANG KELUAR</span>
                    <span class="grand-val">{{ $fmt($report->total_uang_keluar) }}</span>
                </div>
                <div class="detail-grand" style="background: {{ $report->nilai_bersih >= 0 ? '#dcfce7' : '#fef2f2' }}; color: {{ $report->nilai_bersih >= 0 ? '#059669' : '#dc2626' }};">
                    <span>N. NILAI BERSIH DOMPET (C−M)</span>
                    <span class="grand-val">{{ $fmt($report->nilai_bersih) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Analisis Rasio Arus Kas --}}
    <div class="fp-card mt-4">
        <div class="fp-card-header">
            <span class="header-icon" style="background: var(--gradient-blue);"><i class="bi bi-bar-chart"></i></span>
            Analisis Rasio Arus Kas
        </div>
        <div class="analysis-row analysis-header">
            <span>Kategori</span>
            <span style="text-align:right;">Jumlah</span>
            <span style="text-align:right;">Ideal</span>
            <span style="text-align:right;">Realita</span>
            <span>Rasio</span>
            <span></span>
        </div>
        @foreach($analysis as $row)
            <div class="analysis-row {{ ($row['bold'] ?? false) ? 'row-bold' : '' }}">
                <span>{{ $row['kategori'] }}</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace; text-align:right; font-size:.82rem;">{{ $fmt($row['amount']) }}</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace; text-align:right; font-size:.82rem;">{{ $row['ideal'] }}%</span>
                <span class="mono" style="font-family:'JetBrains Mono',monospace; text-align:right; font-size:.82rem;">{{ $row['realita'] }}%</span>
                <span>
                    <div class="ratio-bar-wrap">
                        <div class="ratio-bar">
                            @php
                                $fillPct = min($row['realita'], 100);
                                $fillClass = $row['realita'] <= $row['ideal'] ? 'fill-ok' : 'fill-bad';
                                if (in_array($row['kategori'], ['Menabung / Investasi', 'Asuransi / Proteksi'])) {
                                    $fillClass = $row['realita'] >= $row['ideal'] ? 'fill-ok' : 'fill-bad';
                                }
                            @endphp
                            <div class="ratio-fill {{ $fillClass }}" style="width: {{ $fillPct }}%;"></div>
                        </div>
                    </div>
                </span>
                <span style="text-align:center;">{{ $row['icon'] }}</span>
            </div>
        @endforeach
    </div>

    {{-- Catatan --}}
    <div class="row g-4 mt-2">
        <div class="col-lg-6">
            <div class="fp-card">
                <div class="fp-card-header">
                    <span class="header-icon" style="background: var(--gradient-orange);"><i class="bi bi-eye"></i></span>
                    Observasi
                </div>
                <div class="fp-card-body">
                    @foreach($catatan['observasi'] as $obs)
                        @php
                            $noteClass = str_contains($obs, 'DEFISIT') || str_contains($obs, 'MELAMPAUI') || str_contains($obs, 'PERINGATAN') || str_contains($obs, 'bangkrut') ? 'note-danger' :
                                        (str_contains($obs, 'melebihi') || str_contains($obs, 'belum') || str_contains($obs, 'Belum') || str_contains($obs, 'hanya') ? 'note-warn' : 'note-ok');
                        @endphp
                        <div class="note-item {{ $noteClass }}">
                            <span class="note-icon">{{ $noteClass === 'note-danger' ? '🔴' : ($noteClass === 'note-warn' ? '🟡' : '🟢') }}</span>
                            <span>{{ $obs }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="fp-card">
                <div class="fp-card-header">
                    <span class="header-icon" style="background: var(--gradient-green);"><i class="bi bi-lightbulb"></i></span>
                    Saran Perencana Keuangan
                </div>
                <div class="fp-card-body">
                    <ol style="padding-left:1.25rem; margin:0;">
                        @foreach($catatan['saran'] as $s)
                            <li class="saran-item">{{ $s }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     TAB 3: HASIL FCU  (Analisa Laporan Keuangan)
     ================================================================ --}}
<div class="show-tab-content" id="show-fcu" style="display:none;">

    <div class="text-center mb-4">
        <h5 class="fw-bold" style="letter-spacing:-.3px;">ANALISA LAPORAN KEUANGAN</h5>
        <p class="text-muted" style="font-size:.85rem;">(Diisi oleh Perencana Keuangan)</p>
    </div>

    @foreach($fcuAnalysis as $fcu)
        <div class="fcu-card">
            <div class="d-flex align-items-start">
                <span class="fcu-num">{{ $fcu['no'] }}</span>
                <div style="flex:1;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <span class="fcu-title">{{ $fcu['title'] }}</span>
                        <span class="status-badge {{ $fcu['badge'] }}">
                            <span class="pulse-dot"></span> {{ $fcu['status'] }}
                        </span>
                    </div>
                    <p class="fcu-desc">{{ $fcu['description'] }}</p>
                    @if(!empty($fcu['target']))
                        <div class="mb-2">
                            <span class="badge bg-light text-dark border" style="font-size:.72rem; font-weight:600;">
                                Target: {{ $fcu['target'] }}
                            </span>
                        </div>
                    @endif
                    <div style="background:#f8fafc; border-radius:8px; padding:.75rem 1rem; margin-top:.5rem;">
                        @foreach($fcu['details'] as $detail)
                            <div class="fcu-detail-row">
                                <span style="color:#64748b;">{{ $detail['label'] }}</span>
                                <span class="fcu-val">{{ $detail['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@push('scripts')
<script>
    function switchShowTab(tab) {
        document.querySelectorAll('.show-tab-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('#showTabs .fp-tab').forEach(el => el.classList.remove('active'));
        document.getElementById('show-' + tab).style.display = 'block';
        document.querySelector('#showTabs .fp-tab[data-tab="' + tab + '"]').classList.add('active');
    }
</script>
@endpush
@endsection
