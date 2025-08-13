<?php
$DB_HOST = "127.0.0.1";   // 一定用 127.0.0.1（TCP）
$DB_USER = "web";
$DB_PASS = "leroy1019";
$DB_NAME = "company";

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) { http_response_code(500); die('DB連線失敗: '.$mysqli->connect_error); }
$mysqli->set_charset('utf8mb4');
