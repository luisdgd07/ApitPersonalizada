<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function signup(Request $request)
    {
        $token = bcrypt($request->wallet);
        User::create([
            'wallet' => $request->wallet,
            'token' => $token
        ]);
        return response()->json("su token es:" . $token);
    }
    public function login(Request $request)
    {
        // $credentials = request(['wallet', 'token']);
        if (!Auth::login(["wallet" => $request->wallet, "token" => $request->token]))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }
}
