<?php

use KIPR\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Team::create([
          'name' => 'Test Team 1',
          'email' => 'test1@team.com',
          'code' => '18-001'
        ]);
    }
}
