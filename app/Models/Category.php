<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Quan hệ với products
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope để lấy categories đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}