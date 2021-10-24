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
        $id = DB::table('user_account_base')->insertGetId([
            'account_status' => 0,
            'email' => 'hoge01@example.local',
            'password' => Hash::make('hoge01TEST'),
        ]);

        DB::table('user_account_profile')->insert([
            'user_account_base_id' => $id,
            'birth_date_str' => "",
            'full_name' => "",
        ]);
    }
}
