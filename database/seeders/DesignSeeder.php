<?php

namespace Database\Seeders;

use App\Models\Design;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designs = [
            [
                "request_id" => 1, // Pastikan user dengan id 1 ada
                "designer_id" => 2,
                "design_name" => 'Logo Muda Berdaya',
                "design_files" => 'storage/designs/files/tesZip1.rar',
                "status" => 'in_design',
            ],
        ];

        foreach ($designs as $design) {
            Design::create($design);
        }

    }
}
