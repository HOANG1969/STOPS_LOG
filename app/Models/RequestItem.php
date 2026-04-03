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