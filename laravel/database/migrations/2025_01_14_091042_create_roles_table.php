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
        Schema::create('roles', function (Blueprint $table) {
            $table->tinyIncrements('id'); // Khóa chính
            $table->string('name', 255)->unique(); // Tên vai trò (admin, user, etc.) với giới hạn 255 ký tự
            $table->string('description', 255)->nullable(); // Mô tả vai trò với giới hạn 255 ký tự
            $table->softDeletes(); // Thêm cột deleted_at (mặc định NULL)
            $table->timestamps(); // Thêm cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles'); // Xóa bảng khi rollback
    }
};
