<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\DailyPayrollDetail;
use App\Models\DailyPayrollHeader;
use App\Models\WeeklyPayrollDetail;
use App\Models\WeeklyPayrollHeader;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class WeeklyPayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unpaidWeeklyPayrolls = WeeklyPayrollHeader::with('weeklyPayrollDetail', 'employee', 'employee.roles')->where('paid', 0)->get();
        $paidWeeklyPayrolls = WeeklyPayrollHeader::with('weeklyPayrollDetail', 'employee')->where('paid', 1)->get();
        return view('menus.payroll.weeklyPayroll', compact('paidWeeklyPayrolls', 'unpaidWeeklyPayrolls'));
    }

    public function pay(Request $request)
    {
        $weeklyPayroll = WeeklyPayrollHeader::find($request->weeklyHeaderId);
        if ($weeklyPayroll) {
            $weeklyPayroll->paid = 1;
            $weeklyPayroll->save();
            notify()->success('Salary was paid successfully! ✍️', 'Success!');
            return redirect()->back();
        } else {
            notify()->error('Salary payment was failed!', 'Failed');
            return redirect()->back();
        }
    }

    public function getSalary($id)
    {
        try {
            $weeklyHeader = WeeklyPayrollHeader::select([
                'id',
                'week_start_date',
                'week_end_date'
            ])->find($id);

            if (!$weeklyHeader) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Weekly payroll data not found'
                ], 404);
            }

            $startDate = $weeklyHeader->week_start_date instanceof Carbon
                ? $weeklyHeader->week_start_date
                : Carbon::parse($weeklyHeader->week_start_date);

            $endDate = $weeklyHeader->week_end_date instanceof Carbon
                ? $weeklyHeader->week_end_date
                : Carbon::parse($weeklyHeader->week_end_date);


            $dates = [
                'Sunday' => ['date' => null, 'formatted_date' => 'd M, Y', 'subtotal_pay' => 0],
                'Monday' => ['date' => null, 'formatted_date' => 'd M, Y', 'subtotal_pay' => 0],
                'Tuesday' => ['date' => null, 'formatted_date' => 'd M, Y', 'subtotal_pay' => 0],
                'Wednesday' => ['date' => null, 'formatted_date' => 'd M, Y', 'subtotal_pay' => 0],
                'Thursday' => ['date' => null, 'formatted_date' => 'd M, Y', 'subtotal_pay' => 0],
                'Friday' => ['date' => null, 'formatted_date' => 'd M, Y', 'subtotal_pay' => 0],
                'Saturday' => ['date' => null, 'formatted_date' => 'd M, Y', 'subtotal_pay' => 0]
            ];

            $weeklyDetails = WeeklyPayrollDetail::where('weekly_payroll_header_id', $weeklyHeader->id)
                ->with('dailyPayrollHeader')
                ->get()
                ->keyBy(function ($item) {
                    return $item->dailyPayrollHeader ? $item->dailyPayrollHeader->work_date : null;
                });

            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                $dayName = $currentDate->format('l');

                $dailyHeader = WeeklyPayrollDetail::where('weekly_payroll_header_id', $weeklyHeader->id)
                    ->whereHas('dailyPayrollHeader', function ($query) use ($currentDate) {
                        $query->where('work_date', $currentDate->format(format: 'Y-m-d'));
                    })
                    ->with('dailyPayrollHeader')
                    ->first();
                $dates[$dayName] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'formatted_date' => $currentDate->format('d M, Y'),
                    'subtotal_pay' => $dailyHeader && $dailyHeader->dailyPayrollHeader
                        ?'Rp'. number_format($dailyHeader->dailyPayrollHeader->daily_total_pay, 0, ',', '.')
                        : 'Rp0,00'
                ];
                $currentDate->addDay();
            }

            $response = [
                'status' => 'success',
                'data' => [
                    'dates' => $dates
                ]
            ];

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve dates: ' . $e->getMessage(),
                'debug' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ] : null
            ], 500);
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
