<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineOperation extends Model
{
    use HasFactory;
    protected $fillable = [
        'operator_id',
        'design_id',
        'assistant_id',
        'quantity',
        'comments'
    ];

    public function operator(){
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function assistant(){
        return $this->belongsTo(User::class, 'assistant_id');
    }

    public function design(){
        return $this->belongsTo(Design::class, 'design_id');
    }
}
