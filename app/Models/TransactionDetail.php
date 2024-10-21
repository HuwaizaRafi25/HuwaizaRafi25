<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'design_request_id',
        'subtotal',
    ];

    public function transactionHeader(){
        return $this->belongsTo(TransactionHeader::class, 'transaction_id');
    }

    public function designRequest(){
        return $this->belongsTo(DesignRequest::class, 'design_request_id');
    }
}
