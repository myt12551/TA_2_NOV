<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceOrder extends Model
{
    protected $fillable = [
        'user_id','code','status','pickup_name','phone','notes','total_price',
    ];

    public function items()
    {
        return $this->hasMany(MarketplaceOrderItem::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
