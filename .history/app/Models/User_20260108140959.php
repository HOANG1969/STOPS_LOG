<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'full_name',
        'email',
        'password',
        'department',
        'position',
        'role',
        'phone',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'is_active' => 'boolean'
    ];

    /**
     * Quan hệ với manager (người quản lý)
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Quan hệ với subordinates (nhân viên dưới quyền)
     */
    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    /**
     * Quan hệ với requests (yêu cầu đã tạo)
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    /**
     * Quan hệ với approvals (phê duyệt đã thực hiện)
     */
    public function approvals()
    {
        return $this->hasMany(Approval::class, 'approver_id');
    }

    /**
     * Kiểm tra vai trò
     */
    public function isEmployee()
    {
        return $this->role === 'employee';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isDirector()
    {
        return $this->role === 'director';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Kiểm tra quyền phê duyệt
     */
    public function canApprove($level)
    {
        switch($level) {
            case 'manager':
                return $this->isManager() || $this->isDirector() || $this->isAdmin();
            case 'director':
                return $this->isDirector() || $this->isAdmin();
            case 'admin':
                return $this->isAdmin();
            default:
                return false;
        }
    }
}
