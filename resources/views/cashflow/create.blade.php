@extends('layouts.app')

@section('title', 'Buat Financial Check Up')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="letter-spacing:-.5px;">
            <i class="bi bi-plus-circle text-primary me-2"></i>Buat Financial Check Up Baru
        </h4>
        <p class="text-muted mb-0" style="font-size:.875rem;">Isi data Neraca dan Arus Kas untuk analisa keuangan lengkap.</p>
    </div>
</div>

<form action="{{ route('cashflow.store') }}" method="POST">
    @csrf
    @include('cashflow._form')
</form>
@endsection
