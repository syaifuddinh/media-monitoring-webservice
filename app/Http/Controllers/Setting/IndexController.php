<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{
    public function store(Request $request)
    {
        $key = $request->input('key');
        $value = $request->input('value');

        $data = null;
        try {
            $setting = DB::table("settings")
            ->whereKey($key)
            ->first();
            if(!$setting)
                throw new \Exception("Setting tidak ditemukan");
            else {
                DB::table("settings")
                ->whereId($setting->id)
                ->update([
                    "value" => $value
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "data" => null
            ], 401);
        }

        return response()->json([
            "success" => true,
            "message" => "Sukses",
            "data" => $data
        ]);
    }

    public function show($key)
    {
        $data = null;
        try {
            $setting = DB::table("settings")
            ->whereKey($key)
            ->first();
            if(!$setting)
                throw new \Exception("Setting tidak ditemukan");
            else {
                $data = ["value" => $setting->value];
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "data" => null
            ], 401);
        }

        return response()->json([
            "success" => true,
            "message" => "Sukses",
            "data" => $data
        ]);
    }
}
