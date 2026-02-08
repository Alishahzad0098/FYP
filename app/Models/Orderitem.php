<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orderitem extends Model
{
    protected $fillable = [
        'order_id',
        'brand_name',
        'article_name',
        'type',
        'size',
        'fabric',
        'gender',
        'description',
        'price',
        'quantity',
        'images',
    ];

    protected $casts = [
        'images' => 'array', // automatically decode JSON to array
    ];

    // Relationship to Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
