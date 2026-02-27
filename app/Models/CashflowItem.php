<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashflowItem extends Model
{
    protected $fillable = ['cashflow_report_id', 'category', 'label', 'amount', 'sort_order'];

    protected $casts = [
        'amount' => 'float',
        'sort_order' => 'integer',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(CashflowReport::class, 'cashflow_report_id');
    }
}
