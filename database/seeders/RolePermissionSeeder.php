<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // // Create Permissions
        // $permissions = [
        //     'create-user', 'read-user', 'update-user', 'delete-user',
        //     'create-design', 'read-design', 'update-design', 'delete-design'
        // ];

        // foreach ($permissions as $permission) {
        //     Permission::create(['name' => $permission]);
        // }

        // // Create Roles
        // $roleAdmin = Role::create(['name' => 'admin']);
        // $roleDesigner = Role::create(['name' => 'designer']);

        // // Assign Permissions to Admin Role
        // $roleAdmin->givePermissionTo([
        //     'create-user', 'read-user', 'update-user', 'delete-user',
        //     'create-design', 'read-design', 'update-design', 'delete-design',
        //     'create-design_requests', 'read-design_requests', 'update-design_requests', 'delete-design_requests',
        //     'create-transactions', 'read-transactions', 'update-transactions', 'delete-transactions',
        //     'create-transactions_designs', 'read-transactions_designs', 'update-transactions_designs', 'delete-transactions_designs',
        //     'create-machine_operations', 'read-machine_operations', 'update-machine_operations', 'delete-machine_operations',
        //     'create-expenses', 'read-expenses', 'update-expenses', 'delete-expenses',
        //     'create-payroll_jobs', 'read-payroll_jobs', 'update-payroll_jobs', 'delete-payroll_jobs',
        //     'create-daily_payroll', 'read-daily_payroll', 'update-daily_payroll', 'delete-daily_payroll',
        //     'create-weekly_payroll', 'read-weekly_payroll', 'update-weekly_payroll', 'delete-weekly_payroll',
        // ]);

        // // Assign Permissions to Designer Role
        // $roleDesigner->givePermissionTo([
        //     'create-design', 'read-design', 'update-design', 'delete-design'
        // ]);
        Role::create(["name"=> "machineOps"]);
        Role::create(["name"=> "qcOps"]);
        Role::create(["name"=> "customer"]);
        Role::create(["name"=> "supervisor"]);
    }
}
