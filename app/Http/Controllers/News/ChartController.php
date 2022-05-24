<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Http\Abstracts\News;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use DateTime;

class ChartController extends Controller
{
    public function index(Request $request)
    {
        $data = null;
        $list = [];
        $keyword = $request->input('keyword');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $positif = [];
        $negatif = [];
        $netral = [];
        $total = [];
        $interval = [];

        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);

        for($i = $begin; $i <= $end; $i->modify('+1 day')) {
            $interval[] = $i->format("Y-m-d");
        }

        $news = News::query($keyword, $startDate, $endDate)
        ->select(DB::raw("DATE_FORMAT(published_date, '%Y-%m-%d') AS publishedDate"), "sentneg", "sentpos", "sentneutral", DB::raw("(sentneg + sentpos + sentneutral) AS senttotal"))
        ->get();

        $list = collect($news);
        $grouped = $list->groupBy("publishedDate")->toArray();
        foreach($interval as $index => $primaryDate) {
            $positif[] = 0;
            $negatif[] = 0;
            $netral[] = 0;
            $total[] = 0;
            foreach($grouped as $secondDate => $secondData) {
                if($primaryDate === $secondDate) {
                    $listNews = collect($grouped[$secondDate]);

                    $positif[$index] = $listNews->map(function($data) { return $data->sentpos; })->sum();
                    $positif[$index] = round($positif[$index], 2);

                    $negatif[$index] = $listNews->map(function($data) { return $data->sentneg; })->sum();
                    $negatif[$index] = round($negatif[$index], 2);

                    $netral[$index] = $listNews->map(function($data) { return $data->sentneutral; })->sum();
                    $netral[$index] = round($netral[$index], 2);

                    $total[$index] = $positif[$index] + $negatif[$index] + $netral[$index];
                }
            }
        }
        $data["interval"] = $interval;
        $data["positif"] = $positif;
        $data["negatif"] = $negatif;
        $data["netral"] = $netral;
        $data["total"] = $total;

        return response()->json([
            "success" => true,
            "message" => "Sukses",
            "data" => $data
        ]);
    }
}
