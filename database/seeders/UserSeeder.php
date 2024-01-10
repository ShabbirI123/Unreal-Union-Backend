<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the user database seed.
     */
    public function run(): void
    {
        DB::table('user')->insert([
            'username' => 'john',
            'password' => Hash::make('campus09')
        ]);
    }
}
