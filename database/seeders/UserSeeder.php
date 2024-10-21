<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $admin = User::create([
        //     'name'=> 'Asep Sang Admin',
        //     'username'=> 'Asep Kiw',
        //     'email'=> 'asepp@gmail.com',
        //     'password'=> bcrypt('12345678'),
        //     'contact_info'=> '08815184624',
        //     'address'=> 'Jl. Cisaat',
        // ]);
        // $admin->assignRole('admin');

        $manusiabiasa = User::create([
            'name'=> 'udin manusiabiasa',
            'username'=> 'Udin b aja',
            'email'=> 'dinajah@gmail.com',
            'password'=> bcrypt('12345678'),
            'contact_info'=> '083821732042',
            'address'=> 'Jl. Koreh Kotok',
        ]);
        $manusiabiasa->givePermissionTo(['create-user', 'read-user', 'delete-user']);
    }
}
