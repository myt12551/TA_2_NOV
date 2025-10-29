<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesalePrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'min_qty', 'price', 'item_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'item_id', 'id'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
