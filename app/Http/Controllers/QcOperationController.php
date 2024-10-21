<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Design;
use App\Models\PayrollJob;
use App\Models\QcOperation;
use Illuminate\Http\Request;
use App\Models\DesignRequest;
use App\Models\DailyPayrollDetail;
use App\Models\DailyPayrollHeader;
use Illuminate\Support\Facades\DB;
use App\Models\WeeklyPayrollDetail;
use App\Models\WeeklyPayrollHeader;
use Illuminate\Support\Facades\Log;

class QcOperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $qcOps = QcOperation::with('qc', 'design')->get();
        $designReqsAll = DesignRequest::where('status', 'in_qc')->get();
        $users = User::all();
        return view('menus.qcOps.qcOps', compact('qcOps', 'designReqsAll', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $designId = $request->designId;
            $designRequest = $request->designReqId;
            $today = Carbon::today();
            $quantityInput = $request->quantity;
            $operatorId = $request->operatorId;
            $comment = $request->comment;

            $designRequest = DesignRequest::find($designRequest);
            if (!$designRequest) {
                throw new \Exception("No DesignRequest found for Design ID: {$designId}");
            }


            $totalQuantity = QcOperation::where('design_id', $designId)->sum('quantity_checked');

            if ($quantityInput > $designRequest->total_pieces - $totalQuantity) {
                throw new \Exception("Quantity exceeds the total pieces ordered.");
            }

            $qcOps = QcOperation::create([
                'qc_id' => $operatorId,
                'design_id' => $designId,
                'quantity_checked' => $quantityInput,
                'comments' => $comment
            ]);

            $totalQuantity += $quantityInput;

            if ($totalQuantity >= $designRequest->total_pieces) {
                $designRequest->update([
                    'status' => 'shipped',
                    'completed_at' => Carbon::now(),
                ]);
            }

            $payrollJob = PayrollJob::where('design_request_id', $designRequest->id)->first();
            $payPerPiece = $payrollJob ? $payrollJob->pay_qc : 0;

            $totalPay = $qcOps->quantity_checked * $payPerPiece;

            $processPayroll = function ($employeeId, $quantity_checked, $payPerPiece, $totalPay, $today) use ($designRequest) {
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

                $dailyPayrollDetail = DailyPayrollDetail::create([
                    'daily_payroll_header_id' => $dailyPayrollHeader->id,
                    'design_request_id' => $designRequest->id,
                    'job_type' => 'qc',
                    'pieces_worked' => $quantity_checked,
                    'pay_per_piece' => $payPerPiece,
                    'subtotal_pay' => $totalPay,
                ]);

                $dailyPayrollHeader->increment('total_pieces', $quantity_checked);
                $dailyPayrollHeader->increment('daily_total_pay', $totalPay);

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

            $processPayroll($qcOps->qc_id, $qcOps->quantity_checked, $payPerPiece, $totalPay, $today);
            DB::commit();
            notify()->success('Machine Ops was stored successfully! ✍️', 'Success!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e->getMessage());
            notify()->error('Machine Ops was not stored successfully! ✍️', 'Failed!');
            return redirect()->back();
        }


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
