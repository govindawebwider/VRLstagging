<?php

/**
 * @package App/Database/Seeder
 *
 * @class UserTableSeeder
 *
 * @author Ritu Slaria <ritu.slaria@surmountsoft.com>
 *
 * @copyright 2018 SurmountSoft Pvt. Ltd. All rights reserved.
 */

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profileId = \App\Profile::create([
            'Name' => 'Admin',
            'EmailId' => 'vrlAdmin@gmail.com',
        ]);
        \App\User::create([
            'user_name' => 'Admin',
            'email' => 'vrlAdmin@gmail.com',
            'password' => bcrypt('F8cuksY82@'),
            'profile_id' => $profileId->ProfileId,
            'type' => 'Admin',
            'is_account_active' => 1
        ]);
    }
}
