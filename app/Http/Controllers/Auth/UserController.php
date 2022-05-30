<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Http\Abstracts\User;
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

    public function index(Request $request)
    {
        $data = null;
        $list = [];
        $count = 0;
        $total = 0;
        $keyword = $request->input('keyword');
        $page = $request->input('page');
        $length = $request->input('length');
        $page = $page ? $page : 1;
        $length = $length ? $length : 10;
        $paging = [
            "page" => $page,
            "length" => $length
        ];

        $data = User::index($keyword, $paging);

        return response()->json([
            "success" => true,
            "message" => "Sukses",
            "data" => $data
        ]);
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $username = $request->input('username');
        $password = $request->input('password');
        $confirmPassword = $request->input('confirmPassword');
        $userRole = $request->input('userRole');
        try {
            User::store(
                $name,
                $username,
                $password,
                $confirmPassword,
                $userRole
            );
        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "data" => null
            ], 422); 
        }

        return response()->json([
            "success" => true,
            "message" => "Sukses",
            "data" => null
        ]);        
    }

    public function update(Request $request, $id)
    {
        $name = $request->input('name');
        $username = $request->input('username');
        $password = $request->input('password');
        $confirmPassword = $request->input('confirmPassword');
        $userRole = $request->input('userRole');

        try {
            User::update(
                $name,
                $username,
                $password,
                $confirmPassword,
                $userRole,
                $id
            );
        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "data" => null
            ], 422); 
        }

        return response()->json([
            "success" => true,
            "message" => "Sukses",
            "data" => null
        ]); 
    }

    public function show($id)
    {
        $data = null;
        try {
            $data = User::show($id);
        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "data" => null
            ], 422); 
        }

        return response()->json([
            "success" => true,
            "message" => "Sukses",
            "data" => $data
        ]);
    }

    public function destroy($id)
    {
        try {
            User::destroy($id);
        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "data" => null
            ], 422); 
        }

        return response()->json([
            "success" => true,
            "message" => "Sukses",
            "data" => null
        ]); 
    }
}
