<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyPayrollHeader extends Model
{
    use HasFactory;
    protected $fillable = [
        "employee_id",
        "work_date",
        "total_pieces",
        "daily_total_pay",
    ];

    public function employee(){
        return $this->belongsTo(User::class);
    }

    public function dailyPayrollDetail()
    {
        return $this->hasMany(DailyPayrollDetail::class);
    }
}
