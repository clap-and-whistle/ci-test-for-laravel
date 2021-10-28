<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminAccountBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_account_bases')->insert([
            'account_status' => 0,
            'email' => 'adm01@example.local',
            'password' => Hash::make('hoge01TEST'),
        ]);

    }
}
