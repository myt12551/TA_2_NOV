<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'name',
        'code',
        'category_id',
        'cost_price',
        'selling_price',
        'stock',
        'picture'
    ];

    protected $casts = [
        'cost_price'    => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock'         => 'integer',
        'category_id'   => 'integer',
    ];

    public function getSellingPriceFormattedAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->selling_price, 0, ',', '.');
    }

    public function getPhotoUrlAttribute(): string
    {
        $p = (string) ($this->picture ?? '');

        if ($p && preg_match('#^https?://#i', $p)) {
            return $p;
        }
        if ($p && file_exists(public_path('storage/' . $p))) {
            return asset('storage/' . $p);
        }
        if ($p && file_exists(public_path('images/items/' . $p))) {
            return asset('images/items/' . $p);
        }

        return asset('images/no-image.png');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function wholesalePrices(): HasMany
    {
        return $this->hasMany(WholesalePrice::class);
    }

    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function stockMovementAnalysis()
    {
        return $this->hasOne(StockMovementAnalysis::class);
    }
}