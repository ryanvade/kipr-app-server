<?php

namespace Tests\Feature;

use Tests\TestCase;
use KIPR\User;
use KIPR\Competition;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTeamsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admins_can_create_teams() {
      # given a logged in user
      $user = factory(User::class)->create();
      # and some team information
      $name = 'Test Team';
      $email = 'testemail@foo.foo';
      $code = "18-001";

      # create the team
      $response = $this->actingAs($user)
                       ->json('POST', '/api/team', [
                         'name' => $name,
                         'email' => $email,
                         'code' => $code
                       ]);
      # check the status
      $response->assertStatus(200)
               ->json([
                 'status' => 'success'
               ]);
      $this->assertDatabaseHas('teams', [
        'name' => $name,
        'email' => $email,
        'code' => $code
      ]);
    }

    public function test_authentication_is_required_to_create_a_team() {
      # and some team information
      $name = 'Test Team';
      $email = 'testemail@foo.foo';
      $code = "18-001";

      # create the team
      $response = $this->json('POST', '/api/team', [
                         'name' => $name,
                         'email' => $email,
                         'code' => $code
                       ]);
      # check the status
      $response->assertStatus(401);
      $this->assertDatabaseMissing('teams', [
        'name' => $name,
        'email' => $email,
        'code' => $code
      ]);
    }
}
