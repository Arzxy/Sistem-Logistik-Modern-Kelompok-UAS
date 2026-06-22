@echo off

start cmd /k "cd /d C:\xampp\htdocs\LARAVEL_10_SelebretExpress - Pengguna && php artisan serve --port=8001"

start cmd /k "cd /d C:\xampp\htdocs\LARAVEL_10_SelebretExpress - Paket && php artisan serve --port=8002"

start cmd /k "cd /d C:\xampp\htdocs\LARAVEL_10_SelebretExpress - Tarif && php artisan serve --port=8003"

start cmd /k "cd /d C:\xampp\htdocs\LARAVEL_10_SelebretExpress - Armada && php artisan serve --port=8004"

start cmd /k "cd /d C:\xampp\htdocs\LARAVEL_10_SelebretExpress - Pelacakan && php artisan serve --port=8005"