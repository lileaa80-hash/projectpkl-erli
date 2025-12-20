<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // 1. Ini untuk mendaftarkan nama 'active'
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // 2. Ini untuk mendaftarkan nama 'activeProducts'
    public function activeProducts()
    {
        return $this->hasMany(Product::class);
    }
    
}