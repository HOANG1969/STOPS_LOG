<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'supply_request_id',
        'office_supply_id',
        'quantity',
        'purpose'
    ];

    protected $casts = [
        'quantity' => 'integer'
    ];

    /**
     * Quan hệ với supply request
     */
    public function supplyRequest()
    {
        return $this->belongsTo(SupplyRequest::class);
    }

    /**
     * Quan hệ với office supply
     */
    public function officeSupply()
    {
        return $this->belongsTo(OfficeSupply::class);
    }

    /**
     * Tính tổng giá
     */
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->officeSupply->price;
    }

    /**
     * Get unit price from office supply
     */
    public function getUnitPriceAttribute()
    {
        return $this->officeSupply->price;
    }
}

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    /**
     * Boot method để tự động tính total_price
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($requestItem) {
            $requestItem->total_price = $requestItem->quantity * $requestItem->unit_price;
        });
        
        static::saved(function ($requestItem) {
            // Cập nhật tổng tiền của request
            $requestItem->request->calculateTotalAmount();
        });
        
        static::deleted(function ($requestItem) {
            // Cập nhật tổng tiền của request khi xóa item
            $requestItem->request->calculateTotalAmount();
        });
    }

    /**
     * Quan hệ với request
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    /**
     * Quan hệ với product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Format unit_price
     */
    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 0, ',', '.') . ' VND';
    }

    /**
     * Format total_price
     */
    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 0, ',', '.') . ' VND';
    }
}