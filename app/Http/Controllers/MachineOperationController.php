<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Design;
use App\Models\PayrollJob;
use Illuminate\Http\Request;
use App\Models\DesignRequest;
use App\Models\MachineOperation;
use App\Models\DailyPayrollDetail;
use App\Models\DailyPayrollHeader;
use Illuminate\Support\Facades\DB;
use App\Models\WeeklyPayrollDetail;
use App\Models\WeeklyPayrollHeader;
use Illuminate\Support\Facades\Log;

class MachineOperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $machineOps = MachineOperation::with('operator', 'assistant', 'design')->get(); // Mengambil semua pengguna
        $designReqsAll = DesignRequest::with('design')->where('status', 'in_production')->get();
        $users = User::all();
        return view('menus.machineOps.machineOps', compact('machineOps', 'designReqsAll', 'users'));
    }

    public function download($design_file, $name)
    {
        // Mengakses file di dalam storage/app/public
        $filePath = storage_path('app/public/designs/files/' . $design_file);
        $fileName = $name;

        // Cek apakah file ada di path tersebut
        if (file_exists($filePath)) {
            return response()->download($filePath, $fileName . '.zip');
        }

        // Jika file tidak ditemukan, return error
        return response()->json(['error' => 'File not found.'], 404);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
            $assistantId = $request->assistant;
            $comment = $request->comment;

            $designRequest = DesignRequest::find($designRequest);
            if (!$designRequest) {
                throw new \Exception("No DesignRequest found for Design ID: {$designId}");
            }

            $totalQuantity = MachineOperation::where('design_id', $designId)->sum('quantity');

            if ($quantityInput > $designRequest->total_pieces - $totalQuantity) {
                throw new \Exception("Quantity exceeds the total pieces ordered.");
            }

            $machineOps = MachineOperation::create([
                'operator_id' => $operatorId,
                'design_id' => $designId,
                'assistant_id' => $assistantId,
                'quantity' => $quantityInput,
                'comments' => $comment
            ]);

            $totalQuantity += $quantityInput;

            if ($totalQuantity >= $designRequest->total_pieces) {
                $designRequest->update(['status' => 'in_qc']);
            }

            $payrollJob = PayrollJob::where('design_request_id', $designRequest->id)->first();
            $payPerPiece = $payrollJob ? $payrollJob->pay_machine_operator : 0;

            $totalPay = $machineOps->quantity * $payPerPiece;
            $totalPayForEach = $totalPay / ($machineOps->assistant_id ? 2 : 1);

            $processPayroll = function ($employeeId, $quantity, $totalPayForEach, $payPerPiece, $today) use ($designRequest) {
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
                    'job_type' => 'machine_operator',
                    'pieces_worked' => $quantity,
                    'pay_per_piece' => $payPerPiece,
                    'subtotal_pay' => $totalPayForEach,
                ]);

                $dailyPayrollHeader->increment('total_pieces', $quantity);
                $dailyPayrollHeader->increment('daily_total_pay', $totalPayForEach);

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

                $weeklyPayrollHeader->increment('weekly_total_pay', $totalPayForEach);
            };

            $processPayroll($machineOps->operator_id, $machineOps->quantity, $totalPayForEach, $payPerPiece, $today);

            if ($machineOps->assistant_id) {
                $processPayroll($machineOps->assistant_id, $machineOps->quantity, $totalPayForEach, $payPerPiece, $today);
            }
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
     * Display the specified resource.
     */
    public function show(MachineOperation $machineOperation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MachineOperation $machineOperation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MachineOperation $machineOperation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MachineOperation $machineOperation)
    {
        //
    }
}
