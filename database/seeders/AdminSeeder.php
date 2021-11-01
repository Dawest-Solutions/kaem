<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class AdminSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = collect([
            collect([
                'first_name' => 'super',
                'last_name' => 'admin',
                'email' => '',
                'password' => bcrypt(''),
                'role' => 'Admin',
                'email_verified_at' => now(),
            ]),
            collect([
                'first_name' => 'super',
                'last_name' => 'admin',
                'email' => '',
                'password' => bcrypt(''),
                'role' => 'Admin',
                'email_verified_at' => now(),
            ]),
            collect([
                'first_name' => 'account_1',
                'last_name' => 'account_1',
                'email' => '',
                'password' => bcrypt(''),
                'role' => 'Admin',
                'email_verified_at' => now(),
            ]),
            collect([
                'first_name' => 'account_2',
                'last_name' => 'account_2',
                'email' => '',
                'password' => bcrypt(''),
                'role' => 'Admin',
                'email_verified_at' => now(),
            ]),
            collect([
                'first_name' => 'account_3',
                'last_name' => 'account_3',
                'email' => '',
                'password' => bcrypt(''),
                'role' => 'Admin',
                'email_verified_at' => now(),
            ])
        ]);

        $admins->each(function(Collection $admin) {
            $user = Admin::updateOrCreate([
                'email'=>$admin->get('email')
            ],
                $admin->except(['role', 'email'])->toArray()
            );

            $user->assignRole($admin->get('role'));
        });
    }
}
