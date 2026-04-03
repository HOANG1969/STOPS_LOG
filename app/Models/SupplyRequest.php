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
        'notes',
        'tchc_checker_id',
        'tchc_checked_at',
        'tchc_check_notes',
        'tchc_manager_id',
        'tchc_approved_at',
        'tchc_approval_notes'
    ];

    protected $casts = [
        'needed_date' => 'date',
        'request_date' => 'date',
        'approved_at' => 'datetime',
        'forwarded_at' => 'datetime',
        'tchc_checked_at' => 'datetime',
        'tchc_approved_at' => 'datetime'
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
     * Quan hệ với TCHC checker
     */
    public function tchcChecker()
    {
        return $this->belongsTo(User::class, 'tchc_checker_id');
    }

    /**
     * Quan hệ với TCHC manager 
     */
    public function tchcManager()
    {
        return $this->belongsTo(User::class, 'tchc_manager_id');
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

    public function scopeTchcChecked($query)
    {
        return $query->where('status', 'tchc_checked');
    }

    public function scopeTchcApproved($query)
    {
        return $query->where('status', 'tchc_approved');
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
    public static function generateRequestCode($department = null)
    {
        // Format: TenBoPhan_Thang_Nam_STT
        // Ví dụ: KTSX_02_2026_001, IT_01_2026_002
        
        $month = now()->format('m');
        $year = now()->format('Y');
        
        // Nếu không có department, dùng prefix mặc định
        if ($department) {
            // Chuyển đổi tiếng Việt có dấu thành không dấu
            $deptPrefix = static::removeVietnameseTones($department);
            // Loại bỏ khoảng trắng và chuyển thành chữ hoa
            $deptPrefix = strtoupper(str_replace(' ', '', $deptPrefix));
        } else {
            $deptPrefix = 'REQ';
        }
        
        // Tìm phiếu cuối cùng của bộ phận trong tháng này
        $lastRequest = static::where('requester_department', $department)
                            ->whereYear('created_at', $year)
                            ->whereMonth('created_at', $month)
                            ->orderBy('id', 'desc')
                            ->first();
        
        // Tăng số thứ tự
        if ($lastRequest && $lastRequest->request_code) {
            // Lấy số thứ tự từ mã cuối (3 ký tự cuối)
            $sequence = (int)substr($lastRequest->request_code, -3) + 1;
        } else {
            $sequence = 1;
        }
        
        return $deptPrefix . '_' . $month . '_' . $year . '_' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
    
    /**
     * Remove Vietnamese tones from string
     */
    public static function removeVietnameseTones($str)
    {
        $vietnameseTones = [
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ',
            'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ',
            'È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ',
            'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ',
            'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ',
            'Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ',
            'Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ',
            'Đ'
        ];
        
        $replacements = [
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd',
            'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A',
            'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E',
            'I', 'I', 'I', 'I', 'I',
            'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O',
            'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U',
            'Y', 'Y', 'Y', 'Y', 'Y',
            'D'
        ];
        
        return str_replace($vietnameseTones, $replacements, $str);
    }

    /**
     * Check if can be approved by user
     */
    public function canBeApprovedBy(User $user)
    {
        return $user->canApprove($this) && $this->status === 'pending';
    }
}
