<?php
// 支援環境變數（用於雲端部署）
$DB_HOST = $_ENV['DB_HOST'] ?? $_SERVER['DB_HOST'] ?? "127.0.0.1";
$DB_USER = $_ENV['DB_USER'] ?? $_SERVER['DB_USER'] ?? "web";
$DB_PASS = $_ENV['DB_PASS'] ?? $_SERVER['DB_PASS'] ?? "leroy1019";
$DB_NAME = $_ENV['DB_NAME'] ?? $_SERVER['DB_NAME'] ?? "company";
$DB_PORT = $_ENV['DB_PORT'] ?? $_SERVER['DB_PORT'] ?? 3306;

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
if ($mysqli->connect_errno) { 
    http_response_code(500); 
    die('DB連線失敗: '.$mysqli->connect_error); 
}
$mysqli->set_charset('utf8mb4');
