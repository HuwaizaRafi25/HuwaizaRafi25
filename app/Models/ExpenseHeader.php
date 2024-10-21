<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseHeader extends Model
{
    use HasFactory;
    protected $fillable = [
        "total_amount",
        "description",
        "created_by"
    ];

    public function expenseItems(){
        return $this->hasMany(ExpenseItem::class, 'expense_header_id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
