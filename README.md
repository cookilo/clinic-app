# Hướng Dẫn Cài Đặt Dự Án Laravel

Chào mừng bạn đến với dự án Laravel của chúng tôi! Dưới đây là hướng dẫn từng bước để tải và cấu hình dự án trên máy tính cục bộ của bạn.

## Yêu Cầu

Trước khi bắt đầu, hãy đảm bảo rằng bạn đã cài đặt các phần mềm sau:

- [PHP](https://www.php.net/) (phiên bản 8.0 trở lên)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/) hoặc [MariaDB](https://mariadb.org/)
- [Node.js](https://nodejs.org/) (tuỳ chọn, nếu bạn cần chạy các lệnh npm)
- [Git](https://git-scm.com/) (tuỳ chọn, nếu bạn chưa cài đặt Git)

## 1. Sao Chép Repository
Đầu tiên, sao chép (clone) repository từ GitHub về máy tính của bạn bằng lệnh sau:
```bash
git clone https://github.com/cookilo/Clinic.git
```
## 2. Cài Đặt Các Gói PHP
Di chuyển vào thư mục dự án và cài đặt các gói PHP cần thiết bằng Composer:
```bash
cd /Clinic
composer install
```
## 3. Cấu Hình Môi Trường
Sao chép tệp .env.example thành .env:
```bash
cp .env.example .env
```
Mở tệp .env và cấu hình các thông số kết nối cơ sở dữ liệu và các thông số khác theo nhu cầu của bạn.
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```
## 4. Tạo Key Ứng Dụng
```bash
php artisan key:generate
```
## 5. Chạy Migration
```bash
php artisan migrate
```
## 6. Cài Đặt Các Gói NPM (Tuỳ Chọn)
```bash
npm install
```
## 7. Biên Dịch Tài Nguyên (Tuỳ Chọn)
   Nếu bạn đã cài đặt các gói NPM, hãy biên dịch các tài nguyên bằng lệnh:
```bash
npm run dev
```
   Hoặc nếu bạn muốn biên dịch tài nguyên cho môi trường production:
```bash
npm run prod
```
## 8. Chạy Dự Án
   Cuối cùng, bạn có thể chạy dự án Laravel trên máy chủ phát triển bằng lệnh:
```bash
php artisan serve
```
