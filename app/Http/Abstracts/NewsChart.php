<?php

namespace App\Http\Abstracts;

use DB;
use App\Http\Abstracts\News;
use DateTime;

class NewsChart 
{
    public static function index($keyword, $startDate, $endDate)
    {
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);
        if($begin > $end) {
            $begin = $end;
            $end = new DateTime($startDate);
        }
        $interval = [];
        $positif = [];
        $negatif = [];
        $netral = [];
        $total = [];
        for($i = $begin; $i <= $end; $i->modify('+1 day')) {
            $interval[] = $i->format("Y-m-d");
        }

        $news = News::query($keyword, $startDate, $endDate)
        ->select(DB::raw("DATE_FORMAT(published_date, '%Y-%m-%d') AS publishedDate"), "sentiment", "sentneg", "sentpos", "sentneutral", DB::raw("(sentneg + sentpos + sentneutral) AS senttotal"))
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
                    $positif[$index] = count($listNews->where("sentiment", "positif"));
                    $negatif[$index] = count($listNews->where("sentiment", "negatif"));
                    $netral[$index] = count($listNews->where("sentiment", "netral"));

                    $total[$index] = $positif[$index] + $negatif[$index] + $netral[$index];
                }
            }
        }
        $data["interval"] = $interval;
        $data["positif"] = $positif;
        $data["negatif"] = $negatif;
        $data["netral"] = $netral;
        $data["total"] = $total;

        return $data;
    }
}
