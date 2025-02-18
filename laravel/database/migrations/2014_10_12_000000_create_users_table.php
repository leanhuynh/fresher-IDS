<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 255); // Giới hạn 255 ký tự
            $table->string('last_name', 255); // Giới hạn 255 ký tự
            $table->string('user_name', 50)->unique(); // Giới hạn 255 ký tự
            $table->string('email', 255)->unique(); // Giới hạn 255 ký tự
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255); // Giới hạn 255 ký tự
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->softDeletes(); // Thêm trường deleted_at (mặc định NULL)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
