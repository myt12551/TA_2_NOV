<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovementAnalysis extends Model
{
    protected $fillable = [
        'item_id',
        'total_sold_30_days',
        'avg_daily_sales',
        'movement_status',
        'current_stock',
        'days_until_empty',
        'non_moving_days',
        'stuck_stock_value',
        'recommendation',
        'suggested_reorder_qty',
        'last_sale_date',
        'last_analysis_date'
    ];

    protected $casts = [
        'last_sale_date' => 'datetime',
        'last_analysis_date' => 'datetime'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}