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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->unsignedSmallInteger('city_id');
            $table->string('name_en', 255)->unique(); // Giới hạn 255 ký tự
            $table->string('name_jp', 255)->unique(); // Giới hạn 255 ký tự
            $table->string('telephone', 255); // Giới hạn 255 ký tự
            $table->string('fax', 255)->nullable(); // Giới hạn 255 ký tự
            $table->string('company_name', 255); // Giới hạn 255 ký tự
            $table->string('tax_code', 13)->nullable();
            $table->string('hotel_code', 6)->unique(); // Đúng 6 ký tự
            $table->string('email', 255); // Giới hạn 255 ký tự
            $table->string('address_1', 255); // Giới hạn 255 ký tự
            $table->string('address_2', 255)->nullable(); // Giới hạn 255 ký tự
            $table->softDeletes(); // Thêm cột deleted_at (mặc định NULL)
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('cities')->onDelete('restrict');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotels');
    }
};
