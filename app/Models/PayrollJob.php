<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollJob extends Model
{
    use HasFactory;
    protected $fillable = [
        'design_request_id',
        'pay_designer',
        'pay_machine_operator',
        'pay_qc'
    ];

    public function designRequest()
    {
        return $this->belongsTo(DesignRequest::class, 'design_request_id');
    }
}
