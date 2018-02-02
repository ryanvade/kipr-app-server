<?php

namespace Tests\Feature;

use KIPR\User;
use Carbon\Carbon;
use Tests\TestCase;
use KIPR\Competition;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCompetitionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admins_can_create_competitions()
    {
        # given a logged in user
        $user = factory(User::class)->create();
        # and some competition information
        $name = 'Test Competition';
        $location = 'Test Location';
        $start_date = Carbon::createFromFormat("m/d/Y h:iA", "01/31/2018 05:01PM");
        $end_date = Carbon::createFromFormat("m/d/Y h:iA", "01/31/2018 05:01PM")->addDays(2);
        # attempt to create a competition
        $response = $this->actingAs($user)
                       ->json('POST', "/api/competition", [
                         'name' => $name,
                         'location' => $location,
                         'startDate' => $start_date->format("m/d/Y h:iA"),
                         'endDate' => $end_date->format("m/d/Y h:iA")
                       ]);
        # and check if it was created
        $response->assertStatus(200)
                 ->assertJson([
                   'status' => 'success'
                 ]);
        $this->assertDatabaseHas('competitions', [
          'name' => $name,
          'location' => $location,
          'start_date' => $start_date->toDateTimeString(),
          'end_date' => $end_date->toDateTimeString(),
          'ruleset_id' => null
        ]);
    }

    public function test_authentication_is_required_to_create_a_competition()
    {
        # Given some competition information
        $name = 'Test Competition';
        $location = 'Test Location';
        $start_date = Carbon::createFromFormat("m/d/Y h:iA", "01/31/2018 05:01PM");
        $end_date = Carbon::createFromFormat("m/d/Y h:iA", "01/31/2018 05:01PM")->addDays(2);
        # attempt to create a competition
        $response = $this->json('POST', "/api/competition", [
                       'name' => $name,
                       'location' => $location,
                       'startDate' => $start_date->format("m/d/Y h:iA"),
                       'endDate' => $end_date->format("m/d/Y h:iA")
                     ]);
        # and check if it was created
        $response->assertStatus(401);
        $this->assertDatabaseMissing('competitions', [
          'name' => $name,
          'location' => $location,
          'start_date' => $start_date->toDateTimeString(),
          'end_date' => $end_date->toDateTimeString(),
          'ruleset_id' => null
        ]);
    }

    public function test_cannot_create_a_competition_if_the_name_is_taken()
    {
        # given a logged in user
        $user = factory(User::class)->create();
        # and some competition information
        $name = 'Test Competition';
        $location = 'Test Location';
        $start_date = Carbon::createFromFormat("m/d/Y h:iA", "01/31/2018 05:01PM");
        $end_date = Carbon::createFromFormat("m/d/Y h:iA", "01/31/2018 05:01PM")->addDays(2);
        # create a competition with the name
        $competition = Competition::create([
          'name' => $name,
          'location' => 'Some Other Location',
          'start_date' => Carbon::createFromFormat("m/d/Y h:iA", "02/28/2018 05:01PM"),
          'end_date' => Carbon::createFromFormat("m/d/Y h:iA", "02/28/2018 05:01PM")->addDays(2)
        ]);
        # attempt to create a competition
        $response = $this->actingAs($user)
                             ->json('POST', "/api/competition", [
                               'name' => $name,
                               'location' => $location,
                               'startDate' => $start_date->format("m/d/Y h:iA"),
                               'endDate' => $end_date->format("m/d/Y h:iA")
                             ]);
        # check the response
        $response->assertStatus(422);
        $this->assertDatabaseMissing('competitions', [
          'name' => $name,
          'location' => $location,
          'start_date' => $start_date->toDateTimeString(),
          'end_date' => $end_date->toDateTimeString(),
          'ruleset_id' => null
        ]);
    }
}
