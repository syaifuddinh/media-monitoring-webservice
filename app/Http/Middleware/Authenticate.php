<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use DB;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $auth = $request->headers->get('Authorization');
        $pieces= explode(" ", $auth);
        $tokenType = count($pieces) > 0 ? $pieces[0] : null;
        $token = count($pieces) > 1 ? $pieces[1] : null;
        try {
            if(!$tokenType || $tokenType !== "Bearer")
                throw new \Exception("Verifikasi gagal. Silahkan lakukan login kembali");
            if(!$token)
                throw new \Exception("Verifikasi gagal. Silahkan lakukan login kembali");
            else {
                $existing = DB::table("users")->whereToken($token)->first();
                if(!$existing)
                    throw new \Exception("Verifikasi gagal. Silahkan lakukan login kembali");
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 401);
        }

        return $next($request);
    }
}
