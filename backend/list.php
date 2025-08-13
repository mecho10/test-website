<?php
session_start();

// 處理登出請求
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// 簡單的密碼保護
$admin_password = 'admin123'; // 在實際環境中應該使用更安全的密碼和加密方式

// 檢查是否已登入
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // 處理登入請求
    if (isset($_POST['password'])) {
        if ($_POST['password'] === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $error_message = '密碼錯誤！';
        }
    }
    
    // 顯示登入表單
    ?>
    <!DOCTYPE html>
    <html lang="zh-Hant">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>管理員登入</title>
        <style>
            body { 
                font-family: system-ui, -apple-system, 'Segoe UI', sans-serif; 
                margin: 0; padding: 0; 
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh; 
                display: flex; 
                align-items: center; 
                justify-content: center;
            }
            .login-container { 
                background: white; 
                padding: 40px; 
                border-radius: 12px; 
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                width: 100%; 
                max-width: 400px;
            }
            .login-container h1 { 
                text-align: center; 
                margin-bottom: 30px; 
                color: #333; 
            }
            .form-group { 
                margin-bottom: 20px; 
            }
            label { 
                display: block; 
                margin-bottom: 8px; 
                color: #555; 
                font-weight: 500;
            }
            input[type="password"] { 
                width: 100%; 
                padding: 12px; 
                border: 2px solid #e1e5e9; 
                border-radius: 8px; 
                font-size: 16px;
                transition: border-color 0.3s;
                box-sizing: border-box;
            }
            input[type="password"]:focus { 
                outline: none; 
                border-color: #667eea; 
            }
            button { 
                width: 100%; 
                padding: 12px; 
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                color: white; 
                border: none; 
                border-radius: 8px; 
                font-size: 16px; 
                cursor: pointer;
                transition: transform 0.2s;
            }
            button:hover { 
                transform: translateY(-2px); 
            }
            .error { 
                color: #e74c3c; 
                text-align: center; 
                margin-top: 15px; 
            }
            .back-link { 
                text-align: center; 
                margin-top: 20px; 
            }
            .back-link a { 
                color: #667eea; 
                text-decoration: none; 
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h1>🔐 管理員登入</h1>
            <form method="POST">
                <div class="form-group">
                    <label for="password">請輸入管理密碼：</label>
                    <input type="password" id="password" name="password" required autofocus>
                </div>
                <button type="submit">登入</button>
                <?php if (isset($error_message)): ?>
                    <div class="error"><?= htmlspecialchars($error_message) ?></div>
                <?php endif; ?>
            </form>
            <div class="back-link">
                <a href="../index.html">← 回首頁</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

require __DIR__ . '/db.php';
$res = $mysqli->query("SELECT id, name, email, message, created_at FROM messages ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>留言列表</title>
  <style>
    body { font-family: system-ui, -apple-system, 'Segoe UI', sans-serif; margin: 24px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 10px; vertical-align: top; }
    th { background: #f5f5f5; text-align: left; }
    pre { white-space: pre-wrap; word-wrap: break-word; }
  </style>
</head>
<body>
  <h1>留言列表</h1>
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <a href="../index.html">← 回首頁</a>
    <a href="?logout=1" style="color: #e74c3c; text-decoration: none;">登出 🚪</a>
  </div>
  <table>
    <tr>
      <th>ID</th>
      <th>姓名</th>
      <th>Email</th>
      <th>留言</th>
      <th>時間</th>
    </tr>
    <?php while ($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><pre><?= htmlspecialchars($row['message']) ?></pre></td>
        <td><?= htmlspecialchars($row['created_at']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>