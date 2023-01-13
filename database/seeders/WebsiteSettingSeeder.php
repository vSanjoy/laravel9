<?php
/*****************************************************/
# Company Name      :
# Author            :
# Created Date      :
# Page/Class name   : WebsiteSettingSeeder
# Purpose           : Table declaration
/*****************************************************/

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WebsiteSetting;

class WebsiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WebsiteSetting::create([
            'from_email'        => 'admin@admin.com',
            'to_email'          => 'admin@admin.com',
            'website_title'     => 'Laravel Admin',
            'phone_no'          => '9876543210'
        ]);
    }
}
