<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $now = now();

        $cities = [
            ['name' => 'Hà Nội', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hồ Chí Minh', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hải Phòng', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Đà Nẵng', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cần Thơ', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'An Giang', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bà Rịa - Vũng Tàu', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bắc Giang', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bắc Kạn', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bạc Liêu', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bắc Ninh', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bến Tre', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bình Định', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bình Dương', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bình Phước', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bình Thuận', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cà Mau', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cao Bằng', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Đắk Lắk', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Đắk Nông', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Điện Biên', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Đồng Nai', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Đồng Tháp', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gia Lai', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hà Giang', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hà Nam', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hà Tĩnh', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hải Dương', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hậu Giang', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hòa Bình', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hưng Yên', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Khánh Hòa', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kiên Giang', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kon Tum', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lai Châu', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lâm Đồng', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lạng Sơn', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lào Cai', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Long An', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nam Định', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nghệ An', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ninh Bình', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ninh Thuận', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Phú Thọ', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Phú Yên', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Quảng Bình', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Quảng Nam', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Quảng Ngãi', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Quảng Ninh', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Quảng Trị', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sóc Trăng', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sơn La', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tây Ninh', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Thái Bình', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Thái Nguyên', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Thanh Hóa', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Thừa Thiên Huế', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tiền Giang', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Trà Vinh', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tuyên Quang', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Vĩnh Long', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Vĩnh Phúc', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Yên Bái', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('cities')->insert($cities);
    }

    public function down(): void
    {
        DB::table('cities')->delete();
    }
};
