<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\DesignRequest;
use Illuminate\Database\Seeder;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Ambil beberapa design request yang statusnya tidak completed
            $designRequests = DesignRequest::where('id', '1')->get();

            foreach ($designRequests as $designRequest) {
                // Hitung subtotal berdasarkan design request
                $subtotal = $designRequest->price_per_piece * $designRequest->total_pieces;

                // Buat transaksi header
                $transactionHeader = TransactionHeader::create([
                    'customer_id' => 32, // Ganti dengan ID customer yang sesuai
                    'total_price' => $subtotal,
                    'midtrans_transaction_id' => null,
                    'payment_type' => 'cash',
                    'status' => 'paid', // Status pembayaran
                    'payment_date' => Carbon::now(), // Gunakan Carbon::now() untuk tanggal saat ini
                    'rating' => '5',
                    'feedback' => 'Mantap bang',
                ]);

                // Buat transaksi detail
                TransactionDetail::create([
                    'transaction_id' => $transactionHeader->id,
                    'design_request_id' => $designRequest->id,
                    'subtotal' => $subtotal,
                ]);

                // Update status design request dan design
                $designRequest->update(['status' => 'completed']);
                $design = $designRequest->design; // Ambil design terkait
                if ($design) {
                    $design->update(['status' => 'completed']);
                }
            }
        });
    }
}
