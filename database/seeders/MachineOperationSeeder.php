<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Design;
use App\Models\PayrollJob;
use App\Models\DesignRequest;
use Illuminate\Database\Seeder;
use App\Models\MachineOperation;
use App\Models\DailyPayrollDetail;
use App\Models\DailyPayrollHeader;
use Illuminate\Support\Facades\DB;
use App\Models\WeeklyPayrollDetail;
use App\Models\WeeklyPayrollHeader;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MachineOperationSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            $today = Carbon::today();
            $designId = 7;
            $quantityInput = 50;

            // Ambil design berdasarkan `design_id`
            $design = Design::findOrFail($designId);
            $designRequest = $design->designRequest;

            if (!$designRequest) {
                throw new \Exception("No DesignRequest found for Design ID: {$design->id}");
            }

            // Hitung total quantity yang sudah dikerjakan pada MachineOperation sebelumnya
            $totalQuantity = MachineOperation::where('design_id', $designId)->sum('quantity');

            // Pastikan jumlah yang diinputkan tidak melebihi `total_pieces` yang dipesan
            if ($quantityInput > $designRequest->total_pieces - $totalQuantity) {
                throw new \Exception("Quantity exceeds the total pieces ordered.");
            }

            // Step 1: Create Machine Ops
            $machineOps = MachineOperation::create([
                'operator_id' => 2,
                'design_id' => $designId,
                'assistant_id' => 32,
                'quantity' => $quantityInput,
                'comments' => 'Mesinnya gujag gejug'
            ]);

            // Update untuk mengelola total quantity pada machine ops
            $totalQuantity += $quantityInput;

            if ($totalQuantity >= $designRequest->total_pieces) {
                // Jika sudah mencapai total_pieces, update status ke 'in_qc'
                $designRequest->update(['status' => 'in_qc']);
            }

            $payrollJob = PayrollJob::where('design_request_id', $designRequest->id)->first();
            $payPerPiece = $payrollJob ? $payrollJob->pay_machine_operator : 0;

            // Total Pay divided by two if there's an assistant
            $totalPay = $machineOps->quantity * $payPerPiece;
            $totalPayForEach = $totalPay / ($machineOps->assistant_id ? 2 : 1);

            // Function to fill Daily Payroll Header and Weekly Payroll Header
            $processPayroll = function ($employeeId, $quantity, $totalPayForEach, $payPerPiece, $today) use ($designRequest) {
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
                    'design_request_id' => $designRequest->id,
                    'job_type' => 'machine_operator',
                    'pieces_worked' => $quantity,
                    'pay_per_piece' => $payPerPiece,
                    'subtotal_pay' => $totalPayForEach,
                ]);

                // Update Daily Payroll Header
                $dailyPayrollHeader->increment('total_pieces', $quantity);
                $dailyPayrollHeader->increment('daily_total_pay', $totalPayForEach);

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
                $weeklyPayrollHeader->increment('weekly_total_pay', $totalPayForEach);
            };

            // Process payroll for operator
            $processPayroll($machineOps->operator_id, $machineOps->quantity, $totalPayForEach, $payPerPiece, $today);

            // Process payroll for assistant (if exists)
            if ($machineOps->assistant_id) {
                $processPayroll($machineOps->assistant_id, $machineOps->quantity, $totalPayForEach, $payPerPiece, $today);
            }
        });
    }
}
