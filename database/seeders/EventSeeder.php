<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the event database seed.
     */
    public function run(): void
    {
        Event::factory()
            ->count(10)
            ->create();
    }
}
