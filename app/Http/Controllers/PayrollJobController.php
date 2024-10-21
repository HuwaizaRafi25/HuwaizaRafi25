<?php

namespace App\Http\Controllers;

use App\Models\PayrollJob;
use Illuminate\Http\Request;

class PayrollJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payrollJobs = PayrollJob::with('designRequest')->get();
        return view('menus.payroll.payrollJobs', compact('payrollJobs'));
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
    public function show(PayrollJob $payrollJob)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PayrollJob $payrollJob)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PayrollJob $payrollJob)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PayrollJob $payrollJob)
    {
        //
    }
}
