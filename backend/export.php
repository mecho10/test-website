<?php
session_start();

// 檢查是否已登入
if (!($_SESSION['admin_logged_in'] ?? false)) {
    http_response_code(401);
    die('未授權');
}

require_once 'db.php';

// 設定 CSV 檔案標頭
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="messages_' . date('Y-m-d') . '.csv"');

// 輸出 BOM 以支援中文
echo "\xEF\xBB\xBF";

// 開啟輸出緩衝
$output = fopen('php://output', 'w');

// CSV 標題列
fputcsv($output, ['ID', '姓名', 'Email', '留言內容', '提交時間']);

// 查詢所有數據
$result = $mysqli->query("SELECT * FROM messages ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['name'],
        $row['email'],
        $row['message'],
        $row['created_at']
    ]);
}

fclose($output);
?>
