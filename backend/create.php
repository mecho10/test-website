<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    http_response_code(405); 
    exit("Method Not Allowed"); 
}

// 檢查是否為 AJAX 請求
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// 根據請求類型設置適當的 Content-Type
if ($isAjax) {
    header('Content-Type: application/json; charset=utf-8');
} else {
    header('Content-Type: text/plain; charset=utf-8');
}

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$message = trim($_POST['message'] ?? '');

// 如果不是 AJAX 請求，顯示調試信息
if (!$isAjax) {
    echo "收到 POST:\n";
    var_dump($_POST);
}

// 驗證輸入
if ($name === '' || $email === '' || $message === '') { 
    http_response_code(400); 
    if ($isAjax) {
        echo json_encode(['ok' => false, 'msg' => '缺少必填欄位']);
    } else {
        echo "\n缺少欄位";
    }
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
    http_response_code(400); 
    if ($isAjax) {
        echo json_encode(['ok' => false, 'msg' => 'Email 格式不正確']);
    } else {
        echo "\nEmail 格式不正確";
    }
    exit;
}

// 執行資料庫插入
$stmt = $mysqli->prepare("INSERT INTO messages(name,email,message) VALUES (?,?,?)");
if(!$stmt){ 
    http_response_code(500); 
    if ($isAjax) {
        echo json_encode(['ok' => false, 'msg' => 'prepare 失敗: '.$mysqli->error]);
    } else {
        echo "\nprepare 失敗: ".$mysqli->error;
    }
    exit;
}

$stmt->bind_param('sss', $name, $email, $message);

if($stmt->execute()){ 
    if ($isAjax) {
        echo json_encode(['ok' => true, 'msg' => '寫入成功', 'id' => $stmt->insert_id]);
    } else {
        echo "\nOK，寫入成功，id=".$stmt->insert_id;
    }
    exit;
}

http_response_code(400); 
if ($isAjax) {
    echo json_encode(['ok' => false, 'msg' => '寫入失敗: ('.$stmt->errno.') '.$stmt->error]);
} else {
    echo "\n寫入失敗: (".$stmt->errno.") ".$stmt->error;
}
