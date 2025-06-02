<?php

// تمكين عرض الأخطاء
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// زيادة وقت التنفيذ وحد الذاكرة
ini_set('max_execution_time', '300');
ini_set('memory_limit', '512M');
set_time_limit(300);

ini_set('max_execution_time', 300); // زيادة وقت التنفيذ إلى 5 دقائق

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
