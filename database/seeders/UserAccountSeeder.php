<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_account_base')->insert([
            'account_status' => 0,
            'email' => 'hoge01@example.local',
            'password' => Hash::make('hoge01TEST'),
        ]);
    }
}
