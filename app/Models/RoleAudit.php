<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleAudit extends Model
{
    protected $fillable = [
        'role_id',
        'user_id',
        'action',
        'changes',
        'ip_address'
    ];

    protected $casts = [
        'changes' => 'array'
    ];

    /**
     * العلاقة مع الدور
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * العلاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
