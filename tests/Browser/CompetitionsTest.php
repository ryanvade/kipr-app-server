<?php

namespace Tests\Browser;

use KIPR\User;
use KIPR\Competition;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Laravel\Passport\ClientRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CompetitionsTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_view_all_competitions_page_without_competitions()
    {
      $clients = new ClientRepository();
      User::create([
        'name' => 'admin',
        'email' => 'admin@foo.foo',
        'password' => bcrypt('password123')
      ]);
      $clients->create(1, 'Laravel Password Grant Client', env('APP_URL'));
        $this->browse(function ($first) {
            $first->loginAs(User::where('name', 'admin')->first())
          ->visit('/admin/competitions')
          ->assertDontSee('Click on a competition for more options.');
        });
    }

    public function test_view_all_competitions_page_with_one_competition() {
      $clients = new ClientRepository();
      User::create([
        'name' => 'admin',
        'email' => 'admin@foo.foo',
        'password' => bcrypt('password123')
      ]);
      factory(Competition::class)->create();
      $clients->create(1, 'Laravel Password Grant Client', env('APP_URL'));
        $this->browse(function ($first) {
            $first->loginAs(User::where('name', 'admin')->first())
          ->visit('/admin/competitions')
          ->waitFor('.level')
          ->assertSee('1 Competitions');
        });
    }

    public function test_view_all_competitions_page_with_twenty_competitions() {
      $clients = new ClientRepository();
      User::create([
        'name' => 'admin',
        'email' => 'admin@foo.foo',
        'password' => bcrypt('password123')
      ]);
      for ($i=0; $i < 20; $i++) {
        factory(Competition::class)->create();
      }
      $clients->create(1, 'Laravel Password Grant Client', env('APP_URL'));
        $this->browse(function ($first) {
            $first->loginAs(User::where('name', 'admin')->first())
          ->visit('/admin/competitions')
          ->waitFor('.level')
          ->assertSee('20 Competitions');
        });
    }

    public function test_view_all_competitions_page_with_twenty_one_competitions() {
      $clients = new ClientRepository();
      User::create([
        'name' => 'admin',
        'email' => 'admin@foo.foo',
        'password' => bcrypt('password123')
      ]);
      for ($i=0; $i < 21; $i++) {
        factory(Competition::class)->create();
      }
      $clients->create(1, 'Laravel Password Grant Client', env('APP_URL'));
        $this->browse(function ($first) {
            $first->loginAs(User::where('name', 'admin')->first())
          ->visit('/admin/competitions')
          ->waitFor('.level')
          ->assertSee('21 Competitions')
          ->assertSee('Next Page')
          ->click('.next-page-button')
          ->assertSee('Previous Page');
        });
    }

    public function test_create_competition_page(){
      $clients = new ClientRepository();
      User::create([
        'name' => 'admin',
        'email' => 'admin@foo.foo',
        'password' => bcrypt('password123')
      ]);
      $clients->create(1, 'Laravel Password Grant Client', env('APP_URL'));
      $this->browse(function ($first) {
          $first->loginAs(User::where('name', 'admin')->first())
                ->visit('/admin/competitions/create')
                ->type('name', 'Test Competition')
                ->type('location', 'Test Location')
                // ->type('end_date', '1/30/2019 10:10PM')
                ->click('input[name="end_date"]')
                ->click('.fa-chevron-right')
                ->click('.is-primary')
                ->waitFor('.card')
                ->assertSee('Test Competition');
        });
    }

    public function test_create_competition_page_cannot_duplicate_name(){
      $clients = new ClientRepository();
      User::create([
        'name' => 'admin',
        'email' => 'admin@foo.foo',
        'password' => bcrypt('password123')
      ]);
      $clients->create(1, 'Laravel Password Grant Client', env('APP_URL'));
      $comp = factory(Competition::class)->create();
      $this->browse(function ($first) use($comp) {
          $first->loginAs(User::where('name', 'admin')->first())
                ->visit('/admin/competitions/create')
                ->type('name', $comp->name)
                ->type('location', 'Test Location')
                // ->type('end_date', '1/30/2019 10:10PM')
                ->click('input[name="end_date"]')
                ->click('.fa-chevron-right')
                ->assertSee('The name has already been taken.');
        });
    }

    public function test_edit_competition_page() {
      $clients = new ClientRepository();
      User::create([
        'name' => 'admin',
        'email' => 'admin@foo.foo',
        'password' => bcrypt('password123')
      ]);
      $clients->create(1, 'Laravel Password Grant Client', env('APP_URL'));
      $comp = factory(Competition::class)->create();
      $this->browse(function ($first) {
          $first->loginAs(User::where('name', 'admin')->first())
                ->visit('/admin/competitions/1')
                ->waitFor('.card')
                ->click('#edit')
                ->waitFor('.field')
                ->type('name', 'Test Competition')
                ->type('location', 'Test Location')
                // ->type('end_date', '1/30/2019 10:10PM')
                ->click('input[name="end_date"]')
                ->click('.fa-chevron-right')
                ->assertDontSee('End date must be after the start date.')
                ->click('.is-primary')
                ->waitFor('.card')
                ->assertSee('Test Competition');
        });
    }
}
