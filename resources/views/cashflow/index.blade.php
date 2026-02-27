@extends('layouts.app')

@section('title', 'Dashboard')

@php
    $fmt = fn($v) => 'Rp ' . number_format($v, 0, ',', '.');
    $gradients = [
        'linear-gradient(135deg, #667eea, #764ba2)',
        'linear-gradient(135deg, #11998e, #38ef7d)',
        'linear-gradient(135deg, #eb3349, #f45c43)',
        'linear-gradient(135deg, #f093fb, #f5576c)',
        'linear-gradient(135deg, #4facfe, #00f2fe)',
        'linear-gradient(135deg, #43e97b, #38f9d7)',
    ];
@endphp

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1" style="letter-spacing:-.5px;">
            <i class="bi bi-grid-1x2 text-primary me-2"></i>Financial Check Up
        </h4>
        <p class="text-muted mb-0" style="font-size:.875rem;">Kelola laporan keuangan Anda — Neraca, Arus Kas, dan Hasil FCU.</p>
    </div>
    <a href="{{ route('cashflow.create') }}" class="btn btn-fp btn-fp-primary">
        <i class="bi bi-plus-lg me-1"></i> Buat Baru
    </a>
</div>

@if($reports->isEmpty())
    <div class="fp-card">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-clipboard-data"></i>
            </div>
            <h5 class="fw-bold mb-2">Belum ada laporan</h5>
            <p class="text-muted mb-3" style="font-size:.9rem;">Mulai Financial Check Up pertama Anda dengan mengisi data Neraca dan Arus Kas.</p>
            <a href="{{ route('cashflow.create') }}" class="btn btn-fp btn-fp-primary btn-fp-lg">
                <i class="bi bi-plus-circle me-2"></i> Buat Financial Check Up
            </a>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach($reports as $i => $r)
            @php $r->load('items'); @endphp
            <div class="col-md-6 col-lg-4">
                <div class="report-card">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="rc-avatar" style="background: {{ $gradients[$i % count($gradients)] }};">
                            {{ strtoupper(substr($r->nama, 0, 1)) }}
                        </div>
                        <div>
                            <div class="rc-name">{{ $r->nama }}</div>
                            <div class="rc-period">{{ $r->bulan }} {{ $r->tahun }}</div>
                        </div>
                        <span class="status-badge badge-{{ $r->nilai_bersih > 0 ? 'sehat' : ($r->nilai_bersih == 0 ? 'waspada' : 'bahaya') }} ms-auto" style="font-size:.7rem;">
                            <span class="pulse-dot"></span> {{ $r->getStatusLabel() }}
                        </span>
                    </div>

                    {{-- Quick stats --}}
                    <div style="background:#f8fafc; border-radius:8px; padding:.75rem; margin-bottom:.75rem; font-size:.8rem;">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="color:#64748b;">Total Aset</span>
                            <span style="font-family:'JetBrains Mono',monospace; font-weight:600; color:#059669;">{{ $fmt($r->total_aset) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span style="color:#64748b;">Kekayaan Bersih</span>
                            <span style="font-family:'JetBrains Mono',monospace; font-weight:600; color:{{ $r->kekayaan_bersih >= 0 ? '#059669' : '#dc2626' }};">{{ $fmt($r->kekayaan_bersih) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span style="color:#64748b;">Uang Masuk</span>
                            <span style="font-family:'JetBrains Mono',monospace; font-weight:600; color:#059669;">{{ $fmt($r->total_uang_masuk) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span style="color:#64748b;">Nilai Bersih Dompet</span>
                            <span style="font-family:'JetBrains Mono',monospace; font-weight:600; color:{{ $r->nilai_bersih >= 0 ? '#059669' : '#dc2626' }};">{{ $fmt($r->nilai_bersih) }}</span>
                        </div>
                    </div>

                    <div class="rc-meta">
                        <span><i class="bi bi-list-check me-1"></i>{{ $r->items->count() }} item</span>
                        <span>{{ $r->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="rc-actions">
                        <a href="{{ route('cashflow.show', $r) }}" class="btn btn-sm btn-fp btn-fp-primary" style="flex:1;">
                            <i class="bi bi-eye me-1"></i> Lihat
                        </a>
                        <a href="{{ route('cashflow.edit', $r) }}" class="btn btn-sm btn-fp btn-fp-outline" style="flex:1;">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                        <form action="{{ route('cashflow.destroy', $r) }}" method="POST"
                              onsubmit="return confirm('Hapus laporan {{ $r->nama }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-fp btn-fp-outline" style="color:#dc2626; border-color:#fecaca;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
