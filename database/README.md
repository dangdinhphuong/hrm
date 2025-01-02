# Commands Migration
### Commands Migration dùng để tạo các table trong database


#### Chạy các table cho crm :

     php artisan migrate

#### Chạy các table cho hrm :

     php artisan migrate --database=mysql --path=database/migrations/hrm 

# Chú ý:
             --database  -> Là tham số trỏ tới database đã config
             --path      -> Đường dẫn tới thư mục cần chạy
