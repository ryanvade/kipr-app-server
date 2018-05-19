<?php

namespace KIPR\Providers;

use \DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $date = DB::table('competitions')
                  ->groupBy('start_date')
                  ->pluck('start_date')
                  ->sort()
                  ->last();
        if($date != null) {
          $year = (new Carbon($date))->year;
        } else {
          $year = null;
        }
        View::share('latestseason', $year);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
