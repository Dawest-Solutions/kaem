<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::has('pos')->get()->each(function($user){
            if ($user->pos->first()->isMain) {
                $user->assignRole('POS Main');
            } else {
                $user->assignRole('POS Additional');
            }
        });
    }
}
