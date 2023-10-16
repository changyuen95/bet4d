<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $roleData = [
            [
                'name' => 'normal_user',
                'guard_name' => 'api',
            ],
            [
                'name' => 'member',
                'guard_name' => 'api',
            ],
            [
                'name' => 'operator',
                'guard_name' => 'api',
            ],
            [
                'name' => 'super_admin',
                'guard_name' => 'api',
            ],
        ];



        foreach($roleData as $data){
            Role::updateOrCreate([
                'name' => $data['name'],
            ],
            [
                'guard_name' => $data['guard_name'],
            ]);
        }

    }
}
