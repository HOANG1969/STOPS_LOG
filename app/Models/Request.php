<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_number',
        'user_id',
        'title',
        'purpose',
        'needed_date',
        'priority',
        'status',
        'total_amount',
        'notes'
    ];

    protected $casts = [
        'needed_date' => 'date',
        'total_amount' => 'decimal:2'
    ];

    /**
     * Boot method để tự động tạo request_number
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($request) {
            if (empty($request->request_number)) {
                $request->request_number = static::generateRequestNumber();
            }
        });
    }

    /**
     * Tạo mã đơn yêu cầu tự động
     */
    public static function generateRequestNumber()
    {
        $year = date('Y');
        $month = date('m');
        $lastRequest = static::whereYear('created_at', $year)
                            ->whereMonth('created_at', $month)
                            ->orderBy('id', 'desc')
                            ->first();
        
        $sequence = $lastRequest ? (intval(substr($lastRequest->request_number, -4)) + 1) : 1;
        
        return sprintf('VPP%s%s%04d', $year, $month, $sequence);
    }

    /**
     * Quan hệ với user (người yêu cầu)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ với request_items
     */
    public function requestItems(): HasMany
    {
        return $this->hasMany(RequestItem::class);
    }

    /**
     * Quan hệ với approvals
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class);
    }

    /**
     * Tính tổng tiền từ request_items
     */
    public function calculateTotalAmount()
    {
        $total = $this->requestItems->sum('total_price');
        $this->update(['total_amount' => $total]);
        return $total;
    }

    /**
     * Kiểm tra trạng thái
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isSubmitted()
    {
        return $this->status === 'submitted';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Cập nhật trạng thái dựa trên approvals
     */
    public function updateStatusBasedOnApprovals()
    {
        $approvals = $this->approvals;
        
        // Kiểm tra nếu có approval bị reject
        if ($approvals->where('status', 'rejected')->count() > 0) {
            $this->update(['status' => 'rejected']);
            return;
        }
        
        // Kiểm tra approval theo level
        $managerApproval = $approvals->where('level', 'manager')->first();
        $directorApproval = $approvals->where('level', 'director')->first();
        
        if ($managerApproval && $managerApproval->status === 'approved') {
            if ($this->total_amount >= 10000000) { // >= 10 triệu cần director approve
                if ($directorApproval && $directorApproval->status === 'approved') {
                    $this->update(['status' => 'approved']);
                } else {
                    $this->update(['status' => 'director_approved']);
                }
            } else {
                $this->update(['status' => 'approved']);
            }
        } elseif ($managerApproval && $managerApproval->status === 'approved') {
            $this->update(['status' => 'manager_approved']);
        }
    }

    /**
     * Format số tiền
     */
    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 0, ',', '.') . ' VND';
    }

    /**
     * Lấy màu status
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'secondary',
            'submitted' => 'warning',
            'manager_approved' => 'info',
            'director_approved' => 'primary',
            'approved' => 'success',
            'rejected' => 'danger',
            'completed' => 'dark',
            'cancelled' => 'secondary'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Lấy tên tiếng Việt của status
     */
    public function getStatusNameAttribute()
    {
        $names = [
            'draft' => 'Bản nháp',
            'submitted' => 'Đã gửi',
            'manager_approved' => 'Manager đã duyệt',
            'director_approved' => 'Director đã duyệt', 
            'approved' => 'Đã duyệt',
            'rejected' => 'Từ chối',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];

        return $names[$this->status] ?? 'Không xác định';
    }
}