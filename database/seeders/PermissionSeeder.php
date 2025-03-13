<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\System\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        DB::disableQueryLog(); // Giảm tải bộ nhớ khi chèn dữ liệu lớn

        // Vô hiệu hóa ràng buộc khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Xóa toàn bộ dữ liệu và reset ID về 1
        Permission::truncate();

        // Bật lại ràng buộc khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $permissions = config('permissions.data');
        $dataToInsert = [];

        foreach ($permissions as $category => $permissionList) {
            // Tạo quyền cha (category)
            $parent = Permission::create([
                'name' => $category,
                'code' => $category,
                'is_active' => 1, // Mặc định kích hoạt
            ]);

            //Thêm quyền con vào danh sách để chèn một lần
            foreach ($permissionList as $permission) {
                $dataToInsert[] = [
                    'code' => $permission['code'],
                    'name' => $permission['name'],
                    'description' => $permission['description'],
                    'parent_id' => $parent->id,
                    'is_active' => $permission['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Chèn danh sách quyền con chỉ với một truy vấn
        if (!empty($dataToInsert)) {
            Permission::insert($dataToInsert);
        }
    }
}
