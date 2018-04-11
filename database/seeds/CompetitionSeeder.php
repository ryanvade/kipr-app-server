<?php

use Carbon\Carbon;
use KIPR\Competition;
use Illuminate\Database\Seeder;

class CompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Competition::create([
          'name' => 'Test Competition 1',
          'location' => 'Test Location 1',
          'start_date' => Carbon::now(),
          'end_date' => Carbon::now()->addDays(2),
          'tables' => 1
        ]);
    }
}
