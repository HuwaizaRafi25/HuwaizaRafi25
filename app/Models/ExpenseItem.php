<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    use HasFactory;
    protected $fillable = [
        "expense_header_id",
        "item_name",
        "amount",
        "status"
    ];

    public function expenseHeader(){
        return $this->belongsTo(ExpenseHeader::class, 'expense_header_id');
    }

}
