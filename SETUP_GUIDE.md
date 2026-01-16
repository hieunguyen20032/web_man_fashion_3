# Hướng Dẫn Cài Đặt và Chạy Dự Án Web Man Fashion

Dự án này là một ứng dụng web PHP thuần (Procedural). Để chạy dự án trên Windows, bạn nên sử dụng **XAMPP** (hoặc WAMP/Laragon).

## 1. Cài đặt môi trường

1.  Tải và cài đặt **XAMPP** từ [apachefriends.org](https://www.apachefriends.org/index.html).
2.  Khởi động **XAMPP Control Panel**.
3.  Start hai module **Apache** và **MySQL**.

## 2. Cấu hình Cơ sở dữ liệu (Database)

Ứng dụng yêu cầu một cơ sở dữ liệu tên là `web_man_fashion`.

1.  Mở trình duyệt, truy cập `http://localhost/phpmyadmin`.
2.  Nhấp vào **New** (Mới) ở thanh bên trái.
3.  Tên cơ sở dữ liệu: `web_man_fashion`.
4.  Bảng mã (Collation): Chọn `utf8_general_ci` hoặc `utf8_unicode_ci`.
5.  Nhấn **Create** (Tạo).

**Lưu ý quan trọng**:
Hiện tại trong thư mục dự án chưa thấy file `.sql` để import dữ liệu (cấu trúc bảng và dữ liệu mẫu).

- Nếu bạn đã có file `.sql` (ví dụ từ người cũ bàn giao), hãy chọn database `web_man_fashion` vừa tạo -> chọn thẻ **Import** -> Click **Choose File** để tải file `.sql` lên và nhấn **Go**.
- Nếu không có file database, ứng dụng sẽ báo lỗi khi truy cập vì không tìm thấy bảng dữ liệu.

## 3. Cài đặt mã nguồn

1.  Di chuyển toàn bộ thư mục `web_man_fashion_3` vào thư mục `htdocs` của XAMPP.
    - Đường dẫn mặc định: `C:\xampp\htdocs\web_man_fashion_3`
2.  Kiểm tra file cấu hình kết nối tại `inc/myconnect.php`. Đảm bảo thông tin khớp với cài đặt XAMPP của bạn (mặc định XAMPP user là `root`, password rỗng):
    ```php
    $dbc = mysqli_connect('localhost', 'root', '', 'web_man_fashion');
    ```

## 4. Chạy ứng dụng

1.  Mở trình duyệt web (Chrome, Firefox, Cốc Cốc...).
2.  Truy cập địa chỉ: `http://localhost/web_man_fashion_3`.

## 5. Các lỗi thường gặp

- **Lỗi "Kết nối không thành công"**:
  - Kiểm tra xem MySQL trong XAMPP đã Start chưa.
  - Kiểm tra tên database trong phpMyAdmin có đúng là `web_man_fashion` không.
  - Kiểm tra user/pass trong `inc/myconnect.php`.
- **Lỗi thiếu bảng (Table doesn't exist)**:
  - Bạn chưa import cơ sở dữ liệu. Cần tìm file `.sql` để import.
- **Lỗi hiển thị tiếng Việt**:
  - Đảm bảo database chọn collation là `utf8` và file code được lưu với encoding UTF-8.

## 6. Tài khoản Admin (Dự kiến)

Thường trang quản trị sẽ nằm tại: `http://localhost/web_man_fashion_3/admin`.
Bạn cần kiểm tra trong database bảng `tb_user` (hoặc tương tự) để biết tài khoản đăng nhập.
