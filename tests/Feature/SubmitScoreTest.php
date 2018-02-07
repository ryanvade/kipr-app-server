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

    // TODO: Will need to be tested once the client side is implemented
    public function test_only_authenticated_judges_can_submit_scores() {
      $this->assertTrue(true);
    }
}
