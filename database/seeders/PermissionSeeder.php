<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Get dashboard routes and set permission based on names
         */
        collect(Route::getRoutes())
            ->each(fn($route) => $this->addPermissionRoute($route));
    }

    public function addPermissionRoute($route){
        Permission::firstOrCreate([
            'name' => Str::of($route->getName())
                ->replace('dashboard', 'route')
                ->__toString(),
            'guard_name' => 'web',
        ]);
        Permission::firstOrCreate([
            'name' => Str::of($route->getName())
                ->replace('dashboard', 'route')
                ->__toString(),
            'guard_name' => 'admin',
        ]);
    }
}
