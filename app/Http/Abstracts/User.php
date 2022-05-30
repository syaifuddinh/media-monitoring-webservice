<?php

namespace App\Http\Abstracts;

use DB;
use Illuminate\Support\Facades\Hash;

class User 
{
    public static $table = "users";

    public function query($keyword) {
        $list = DB::table(self::$table);
        $list = $list->orderBy("created_at", "DESC");
        $list = $keyword ? $list->where(function($query) use ($keyword) {
            $query->where("name", "LIKE", "%$keyword%");
            $query->orWhere("username", "LIKE", "%$keyword%");
        }) : $list;

        return $list;
    }

    public static function index($keyword, $paging = [])
    {
        
        $page = $paging["page"] ?? 1;
        $length = $paging["length"] ?? 10;
        $skip = ($page - 1) * $length;
        $list = self::query($keyword);
        $list = $list->select("id", "name", "username", "user_role AS userRole");
        $list = $list->skip($skip);
        $list = $list->take($length);
        $count = count($list->get());

        $queryTotal = DB::table(self::$table);
        $total = $queryTotal->count("id");

        $list = $list->get();

        $data["list"] = $list;
        $data["count"] = $count;
        $data["total"] = $total;

        return $data;
    }

    public static function validateUsername($username) {
        $query = DB::table(self::$table);
        $query = $query->whereUsername($username)->first();
        if($query)
            throw new \Exception("Username sudah digunakan oleh pengguna lain");
    }

    public static function store($name, $username, $password, $confirmPassword, $userRole) {
        $query = DB::table(self::$table);
        if(!$name)
            throw new \Exception("Nama wajib diisi");
        if(!$username)
            throw new \Exception("Username wajib diisi");
        if(!$userRole)
            throw new \Exception("Hak akses wajib diisi");
        if(!$password)
            throw new \Exception("Password wajib diisi");
        if(!$confirmPassword)
            throw new \Exception("Password konfirmasi wajib diisi");
        if($confirmPassword !== $password)
            throw new \Exception("Password dan password konfirmasi harus sama");

        self::validateUsername($username);
        $password = Hash::make($password);
        $query->insert([
            "name" => $name,
            "username" => $username,
            "user_role" => $userRole,
            "password" => $password,
            "created_at" => date("Y-m-d H:i:s")
        ]);
    }

    public static function update($name, $username, $password, $confirmPassword, $userRole, $id) {

        self::validate($id);

        if(!$name)
            throw new \Exception("Nama wajib diisi");
        if(!$username)
            throw new \Exception("Username wajib diisi");
        if(!$userRole)
            throw new \Exception("Hak akses wajib diisi");

        $params = [
            "name" => $name,
            "username" => $username,
            "user_role" => $userRole,
            "updated_at" => date("Y-m-d H:i:s")
        ];

        if($password) {
            if(!$confirmPassword)
                throw new \Exception("Password konfirmasi wajib diisi");
            if($confirmPassword !== $password)
                throw new \Exception("Password dan password konfirmasi harus sama");
            $password = Hash::make($password);
            $params["password"] = $password;
        }
        
        $query = DB::table(self::$table);
        $query = $query->whereId($id);
        $query->update($params);
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
        $query = $query->select("id", "username", "name", "created_at", "user_role");
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
