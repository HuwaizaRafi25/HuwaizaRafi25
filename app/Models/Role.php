<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = ['name', 'guard_name'];

    public function permissions(){
        return $this->belongsToMany(Role::class, 'role_has_permission');
    }

    public function users(){
        return $this->belongsToMany(User::class,'model_has_permission', 'permission_id', 'user_id');
    }
}
