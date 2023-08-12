# 洛嬉遊戲 LSGames 後端專案

> [返回根目錄](https://github.com/samuikaze/my-work-2023)

這是洛嬉遊戲的後端專案，使用 Lumen Framework (PHP) 撰寫而成

## TODO

1. 後端功能依據功能拆分專案

## 說明

本專案起初為職訓局的專題作品，使用 HTML + PHP 與少量 jQuery 撰寫而成。

後因該專題作品拿去求職不順利，又花了一點時間自學 Laravel 框架，並把作品改以 Laravel + blade.php 以及少量 jQuery 撰寫而成，此時所有的資料大多數仍以 PHP 直接渲染為主，僅有購物車採用 ajax 方式呼叫 API。

而後在職場打滾三年多後，應徵的職位是後端，主要都是以 Laravel 這個 PHP 框架為基底去撰寫功能介面，因此這個專案本來也是採用 Laravel 專案撰寫。

後續學習 Kubernetes 相關知識後，決定將後端服務依據領域拆分，也因為目前還沒拆分完成，故這個專案即便採用 Lumen 框架撰寫，功能仍然是一大包...

## 事前準備

使用本專案前請先安裝以下軟體

- php 8.1 或以上
- composer 2.0 或以上
- MySQL 或 MariaDB
- Nginx 或 Apache

## 本機除錯

可以遵循以下步驟在本機進行除錯或檢視

> ⚠️請注意，`.env` 檔中的相關設定請依據需求作修改

1. `git clone` 將本專案 clone 到本機
2. 打開終端機，切換到本專案資料夾
3. 執行指令 `composer install && composer dump-autoload`
4. 啟動 `nginx` 或 `Apache` 伺服器

  > 也可使用 `php artisan serve` 啟動服務，但此方式在 CORS 預檢請求會得到 404 回應，目前仍未找出問題...
