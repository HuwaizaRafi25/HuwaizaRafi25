<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\WeeklyPayrollHeader;

class WeeklyPayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $weeklyPayrolls = WeeklyPayrollHeader::with('weeklyPayrollDetail', 'employee')->get();
        return view('menus.payroll.weeklyPayroll', compact('weeklyPayrolls'));
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
