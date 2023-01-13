<?php
/*****************************************************/
# Company Name      :
# Author            :
# Created Date      :
# Page/Class name   : AdminUserSeeder
# Purpose           : Table declaration
/*****************************************************/

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name'        => 'John',
            'last_name'         => 'Doe',
            'full_name'         => 'John Doe',
            'email'             => 'admin@admin.com',   // Used in login
            'phone_no'          => '9876543210',
            'password'          => 'Admin@123',         // Used in login
            'role_id'           => 1,
            'type'              => 'SA',
            'status'            => '1',
            'sample_login_show' => 'Y'
        ]);
    }
}
