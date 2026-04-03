<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'approver_id',
        'level',
        'status',
        'comments',
        'approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime'
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saved(function ($approval) {
            // Cập nhật trạng thái request sau khi approval thay đổi
            $approval->request->updateStatusBasedOnApprovals();
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
     * Quan hệ với approver (người phê duyệt)
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Approve request
     */
    public function approve($comments = null)
    {
        $this->update([
            'status' => 'approved',
            'comments' => $comments,
            'approved_at' => now()
        ]);
    }

    /**
     * Reject request
     */
    public function reject($comments = null)
    {
        $this->update([
            'status' => 'rejected',
            'comments' => $comments,
            'approved_at' => now()
        ]);
    }

    /**
     * Kiểm tra trạng thái
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Lấy màu status
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Lấy tên tiếng Việt của status
     */
    public function getStatusNameAttribute()
    {
        $names = [
            'pending' => 'Chờ phê duyệt',
            'approved' => 'Đã phê duyệt',
            'rejected' => 'Từ chối'
        ];

        return $names[$this->status] ?? 'Không xác định';
    }

    /**
     * Lấy tên tiếng Việt của level
     */
    public function getLevelNameAttribute()
    {
        $names = [
            'manager' => 'Quản lý',
            'director' => 'Giám đốc',
            'admin' => 'Admin'
        ];

        return $names[$this->level] ?? 'Không xác định';
    }
}