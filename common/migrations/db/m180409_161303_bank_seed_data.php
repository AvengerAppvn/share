<?php

use yii\db\Migration;

/**
 * Class m180409_161303_bank_seed_data
 */
class m180409_161303_bank_seed_data extends Migration
{
    public function safeUp()
    {
        $this->insert('{{%bank}}', ['id' => 1, 'name' => 'Ngân hàng Á Châu (ACB)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 2, 'name' => 'Ngân hàng Tiên Phong (TPBank)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 3, 'name' => 'Ngân hàng Bắc Á (BacABank)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 4, 'name' => 'Ngân hàng Kỹ Thương Việt Nam (Techcombank)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 5, 'name' => 'Ngân hàng Phát triển nhà Thành phố Hồ Chí Minh (HDBank)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 6, 'name' => 'Ngân hàng Việt Nam Thương Tín (VietBank)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 7, 'name' => 'Ngân hàng Ngoại thương Việt Nam (VietcomBank)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 8, 'name' => 'Ngân hàng Công Thương Việt Nam (VietinBank)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 9, 'name' => 'Ngân hàng Nông Nghiệp & Phát Triển Nông Thôn VN( AGribank)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 10, 'name' => 'Ngân hàng TMCP Sài Gòn THường Tín (Sacombank)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 11, 'name' => 'Ngân hàng Phát Triển Nhà DBSCL (MHB)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 12, 'name' => 'Ngân hàng Phát Triển Việt Nam (VDB)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 13, 'name' => 'Ngân hàng TMCP Bản Việt', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 14, 'name' => 'Ngân hàng Phương Đông (OCB)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 15, 'name' => 'Ngân hàng Quốc Tế (VIB)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 16, 'name' => 'Ngân hàng TMCP Quân đội (MBB)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 17, 'name' => 'Ngân hàng Việt Á (VietABank)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 18, 'name' => 'Ngân hàng Bảo Việt (BaoVietBank)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 19, 'name' => 'Ngân hàng Đầu tư và Phát triển Việt Nam (BIDV)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 20, 'name' => 'Ngân hàng Xây dựng (CB)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 21, 'name' => 'Ngân hàng Đại Dương (Oceanbank)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 22, 'name' => 'Ngân hàng Dầu Khí Toàn Cầu (GPBank)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 23, 'name' => 'Ngân hàng Tiên Phong (GPBank)', 'status' => 1]);
        $this->insert('{{%bank}}', ['id' => 24, 'name' => 'Ngân hàng Đông Á (DAF)', 'status' => 1]);
    }
    public function down()
    {
        $this->truncateTable('bank');
    }

}
