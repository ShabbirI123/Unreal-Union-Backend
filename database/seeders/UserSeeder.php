<?php

namespace Database\Seeders;

use App\Models\User;
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
       User::factory()
           ->count(3)
           ->create();
    }
}
