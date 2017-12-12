<?php

namespace KIPR\Http\Controllers;

use Carbon\Carbon;
use KIPR\Competition;
use Illuminate\Http\Request;

class ApiController extends Controller
{
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
        # get a new Personal Access Token
        $token = $user->createToken('Judging Competition ' . $competition->id)->accessToken;
        # get a token and expiration date string
        $value = $token . ";" . Carbon::now()->addHours(8)->toDateTimeString() . ";";
        # generate the QR code and put it in an img src format
        $src = "data:image/png;base64, " . base64_encode(QrCode::format('png')->generate($value));
        # return the image
        return response()->json([
        'status' => 'success',
        'qrcode_src' => $src
      ]);
    }
}
