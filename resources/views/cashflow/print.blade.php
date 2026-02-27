<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>FCU — {{ $report->nama }} — {{ $report->bulan }} {{ $report->tahun }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .page { page-break-after: always; padding: 15px 20px; }
        .page:last-child { page-break-after: auto; }
        h1 { font-size: 16px; text-align: center; margin-bottom: 2px; }
        h2 { font-size: 13px; text-align: center; margin-bottom: 8px; color: #555; }
        h3 { font-size: 12px; margin: 10px 0 5px; padding: 4px 8px; background: #f0f0f0; border-left: 3px solid #333; }
        .info { margin-bottom: 10px; font-size: 11px; }
        .info span { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 8px; }
        th, td { border: 1px solid #ddd; padding: 4px 8px; }
        th { background: #f5f5f5; font-weight: 700; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: .3px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mono { font-family: 'Consolas', monospace; font-size: 10px; }
        .subtotal { background: #f9f9f9; font-weight: 700; }
        .grand { background: #e8e8e8; font-weight: 800; font-size: 11px; }
        .section-header td { background: #333; color: #fff; font-weight: 700; font-size: 10px; text-transform: uppercase; letter-spacing: .5px; }
        .two-col { display: flex; gap: 15px; }
        .two-col > div { flex: 1; }
        .fcu-card { border: 1px solid #ddd; border-radius: 4px; padding: 8px 10px; margin-bottom: 8px; }
        .fcu-num { display: inline-block; width: 22px; height: 22px; border-radius: 50%; background: #333; color: #fff; text-align: center; line-height: 22px; font-weight: 700; font-size: 10px; margin-right: 6px; }
        .fcu-title { font-weight: 700; font-size: 11px; }
        .fcu-status { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: 700; }
        .status-ok { background: #d4edda; color: #155724; }
        .status-bad { background: #f8d7da; color: #721c24; }
        .status-warn { background: #fff3cd; color: #856404; }
        .fcu-detail { font-size: 10px; margin-top: 4px; }
        .fcu-detail .row { display: flex; justify-content: space-between; padding: 2px 0; border-bottom: 1px dashed #eee; }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
        .no-print { cursor: pointer; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>

@php
    $fmt = fn($v) => 'Rp ' . number_format($v, 0, ',', '.');
    $neracaCats = \App\Models\CashflowReport::NERACA_CATEGORIES;
    $arusKasCats = \App\Models\CashflowReport::CATEGORIES;
@endphp

<div style="text-align:center; padding:10px;" class="no-print">
    <button onclick="window.print()" style="padding:8px 24px; font-size:13px; cursor:pointer; background:#333; color:#fff; border:none; border-radius:4px;">
        🖨️ Print
    </button>
    <a href="{{ route('cashflow.show', $report) }}" style="margin-left:10px; font-size:12px;">← Kembali</a>
</div>

{{-- ======================== PAGE 1: NERACA ======================== --}}
<div class="page">
    <h1>Financial Check Up</h1>
    <h2>NERACA KEUANGAN PRIBADI</h2>
    <div class="info">
        <span>NAMA:</span> {{ $report->nama }} &nbsp;&nbsp;
        <span>BULAN:</span> {{ $report->bulan }} &nbsp;&nbsp;
        <span>THN:</span> {{ $report->tahun }}
    </div>

    <div class="two-col">
        {{-- ASET --}}
        <div>
            <table>
                <tr class="section-header"><td colspan="3">ASET</td></tr>

                <tr><td colspan="2" style="font-weight:700; background:#f5f5f5;"><i>Aset Likuid</i></td><td></td></tr>
                <tr><td colspan="2" style="padding-left:12px; font-weight:600; font-size:9px;">Tabungan</td><td></td></tr>
                @foreach($report->itemsByCategory('aset_likuid_tabungan') as $item)
                <tr><td style="width:10px;">▪</td><td>{{ $item->label }}</td><td class="text-right mono">{{ $fmt($item->amount) }}</td></tr>
                @endforeach
                <tr class="subtotal"><td>A.</td><td>Sub Total</td><td class="text-right mono">{{ $fmt($report->sub_total_tabungan) }}</td></tr>

                <tr><td colspan="2" style="padding-left:12px; font-weight:600; font-size:9px;">Others</td><td></td></tr>
                @foreach($report->itemsByCategory('aset_likuid_others') as $item)
                <tr><td>▪</td><td>{{ $item->label }}</td><td class="text-right mono">{{ $fmt($item->amount) }}</td></tr>
                @endforeach
                <tr class="subtotal"><td>B.</td><td>Sub Total</td><td class="text-right mono">{{ $fmt($report->sub_total_others) }}</td></tr>
                <tr class="grand"><td>C.</td><td>Total Aset Likuid (A+B)</td><td class="text-right mono">{{ $fmt($report->total_aset_likuid) }}</td></tr>

                <tr><td colspan="2" style="font-weight:700; background:#f5f5f5;"><i>Aset Investasi</i></td><td></td></tr>
                @foreach($report->itemsByCategory('aset_investasi_neraca') as $item)
                <tr><td>▪</td><td>{{ $item->label }}</td><td class="text-right mono">{{ $fmt($item->amount) }}</td></tr>
                @endforeach
                <tr class="subtotal"><td>D.</td><td>Sub Total</td><td class="text-right mono">{{ $fmt($report->sub_total_aset_investasi) }}</td></tr>

                <tr><td colspan="2" style="font-weight:700; background:#f5f5f5;"><i>Aset Investasi Belum Dicairkan</i></td><td></td></tr>
                @foreach($report->itemsByCategory('aset_investasi_belum_cair') as $item)
                <tr><td>▪</td><td>{{ $item->label }}</td><td class="text-right mono">{{ $fmt($item->amount) }}</td></tr>
                @endforeach
                <tr class="subtotal"><td></td><td>Sub Total</td><td class="text-right mono">{{ $fmt($report->sub_total_aset_belum_cair) }}</td></tr>
                <tr class="grand"><td>E.</td><td>Total Aset Investasi</td><td class="text-right mono">{{ $fmt($report->total_aset_investasi_neraca) }}</td></tr>

                <tr><td colspan="2" style="font-weight:700; background:#f5f5f5;"><i>Aset Penggunaan Pribadi</i></td><td></td></tr>
                @foreach($report->itemsByCategory('aset_pribadi') as $item)
                <tr><td>▪</td><td>{{ $item->label }}</td><td class="text-right mono">{{ $fmt($item->amount) }}</td></tr>
                @endforeach
                <tr class="grand"><td>F.</td><td>Total Aset Pribadi</td><td class="text-right mono">{{ $fmt($report->total_aset_pribadi) }}</td></tr>

                <tr class="grand" style="background:#ccc;"><td>G.</td><td>TOTAL ASET (C+E+F)</td><td class="text-right mono" style="font-size:11px;">{{ $fmt($report->total_aset) }}</td></tr>
            </table>
        </div>

        {{-- KEWAJIBAN --}}
        <div>
            <table>
                <tr class="section-header"><td colspan="3">KEWAJIBAN (UTANG)</td></tr>

                <tr><td colspan="2" style="font-weight:700; background:#f5f5f5;"><i>Kewajiban Jangka Pendek</i></td><td></td></tr>
                @foreach($report->itemsByCategory('neraca_utang_pendek') as $item)
                <tr><td>▪</td><td>{{ $item->label }}</td><td class="text-right mono">{{ $fmt($item->amount) }}</td></tr>
                @endforeach
                <tr class="subtotal"><td>H.</td><td>Total Utang Jk. Pendek</td><td class="text-right mono">{{ $fmt($report->total_neraca_utang_pendek) }}</td></tr>

                <tr><td colspan="2" style="font-weight:700; background:#f5f5f5;"><i>Kewajiban Jangka Panjang</i></td><td></td></tr>
                @foreach($report->itemsByCategory('neraca_utang_panjang') as $item)
                <tr><td>▪</td><td>{{ $item->label }}</td><td class="text-right mono">{{ $fmt($item->amount) }}</td></tr>
                @endforeach
                <tr class="subtotal"><td>I.</td><td>Total Utang Jk. Panjang</td><td class="text-right mono">{{ $fmt($report->total_neraca_utang_panjang) }}</td></tr>

                <tr class="grand" style="background:#ccc;"><td>J.</td><td>TOTAL KEWAJIBAN (H+I)</td><td class="text-right mono" style="font-size:11px;">{{ $fmt($report->total_kewajiban) }}</td></tr>

                <tr><td colspan="3" style="height:10px; border:none;"></td></tr>
                <tr><td colspan="2" style="font-weight:700; background:#f5f5f5;"><i>Nilai Kekayaan Bersih</i></td><td></td></tr>
                <tr class="grand" style="background: {{ $report->kekayaan_bersih >= 0 ? '#d4edda' : '#f8d7da' }};"><td>K.</td><td>KEKAYAAN BERSIH (G−J)</td><td class="text-right mono" style="font-size:11px;">{{ $fmt($report->kekayaan_bersih) }}</td></tr>
            </table>
        </div>
    </div>
</div>

{{-- ======================== PAGE 2: ARUS KAS ======================== --}}
<div class="page">
    <h1>Financial Check Up</h1>
    <h2>ARUS KAS</h2>
    <div class="info">
        <span>NAMA:</span> {{ $report->nama }} &nbsp;&nbsp;
        <span>BULAN:</span> {{ $report->bulan }} &nbsp;&nbsp;
        <span>THN:</span> {{ $report->tahun }}
    </div>

    <div class="two-col">
        {{-- UANG MASUK --}}
        <div>
            <table>
                <tr class="section-header"><td colspan="3">DOMPET UANG MASUK</td></tr>
                <tr><td colspan="2" style="font-weight:700; background:#f5f5f5;">Uang Masuk Tetap</td><td></td></tr>
                @foreach($report->itemsByCategory('uang_masuk_tetap') as $item)
                    @if($item->amount > 0)
                    <tr><td>▪</td><td>{{ $item->label }}</td><td class="text-right mono">{{ $fmt($item->amount) }}</td></tr>
                    @endif
                @endforeach
                <tr class="subtotal"><td>A.</td><td>Total Uang Masuk Tetap</td><td class="text-right mono">{{ $fmt($report->total_uang_masuk_tetap) }}</td></tr>

                <tr><td colspan="2" style="font-weight:700; background:#f5f5f5;">Uang Masuk Tidak Tetap</td><td></td></tr>
                @foreach($report->itemsByCategory('uang_masuk_tidak_tetap') as $item)
                    @if($item->amount > 0)
                    <tr><td>▪</td><td>{{ $item->label }}</td><td class="text-right mono">{{ $fmt($item->amount) }}</td></tr>
                    @endif
                @endforeach
                <tr class="subtotal"><td>B.</td><td>Total Uang Masuk Tdk Tetap</td><td class="text-right mono">{{ $fmt($report->total_uang_masuk_tidak_tetap) }}</td></tr>
                <tr class="grand"><td>C.</td><td>TOTAL UANG MASUK (A+B)</td><td class="text-right mono">{{ $fmt($report->total_uang_masuk) }}</td></tr>
            </table>
        </div>

        {{-- UANG KELUAR --}}
        <div>
            <table>
                <tr class="section-header"><td colspan="4">DOMPET UANG KELUAR</td></tr>
                <tr><th></th><th>Item</th><th class="text-right">Jumlah</th><th class="text-center">IDEAL / REALITA</th></tr>

                @php
                    $sections = [
                        ['cat' => 'pengeluaran_rt', 'label' => 'Pengeluaran Rumah Tangga', 'sub' => 'D. Total Pengeluaran RT', 'total' => $report->total_pengeluaran_rt, 'ideal' => null],
                        ['cat' => 'pengeluaran_konsumtif', 'label' => 'Pengeluaran Konsumtif', 'sub' => 'Sub Total Konsumtif', 'total' => $report->total_pengeluaran_konsumtif, 'ideal' => 40],
                        ['cat' => 'pengeluaran_pendidikan', 'label' => 'Pengeluaran Pendidikan', 'sub' => 'E. Total Pendidikan', 'total' => $report->total_pendidikan, 'ideal' => 10],
                        ['cat' => 'gaya_hidup', 'label' => 'Gaya Hidup', 'sub' => 'Sub Total Gaya Hidup', 'total' => $report->total_gaya_hidup, 'ideal' => 5],
                        ['cat' => 'utang_pendek', 'label' => 'Utang Jk. Pendek', 'sub' => 'G. Total Utang Jk. Pendek', 'total' => $report->total_utang_pendek, 'ideal' => 15],
                        ['cat' => 'utang_panjang', 'label' => 'Utang Jk. Panjang', 'sub' => 'H. Total Utang Jk. Panjang', 'total' => $report->total_utang_panjang, 'ideal' => 15],
                        ['cat' => 'investasi', 'label' => 'Investasi / Tabungan', 'sub' => 'J. Total Investasi', 'total' => $report->total_investasi, 'ideal' => 10],
                        ['cat' => 'proteksi', 'label' => 'Proteksi / Asuransi', 'sub' => 'K. Total Proteksi', 'total' => $report->total_proteksi, 'ideal' => 5],
                    ];
                @endphp

                @foreach($sections as $sec)
                    <tr><td colspan="3" style="font-weight:700; background:#f5f5f5;"><i>{{ $sec['label'] }}</i></td><td></td></tr>
                    @foreach($report->itemsByCategory($sec['cat']) as $item)
                        @if($item->amount > 0)
                        <tr><td>▪</td><td>{{ $item->label }}</td><td class="text-right mono">{{ $fmt($item->amount) }}</td><td></td></tr>
                        @endif
                    @endforeach
                    <tr class="subtotal">
                        <td></td><td>{{ $sec['sub'] }}</td><td class="text-right mono">{{ $fmt($sec['total']) }}</td>
                        <td class="text-center mono">
                            @if($sec['ideal'])
                                {{ $sec['ideal'] }}% / {{ $report->pct($sec['total']) }}%
                            @endif
                        </td>
                    </tr>
                @endforeach

                <tr class="grand"><td></td><td>M. TOTAL UANG KELUAR</td><td class="text-right mono">{{ $fmt($report->total_uang_keluar) }}</td><td class="text-center mono">100% / {{ $report->pct($report->total_uang_keluar) }}%</td></tr>
                <tr class="grand" style="background: {{ $report->nilai_bersih >= 0 ? '#d4edda' : '#f8d7da' }};"><td>N.</td><td>NILAI BERSIH DOMPET (C−M)</td><td class="text-right mono">{{ $fmt($report->nilai_bersih) }}</td><td></td></tr>
            </table>
        </div>
    </div>
</div>

{{-- ======================== PAGE 3: HASIL FCU ======================== --}}
<div class="page">
    <h1>ANALISA LAPORAN KEUANGAN</h1>
    <h2>(Diisi oleh Perencana Keuangan)</h2>
    <div class="info">
        <span>Nama:</span> {{ $report->nama }}
    </div>

    @foreach($fcuAnalysis as $fcu)
        <div class="fcu-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:4px;">
                <div>
                    <span class="fcu-num">{{ $fcu['no'] }}</span>
                    <span class="fcu-title">{{ $fcu['title'] }}</span>
                </div>
                <span class="fcu-status {{ $fcu['badge'] === 'badge-sehat' ? 'status-ok' : ($fcu['badge'] === 'badge-waspada' ? 'status-warn' : 'status-bad') }}">
                    {{ $fcu['status'] }}
                </span>
            </div>
            <div style="font-size:9px; color:#666; margin-bottom:4px;">{{ $fcu['description'] }}</div>
            <div class="fcu-detail">
                @foreach($fcu['details'] as $d)
                    <div class="row">
                        <span>{{ $d['label'] }}</span>
                        <span class="mono" style="font-weight:600;">{{ $d['value'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    {{-- Catatan --}}
    <h3 style="margin-top:15px;">Catatan Perencana Keuangan</h3>
    <div style="padding:6px 10px;">
        <div style="font-weight:700; font-size:10px; margin-bottom:4px;">Observasi:</div>
        <ul style="padding-left:16px; margin-bottom:8px;">
            @foreach($catatan['observasi'] as $obs)
                <li style="margin-bottom:3px; font-size:10px;">{{ $obs }}</li>
            @endforeach
        </ul>
        <div style="font-weight:700; font-size:10px; margin-bottom:4px;">Saran:</div>
        <ol style="padding-left:16px;">
            @foreach($catatan['saran'] as $s)
                <li style="margin-bottom:3px; font-size:10px;">{{ $s }}</li>
            @endforeach
        </ol>
    </div>
</div>

</body>
</html>
