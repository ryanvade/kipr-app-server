<?php

namespace KIPR\Http\Controllers;

use Carbon\Carbon;
use KIPR\Competition;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');
    }
    /**
      * Get a QR code for judging.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  \KIPR\Competition $competition
      * @return \Illuminate\Http\Response
      */
    public function getQRCodeForJudging(Competition $competition)
    {
        # get the authenticated user
        $user = auth()->user();
        # if no user, then return unathorized
        if ($user == null) {
            return response()->json([
          'status' => 'unathorized'
        ], 403);
        }
        # get token count
        $count = $user->tokens()->count();
        # get a new Personal Access Token
        $token = $user->createToken($user->name . ' Judging ' . $count)->accessToken;
        # get a token and expiration date string
        $value = $token . ";" . Carbon::now()->addHours(8)->toDateTimeString() . ";";
        # generate the QR code and put it in an img src format
        $token->image = "data:image/png;base64, " . base64_encode(QrCode::format('png')->generate($value));
        # return the image
        return response()->json([
        'status' => 'success',
        'token' => $token
      ]);
    }

    public function getAuthTokensForCompetition(Request $request, Competition $competition) {
      // Get the Tokens
      $user = auth()->user();
      $tokens = $user->tokens()->get();
      $judging_tokens = [];
      foreach ($tokens as $token ) {
        if(in_array("judging", $token->scopes)) {
          // Append the required Competition information to the token
          $token->competition = $competition;
          // Turn the tokens into QR Codes
          $token->image = "data:image/png;base64," . base64_encode(\QrCode::format('png')->generate($token->id . '|' . $competition->id));
          array_push($judging_tokens, $token);
        }
      }
      return response()->json([
        'status' => 'success',
        'tokens' => collect($judging_tokens)
      ]);
    }
}
