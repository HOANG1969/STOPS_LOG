<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_code',
        'user_id',
        'requester_name',
        'requester_email',
        'requester_position',
        'request_date',
        'department',
        'requester_department',
        'priority',
        'needed_date',
        'period',
        'status',
        'approved_by',
        'approved_at',
        'forwarded_at',
        'approval_notes',
        'rejection_reason',
        'notes'
    ];

    protected $casts = [
        'needed_date' => 'date',
        'request_date' => 'date',
        'approved_at' => 'datetime',
        'forwarded_at' => 'datetime'
    ];

    /**
     * Quan hệ với user (người tạo yêu cầu)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ với approver (người phê duyệt)
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Quan hệ với request items
     */
    public function requestItems()
    {
        return $this->hasMany(RequestItem::class);
    }

    /**
     * Scope cho status
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope theo department
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('requester_department', $department);
    }

    /**
     * Generate request code
     */
    public static function generateRequestCode()
    {
        $prefix = 'REQ';
        $date = now()->format('Ymd');
        $lastRequest = static::whereDate('created_at', today())
                            ->orderBy('id', 'desc')
                            ->first();
        
        $sequence = $lastRequest ? (int)substr($lastRequest->request_code, -3) + 1 : 1;
        
        return $prefix . $date . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Check if can be approved by user
     */
    public function canBeApprovedBy(User $user)
    {
        return $user->canApprove($this) && in_array($this->status, ['pending', 'forwarded']);
    }
}
