<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Design;
use App\Models\PayrollJob;
use App\Models\DesignRequest;
use Illuminate\Database\Seeder;
use App\Models\DailyPayrollDetail;
use App\Models\DailyPayrollHeader;
use Illuminate\Support\Facades\DB;
use App\Models\WeeklyPayrollDetail;
use App\Models\WeeklyPayrollHeader;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DesignerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $today = Carbon::today();

            $requestId = 7;

            // Step 1: Update status menjadi approved
            $design = Design::where('request_id', $requestId)->first(); // Mengambil data berdasarkan `request_id`
            $designRequest = $design->designRequest;

            if ($design) {
                $design->update([
                    'designer_id' => 2,
                    'design_name' => 'Logo Muda Berdaya',
                    'design_files' => 'storage/designs/files/tesZip1.rar',
                    'status' => 'approved',
                ]);
                $designRequest->update(['status' => 'in_production']);
            }
            // Find related Design based on design design_id
            $payrollJob = PayrollJob::where('design_request_id', $requestId)->first();
            $totalPay = $payrollJob->pay_designer;

            // Function to fill Daily Payroll Header and Weekly Payroll Header
            $processPayroll = function ($employeeId, $quantity, $totalPay, $today) use ($requestId) {
                // Step 2: Check or create Daily Payroll Header
                $dailyPayrollHeader = DailyPayrollHeader::firstOrCreate(
                    [
                        'work_date' => $today->toDateString(),
                        'employee_id' => $employeeId,
                    ],
                    [
                        'total_pieces' => 0,
                        'daily_total_pay' => 0,
                    ]
                );

                // Step 3: Create or update Daily Payroll Detail
                $dailyPayrollDetail = DailyPayrollDetail::create([
                    'daily_payroll_header_id' => $dailyPayrollHeader->id,
                    'design_request_id' => $requestId,
                    'job_type' => 'designer',
                    'pieces_worked' => $quantity,
                    'pay_per_piece' => $totalPay,
                    'subtotal_pay' => $totalPay,
                ]);

                // Update Daily Payroll Header
                $dailyPayrollHeader->increment('total_pieces', $quantity);
                $dailyPayrollHeader->increment('daily_total_pay', $totalPay);

                // Step 4: Check or create Weekly Payroll Header
                $weekStart = $today->copy()->startOfWeek(0);
                $weekEnd = $weekStart->copy()->endOfWeek(6);
                $weeklyPayrollHeader = WeeklyPayrollHeader::firstOrCreate(
                    [
                        'week_start_date' => $weekStart->toDateString(),
                        'week_end_date' => $weekEnd->toDateString(),
                        'employee_id' => $employeeId,
                    ],
                    [
                        'weekly_total_pay' => 0,
                        'paid' => false,
                    ]
                );

                // Step 5: Create or update Weekly Payroll Detail
                $weeklyPayrollDetail = WeeklyPayrollDetail::updateOrCreate(
                    [
                        'weekly_payroll_header_id' => $weeklyPayrollHeader->id,
                        'daily_payroll_header_id' => $dailyPayrollHeader->id,
                    ],
                    [
                        'subtotal_pay' => $dailyPayrollHeader->daily_total_pay,
                    ]
                );

                // Update Weekly Payroll Header
                $weeklyPayrollHeader->increment('weekly_total_pay', $totalPay);
            };

            // Process payroll for operator
            $processPayroll($design->designer_id, 1, $totalPay, $today);
        });

    }
}
