<?php

namespace Database\Seeders;

use App\Imports\PermissionsImport;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Excel::import(new PermissionsImport, storage_path("../database/excels/permission.xlsx"));

        $admin = config('default-permission.admin-api');

        $adminRoles = collect(array_keys($admin))->map(function ($name) {
            return [
                'name' => $name,
                'guard_name' => Role::ADMIN_GUARD
            ];
        });


        foreach($adminRoles->toArray() as $role){
            Role::updateOrCreate(
                [
                    'name' => $role['name'],
                    'guard_name' => $role['guard_name']
                ]
            );
        }

        foreach ($admin as $role => $permissions) {
            Role::whereName($role)
                ->first()
                ->syncPermissions($permissions);
        }
    }
}
