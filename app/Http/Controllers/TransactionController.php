<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Debt;
use App\Models\DebtPayment;
use Illuminate\Http\Request;
use App\Models\DesignRequest;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use Illuminate\Support\Facades\DB;
use App\Models\DesignRequestHeader;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactionAll = TransactionHeader::with(['customer', 'transactionDetails'])->get();
        $transactionCustomer = TransactionHeader::where('customer_id', Auth::id())->get();
        $designHeaderAll = DesignRequestHeader::with('customer', 'designRequests')
            ->whereHas('designRequests', function ($query) {
                $query->where('status', 'shipped');
            })
            ->get();
        $designHeaderCustomer = DesignRequestHeader::with('customer', 'designRequests')
            ->whereHas('designRequests', function ($query) {
                $query->where('status', 'shipped');
            })->where('customer_id', Auth::id())->get();
        return view('menus.transactions.allTransactions', compact('transactionAll', 'transactionCustomer', 'designHeaderAll', 'designHeaderCustomer'));
    }

    public function getDesignRequests($id)
    {
        $designRequests = DesignRequest::where('design_request_header_id', $id)->where('status', 'shipped')->get();
        return response()->json($designRequests);
    }

    public function getDetailTransactions($id)
    {
        $transactionDetails = TransactionDetail::with('designRequest')->where('transaction_id', $id)->get();

        // Cek apakah data ditemukan
        if ($transactionDetails->isEmpty()) {
            return response()->json(['message' => 'No transaction details found.'], 404);
        }

        return response()->json($transactionDetails);
    }

    public function confirm($id){
        $transHeader = TransactionHeader::find($id);
        $transHeader->status = 'paid';
        $transHeader->save();
        notify()->success('Confirmation successfully! ✍️', 'Success!');
        return redirect()->back();
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
        // $request->validate([
        //     'proofPayment' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        //     'method' => ['string', 'max:255'],
        //     'totalPaymentPrice' => ['numeric', 'max:255'],
        //     'rating' => ['numeric', 'between:1,5'],
        //     'feedback' => ['string', 'max:500'],
        //     'creditPayment' => ['numeric', 'min:0'],
        //     'designRequests' => ['numeric', 'min:1'],
        //     'customerId' => ['numeric', 'exists:users,id'],
        // ]);

        DB::beginTransaction();
        try {
            if ($request->method == 'cash') {
                $transactionHeader = TransactionHeader::create([
                    'customer_id' => $request->customerId,
                    'total_price' => $request->totalPaymentPrice,
                    'payment_type' => $request->method,
                    'status' => 'paid',
                    'rating' => $request->rating,
                    'feedback' => $request->feedback
                ]);
                foreach ($request->input('designRequests') as $index => $designRequestId) {
                    $designRequest = DesignRequest::find($designRequestId);
                    $designRequest->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);

                    $designReqHeaderId = $designRequest->design_request_header_id;
                    $header = DesignRequestHeader::find($designReqHeaderId);
                    $allCompleted = $header->designRequests()->where('status', '!=', 'completed')->count() === 0;
                    if ($allCompleted) {
                        $header->status = 'completed';
                        $header->save();
                    }

                    $transactionDetailCreate = TransactionDetail::create([
                        'transaction_id' => $transactionHeader->id,
                        'design_request_id' => $designRequestId,
                        'subtotal' => $designRequest->price_per_piece * $designRequest->total_pieces,
                    ]);
                }
                DB::commit();
                notify()->success('Transaction successfully! ✍️', 'Success!');
                return redirect()->back();

            } elseif ($request->method == 'credit') {
                $transactionHeader = TransactionHeader::create([
                    'customer_id' => $request->customerId,
                    'total_price' => $request->totalPaymentPrice,
                    'payment_type' => $request->method,
                    'status' => 'pending',
                    'rating' => $request->rating,
                    'feedback' => $request->feedback
                ]);
                foreach ($request->input('designRequests') as $index => $designRequestId) {
                    $designRequest = DesignRequest::find($designRequestId);
                    $designRequest->update([
                        'status' => 'completed',
                    ]);

                    $designReqHeaderId = $designRequest->design_request_header_id;
                    $header = DesignRequestHeader::find($designReqHeaderId);
                    $allCompleted = $header->designRequests()->whereNotIn('status', ['completed', 'cancelled'])->count() === 0;
                    if ($allCompleted) {
                        $header->status = 'completed';
                        $header->save();
                    }

                    $transactionDetailCreate = TransactionDetail::create([
                        'transaction_id' => $transactionHeader->id,
                        'design_request_id' => $designRequestId,
                        'subtotal' => $designRequest->price_per_piece * $designRequest->total_pieces,
                    ]);

                    $debts = Debt::create([
                        'transaction_headers' => $transactionHeader->id,
                        'customer_id' => $transactionHeader->customer_id,
                        'total_debt' => $transactionHeader->total_price,
                        'due_date' => Carbon::now()->addWeek(),
                        'status' => 'unpaid'
                    ]);

                    $debtPayments = DebtPayment::create([
                        'debt_id' => $debts->id,
                        'payment_amount' => $request->creditPayment
                    ]);
                }
                DB::commit();
                notify()->success('Transaction successfully! ✍️', 'Success!');
                return redirect()->back();
            } elseif ($request->method == 'ewallet') {
                $proofPaymentPicPath = null;
                if ($request->hasFile('proofPayment')) {
                    $image = $request->file('proofPayment');
                    $path = $image->store('transactions', 'public');
                    $proofPaymentPicPath = 'storage/' . $path; // Simpan path di database
                }
                $transactionHeader = TransactionHeader::create([
                    'customer_id' => $request->customerId,
                    'total_price' => $request->totalPaymentPrice,
                    'payment_type' => 'e-wallet',
                    'payment_proof_pic' => $proofPaymentPicPath,
                    'status' => 'pending',
                    'rating' => $request->rating,
                    'feedback' => $request->feedback
                ]);
                foreach ($request->input('designRequests') as $index => $designRequestId) {
                    $designRequest = DesignRequest::find($designRequestId);
                    $designRequest->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);

                    $designReqHeaderId = $designRequest->design_request_header_id;
                    $header = DesignRequestHeader::find($designReqHeaderId);
                    $allCompleted = $header->designRequests()->where('status', '!=', 'completed')->count() === 0;
                    if ($allCompleted) {
                        $header->status = 'completed';
                        $header->save();
                    }

                    $transactionDetailCreate = TransactionDetail::create([
                        'transaction_id' => $transactionHeader->id,
                        'design_request_id' => $designRequestId,
                        'subtotal' => $designRequest->price_per_piece * $designRequest->total_pieces,
                    ]);
                }
                DB::commit();
                notify()->success('Transaction successfully! ✍️', 'Success!');
                return redirect()->back();
            } elseif ($request->method == 'transfer') {
                $proofPaymentPicPath = null;
                if ($request->hasFile('proofPayment')) {
                    $image = $request->file('proofPayment');
                    $path = $image->store('transactions', 'public');
                    $proofPaymentPicPath = 'storage/' . $path; // Simpan path di database
                }
                $transactionHeader = TransactionHeader::create([
                    'customer_id' => $request->customerId,
                    'total_price' => $request->totalPaymentPrice,
                    'payment_type' => 'transfer_bank',
                    'payment_proof_pic' => $proofPaymentPicPath,
                    'status' => 'pending',
                    'rating' => $request->rating,
                    'feedback' => $request->feedback
                ]);
                foreach ($request->input('designRequests') as $index => $designRequestId) {
                    $designRequest = DesignRequest::find($designRequestId);
                    $designRequest->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);

                    $designReqHeaderId = $designRequest->design_request_header_id;
                    $header = DesignRequestHeader::find($designReqHeaderId);
                    $allCompleted = $header->designRequests()->where('status', '!=', 'completed')->count() === 0;
                    if ($allCompleted) {
                        $header->status = 'completed';
                        $header->save();
                    }

                    $transactionDetailCreate = TransactionDetail::create([
                        'transaction_id' => $transactionHeader->id,
                        'design_request_id' => $designRequestId,
                        'subtotal' => $designRequest->price_per_piece * $designRequest->total_pieces,
                    ]);
                }
                DB::commit();
                notify()->success('Transaction successfully! ✍️', 'Success!');
                return redirect()->back();
            } else {
                return response()->json(['message' => 'Invalid payment method'], 400);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            echo "yaaaaah";
            throw $th;
        }
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
