<?php

use KIPR\Team;
use KIPR\Competition;
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
        $team = Team::create([
          'name' => 'Test Team 1',
          'email' => 'test1@team.com',
          'code' => '18-001'
        ]);
        $competition = Competition::first();
        $competiton->teams()->attach($team);
        if($competition == null) {
          $competition = factory(Competition::class)->create();
        }
        for ($i=0; $i < 10; $i++) {
          $team = factory(Team::class)->create();
          $competition->teams()->attach($team);
        }
        for ($i=0; $i < 10; $i++) {
          $team = factory(Team::class)->create();
        }
    }
}
