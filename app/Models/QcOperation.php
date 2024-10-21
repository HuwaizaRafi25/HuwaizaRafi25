<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QcOperation extends Model
{
    use HasFactory;
    protected $fillable = [
        'qc_id',
        'design_id',
        'quantity_checked',
        'comments'
    ];

    public function qc(){
        return $this->belongsTo(User::class, 'qc_id');
    }

    public function design(){
        return $this->belongsTo(Design::class, 'design_id');
    }

}
