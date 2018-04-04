<?php

use yii\db\Migration;

/**
 * Class m180404_025127_province_seed_data
 */
class m180404_025127_province_seed_data extends Migration
{
    public function safeUp()
    {
        $this->insert('{{%criteria_province}}', ['id' => 1, 'name' => 'An Giang', 'slug' => 'An Giang', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 2, 'name' => 'Bà Rịa - Vũng Tàu', 'slug' => 'Ba Ria - Vung Tau', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 3, 'name' => 'Bình Dương', 'slug' => 'Binh Duong', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 4, 'name' => 'Bình Phước', 'slug' => 'Binh Phuoc', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 5, 'name' => 'Bình Thuận', 'slug' => 'Binh Thuan', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 6, 'name' => 'Bình Ðịnh', 'slug' => 'Binh Dinh', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 7, 'name' => 'Bạc Liêu', 'slug' => 'Bac lieu', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 8, 'name' => 'Bắc Giang', 'slug' => 'Bac Giang', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 9, 'name' => 'Bắc Kạn', 'slug' => 'Bac Kan', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 10, 'name' => 'Bắc Ninh', 'slug' => 'Bac Ninh', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 11, 'name' => 'Bến Tre', 'slug' => 'Ben tre', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 12, 'name' => 'Cao Bằng', 'slug' => 'Cao Bang', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 13, 'name' => 'Cà Mau', 'slug' => 'Ca Mau', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 14, 'name' => 'Cần Thơ', 'slug' => 'Can Tho', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 15, 'name' => 'Đà Nẵng', 'slug' => 'Da Nang', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 16, 'name' => 'Đắk Lắk', 'slug' => 'Dak Lak', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 17, 'name' => 'Đắk Nông', 'slug' => 'Dak Nong', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 18, 'name' => 'Điện Biên', 'slug' => 'Dien Bien', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 19, 'name' => 'Đồng Nai', 'slug' => 'Dong Nai', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 20, 'name' => 'Đồng Tháp', 'slug' => 'Dong thap', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 21, 'name' => 'Gia Lai', 'slug' => 'Gia Lai', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 22, 'name' => 'Hà Giang', 'slug' => 'Ha Giang', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 23, 'name' => 'Hà Nam', 'slug' => 'Ha Nam', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 24, 'name' => 'Hà Nội', 'slug' => 'Ha Noi', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 25, 'name' => 'Hà Tĩnh', 'slug' => 'Ha Tinh', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 26, 'name' => 'Hải Dương', 'slug' => 'Hai Duong', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 27, 'name' => 'Hải Phòng', 'slug' => 'Hai Phong', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 28, 'name' => 'Hậu Giang', 'slug' => 'Hau Giang', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 29, 'name' => 'Hòa Bình', 'slug' => 'Hoa Binh', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 30, 'name' => 'Hưng Yên', 'slug' => 'Hung Yen', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 31, 'name' => 'Khánh Hòa', 'slug' => 'Khanh Hoa', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 32, 'name' => 'Kiên Giang', 'slug' => 'Kien Giang', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 33, 'name' => 'Kon Tum', 'slug' => 'Kon Tum', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 34, 'name' => 'Lai Châu', 'slug' => 'Lai Chau', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 35, 'name' => 'Lạng Sơn', 'slug' => 'Lang Son', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 36, 'name' => 'Lào Cai', 'slug' => 'Lao Cai', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 37, 'name' => 'Lâm Đồng', 'slug' => 'Lam Dong', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 38, 'name' => 'Long An', 'slug' => 'Long An', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 39, 'name' => 'Nam Định', 'slug' => 'Nam Dinh', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 40, 'name' => 'Nghệ An', 'slug' => 'Nghe An', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 41, 'name' => 'Ninh Bình', 'slug' => 'Ninh Binh', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 42, 'name' => 'Ninh Thuận', 'slug' => 'Ninh Thuan', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 43, 'name' => 'Phú Thọ', 'slug' => 'Phu Tho', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 44, 'name' => 'Phú Yên', 'slug' => 'Phu Yen', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 45, 'name' => 'Quảng Bình', 'slug' => 'Quang Binh', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 46, 'name' => 'Quảng Nam', 'slug' => 'Quang Nam', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 47, 'name' => 'Quảng Ngãi', 'slug' => 'Quang Ngai', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 48, 'name' => 'Quảng Ninh', 'slug' => 'Quang Ninh', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 49, 'name' => 'Quảng Trị', 'slug' => 'Quang Tri', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 50, 'name' => 'Sóc Trăng', 'slug' => 'Soc Trang', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 51, 'name' => 'Sơn La', 'slug' => 'Son La', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 52, 'name' => 'Tây Ninh', 'slug' => 'Tay Ninh', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 53, 'name' => 'Thái Bình', 'slug' => 'Thai Binh', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 54, 'name' => 'Thái Nguyên', 'slug' => 'Thai Nguyen', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 55, 'name' => 'Thanh Hóa', 'slug' => 'Thanh Hoa', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 56, 'name' => 'Thừa Thiên Huế', 'slug' => 'Thua Thien Hue', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 57, 'name' => 'Tiền Giang', 'slug' => 'Tien Giang', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 58, 'name' => 'TP.Hồ Chí Minh', 'slug' => 'TP.Ho Chi Minh', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 59, 'name' => 'Trà Vinh', 'slug' => 'Tra Vinh', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 60, 'name' => 'Tuyên Quang', 'slug' => 'Tuyen Quang', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 61, 'name' => 'Vĩnh Long', 'slug' => 'Vinh Long', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 62, 'name' => 'Vĩnh Phúc', 'slug' => 'Vinh Phuc', 'status' => 1]);
        $this->insert('{{%criteria_province}}', ['id' => 63, 'name' => 'Yên Bái', 'slug' => 'Yen Bai', 'status' => 1]);
    }
}
