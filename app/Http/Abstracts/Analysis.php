<?php

namespace App\Http\Abstracts;

use DB;

class Analysis 
{
    public static $table = "analysis";

    public function query($keyword, $startDate, $endDate, $date = null) {
        $endDate = $endDate ? $endDate . " 23:59" : $endDate;
        $list = DB::table(self::$table);
        $list = $list->orderBy("date", "DESC");
        $list = $keyword ? $list->where(function($query) use ($keyword) {
            $query->where("description", "LIKE", "%$keyword%");
        }) : $list;
        $list = $startDate ? $list->where("date", ">=", $startDate) : $list;
        $list = $endDate ? $list->where("date", "<=", $endDate) : $list;
        $list = $date ? $list->where("date", "=", $date) : $list;

        return $list;
    }

    public static function index($keyword, $startDate, $endDate, $paging = [], $date = null)
    {
        
        $page = $paging["page"] ?? 1;
        $length = $paging["length"] ?? 1000000000000;
        $skip = ($page - 1) * $length;
        $list = self::query($keyword, $startDate, $endDate, $date);
        $list = $list->select("id", "date", "description");
        $count = $list->count("id");
        $list = $list->skip($skip);
        $list = $list->take($length);

        $endDate = $endDate ? $endDate . " 23:59" : $endDate;

        $queryTotal = DB::table(self::$table);
        $total = $queryTotal->count("id");

        $list = $list->get();

        $data["list"] = $list;
        $data["count"] = $count;
        $data["total"] = $total;

        return $data;
    }

    public static function store($date, $description) {
        $query = DB::table(self::$table);
        if(!$description)
            throw new \Exception("Analisa wajib diisi");
        if(!$date)
            throw new \Exception("Tanggal wajib diisi");
        $query->insert([
            "date" => $date,
            "created_at" => date("Y-m-d H:i:s"),
            "description" => $description
        ]);
    }

    public static function update($date, $description, $id) {

        self::validate($id);
        if(!$description)
            throw new \Exception("Analisa wajib diisi");
        $query = DB::table(self::$table);
        $query = $query->whereId($id);
        $query->update([
            "updated_at" => date("Y-m-d H:i:s"),
            "description" => $description
        ]);
    }

    public static function destroy($id) {
        self::validate($id);
        $query = DB::table(self::$table);
        $query = $query->whereId($id);
        $query->delete();
    }

    public static function show($id) {
        self::validate($id);
        $query = DB::table(self::$table);
        $query = $query->whereId($id);
        $result = $query->first();

        return $result;
    }

    public static function validate($id) {
        $query = DB::table(self::$table);
        $query = $query->whereId($id)->first();
        if(!$query)
            throw new \Exception("Data tidak ditemukan");
    }
}
