<?php

use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('setting')->truncate();
        \DB::table('setting')->insert([
            [
                'id'      => 1,
                'status'  => 'hide',
                'name'=> "signup"
            ], [
                'id'       => 2,
                'status'   => '3',
                'name'=> "threshold"
            ],
            [
                'id'        => 3,
                'status'      => '60',
                'name'=> "purge"
            ],
            [
                'id'        => 4,
                'status'      => '0.16',
                'name'=> "storage"
            ],
            [
                'id'        => 5,
                'status'      => '90',
                'name'=> "video_price"
            ]
        ]);
    }
}
