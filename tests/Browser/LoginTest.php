<?php

namespace Tests\Browser;

use KIPR\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Laravel\Passport\ClientRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;
    public function test_authentication_is_required_to_access_the_admin_panel()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin')
                ->assertDontSee('Hello, admin')
                ->assertSee('Login');
        });
    }

    public function test_authenticated_users_can_access_admin_panel()
    {
        $clients = new ClientRepository();
        User::create([
          'name' => 'admin',
          'email' => 'admin@foo.foo',
          'password' => bcrypt('password123')
        ]);
        $clients->create(1, 'Laravel Password Grant Client', env('APP_URL'));
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin')
              ->assertDontSee('Hello, admin')
              ->assertSee('Login')
              ->type('email', 'admin@foo.foo')
              ->type('password', 'password123')
              ->click('.button')
              ->assertPathIs('/admin')
              ->assertDontSee('Sorry, the page you are looking for could not be found.');
        });
    }
}
