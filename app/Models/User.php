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
        'zalo_user_id',
        'is_active',
        'is_tchc_checker',
        'is_tchc_manager'
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
        'is_active' => 'boolean',
        'is_tchc_checker' => 'boolean',
        'is_tchc_manager' => 'boolean'
    ];

    /**
     * Quan hệ với supply requests (yêu cầu văn phòng phẩm)
     */
    public function supplyRequests()
    {
        return $this->hasMany(SupplyRequest::class);
    }

    /**
     * Quan hệ với approved requests (yêu cầu đã phê duyệt) 
     */
    public function approvedRequests()
    {
        return $this->hasMany(SupplyRequest::class, 'approved_by');
    }

    /**
     * Kiểm tra vai trò
     */
    public function isEmployee()
    {
        return $this->role === 'employee';
    }

    public function isApprover()
    {
        return $this->role === 'approver';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTchcChecker()
    {
        return $this->role === 'admin' || $this->role === 'tchc_checker';
    }

    public function isTchcManager()
    {
        return $this->role === 'admin' || $this->role === 'tchc_manager';
    }

    /**
     * Kiểm tra quyền phê duyệt theo bộ phận
     */
    public function canApprove($request)
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        if ($this->isApprover()) {
            // Chỉ có thể phê duyệt yêu cầu của cùng bộ phận
            return $this->department === $request->requester_department;
        }
        
        return false;
    }

    /**
     * Kiểm tra quyền check của TCHC
     */
    public function canTchcCheck($request)
    {
        return $this->isTchcChecker() && $request->status === 'approved';
    }

    /**
     * Kiểm tra quyền phê duyệt cuối của TCHC Manager
     */
    public function canTchcApprove($request) 
    {
        return $this->isTchcManager() && $request->status === 'tchc_checked';
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute()
    {
        return $this->attributes['full_name'] ?? $this->name;
    }
}
