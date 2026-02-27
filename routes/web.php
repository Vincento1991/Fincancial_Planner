<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CashflowController;

// Redirect root to cashflow index
Route::get('/', fn() => redirect()->route('cashflow.index'));

// Cashflow CRUD
Route::resource('cashflow', CashflowController::class);

// Print view
Route::get('cashflow/{cashflow}/print', [CashflowController::class, 'print'])->name('cashflow.print');
