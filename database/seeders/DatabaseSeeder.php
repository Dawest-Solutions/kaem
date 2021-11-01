<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seeder for two version production and local.
        $this->call([
            SettingSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            AdminSeeder::class,
        ]);

        if (App::environment() == 'production') {
            // Seeder production.
            $this->call([

            ]);
        } elseif (App::environment() == 'development') {
            // Seeder development.
            $this->call([
                PeriodSeeder::class,
                PrizeCategoriesSeeder::class,
                InitialRKS_PH_POS_UsersSeeder::class,
                UserPermissionSeeder::class,
                ManagerPermissionSeeder::class,
                UserPermissionSeeder::class,
                OrderStatusesSeeder::class,
            ]);
        } else {
            // Seeder local.
            $this->call([
                PosSeeder::class,
                RKSSeeder::class,
                UserSeeder::class,
                PeriodSeeder::class,
                ResultSeeder::class,
                PrizeSeeder::class,
                PrizeOrderSeeder::class,
                NewsSeeder::class,
                UserPermissionSeeder::class,
                SetUserToPosSeeder::class
            ]);
        }

    }
}
