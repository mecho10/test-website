# 現代商業網站

一個具有動畫效果和聯絡表單功能的現代化商業網站。

## ✨ 特色功能

- 🎨 **現代化設計**：使用 Tailwind CSS 打造的響應式設計
- 🎬 **流暢動畫**：GSAP 驅動的進場和滾動動畫效果
- 📝 **聯絡表單**：Ajax 表單提交，無需頁面刷新
- 📱 **響應式**：完美適配桌面、平板和手機
- 🚀 **高效能**：優化的載入速度和用戶體驗

## 🛠 技術棧

### 前端
- **HTML5**：語義化標記
- **Tailwind CSS**：實用優先的 CSS 框架
- **GSAP**：專業級動畫庫
- **Vanilla JavaScript**：原生 JS，無額外依賴

### 後端
- **PHP**：處理表單提交和資料庫操作
- **MySQL**：資料存儲

## 📁 專案結構

```
project/
├── index.html          # 主頁面
├── assets/
│   ├── app.js         # 主要 JavaScript 邏輯
│   ├── style.css      # 編譯後的樣式
│   └── style.scss     # SCSS 源文件
├── backend/
│   ├── app.js         # 後端主文件
│   ├── create.php     # 創建聯絡紀錄
│   ├── db.php         # 資料庫連接
│   └── list.php       # 獲取聯絡紀錄
└── schema.sql         # 資料庫結構

```

## 🚀 快速開始

### 1. 克隆專案
```bash
git clone [你的倉庫URL]
cd project
```

### 2. 本地開發
```bash
# 使用 PHP 內建服務器
php -S localhost:8000

# 或使用 Python
python -m http.server 8000

# 或使用 Node.js
npx serve .
```

### 3. 資料庫設置（可選）
如果需要聯絡表單功能：
```bash
# 導入資料庫結構
mysql -u [用戶名] -p [資料庫名] < schema.sql

# 更新 backend/db.php 中的資料庫連接信息
```

## 🌐 部署選項

### GitHub Pages
1. 推送到 GitHub
2. 在倉庫設置中啟用 GitHub Pages
3. 選擇主分支作為源

### Netlify
1. 連接 GitHub 倉庫
2. 構建設置：無需特殊配置
3. 自動部署

### Vercel
1. 導入 GitHub 倉庫
2. 自動檢測靜態網站
3. 一鍵部署

## 🎨 自定義

### 修改顏色主題
編輯 `assets/style.scss` 中的顏色變數：
```scss
:root {
  --primary-color: #3b82f6;
  --secondary-color: #1e40af;
}
```

### 調整動畫效果
修改 `assets/app.js` 中的 GSAP 動畫參數：
```javascript
gsap.from('header', { 
  y: -40, 
  opacity: 0, 
  duration: 0.6,  // 調整持續時間
  ease: 'power2.out' 
});
```

## 📱 瀏覽器支援

- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+

## 📄 授權

MIT License - 可自由使用和修改

## 🤝 貢獻

歡迎提交 Issue 和 Pull Request！

---

⭐ 如果這個專案對你有幫助，請給個星星支持！
