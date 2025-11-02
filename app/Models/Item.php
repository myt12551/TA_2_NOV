<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

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
        'picture',
        'unit'
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
        // Jika tidak ada gambar
        if (empty($this->picture)) {
            return asset('images/no-image.png');
        }

        // Jika gambar adalah URL lengkap
        if (filter_var($this->picture, FILTER_VALIDATE_URL)) {
            return $this->picture;
        }

        // Coba cari gambar di storage publik
        $filename = basename($this->picture);
        if (Storage::disk('public')->exists("items/{$filename}")) {
            return asset("storage/items/{$filename}");
        }

        // Coba cari di folder public/images/items
        if (file_exists(public_path("images/items/{$filename}"))) {
            return asset("images/items/{$filename}");
        }

        // Fallback ke gambar default
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

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'item_supplier');
    }

    public function supplierProducts()
    {
        return $this->hasMany(SupplierProduct::class);
    }
}