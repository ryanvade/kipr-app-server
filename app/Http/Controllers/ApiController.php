<?php

namespace KIPR\Http\Controllers;

use Carbon\Carbon;
use KIPR\Competition;
use Laravel\Passport\Token;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
      * Get a Auth Tokens for judging.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  \KIPR\Competition $competition
      * @return \Illuminate\Http\Response
      */
    public function getAuthTokensForJudging(Request $request, Competition $competition)
    {
        // Get the Tokens
        $user = auth()->user();
        $tokens = $user->tokens()->get();
        $judging_tokens = [];
        foreach ($tokens as $token) {
            if (in_array("judging", $token->scopes) && starts_with($token->name, "Competition " . $competition->id)) {
                $token->expires_at = $competition->end_date;
                $token->save();
                // Append the required Competition information to the token
                $token->competition = $competition;
                // Turn the tokens into QR Codes
                $token->image = "data:image/png;base64," . base64_encode(\QrCode::format('png')->size(500)->generate($token->id . '|' . $competition->id));
                array_push($judging_tokens, $token);
            }
        }
        return response()->json([
        'status' => 'success',
        'tokens' => collect($judging_tokens)
      ]);
    }

    /**
      * Get a Auth Tokens for sign in.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  \KIPR\Competition $competition
      * @return \Illuminate\Http\Response
      */
    public function getAuthTokensForSignIn(Request $request, Competition $competition)
    {
        // Get the Tokens
        $user = auth()->user();
        $tokens = $user->tokens()->get();
        $judging_tokens = [];
        foreach ($tokens as $token) {
            if (in_array("sign_in", $token->scopes) && starts_with($token->name, "Competition " . $competition->id)) {
                $token->expires_at = $competition->end_date;
                $token->save();
                // Append the required Competition information to the token
                $token->competition = $competition;
                // Turn the tokens into QR Codes
                $token->image = "data:image/png;base64," . base64_encode(\QrCode::format('png')->size(500)->generate($token->id . '|' . $competition->id));
                array_push($judging_tokens, $token);
            }
        }
        return response()->json([
        'status' => 'success',
        'tokens' => collect($judging_tokens)
      ]);
    }

    /**
     * Validate Token - Get the Auth Token for Validation
     * @param  Request $request
     * @return Token|null
     */
    public function getToken(Request $request) {
      $token = $request->header('Authorization');
      $token = explode(" ", $token)[1];
      return Token::where('id', $token)->first();
    }
}
