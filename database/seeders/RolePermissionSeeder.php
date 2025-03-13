<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        DB::disableQueryLog(); // Giảm tải bộ nhớ khi chèn dữ liệu lớn

        // Vô hiệu hóa ràng buộc khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Xóa dữ liệu cũ trước khi insert
        DB::table('role_permissions')->truncate();

        // Lấy danh sách các permission có parent_id khác null
        $permissions = DB::table('permissions')->whereNotNull('parent_id')->pluck('id');

        $roleId = 1; // ID của role

        // Chuẩn bị dữ liệu để insert
        $data = [];
        foreach ($permissions as $permissionId) {
            $data[] = [
                'role_id' => $roleId,
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Chèn dữ liệu vào bảng role_permissions
        DB::table('role_permissions')->insert($data);

        // Bật lại ràng buộc khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
