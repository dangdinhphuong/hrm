<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\System\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'checkin_time', 'value' => '08:00'],
            ['key' => 'checkout_time', 'value' => '17:30'],
            ['key' => 'company_logo', 'value' => 'logo.png'],
            ['key' => 'website_name', 'value' => 'HRM System'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }
    }
}
