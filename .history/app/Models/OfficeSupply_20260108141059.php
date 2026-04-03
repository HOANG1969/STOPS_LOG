<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeSupply extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'unit',
        'price',
        'stock_quantity',
        'category',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Quan hệ với request items
     */
    public function requestItems()
    {
        return $this->hasMany(RequestItem::class);
    }

    /**
     * Scope để lấy văn phòng phẩm đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Kiểm tra còn hàng trong kho
     */
    public function hasStock($quantity = 1)
    {
        return $this->stock_quantity >= $quantity;
    }

    /**
     * Giảm số lượng trong kho
     */
    public function decreaseStock($quantity)
    {
        if ($this->hasStock($quantity)) {
            $this->decrement('stock_quantity', $quantity);
            return true;
        }
        return false;
    }
}
