<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');
        try {
            $user = DB::table("users")
            ->where('username', $username)
            ->first();
            if (!$user) {
                throw new \Exception("Username atau password tidak ditemukan");
            }

            $isValidPassword = Hash::check($password, $user->password);
            if (!$isValidPassword) {
                throw new \Exception("Username atau password tidak ditemukan");
            }

            $generateToken = bin2hex(random_bytes(40));
            DB::table("users")
            ->where('username', $username)
            ->update([
                'token' => $generateToken
            ]);
            $user = DB::table("users")
            ->where('username', $username)
            ->select("name", "token", DB::raw("'Bearer' AS tokenType"))
            ->first();
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "data" => null
            ], 401);
        }

        return response()->json([
            "success" => true,
            "data" => $user
        ]);
    }

    public function check(Request $request) {
           
    }

    public function logout(Request $request) {
        $auth = $request->headers->get('Authorization');
        $pieces= explode(" ", $auth);
        $tokenType = count($pieces) > 0 ? $pieces[0] : null;
        $token = count($pieces) > 1 ? $pieces[1] : null;
        try {
            if(!$tokenType || $tokenType !== "Bearer")
                throw new \Exception("Logout failed");
            if(!$token)
                throw new \Exception("Logout failed");
            else {
                $existing = DB::table("users")->whereToken($token)->first();
                if(!$existing)
                    throw new \Exception("User not found");
                else {
                    DB::table("users")
                    ->whereId($existing->id)
                    ->update([
                        "token" => null,
                        "updated_at" => date("Y-m-d H:i:s")
                    ]);
                }


            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 400);
        }

        return response()->json([
            "success" => true,
            "message" => "Ok"
        ]);
    }
}
