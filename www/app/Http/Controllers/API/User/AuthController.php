<?php

namespace App\Http\Controllers\API\User;

use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public $loginAfterSignUp = true;

    public function __construct()
    {
        $this->middleware('auth.jwt', ['except' => ['login','register','bj']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only("email", "password");
        $token = null;

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = auth()->login($user);

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return $this->respondWithToken($token);

    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            "token" => "required"
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                "status" => true,
                "message" => "User logged out successfully"
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                "status" => false,
                "message" => "Ops, the user can not be logged out"
            ]);
        }
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function bj(Request $request)
    {
        return $request;
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'auth' => true,
            'token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user(),
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}