<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventorySetting extends Model
{
    protected $fillable = [
        'analysis_period',
        'fast_moving_threshold',
        'slow_moving_threshold',
        'lead_time_days',
        'safety_stock_days'
    ];
}