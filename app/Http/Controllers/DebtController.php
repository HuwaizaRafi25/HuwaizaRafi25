<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\DebtPayment;
use App\Models\TransactionHeader;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $qcOps = QcOperation::with('qc', 'design')->get();
        // $designReqsAll = DesignRequest::where('status', 'in_qc')->get();
        // $users = User::all();
        $debts = Debt::with('debtPayment', 'customer')->where('status', 'unpaid')->get();
        $paidDebts = Debt::where('status', 'paid')->get();
        return view('menus.debts.allDebts', compact('debts', 'paidDebts'));
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
        $request->validate([
            'debtId' => 'required|exists:debts,id',
            'pay_Amount' => 'required|numeric|min:0',
        ]);

        $debt = Debt::find($request->debtId);

        if (!$debt) {
            notify()->error('Debt not found.', 'Error');
            return redirect()->back();
        }

        $debtPayment = DebtPayment::create([
            'debt_id' => $request->debtId,
            'payment_amount' => $request->pay_Amount,
        ]);

        $totalPaid = $debt->debtPayment->sum('payment_amount');

        if ($totalPaid == $debt->total_debt) {
            $debt->status = 'paid';
            $debt->save();
            notify()->success('Debt fully paid and marked as completed!', 'Success');
        } else {
            notify()->success('Payment recorded successfully!', 'Success');
        }

        return redirect()->back();
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
