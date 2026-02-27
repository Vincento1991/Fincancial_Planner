@extends('layouts.app')

@section('title', 'Edit — ' . $report->nama)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="letter-spacing:-.5px;">
            <i class="bi bi-pencil-square text-primary me-2"></i>Edit Financial Check Up
        </h4>
        <p class="text-muted mb-0" style="font-size:.875rem;">{{ $report->nama }} — {{ $report->bulan }} {{ $report->tahun }}</p>
    </div>
</div>

<form action="{{ route('cashflow.update', $report) }}" method="POST">
    @csrf
    @method('PUT')
    @include('cashflow._form')
</form>
@endsection
