<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as spatiePermission;

class Permission extends spatiePermission
{
    protected $fillable = ['name', 'guard_name'];

    public function roles(){
        return $this->belongsToMany(Role::class, 'role_has_permission');
    }

    public function users(){
        return $this->belongsToMany(User::class,'model_has_role', 'role_id', 'user_id');
    }
}
