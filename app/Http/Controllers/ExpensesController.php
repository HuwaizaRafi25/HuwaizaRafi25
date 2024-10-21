<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\ExpenseItem;
use App\Models\ExpenseHeader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ExpensesController extends Controller
{
    public function index()
    {
        // Mengambil semua data expenses
        $ExpenseHeaders = ExpenseHeader::with('expenseItems', 'createdBy')->get(); // Mengambil semua expenses
        return view('menus.expenses.expenses', compact('ExpenseHeaders'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $expenseHeader = ExpenseHeader::create([
                'total_amount' => 0,
                'description' => $request->description,
                'created_by' => auth::id(),
            ]);

            foreach ($request->name as $index => $name) {
                ExpenseItem::create([
                    'expense_header_id' => $expenseHeader->id,
                    'item_name' => $request->input('name')[$index],
                    'amount' => $request->input('total_pieces')[$index],
                ]);
            }

            $expenseHeader->update([
                'total_amount' => $expenseHeader->expenseItems->sum('amount'),
            ]);

            DB::commit();

            notify()->success('Requests was submitted successfully! ✍️', 'Success!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e->getMessage());
            notify()->success('User was not successfully! ✍️', 'Failed!');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        // Ambil ID expense dari input
        $expense = ExpenseHeader::findOrFail($id);

        // Validasi input
        $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        // Update data expense
        $expense->update([
            'item_name' => $request->item_name,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        // Notifikasi sukses dan redirect
        notify()->success('Expense updated successfully! 👌', 'Success!');
        return redirect()->back();
    }

    public function destroy($id): RedirectResponse
    {
        $expense = ExpenseHeader::findOrFail($id);
        $expense->delete();
        notify()->success('Expense was deleted successfully! 👍', 'Success!');
        return redirect()->back();
    }
}
