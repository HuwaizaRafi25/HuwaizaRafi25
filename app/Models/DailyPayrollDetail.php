<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyPayrollDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        "daily_payroll_header_id",
        "design_request_id",
        "job_type",
        "pieces_worked",
        "pay_per_piece",
        "subtotal_pay",
    ];

    public function dailyPayrollHeader(){
        return $this->belongsTo(DailyPayrollHeader::class, 'daily_payroll_header_id');
    }

    public function designRequest(){
        return $this->belongsTo(DesignRequest::class, 'design_request_id');
    }

    public function weeklyPayrollDetail(){
        return $this->hasOne(WeeklyPayrollDetail::class, 'daily_payroll_header_id');
    }
}
