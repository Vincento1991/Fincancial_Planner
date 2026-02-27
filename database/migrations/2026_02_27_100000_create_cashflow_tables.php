<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cashflow_reports', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('bulan');
            $table->string('tahun');
            $table->timestamps();
        });

        Schema::create('cashflow_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashflow_report_id')->constrained()->cascadeOnDelete();
            // Categories match the Excel structure:
            // uang_masuk_tetap, uang_masuk_tidak_tetap,
            // pengeluaran_rt, pengeluaran_konsumtif, pengeluaran_pendidikan,
            // gaya_hidup, utang_pendek, utang_panjang,
            // investasi, proteksi
            $table->string('category');
            $table->string('label');
            $table->decimal('amount', 15, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashflow_items');
        Schema::dropIfExists('cashflow_reports');
    }
};
