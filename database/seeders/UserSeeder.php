<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                "username" => "admin",
                "password" => "12345"
            ],
            [
                "username" => "didin",
                "password" => "12345"
            ]
        ];
        foreach($users as $user) {
            $password = Hash::make($user["password"]);
            $username = $user["username"];
            DB::table("users")->insert([
                "name" => $username,
                "username" => $username,
                "password" => $password,
                "created_at" => date("Y-m-d H:i:s")
            ]);
        }
    }
}
