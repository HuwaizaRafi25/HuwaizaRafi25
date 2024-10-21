<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DesignRequest;
use App\Models\TransactionHeader;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactionAll= TransactionHeader::with(['customer', 'transactionDetails'])->get();
        $transactionCustomer = TransactionHeader::where('customer_id', Auth::id())->get();
        $designReqAll = DesignRequest::where('status', 'shipped')->get();;
        $designReqCustomer = DesignRequest::whereRelation('designRequestHeader', 'customer_id', Auth::id())->get();
        return view('menus.transactions.allTransactions', compact('transactionAll', 'transactionCustomer', 'designReqAll', 'designReqCustomer'));
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
