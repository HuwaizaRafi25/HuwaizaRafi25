<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DesignRequest;

class DesignRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designRequests = [
            [
                "customer_id" => 29, // Pastikan user dengan id 1 ada
                "reference_image" => 'storage/profiles/2mDrwV8hWUTrNAHrvACNSlbTXyyX8gWNh75dokPH.jpg',
                "description" => 'Desain pertama untuk acara perusahaan',
                "total_pieces" => 100,
                "price_per_design" => 150000,
                "price_per_piece" => 1500,
                "status" => 'pending',
                "supervisor_id" => 1, // Pastikan user dengan id 2 ada
            ],
            [
                "customer_id" => 19,
                "reference_image" => 'storage/profiles/bbmP9Tj71jLARMgkYvaogbJwBo4hEMt8IhqnVXOn.png',
                "description" => 'Desain kedua untuk kampanye promosi',
                "total_pieces" => 200,
                "price_per_design" => 200000,
                "price_per_piece" => 1800,
                "status" => 'approved',
                "supervisor_id" => 1,
            ],
            [
                "customer_id" => 29,
                "reference_image" => 'storage/profiles/bbmP9Tj71jLARMgkYvaogbJwBo4hEMt8IhqnVXOn.png',
                "description" => 'Desain ketiga untuk peluncuran produk baru',
                "total_pieces" => 300,
                "price_per_design" => 250000,
                "price_per_piece" => 2000,
                "status" => 'in_production',
                "supervisor_id" => 1,
            ],
        ];

        foreach ($designRequests as $designRequest) {
            DesignRequest::create($designRequest);
        }
    }
}
