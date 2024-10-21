<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeeklyPayrollHeader extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'week_start_date',
        'week_end_date',
        'weekly_total_pay',
        'paid'
    ];
    protected $dates = ['week_start_date', 'week_end_date'];

    public function getWeekStartDateAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getWeekEndDateAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function weeklyPayrollDetail()
    {
        return $this->hasMany(WeeklyPayrollDetail::class, 'weekly_payroll_header_id');
    }
}
