<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->String('user_Id');
            $table->String('name');
            $table->String('mobile');
            $table->String('password');
            $table->String('nid');
            $table->String('address');
            $table->String('thana');
            $table->String('zilla');
            $table->String('district');
            $table->String('division');
            $table->String('image');
            $table->String('subscription');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_users');
    }
};
