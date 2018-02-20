<?php

use Laravel\Passport\Client;
use Illuminate\Database\Seeder;

class PassportClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Client::create([
        'user_id' => 1,
        'name' => 'Laravel Password Grant Client',
        'secret' => str_random(40),
        'redirect' => env('APP_URL'),
        'personal_access_client' => false,
        'password_client' => true,
        'revoked' => false
      ]);
    }
}
