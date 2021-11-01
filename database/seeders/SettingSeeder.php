<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Setting::availableSettings() as $setting) {
            Setting::firstOrCreate(
                [
                    'key' => $setting['key']
                ],
                [
                    'value' => $setting['value'] ?? null,
                    'type' => $setting['type'] ?? null,
                    'description' => $setting['description'] ?? null,
                ]);
        }
    }
}
