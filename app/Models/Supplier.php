<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // DEFINE GUARDED PROPERTY
    protected $guarded = ['id'];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_supplier');
    }

    public function products()
    {
        return $this->hasMany(SupplierProduct::class);
    }
    public function supplierProducts()
    {
        return $this->hasMany(SupplierProduct::class);
    }



}
