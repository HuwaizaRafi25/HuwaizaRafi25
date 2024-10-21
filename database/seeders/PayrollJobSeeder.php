<?php

namespace Database\Seeders;

use App\Models\PayrollJob;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PayrollJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payrollJobs = [
            [
                "design_request_id" => 1, // Pastikan user dengan id 1 ada
                "pay_designer" => 50000,
                "pay_machine_operator" => 450,
                "pay_qc" => 100,
            ],
            [
                "design_request_id" => 2,
                "pay_designer" => 25000,
                "pay_machine_operator" => 400,
                "pay_qc" => 150,
            ],
            [
                "design_request_id" => 3,
                "pay_designer" => 40000,
                "pay_machine_operator" => 350,
                "pay_qc" => 50,
            ],
        ];

        foreach ($payrollJobs as $payrolljob) {
            PayrollJob::create($payrolljob);
        }

    }
}
