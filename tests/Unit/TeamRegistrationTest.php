<?php

namespace Tests\Unit;

use KIPR\Team;
use KIPR\User;
use Tests\TestCase;
use KIPR\Competition;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamRegistrationTest extends TestCase
{
    use RefreshDatabase;
    public function test_an_admin_can_register_a_set_of_teams() {
      // given an admin
      $admin = factory(User::class)->create();
      // acting as the admin
      Passport::actingAs(
        factory(User::class)->create(),
        []
      );
      // and a competition
      $comp = factory(Competition::class)->create();
      // and some teams
      $teamA = factory(Team::class)->create();
      $teamB = factory(Team::class)->create();
      $teamC = factory(Team::class)->create();
      $teamD = factory(Team::class)->create();
      // attempt to register the teams with the competition
      $response = $this->json('POST', '/api/competition/1/team/register', [
        'team_ids' => [
          $teamA->id,
          $teamB->id,
          $teamC->id,
          $teamD->id
        ]
      ]);

      $response->assertStatus(200)
               ->assertJson([
                 'teams' => [
                   $teamA->toArray(),
                   $teamB->toArray(),
                   $teamC->toArray(),
                   $teamD->toArray(),
                 ]
               ]);
    }

    public function test_authentication_is_required_to_register_teams() {
      // given a competition
      $comp = factory(Competition::class)->create();
      // and some teams
      $teamA = factory(Team::class)->create();
      $teamB = factory(Team::class)->create();
      $teamC = factory(Team::class)->create();
      $teamD = factory(Team::class)->create();
      // attempt to register the teams with the competition
      $response = $this->json('POST', '/api/competition/1/team/register', [
        'team_ids' => [
          $teamA->id,
          $teamB->id,
          $teamC->id,
          $teamD->id
        ]
      ]);
      $response->assertStatus(401);
    }

    public function test_cannot_register_teams_that_do_not_exist() {
      // given an admin
      $admin = factory(User::class)->create();
      // acting as the admin
      Passport::actingAs(
        factory(User::class)->create(),
        []
      );
      // and a competition
      $comp = factory(Competition::class)->create();
      // attempt to register the teams with the competition
      $response = $this->json('POST', '/api/competition/1/team/register', [
        'team_ids' => [
          5,
          9,
          588
        ]
      ]);

      $response->assertStatus(422)
               ->assertJsonMissing([
                 'teams' => [
                   5,
                   9,
                   588
                 ]
               ]);
    }

    public function test_cannot_register_teams_for_a_competition_that_doesnt_exist() {
      // given an admin
      $admin = factory(User::class)->create();
      // acting as the admin
      Passport::actingAs(
        factory(User::class)->create(),
        []
      );
      // and some teams
      $teamA = factory(Team::class)->create();
      $teamB = factory(Team::class)->create();
      $teamC = factory(Team::class)->create();
      $teamD = factory(Team::class)->create();
      // attempt to register the teams with the competition
      $response = $this->json('POST', '/api/competition/9/team/register', [
        'team_ids' => [
          $teamA->id,
          $teamB->id,
          $teamC->id,
          $teamD->id
        ]
      ]);

      $response->assertStatus(404)
               ->assertJsonMissing([
                 'teams' => [
                   $teamA->toArray(),
                   $teamB->toArray(),
                   $teamC->toArray(),
                   $teamD->toArray(),
                 ]
               ]);
    }

    public function test_admin_can_deregister_teams() {
      // given an admin
      $admin = factory(User::class)->create();
      // acting as the admin
      Passport::actingAs(
        factory(User::class)->create(),
        []
      );
      // and a competition
      $comp = factory(Competition::class)->create();
      // and some teams
      $teamA = factory(Team::class)->create();
      $teamB = factory(Team::class)->create();
      $teamC = factory(Team::class)->create();
      $teamD = factory(Team::class)->create();
      // register the teams
      $comp->teams()->attach($teamA);
      $comp->teams()->attach($teamB);
      $comp->teams()->attach($teamC);
      $comp->teams()->attach($teamD);
      // attempt to register the teams with the competition
      $response = $this->json('POST', '/api/competition/1/team/deregister', [
        'team_ids' => [
          $teamA->id,
          $teamB->id,
          $teamC->id,
          $teamD->id
        ]
      ]);

      $response->assertStatus(200)
               ->assertJson([
                 'teams' => []
               ]);
    }

    public function test_authentication_is_required_to_deregister_teams() {
      // given a competition
      $comp = factory(Competition::class)->create();
      // and some teams
      $teamA = factory(Team::class)->create();
      $teamB = factory(Team::class)->create();
      $teamC = factory(Team::class)->create();
      $teamD = factory(Team::class)->create();
      // register the teams
      $comp->teams()->attach($teamA);
      $comp->teams()->attach($teamB);
      $comp->teams()->attach($teamC);
      $comp->teams()->attach($teamD);
      // attempt to register the teams with the competition
      $response = $this->json('POST', '/api/competition/1/team/register', [
        'team_ids' => [
          $teamA->id,
          $teamB->id,
          $teamC->id,
          $teamD->id
        ]
      ]);
      $response->assertStatus(401);
    }

    public function test_cannot_deregister_teams_that_do_not_exist() {
      // given an admin
      $admin = factory(User::class)->create();
      // acting as the admin
      Passport::actingAs(
        factory(User::class)->create(),
        []
      );
      // and a competition
      $comp = factory(Competition::class)->create();
      // attempt to register the teams with the competition
      $response = $this->json('POST', '/api/competition/1/team/deregister', [
        'team_ids' => [
          5,
          9,
          588
        ]
      ]);

      $response->assertStatus(422)
               ->assertJsonMissing([
                 'teams' => [
                   5,
                   9,
                   588
                 ]
               ]);
    }

    public function test_cannot_deregister_teams_for_a_competition_that_doesnt_exist() {
      // given an admin
      $admin = factory(User::class)->create();
      // acting as the admin
      Passport::actingAs(
        factory(User::class)->create(),
        []
      );
      // and some teams
      $teamA = factory(Team::class)->create();
      $teamB = factory(Team::class)->create();
      $teamC = factory(Team::class)->create();
      $teamD = factory(Team::class)->create();
      // attempt to register the teams with the competition
      $response = $this->json('POST', '/api/competition/9/team/deregister', [
        'team_ids' => [
          $teamA->id,
          $teamB->id,
          $teamC->id,
          $teamD->id
        ]
      ]);

      $response->assertStatus(404)
               ->assertJsonMissing([
                 'teams' => [
                   $teamA->toArray(),
                   $teamB->toArray(),
                   $teamC->toArray(),
                   $teamD->toArray(),
                 ]
               ]);
    }
}
