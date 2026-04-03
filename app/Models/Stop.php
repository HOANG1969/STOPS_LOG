<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'issue_category',
        'priority_level',
        'priority_scored_by',
        'priority_scored_at',
        'shift_leader_scored_by',
        'shift_leader_scored_at',
        'shift_leader_priority_level',
        'shift_leader_note',
        'safety_officer_scored_by',
        'safety_officer_scored_at',
        'safety_officer_priority_level',
        'safety_officer_note',
        'observer_name',
        'observer_phone',
        'observation_date',
        'observation_time',
        'location',
        'equipment_name',
        'issue_description',
        'corrective_action',
        'status',
        'completion_date',
        'notes'
    ];

    protected $casts = [
        'observation_date' => 'date',
        'observation_time' => 'datetime:H:i',
        'completion_date' => 'date',
        'priority_scored_at' => 'datetime',
        'shift_leader_scored_at' => 'datetime',
        'safety_officer_scored_at' => 'datetime',
    ];

    /**
     * Relationship: STOP belongs to a User (observer)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: User who scored the priority level
     */
    public function scorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'priority_scored_by');
    }

    public function shiftLeaderScorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shift_leader_scored_by');
    }

    public function safetyOfficerScorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'safety_officer_scored_by');
    }

    public function scoreHistories(): HasMany
    {
        return $this->hasMany(StopScoreHistory::class)->orderByDesc('scored_at');
    }

    /**
     * Check if STOP is open
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Check if STOP is in progress
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in-progress';
    }

    /**
     * Check if STOP is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'open' => 'badge bg-danger',
            'in-progress' => 'badge bg-warning',
            'completed' => 'badge bg-success',
            default => 'badge bg-secondary'
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'open' => 'Chưa xử lý',
            'in-progress' => 'Đang xử lý',
            'completed' => 'Hoàn thành',
            default => 'Không xác định'
        };
    }

    /**
     * Get all issue categories
     */
    public static function getIssueCategories(): array
    {
        return [
            'quy_trinh_noi_quy' => 'Quy trình - nội quy',
            'dung_cu_thiet_bi' => 'Dụng cụ thiết bị',
            'bao_ho_lao_dong' => 'Bảo hộ lao động',
            'tu_the_hanh_dong' => 'Tư thế, hành động',
            'moi_truong' => 'Môi trường',
            'ro_ri_hc' => 'Rò rỉ HC (ngoài ý muốn)',
            'dieu_kien_khong_an_toan' => 'Điều kiện không an toàn',
            'vi_pham_chuan_muc_vhat' => 'Vi phạm chuẩn mực VHAT',
            'bieu_duong_an_toan' => 'Biểu dương về công tác AN TOÀN',
            'van_de_khac' => 'Vấn đề khác liên quan đến mất AN TOÀN'
        ];
    }

    /**
     * Get category label
     */
    public function getCategoryLabel(): string
    {
        $categories = self::getIssueCategories();
        return $categories[$this->issue_category] ?? 'Không xác định';
    }

    /**
     * Get priority level label
     */
    public function getPriorityLabel(): string
    {
        return match($this->priority_level) {
            0 => 'Mức 0',
            1 => 'Mức 1',
            2 => 'Mức 2',
            3 => 'Mức 3',
            default => 'Chưa xác định'
        };
    }

    /**
     * Get priority level badge class
     */
    public function getPriorityBadgeClass(): string
    {
        return match($this->priority_level) {
            0 => 'badge bg-danger',      // Cao nhất - Đỏ
            1 => 'badge bg-warning',     // Cao - Vàng
            2 => 'badge bg-info',        // Trung bình - Xanh dương
            3 => 'badge bg-success',     // Thấp - Xanh lá
            default => 'badge bg-secondary'
        };
    }

    /**
     * Get all priority levels
     */
    public static function getPriorityLevels(): array
    {
        return [
            0 => 'Mức 0 - Cao nhất',
            1 => 'Mức 1 - Cao',
            2 => 'Mức 2 - Trung bình',
            3 => 'Mức 3 - Thấp'
        ];
    }
}
