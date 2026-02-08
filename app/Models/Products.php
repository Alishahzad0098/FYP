<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_name',
        'article_name',
        'type',
        'size',
        'fabric',
        'gender',
        'description',
        'price',
        'images',
    ];
    protected $casts = [
        'images' => 'array',
        'size'   => 'array',
    ];
}
