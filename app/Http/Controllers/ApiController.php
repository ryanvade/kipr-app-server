<!-- Copyright (c) 2018 KISS Institute for Practical Robotics

BSD v3 License

All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

* Neither the name of KIPR Scoring App nor the names of its
  contributors may be used to endorse or promote products derived from
  this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. -->
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
