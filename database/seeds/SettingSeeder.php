<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create(['key' => 'app_name', 'value' => 'Pc comparison']);
        Setting::create(['key' => 'date_format', 'value' => 'DD-MM-YYYY']);
        Setting::create(['key' => 'time_format', 'value' => 'h:mm a']);
        Setting::create(['key' => 'timezone', 'value' => 'Asia/Dhaka']);
        Setting::create(['key' => 'app_url', 'value' => 'pc-comparison.com']);
        Setting::create(['key' => 'per_page', 'value' => '10']);
        Setting::create(['key' => 'toast_position', 'value' => 'top-end']);
        Setting::create(['key' => 'copyright_text', 'value' => 'Copyright © PC-comparison']);
    }
}
