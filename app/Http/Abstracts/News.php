<?php

namespace App\Http\Abstracts;

use DB;

class News 
{
    public function query($keyword, $startDate, $endDate) {
        $endDate = $endDate ? $endDate . " 23:59" : $endDate;
        $list = DB::table("rawdata");
        $list = $list->orderBy("published_date", "DESC");
        $list = $keyword ? $list->where(function($query) use ($keyword) {
            $query->where("title", "LIKE", "%$keyword%");
            $query->orWhere("textcontent", "LIKE", "%$keyword%");
            $query->orWhere("source", "LIKE", "%$keyword%");
        }) : $list;
        $list = $startDate ? $list->where("published_date", ">=", $startDate) : $list;
        $list = $endDate ? $list->where("published_date", "<=", $endDate) : $list;

        return $list;
    }

    public static function index($keyword, $startDate, $endDate)
    {
        
        $list = self::query($keyword, $startDate, $endDate);
        $list = $list->select("rawid AS id", "published_date AS publishedDate", "title", DB::raw("CONCAT(SUBSTRING(textcontent, 1, 426), '....') AS description"), "source", "sentiment");
        $count = $list->count("rawid");
        $total = DB::table("rawdata")->count("rawid");
        $list = $list->get();

        $data["list"] = $list;
        $data["count"] = $count;
        $data["total"] = $total;

        return $data;
    }
}
