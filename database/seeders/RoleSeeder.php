<?php
/*****************************************************/
# Company Name      :
# Author            :
# Created Date      :
# Page/Class name   : RoleSeeder
# Purpose           : Table declaration
/*****************************************************/

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name'      => 'Super Admin',
            'slug'      => 'super-admin',
            'is_admin'  => '1',
            'status'    => '1'
        ]);
    }
}
