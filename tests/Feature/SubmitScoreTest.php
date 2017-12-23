<?php

namespace Tests\Feature;

use KIPR\User;
use KIPR\Match;
use Tests\TestCase;
use KIPR\Competition;
use Laravel\Passport\Passport;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\PersonalAccessClient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubmitScoreTest extends TestCase
{
    use RefreshDatabase;

    private $token = '';
    public function setUp()
    {
        parent::setUp();

        $admin = Factory(User::class)->create();
        $notadmin = Factory(User::class)->create();

        $clients = new ClientRepository();
        $client = $clients->createPersonalAccessClient(
          null,
            'judging',
            env('APP_URL', 'http://localhost')
      );
        $accessClient = new PersonalAccessClient();
        $accessClient->client_id = $client->id;
        $accessClient->save();

        Passport::tokensCan([
            'judge-tournaments' => 'Judge Tournaments',
            'check-in-teams' => 'Check in Teams',
        ]);

        $this->token = $admin->createToken('judging', ['judge-tournaments'])->accessToken;
    }

    public function test_only_authenticated_judges_can_submit_match_scores()
    {
        # Given a competition
        $competition = Factory(Competition::class)->create();
        # and a rule
        $competition->rule()->create([
          'year' => '2017',
          'rules' => json_encode([
                [
                  "type" =>  "count",
                  "object" =>  "robot",
                  "location" =>  "terrace",
                  "count" =>  1,
                  "value" =>  15,
                ],
                [
                  "type" =>  "count",
                  "object" =>  "golfball",
                  "location" =>  "cup",
                  "count" =>  1,
                  "value" =>  1,
                ],
                [
                  "type" =>  "multiplier",
                  "object" =>  "golfball",
                  "location" =>  "cup",
                  "count" =>  3,
                  "value" =>  5,
                ],
          ])
        ]);
        # and a match
        $match = Factory(Match::class)->create();
        # and a judging code
        $code = '';
        # and a set of match results
        $results = '';
        # post the results to the server
        $response = $this->withHeaders([
          'Accept' => 'application/json',
          'Authorization' => 'Bearer '. $this->token,
        ])
        ->json('patch', '/api/match/' . $match->id . '/score', [
          'results' => json_encode($results)
        ]);

        $response
                ->assertStatus(200)
                ->assertJson([
                  'status' => 'scored'
                ]);
    }
}
