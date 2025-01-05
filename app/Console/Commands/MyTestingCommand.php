<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Role;
use App\Models\Outlet;
use App\Models\TempAdmin;
use App\Models\WinnerList;
use Illuminate\Console\Command;

class MyTestingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testing:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // create admins for each outlet

        // $outlets = Outlet::all();
        $accounts = TempAdmin::all();
        $count = 1;

        foreach($accounts as $account){
            $admin = Admin::where('username', $admin->username)->first();
            $outlet = Outlet::where('name', $account->outlet_name)->first();
            $admin->outlet_id = $outlet->id;
            //assign role
            if($outlet){
                $admin->assignRole($admin->role);
                $admin->save();
           }
        }

        // foreach($outlets as $outlet) {

        //     for($i = 0; $i < 5; $i++) {

        //         $admin = Admin::create([
        //             $password = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', 10)), 0, 10),
        //             'name' => 'Staff '.$count,
        //             'username' => 'staff'.$count,
        //             'email' => 'support@fortknox.group',
        //             'password' => bcrypt($password),
        //             'outlet_id' => $outlet->id,
        //             'role' => Role::OPERATOR,
        //             'phone_e164' => '-',
        //         ]);
        //         $count++;
        //         $admin->assignRole($admin->role);

        //         $new_admin = TempAdmin::create([
        //             'username' => $admin->username,
        //             'password' => $password,
        //             'outlet_name' => $outlet->name,
        //         ]);

        //     }


        // }



    }
}
