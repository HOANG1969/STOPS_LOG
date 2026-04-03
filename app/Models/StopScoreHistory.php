<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StopScoreHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'stop_id',
        'scored_by',
        'scorer_type',
        'scorer_role',
        'previous_priority_level',
        'priority_level',
        'note',
        'scored_at',
    ];

    protected $casts = [
        'scored_at' => 'datetime',
    ];

    public function stop(): BelongsTo
    {
        return $this->belongsTo(Stop::class);
    }

    public function scorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scored_by');
    }

    public function getScorerTypeLabel(): string
    {
        return match ($this->scorer_type) {
            'shift_leader' => 'Trưởng ca',
            'safety_officer' => 'CBAT',
            default => 'Khác',
        };
    }
}
