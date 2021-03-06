<?php

namespace App\Http\Abstracts;

use DB;

class News 
{
    public static $table = "rawdata";

    public function query($keyword, $startDate, $endDate, $sentiment = "", $newsSource = "") {
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
        $list = $sentiment ? $list->where("sentiment", "=", $sentiment) : $list;
        $list = $newsSource ? $list->where("source", "=", $newsSource) : $list;

        return $list;
    }

    public static function index($keyword, $startDate, $endDate, $paging = [], $sentiment = "", $newsSource = "")
    {
        $page = $paging["page"] ?? 1;
        $length = $paging["length"] ?? 1000000000000;
        $skip = ($page - 1) * $length;
        $list = self::query($keyword, $startDate, $endDate, $sentiment, $newsSource);
        $list = $list->select("rawid AS id", "published_date AS publishedDate", "title", DB::raw("CONCAT(SUBSTRING(textcontent, 1, 426), '....') AS description"), "source", "sentiment");
        $count = $list->count("rawid");
        $list = $list->skip($skip);
        $list = $list->take($length);

        $endDate = $endDate ? $endDate . " 23:59" : $endDate;

        $queryTotal = DB::table("rawdata");
        $total = $queryTotal->count("rawid");

        $list = $list->get();

        $data["list"] = $list;
        $data["count"] = $count;
        $data["total"] = $total;

        return $data;
    }


    public static function show($id) {
        self::validate($id);
        $query = DB::table(self::$table);
        $query = $query->whereRawid($id);
        $result = $query
        ->select(
            "rawdata.*",
            DB::raw("sentpos * 100 AS sentimenPositif"),
            DB::raw("sentneg * 100 AS sentimenNegatif"),
            DB::raw("sentneutral * 100 AS sentimenNetral")
        )
        ->first();
        $result->sentimenPositif = number_format((float)$result->sentimenPositif, 2, '.', '');
        $result->sentimenNegatif = number_format((float)$result->sentimenNegatif, 2, '.', '');
        $result->sentimenNetral = number_format((float)$result->sentimenNetral, 2, '.', '');

        return $result;
    }

    public static function validate($id) {
        $query = DB::table(self::$table);
        $query = $query->whereRawid($id)->first();
        if(!$query)
            throw new \Exception("Data tidak ditemukan");
    }
}
