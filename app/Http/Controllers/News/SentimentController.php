<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Http\Abstracts\News;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;

class SentimentController extends Controller
{
    public function update(Request $request, $newsId)
    {
        $data = null;
        $sentiment = $request->input('sentiment');
        try {
            $data = News::validate($newsId);
            DB::table(News::$table)
            ->whereRawid($newsId)
            ->update([
                "sentiment" => $sentiment
            ]);
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
}
