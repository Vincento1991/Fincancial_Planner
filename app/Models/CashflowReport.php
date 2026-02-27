<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashflowReport extends Model
{
    protected $fillable = ['nama', 'bulan', 'tahun'];

    // =========================================================
    //  RELATIONSHIPS
    // =========================================================

    public function items(): HasMany
    {
        return $this->hasMany(CashflowItem::class)->orderBy('sort_order');
    }

    public function itemsByCategory(string $category)
    {
        return $this->items->where('category', $category);
    }

    // =========================================================
    //  NERACA CATEGORIES  (Sheet 1: Neraca Keuangan Pribadi)
    // =========================================================

    public const NERACA_CATEGORIES = [
        'aset_likuid_tabungan' => [
            'label' => 'Tabungan',
            'group' => 'aset_likuid',
            'section' => 'aset',
            'defaults' => ['Bank A', 'Bank B', 'Bank C'],
        ],
        'aset_likuid_others' => [
            'label' => 'Others',
            'group' => 'aset_likuid',
            'section' => 'aset',
            'defaults' => ['Deposito', 'Reksadana Pasar Uang', 'Obligasi'],
        ],
        'aset_investasi_neraca' => [
            'label' => 'Aset Investasi',
            'group' => 'aset_investasi',
            'section' => 'aset',
            'defaults' => ['Apartemen', 'Rumah Kontrakan', 'Tanah', 'Rumah Kedua', 'Sawah', 'Logam Mulia'],
        ],
        'aset_investasi_belum_cair' => [
            'label' => 'Aset Investasi Belum Dicairkan',
            'group' => 'aset_investasi',
            'section' => 'aset',
            'defaults' => ['Simpanan Tetap u/ Pensiun', 'DPLK', 'Jamsostek', 'Koperasi', 'Saham'],
        ],
        'aset_pribadi' => [
            'label' => 'Aset Penggunaan Pribadi',
            'group' => 'aset_pribadi',
            'section' => 'aset',
            'defaults' => ['Rumah', 'Mobil', 'Motor', 'Emas Perhiasan', 'Aset Pribadi Lainnya'],
        ],
        'neraca_utang_pendek' => [
            'label' => 'Kewajiban (Utang) Jangka Pendek',
            'group' => 'kewajiban',
            'section' => 'kewajiban',
            'defaults' => ['Kredit Kepemilikan Mobil', 'Kartu Kredit', 'KTA', 'Paylater'],
        ],
        'neraca_utang_panjang' => [
            'label' => 'Kewajiban (Utang) Jangka Panjang',
            'group' => 'kewajiban',
            'section' => 'kewajiban',
            'defaults' => ['KPA', 'KPR'],
        ],
    ];

    // =========================================================
    //  ARUS KAS CATEGORIES  (Sheet 2: Arus Kas)
    // =========================================================

    public const CATEGORIES = [
        'uang_masuk_tetap' => [
            'label' => 'Uang Masuk Tetap',
            'group' => 'income',
            'defaults' => ['Gaji', 'Tunjangan', 'Pensiun'],
        ],
        'uang_masuk_tidak_tetap' => [
            'label' => 'Uang Masuk Tidak Tetap',
            'group' => 'income',
            'defaults' => ['Sewa Apartemen', 'Honor & Komisi', 'Freelance'],
        ],
        'pengeluaran_rt' => [
            'label' => 'Pengeluaran Rumah Tangga',
            'group' => 'expense',
            'parent' => 'konsumtif',
            'defaults' => ['Makanan', 'Kebutuhan RT', 'Membantu Keluarga', 'Gaji Asisten RT', 'Gaji Supir', 'Zakat / Perpuluhan', 'Pajak Mobil', 'Pajak Rumah', 'Suplemen', 'Iuran Keamanan / Lingkungan', 'Susu/Pampers'],
        ],
        'pengeluaran_konsumtif' => [
            'label' => 'Pengeluaran Konsumtif',
            'group' => 'expense',
            'parent' => 'konsumtif',
            'defaults' => ['Telepon Selular', 'Internet', 'Listrik', 'Uang Saku Anak', 'Uang Saku Ibu (Istri)', 'Uang Saku Ayah (Suami)', 'Transportasi'],
        ],
        'pengeluaran_pendidikan' => [
            'label' => 'Pengeluaran Pendidikan',
            'group' => 'expense',
            'parent' => 'pendidikan',
            'defaults' => ['Iuran Sekolah', 'Semesteran (bulanan)', 'Les'],
        ],
        'gaya_hidup' => [
            'label' => 'Pengeluaran Gaya Hidup (Lifestyle)',
            'group' => 'expense',
            'parent' => 'lifestyle',
            'defaults' => ['Hiburan Keluarga (Healing, Mall, Padel)', 'Arisan', 'Belanja Online', 'Skin Care'],
        ],
        'utang_pendek' => [
            'label' => 'Utang Jangka Pendek',
            'group' => 'expense',
            'parent' => 'utang',
            'defaults' => ['Kredit Kendaraan', 'KTA', 'Kartu Kredit', 'Paylater'],
        ],
        'utang_panjang' => [
            'label' => 'Utang Jangka Panjang',
            'group' => 'expense',
            'parent' => 'utang',
            'defaults' => ['KPR (Rumah)', 'KPA (Apartemen)'],
        ],
        'investasi' => [
            'label' => 'Investasi / Tabungan',
            'group' => 'expense',
            'parent' => 'investasi',
            'defaults' => ['Menabung', 'Reksadana'],
        ],
        'proteksi' => [
            'label' => 'Proteksi / Asuransi',
            'group' => 'expense',
            'parent' => 'proteksi',
            'defaults' => ['BPJS Kesehatan', 'Premi Asuransi A', 'Premi Asuransi B', 'Premi C'],
        ],
    ];

    // =========================================================
    //  NERACA TOTALS  (persis rumus Excel Sheet 1)
    // =========================================================

    public function categoryTotal(string $category): float
    {
        return (float) $this->itemsByCategory($category)->sum('amount');
    }

    /** A. Sub Total Tabungan */
    public function getSubTotalTabunganAttribute(): float
    {
        return $this->categoryTotal('aset_likuid_tabungan');
    }

    /** B. Sub Total Others */
    public function getSubTotalOthersAttribute(): float
    {
        return $this->categoryTotal('aset_likuid_others');
    }

    /** C. Total Aset Likuid (A+B) */
    public function getTotalAsetLikuidAttribute(): float
    {
        return $this->sub_total_tabungan + $this->sub_total_others;
    }

    /** D. Sub Total Aset Investasi */
    public function getSubTotalAsetInvestasiAttribute(): float
    {
        return $this->categoryTotal('aset_investasi_neraca');
    }

    /** Sub Total Aset Investasi Belum Dicairkan */
    public function getSubTotalAsetBelumCairAttribute(): float
    {
        return $this->categoryTotal('aset_investasi_belum_cair');
    }

    /** E. Total Aset Investasi (D + belum cair) */
    public function getTotalAsetInvestasiNeracaAttribute(): float
    {
        return $this->sub_total_aset_investasi + $this->sub_total_aset_belum_cair;
    }

    /** F. Total Aset Penggunaan Pribadi */
    public function getTotalAsetPribadiAttribute(): float
    {
        return $this->categoryTotal('aset_pribadi');
    }

    /** G. TOTAL ASET (C+E+F) */
    public function getTotalAsetAttribute(): float
    {
        return $this->total_aset_likuid + $this->total_aset_investasi_neraca + $this->total_aset_pribadi;
    }

    /** H. Total Utang Jangka Pendek (Neraca) */
    public function getTotalNeracaUtangPendekAttribute(): float
    {
        return $this->categoryTotal('neraca_utang_pendek');
    }

    /** I. Total Utang Jangka Panjang (Neraca) */
    public function getTotalNeracaUtangPanjangAttribute(): float
    {
        return $this->categoryTotal('neraca_utang_panjang');
    }

    /** J. TOTAL KEWAJIBAN (H+I) */
    public function getTotalKewajibanAttribute(): float
    {
        return $this->total_neraca_utang_pendek + $this->total_neraca_utang_panjang;
    }

    /** K. KEKAYAAN BERSIH (G-J) */
    public function getKekayaanBersihAttribute(): float
    {
        return $this->total_aset - $this->total_kewajiban;
    }

    // =========================================================
    //  ARUS KAS TOTALS  (persis rumus Excel Sheet 2)
    // =========================================================

    /** A. Total Uang Masuk Tetap */
    public function getTotalUangMasukTetapAttribute(): float
    {
        return $this->categoryTotal('uang_masuk_tetap');
    }

    /** B. Total Uang Masuk Tidak Tetap */
    public function getTotalUangMasukTidakTetapAttribute(): float
    {
        return $this->categoryTotal('uang_masuk_tidak_tetap');
    }

    /** C. TOTAL UANG MASUK (A+B) — basis semua persentase */
    public function getTotalUangMasukAttribute(): float
    {
        return $this->total_uang_masuk_tetap + $this->total_uang_masuk_tidak_tetap;
    }

    /** D. Total Pengeluaran RT */
    public function getTotalPengeluaranRtAttribute(): float
    {
        return $this->categoryTotal('pengeluaran_rt');
    }

    /** Sub Total Pengeluaran Konsumtif & RT — target 40% */
    public function getTotalPengeluaranKonsumtifAttribute(): float
    {
        return $this->total_pengeluaran_rt + $this->categoryTotal('pengeluaran_konsumtif');
    }

    /** E. Total Pengeluaran Pendidikan — target 10% */
    public function getTotalPendidikanAttribute(): float
    {
        return $this->categoryTotal('pengeluaran_pendidikan');
    }

    /** TOTAL PENGELUARAN TETAP = Konsumtif + Pendidikan (target 50%) */
    public function getTotalPengeluaranTetapAttribute(): float
    {
        return $this->total_pengeluaran_konsumtif + $this->total_pendidikan;
    }

    /** Gaya Hidup — target 5% */
    public function getTotalGayaHidupAttribute(): float
    {
        return $this->categoryTotal('gaya_hidup');
    }

    /** TOTAL PENGELUARAN KONSUMTIF ALL = Tetap + Lifestyle (target 55%) */
    public function getTotalPengeluaranKonsumtifAllAttribute(): float
    {
        return $this->total_pengeluaran_tetap + $this->total_gaya_hidup;
    }

    /** G. Total Utang Jangka Pendek (Arus Kas) — target 15% */
    public function getTotalUtangPendekAttribute(): float
    {
        return $this->categoryTotal('utang_pendek');
    }

    /** H. Total Utang Jangka Panjang (Arus Kas) — target 15% */
    public function getTotalUtangPanjangAttribute(): float
    {
        return $this->categoryTotal('utang_panjang');
    }

    /** TOTAL UTANG (G+H) — target 30% */
    public function getTotalUtangAttribute(): float
    {
        return $this->total_utang_pendek + $this->total_utang_panjang;
    }

    /** J. Total Investasi — target 10% */
    public function getTotalInvestasiAttribute(): float
    {
        return $this->categoryTotal('investasi');
    }

    /** K. Total Proteksi — target 5% */
    public function getTotalProteksiAttribute(): float
    {
        return $this->categoryTotal('proteksi');
    }

    /** M. TOTAL UANG KELUAR */
    public function getTotalUangKeluarAttribute(): float
    {
        return $this->total_pengeluaran_konsumtif_all
             + $this->total_utang
             + $this->total_investasi
             + $this->total_proteksi;
    }

    /** N. NILAI BERSIH DOMPET = C − M */
    public function getNilaiBersihAttribute(): float
    {
        return $this->total_uang_masuk - $this->total_uang_keluar;
    }

    // =========================================================
    //  PERCENTAGE & STATUS HELPERS
    // =========================================================

    public function pct(float $amount): float
    {
        if ($this->total_uang_masuk == 0) return 0;
        return round(($amount / $this->total_uang_masuk) * 100, 2);
    }

    public function statusIcon(float $realita, float $ideal, bool $inverse = false): string
    {
        if ($inverse) {
            return $realita >= $ideal ? '✅' : '❌';
        }
        return $realita <= $ideal ? '✅' : '❌';
    }

    public function getStatusLabel(): string
    {
        if ($this->nilai_bersih > 0) return 'Sehat';
        if ($this->nilai_bersih == 0) return 'Waspada';
        return 'Bahaya';
    }

    public function getStatusBadge(): string
    {
        if ($this->nilai_bersih > 0) return 'success';
        if ($this->nilai_bersih == 0) return 'warning';
        return 'danger';
    }

    // =========================================================
    //  ARUS KAS ANALYSIS TABLE
    // =========================================================

    public function getAnalysis(): array
    {
        return [
            [
                'kategori' => 'Pengeluaran Konsumtif',
                'amount'   => $this->total_pengeluaran_konsumtif,
                'realita'  => $this->pct($this->total_pengeluaran_konsumtif),
                'ideal'    => 40,
                'icon'     => $this->statusIcon($this->pct($this->total_pengeluaran_konsumtif), 40),
            ],
            [
                'kategori' => 'Pengeluaran Pendidikan',
                'amount'   => $this->total_pendidikan,
                'realita'  => $this->pct($this->total_pendidikan),
                'ideal'    => 10,
                'icon'     => $this->statusIcon($this->pct($this->total_pendidikan), 10),
            ],
            [
                'kategori' => 'Hiburan / Gaya Hidup',
                'amount'   => $this->total_gaya_hidup,
                'realita'  => $this->pct($this->total_gaya_hidup),
                'ideal'    => 5,
                'icon'     => $this->statusIcon($this->pct($this->total_gaya_hidup), 5),
            ],
            [
                'kategori' => 'Utang Konsumtif (Jk. Pendek)',
                'amount'   => $this->total_utang_pendek,
                'realita'  => $this->pct($this->total_utang_pendek),
                'ideal'    => 15,
                'icon'     => $this->statusIcon($this->pct($this->total_utang_pendek), 15),
            ],
            [
                'kategori' => 'Utang Produktif (Jk. Panjang)',
                'amount'   => $this->total_utang_panjang,
                'realita'  => $this->pct($this->total_utang_panjang),
                'ideal'    => 15,
                'icon'     => $this->statusIcon($this->pct($this->total_utang_panjang), 15),
            ],
            [
                'kategori' => 'Menabung / Investasi',
                'amount'   => $this->total_investasi,
                'realita'  => $this->pct($this->total_investasi),
                'ideal'    => 10,
                'icon'     => $this->statusIcon($this->pct($this->total_investasi), 10, true),
            ],
            [
                'kategori' => 'Asuransi / Proteksi',
                'amount'   => $this->total_proteksi,
                'realita'  => $this->pct($this->total_proteksi),
                'ideal'    => 5,
                'icon'     => $this->statusIcon($this->pct($this->total_proteksi), 5, true),
            ],
        ];
    }

    // =========================================================
    //  HASIL FCU — 8 Rasio Keuangan  (Sheet 3)
    // =========================================================

    public function getFcuAnalysis(): array
    {
        $fmt = fn($v) => 'Rp ' . number_format($v, 0, ',', '.');
        $totalAset = $this->total_aset;
        $totalKewajiban = $this->total_kewajiban;
        $kekayaanBersih = $this->kekayaan_bersih;
        $asetLikuid = $this->total_aset_likuid;
        $totalUangMasuk = $this->total_uang_masuk;
        $totalUangKeluar = $this->total_uang_keluar;
        $totalInvestasi = $this->total_investasi;
        $totalUtangArusKas = $this->total_utang;
        $totalUtangPendekArusKas = $this->total_utang_pendek;

        // 1. Nilai Bersih Kekayaan
        $rasioKewajiban = $totalAset > 0 ? round(($totalKewajiban / $totalAset) * 100, 1) : 0;
        if ($totalKewajiban > $totalAset) {
            $statusNBK = 'Bangkrut';
            $badgeNBK = 'badge-bahaya';
        } elseif ($rasioKewajiban <= 50) {
            $statusNBK = 'Aman';
            $badgeNBK = 'badge-sehat';
        } else {
            $statusNBK = 'Belum Aman';
            $badgeNBK = 'badge-waspada';
        }

        // 2. Rasio Likuiditas (Dana Darurat)
        $pengeluaranBulanan = $totalUangKeluar;
        $danaDarurat = $asetLikuid;
        $rasioLikuiditas = $pengeluaranBulanan > 0 ? round($danaDarurat / $pengeluaranBulanan, 1) : 0;
        $ddIdeal3 = $pengeluaranBulanan * 3;
        $ddIdeal6 = $pengeluaranBulanan * 6;
        if ($rasioLikuiditas >= 6) {
            $statusDD = 'Sangat Baik';
            $badgeDD = 'badge-sehat';
        } elseif ($rasioLikuiditas >= 3) {
            $statusDD = 'Cukup';
            $badgeDD = 'badge-sehat';
        } else {
            $statusDD = 'Kurang';
            $badgeDD = 'badge-bahaya';
        }

        // 3. Rasio Aset Likuid vs Kekayaan Bersih (≥15% baik)
        $rasioAsetLikuid = $kekayaanBersih > 0 ? round(($asetLikuid / $kekayaanBersih) * 100, 1) : 0;
        $statusAL = $rasioAsetLikuid >= 15 ? 'Baik' : 'Kurang';
        $badgeAL = $rasioAsetLikuid >= 15 ? 'badge-sehat' : 'badge-bahaya';

        // 4. Rasio Tabungan (≥10% baik)
        $rasioTabungan = $totalUangMasuk > 0 ? round(($totalInvestasi / $totalUangMasuk) * 100, 1) : 0;
        $statusTab = $rasioTabungan >= 10 ? 'Baik' : 'Kurang';
        $badgeTab = $rasioTabungan >= 10 ? 'badge-sehat' : 'badge-bahaya';

        // 5. Rasio Utang terhadap Aset (≤50% baik)
        $rasioUtangAset = $totalAset > 0 ? round(($totalKewajiban / $totalAset) * 100, 1) : 0;
        $statusUA = $rasioUtangAset <= 50 ? 'Baik' : 'Bahaya';
        $badgeUA = $rasioUtangAset <= 50 ? 'badge-sehat' : 'badge-bahaya';

        // 6. Rasio Pelunasan Hutang (≤30% baik)
        $rasioHutang = $totalUangMasuk > 0 ? round(($totalUtangArusKas / $totalUangMasuk) * 100, 1) : 0;
        $statusPH = $rasioHutang <= 30 ? 'Baik' : 'Bahaya';
        $badgePH = $rasioHutang <= 30 ? 'badge-sehat' : 'badge-bahaya';

        // 7. Rasio Hutang Non Hipotik (≤15% baik)
        $rasioNonHipotik = $totalUangMasuk > 0 ? round(($totalUtangPendekArusKas / $totalUangMasuk) * 100, 1) : 0;
        $statusNH = $rasioNonHipotik <= 15 ? 'Baik' : 'Bahaya';
        $badgeNH = $rasioNonHipotik <= 15 ? 'badge-sehat' : 'badge-bahaya';

        // 8. Rasio Aset Investasi vs Aset Bersih (≥50% baik)
        $asetInvNeraca = $this->total_aset_investasi_neraca;
        $rasioInvAset = $kekayaanBersih > 0 ? round(($asetInvNeraca / $kekayaanBersih) * 100, 1) : 0;
        $statusIA = $rasioInvAset >= 50 ? 'Baik' : 'Kurang';
        $badgeIA = $rasioInvAset >= 50 ? 'badge-sehat' : 'badge-bahaya';

        return [
            [
                'no' => 1,
                'title' => 'Nilai Bersih Kekayaan Anda',
                'description' => 'Bila hasilnya positif > 50%, Anda aman; Bila kewajiban (utang) lebih besar dari aset, maka Anda dinyatakan bangkrut.',
                'details' => [
                    ['label' => 'Total Aset', 'value' => $fmt($totalAset)],
                    ['label' => 'Total Kewajiban', 'value' => $fmt($totalKewajiban)],
                    ['label' => 'Kekayaan Bersih', 'value' => $fmt($kekayaanBersih)],
                    ['label' => 'Rasio Kewajiban/Aset', 'value' => $rasioKewajiban . '%'],
                ],
                'status' => $statusNBK,
                'badge' => $badgeNBK,
            ],
            [
                'no' => 2,
                'title' => 'Rasio Likuiditas (Dana Darurat)',
                'description' => 'Dana darurat ideal 3 bulan – 6 bulan pengeluaran.',
                'details' => [
                    ['label' => 'Pengeluaran Bulanan', 'value' => $fmt($pengeluaranBulanan)],
                    ['label' => 'Dana Darurat Saat Ini', 'value' => $fmt($danaDarurat)],
                    ['label' => 'Cukup untuk', 'value' => $rasioLikuiditas . ' bulan'],
                    ['label' => 'Ideal (3 bulan)', 'value' => $fmt($ddIdeal3)],
                    ['label' => 'Ideal (6 bulan)', 'value' => $fmt($ddIdeal6)],
                ],
                'status' => $statusDD,
                'badge' => $badgeDD,
            ],
            [
                'no' => 3,
                'title' => 'Rasio Aset Likuid vs Kekayaan Bersih',
                'description' => 'Aset likuid sama dengan 15% dari kekayaan bersih dinilai baik.',
                'details' => [
                    ['label' => 'Aset Likuid', 'value' => $fmt($asetLikuid)],
                    ['label' => 'Kekayaan Bersih', 'value' => $fmt($kekayaanBersih)],
                    ['label' => 'Rasio', 'value' => $rasioAsetLikuid . '%'],
                ],
                'status' => $statusAL,
                'badge' => $badgeAL,
                'target' => '≥ 15%',
            ],
            [
                'no' => 4,
                'title' => 'Rasio Tabungan',
                'description' => 'Menabung / berinvestasi minimal 10% dari penghasilan, lebih besar tentu lebih baik.',
                'details' => [
                    ['label' => 'Investasi/Tabungan', 'value' => $fmt($totalInvestasi)],
                    ['label' => 'Total Penghasilan', 'value' => $fmt($totalUangMasuk)],
                    ['label' => 'Rasio', 'value' => $rasioTabungan . '%'],
                ],
                'status' => $statusTab,
                'badge' => $badgeTab,
                'target' => '≥ 10%',
            ],
            [
                'no' => 5,
                'title' => 'Rasio Utang terhadap Aset',
                'description' => 'Maksimum 50% dari aset masih diperbolehkan, dengan tujuan ketika pensiun nanti utang sudah 0%.',
                'details' => [
                    ['label' => 'Total Utang (Kewajiban)', 'value' => $fmt($totalKewajiban)],
                    ['label' => 'Total Aset', 'value' => $fmt($totalAset)],
                    ['label' => 'Rasio', 'value' => $rasioUtangAset . '%'],
                ],
                'status' => $statusUA,
                'badge' => $badgeUA,
                'target' => '≤ 50%',
            ],
            [
                'no' => 6,
                'title' => 'Rasio Kemampuan Pelunasan Hutang',
                'description' => 'Maksimum total cicilan Utang (Kewajiban) adalah 30% termasuk hutang KPR.',
                'details' => [
                    ['label' => 'Total Cicilan/bln', 'value' => $fmt($totalUtangArusKas)],
                    ['label' => 'Total Penghasilan', 'value' => $fmt($totalUangMasuk)],
                    ['label' => 'Rasio', 'value' => $rasioHutang . '%'],
                ],
                'status' => $statusPH,
                'badge' => $badgePH,
                'target' => '≤ 30%',
            ],
            [
                'no' => 7,
                'title' => 'Rasio Kemampuan Pelunasan Hutang Non Hipotik',
                'description' => 'Utang Konsumtif maksimal 15% dari penghasilan.',
                'details' => [
                    ['label' => 'Cicilan Non-Hipotik', 'value' => $fmt($totalUtangPendekArusKas)],
                    ['label' => 'Total Penghasilan', 'value' => $fmt($totalUangMasuk)],
                    ['label' => 'Rasio', 'value' => $rasioNonHipotik . '%'],
                ],
                'status' => $statusNH,
                'badge' => $badgeNH,
                'target' => '≤ 15%',
            ],
            [
                'no' => 8,
                'title' => 'Rasio Aset Investasi Bersih vs Aset Bersih',
                'description' => 'Nilai 50% atau lebih dianggap baik.',
                'details' => [
                    ['label' => 'Aset Investasi', 'value' => $fmt($asetInvNeraca)],
                    ['label' => 'Kekayaan Bersih', 'value' => $fmt($kekayaanBersih)],
                    ['label' => 'Rasio', 'value' => $rasioInvAset . '%'],
                ],
                'status' => $statusIA,
                'badge' => $badgeIA,
                'target' => '≥ 50%',
            ],
        ];
    }

    // =========================================================
    //  CATATAN PERENCANA KEUANGAN  (smart advice)
    // =========================================================

    public function getCatatan(): array
    {
        $obs = [];
        $saran = [];
        $fmt = fn($v) => 'Rp ' . number_format($v, 0, ',', '.');

        // 1. Surplus / Defisit
        if ($this->nilai_bersih < 0) {
            $obs[] = 'Anda mengalami DEFISIT sebesar ' . $fmt(abs($this->nilai_bersih)) . '. Pengeluaran melebihi pendapatan!';
            $saran[] = 'Segera pangkas pengeluaran atau cari sumber pendapatan tambahan agar tidak terus defisit.';
        } else {
            $obs[] = 'Keuangan Anda SURPLUS ' . $fmt($this->nilai_bersih) . '. Pertahankan dan alokasikan surplus ke investasi.';
        }

        // 2. Kekayaan Bersih
        if ($this->total_aset > 0) {
            $rasioKewajiban = ($this->total_kewajiban / $this->total_aset) * 100;
            if ($this->total_kewajiban > $this->total_aset) {
                $obs[] = 'PERINGATAN: Kewajiban melebihi aset — secara teknis Anda bangkrut.';
                $saran[] = 'Segera kurangi utang dan hindari utang baru. Fokus pelunasan utang berbunga tertinggi.';
            } elseif ($rasioKewajiban > 50) {
                $obs[] = 'Rasio kewajiban ' . round($rasioKewajiban, 1) . '% — belum aman (target ≤ 50%).';
                $saran[] = 'Kurangi utang secara bertahap agar rasio kewajiban di bawah 50%.';
            }
        }

        // 3. Dana Darurat
        if ($this->total_uang_keluar > 0) {
            $bulanDD = $this->total_aset_likuid / $this->total_uang_keluar;
            if ($bulanDD < 3) {
                $obs[] = 'Dana darurat hanya cukup ' . round($bulanDD, 1) . ' bulan (ideal 3-6 bulan).';
                $saran[] = 'Tingkatkan tabungan/aset likuid hingga minimal 3 bulan pengeluaran (' . $fmt($this->total_uang_keluar * 3) . ').';
            }
        }

        // 4. Konsumtif
        $p = $this->pct($this->total_pengeluaran_konsumtif);
        if ($p > 40) {
            $obs[] = "Pengeluaran Konsumtif & RT {$p}% — melebihi batas ideal 40%.";
            $gap = ($p - 40) / 100 * $this->total_uang_masuk;
            $saran[] = 'Kurangi pengeluaran konsumtif minimal ' . $fmt($gap) . '/bulan untuk kembali ke 40%.';
        }

        // 5. Utang
        $p = $this->pct($this->total_utang);
        if ($p > 30) {
            $obs[] = "Total cicilan Utang {$p}% — MELAMPAUI batas aman 30%!";
            $saran[] = 'Prioritaskan pelunasan utang berbunga tinggi (Paylater, Kartu Kredit, KTA).';
        }

        // 6. Investasi
        $p = $this->pct($this->total_investasi);
        if ($p < 10) {
            $gap = $this->total_uang_masuk * 0.10 - $this->total_investasi;
            $obs[] = "Alokasi Investasi/Tabungan hanya {$p}% — belum mencapai target 10%.";
            if ($gap > 0) {
                $saran[] = 'Tambahkan investasi minimal ' . $fmt($gap) . '/bulan untuk mencapai 10%.';
            }
        } else {
            $obs[] = "Investasi {$p}% sudah memenuhi target 10%. Pertahankan!";
        }

        // 7. Proteksi
        if ($this->total_proteksi == 0) {
            $obs[] = 'Belum ada alokasi Proteksi/Asuransi. Ini risiko besar bagi finansial keluarga.';
            $saran[] = 'Mulai dari BPJS Kesehatan, dan tambahkan asuransi jiwa jika ada tanggungan.';
        } elseif ($this->pct($this->total_proteksi) < 5) {
            $saran[] = 'Pertimbangkan menambah proteksi asuransi untuk mencapai 5%.';
        }

        if (empty($saran)) {
            $saran[] = 'Keuangan Anda sudah cukup sehat. Terus tingkatkan investasi dan disiplin anggaran.';
        }

        return [
            'observasi' => array_slice($obs, 0, 6),
            'saran'     => array_slice($saran, 0, 6),
        ];
    }
}
