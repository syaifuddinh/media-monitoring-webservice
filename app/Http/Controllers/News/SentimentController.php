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
        DB::beginTransaction();
        try {
            $data = News::show($newsId);
            $options = [
                [
                    "key" => "positif",
                    "column" => "sentpos",
                    "value" => $data->sentpos
                ],
                [
                    "key" => "negatif",
                    "column" => "sentneg",
                    "value" => $data->sentneg
                ],
                [
                    "key" => "netral",
                    "column" => "sentneutral",
                    "value" => $data->sentneutral
                ]
            ];
            $options = collect($options);
            $max = $options->max("value");
            $column = $options->where("key", $sentiment)->first()["column"];
            $otherColumn = $options->where("key", "!=", $sentiment)->toArray();
            $remainValue = (1 - $max) / 2;
            DB::table(News::$table)
            ->whereRawid($newsId)
            ->update([
                "sentiment" => $sentiment,
                $column => $max
            ]);
            foreach($otherColumn as $value) {
                DB::table(News::$table)
                ->whereRawid($newsId)
                ->update([
                    $value['column'] => $remainValue
                ]);
            }
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
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
