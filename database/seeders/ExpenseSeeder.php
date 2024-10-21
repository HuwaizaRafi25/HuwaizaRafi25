<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Expense;
use App\Models\ExpenseItem;
use App\Models\ExpenseHeader;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $expenses = [
        //     [
        //         "item_name" => "Benang",
        //         "amount" => 970000,
        //         "date" => '2019-01-01',
        //         "description" => "Warna merah 2, kuning 5, abu 10, dan biru 7",
        //     ],
        //     [
        //         "item_name" => "Kain Matador",
        //         "amount" => 800000,
        //         "date" => '2017-01-01',
        //         "description" => "Kain matador keras",
        //     ]
        // ];

        // foreach ($expenses as $expense) {
        //     Expense::create($expense);
        // }


        // Ambil user yang akan dijadikan created_by
        // $userId = User::inRandomOrder()->first()->id;

        // // Membuat 3 ExpenseHeader
        // for ($i = 1; $i <= 3; $i++) {
        //     ExpenseHeader::create([
        //         'total_amount' => rand(100000, 1000000), // Total amount random antara 100.000 hingga 1.000.000
        //         'description' => 'Description for expense header ' . $i,
        //         'created_by' => $userId,
        //     ]);
        // }

        $expenseHeaders = ExpenseHeader::all();

        foreach ($expenseHeaders as $header) {
            // Membuat 2 ExpenseItem untuk setiap ExpenseHeader
            for ($j = 1; $j <= 2; $j++) {
                ExpenseItem::create([
                    'expense_header_id' => $header->id,
                    'item_name' => 'Item ' . $j . ' for header ' . $header->id,
                    'amount' => rand(50000, 500000), // Amount random antara 50.000 hingga 500.000
                ]);
            }
        }

    }
}
