<?php

namespace App\Models;
use App\Models\Orderitem;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    
   // app/Models/Order.php
protected $fillable = [
    'customer_name',
    'number',
    'customer_email',
    'address',
    'payment',          // ✅ Add this
    'total_amount',
];

   // Order.php
public function items()
{
    return $this->hasMany(OrderItem::class);
}

}
