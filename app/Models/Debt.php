<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;
    protected $casts = [
        'due_date' => 'datetime',
    ];
    protected $fillable = [
        "transaction_headers",
        "customer_id",
        "total_debt",
        "due_date",
        "status"
    ];

    public function transactionHeaders(){
        return $this->belongsTo(TransactionHeader::class, 'transaction_headers');
    }

    public function customer(){
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function debtPayment(){
        return $this->hasMany(DebtPayment::class, 'debt_id');
    }
}
