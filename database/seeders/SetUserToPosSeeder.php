<?php

namespace Database\Seeders;

use App\Models\Pos;
use App\Models\User;
use Illuminate\Database\Seeder;

class SetUserToPosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pos::all()->each(function($pos){
            $pos->user_id = User::inRandomOrder()->first()->id;
            $pos->save();
        });
    }
}
