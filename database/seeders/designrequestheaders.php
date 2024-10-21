<?php

namespace Database\Seeders;

use App\Models\DesignRequest;
use Illuminate\Database\Seeder;
use App\Models\DesignRequestHeader;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class designrequestheaders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // for ($i = 1; $i <= 10; $i++) {
        //     DesignRequestHeader::create([
        //         'customer_id' => '32',
        //         'supervisor_id' => '1',
        //         'status' => array_rand(['pending', 'in_progress', 'completed', 'cancelled']),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
        $headers = DesignRequestHeader::all();

        foreach ($headers as $header) {
            for ($i = 1; $i <= rand(2, 5); $i++) {
                DesignRequest::create([
                    'design_request_header_id' => $header->id,
                    'assigned_designer_id' => '2',
                    'supervisor_id' => $header->supervisor_id,
                    'reference_image' => 'images/sample_image.png',
                    'price_per_piece' => rand(1000, 5000),
                    'total_pieces' => rand(1, 50),
                    'status' => array_rand(['pending', 'approved', 'redesign', 'in_design', 'in_production', 'in_qc', 'completed', 'shipped', 'cancelled']),
                    'description' => 'Sample description for design request.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
