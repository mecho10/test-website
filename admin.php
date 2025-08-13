<?php
session_start();

// 簡單的登入驗證
$ADMIN_USER = 'admin';
$ADMIN_PASS = 'boss123'; // 建議改成更安全的密碼

// 處理登入
if ($_POST['action'] ?? '' === 'login') {
    if ($_POST['username'] === $ADMIN_USER && $_POST['password'] === $ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = '帳號或密碼錯誤';
    }
}

// 處理登出
if ($_GET['action'] ?? '' === 'logout') {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// 檢查是否已登入
$isLoggedIn = $_SESSION['admin_logged_in'] ?? false;
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理後台</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<?php if (!$isLoggedIn): ?>
    <!-- 登入表單 -->
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h1 class="text-2xl font-bold mb-6 text-center">管理後台登入</h1>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="hidden" name="action" value="login">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">帳號</label>
                    <input type="text" name="username" required 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">密碼</label>
                    <input type="password" name="password" required 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                    登入
                </button>
            </form>
        </div>
    </div>

<?php else: ?>
    <!-- 管理介面 -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">管理後台</h1>
            <a href="?action=logout" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                登出
            </a>
        </div>

        <!-- 統計卡片 -->
        <?php
        require_once 'backend/db.php';
        
        // 獲取統計數據
        $totalMessages = $mysqli->query("SELECT COUNT(*) as count FROM messages")->fetch_assoc()['count'];
        $todayMessages = $mysqli->query("SELECT COUNT(*) as count FROM messages WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['count'];
        $weekMessages = $mysqli->query("SELECT COUNT(*) as count FROM messages WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetch_assoc()['count'];
        ?>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-600">總留言數</h3>
                <p class="text-3xl font-bold text-blue-600"><?= $totalMessages ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-600">今日留言</h3>
                <p class="text-3xl font-bold text-green-600"><?= $todayMessages ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-600">本週留言</h3>
                <p class="text-3xl font-bold text-purple-600"><?= $weekMessages ?></p>
            </div>
        </div>

        <!-- 留言列表 -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold">最新留言</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">姓名</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">留言</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">時間</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        $result = $mysqli->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 50");
                        while ($row = $result->fetch_assoc()):
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $row['id'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($row['name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs truncate"><?= htmlspecialchars($row['message']) ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('Y-m-d H:i', strtotime($row['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- 快速動作 -->
        <div class="mt-8 flex gap-4">
            <a href="backend/list.php" target="_blank" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                📊 查看 JSON 數據
            </a>
            <button onclick="exportData()" 
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                📁 匯出 CSV
            </button>
        </div>
    </div>

    <script>
    function exportData() {
        // 簡單的 CSV 匯出
        window.open('backend/export.php', '_blank');
    }
    </script>
<?php endif; ?>

</body>
</html>
