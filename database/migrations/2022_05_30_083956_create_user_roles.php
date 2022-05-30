<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string("slug")->unique();
            $table->string("name")->nullable(false);
        });
        DB::table("user_roles")
        ->insert([
            [
                "slug" => "admin",
                "name" => "Admin"
            ],
            [
                "slug" => "visitor",
                "name" => "Visitor"
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}
