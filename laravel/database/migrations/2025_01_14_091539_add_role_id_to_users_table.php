<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Common\Constant;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('role_id')->default(Constant::DEFAULT_USER_ROLE)->after('id'); // Thêm cột role_id
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict'); // Thiết lập khóa ngoại (nếu cần)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']); // Xóa khóa ngoại (nếu cần)
            $table->dropColumn('role_id');   // Xóa cột
        });
    }
};
