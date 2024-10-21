<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHeader extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'total_price',
        'midtrans_transaction_id',
        'payment_type',
        'status',
        'payment_date',
        'rating',
        'feedback',
    ];

    protected $dates = [
        'payment_date',
    ];

    public function customer(){
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function transactionDetails(){
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }
}
