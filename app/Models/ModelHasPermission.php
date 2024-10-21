<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasPermission extends Model
{
    protected $table = 'model_has_permissions';

    // Tambahkan relasi jika diperlukan
    public function model()
    {
        return $this->belongsTo(User::class, 'model_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
