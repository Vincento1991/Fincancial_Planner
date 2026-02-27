<?php

namespace App\Http\Controllers;

use App\Models\CashflowReport;
use App\Models\CashflowItem;
use Illuminate\Http\Request;

class CashflowController extends Controller
{
    /**
     * Daftar semua laporan
     */
    public function index()
    {
        $reports = CashflowReport::withCount('items')
            ->orderByDesc('created_at')
            ->get();

        return view('cashflow.index', compact('reports'));
    }

    /**
     * Form input baru
     */
    public function create()
    {
        return view('cashflow.create');
    }

    /**
     * Simpan laporan baru beserta semua item
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'bulan' => 'required|string|max:50',
            'tahun' => 'required|string|max:10',
        ]);

        $report = CashflowReport::create($request->only('nama', 'bulan', 'tahun'));

        $this->syncItems($report, $request);

        return redirect()->route('cashflow.show', $report)
            ->with('success', 'Laporan Arus Kas berhasil dibuat!');
    }

    /**
     * Tampilkan laporan lengkap (3 tab: Neraca, Arus Kas, Hasil FCU)
     */
    public function show(CashflowReport $cashflow)
    {
        $cashflow->load('items');
        $report = $cashflow;
        $analysis = $cashflow->getAnalysis();
        $fcuAnalysis = $cashflow->getFcuAnalysis();
        $catatan  = $cashflow->getCatatan();

        return view('cashflow.show', compact('report', 'analysis', 'fcuAnalysis', 'catatan'));
    }

    /**
     * Form edit laporan
     */
    public function edit(CashflowReport $cashflow)
    {
        $cashflow->load('items');
        $report = $cashflow;

        return view('cashflow.edit', compact('report'));
    }

    /**
     * Update laporan
     */
    public function update(Request $request, CashflowReport $cashflow)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'bulan' => 'required|string|max:50',
            'tahun' => 'required|string|max:10',
        ]);

        $cashflow->update($request->only('nama', 'bulan', 'tahun'));

        // Hapus item lama, simpan ulang
        $cashflow->items()->delete();
        $this->syncItems($cashflow, $request);

        return redirect()->route('cashflow.show', $cashflow)
            ->with('success', 'Laporan Arus Kas berhasil diperbarui!');
    }

    /**
     * Hapus laporan
     */
    public function destroy(CashflowReport $cashflow)
    {
        $cashflow->delete();
        return redirect()->route('cashflow.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    /**
     * Print-friendly view
     */
    public function print(CashflowReport $cashflow)
    {
        $cashflow->load('items');
        $report = $cashflow;
        $analysis = $cashflow->getAnalysis();
        $fcuAnalysis = $cashflow->getFcuAnalysis();
        $catatan  = $cashflow->getCatatan();

        return view('cashflow.print', compact('report', 'analysis', 'fcuAnalysis', 'catatan'));
    }

    // =============================================================
    //  PRIVATE HELPERS
    // =============================================================

    /**
     * Parse items dari form request dan simpan ke database.
     * Format input:  items[category][0][label], items[category][0][amount]
     */
    private function syncItems(CashflowReport $report, Request $request): void
    {
        $allItems = $request->input('items', []);

        foreach ($allItems as $category => $rows) {
            if (!is_array($rows)) continue;

            $order = 0;
            foreach ($rows as $row) {
                $label  = trim($row['label'] ?? '');
                $amount = $this->parseAmount($row['amount'] ?? 0);

                if ($label === '' && $amount == 0) continue; // skip empty rows

                $report->items()->create([
                    'category'   => $category,
                    'label'      => $label ?: 'Item',
                    'amount'     => $amount,
                    'sort_order' => $order++,
                ]);
            }
        }
    }

    /**
     * Parse jumlah — support format: 5000000 / 5.000.000 / 5,000,000
     */
    private function parseAmount($raw): float
    {
        $str = trim((string) $raw);
        if ($str === '' || $str === '0') return 0;

        // Remove Rp prefix
        $str = preg_replace('/^[Rr][Pp]\.?\s*/', '', $str);

        // If contains both dots and commas, determine format
        if (str_contains($str, '.') && str_contains($str, ',')) {
            // 5.000.000,00  → ID format
            $str = str_replace('.', '', $str);
            $str = str_replace(',', '.', $str);
        } elseif (str_contains($str, '.')) {
            // Could be 5.000.000 (thousand sep) or 5000.50 (decimal)
            $parts = explode('.', $str);
            $lastPart = end($parts);
            if (count($parts) > 2 || strlen($lastPart) == 3) {
                // Thousand separator (e.g. 189.000 or 1.000.000)
                $str = str_replace('.', '', $str);
            }
            // else: true decimal like 5000.50
        } elseif (str_contains($str, ',')) {
            // 5,000,000 or 5000,50
            $parts = explode(',', $str);
            $lastPart = end($parts);
            if (count($parts) > 2 || strlen($lastPart) == 3) {
                $str = str_replace(',', '', $str);
            } else {
                $str = str_replace(',', '.', $str);
            }
        }

        return (float) preg_replace('/[^0-9.]/', '', $str);
    }
}
