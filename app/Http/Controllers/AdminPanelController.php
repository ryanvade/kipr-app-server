<?php

namespace KIPR\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Passport\Client;

class AdminPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    /**
      * Return the view which contains the admin panel SPA.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
    public function index(Request $request)
    {
      $client = Client::where('name', 'Laravel Password Grant Client')->firstOrFail();
      $user = auth()->user();
        return view('adminpanel')->with([
          'client_id' => $client->id,
          'client_secret' => $client->secret,
          'user' => $user
        ]);
    }
}
