<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class ManagerPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::all()->each(function($manager) {
            switch($manager->level) {
                case '':
                    $manager->assignRole('Admin');
                    break;
                case 'Administrator':
                    $manager->assignRole('Central');
                    break;
                case 'kierownik regionu':
                    $manager->assignRole('RKS');
                    break;
                case 'przedstawiciel handlowy':
                    $manager->assignRole('PH');
            }
        });
    }
}
