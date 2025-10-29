<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceOrderItem extends Model
{
    protected $fillable = ['order_id','item_id','qty','price'];

    public function order()
    {
        return $this->belongsTo(MarketplaceOrder::class, 'order_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
