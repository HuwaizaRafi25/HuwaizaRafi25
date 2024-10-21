<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyPayrollDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'weekly_payroll_header_id',
        'daily_payroll_header_id',
        'subtotal_pay',
    ];

    public function weeklyPayrollHeader(){
        return $this->belongsTo(WeeklyPayrollHeader::class, 'weekly_payroll_header_id');
    }

    public function dailyPayrollHeader(){
        return $this->belongsTo(DailyPayrollHeader::class, 'daily_payroll_header_id');
    }


}
