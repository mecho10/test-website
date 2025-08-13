// 等待頁面完全載入後再執行動畫
document.addEventListener('DOMContentLoaded', function() {
  console.log('DOM載入完成，檢查GSAP...');
  
  // 檢查 GSAP 是否載入
  if (typeof gsap === 'undefined') {
    console.error('GSAP未載入！');
    return;
  }
  
  console.log('GSAP已載入，開始執行動畫...');
  
  // 註冊 ScrollTrigger 插件
  if (typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);
    console.log('ScrollTrigger已註冊');
  } else {
    console.error('ScrollTrigger未載入！');
  }

  // 進場動畫：Header、Hero
  console.log('執行進場動畫...');
  gsap.from('header', { y: -40, opacity: 0, duration: 0.6, ease: 'power2.out' });
  gsap.from('#hero h1', { y: 20, opacity: 0, duration: 0.8, delay: 0.2 });
  gsap.from('#hero p', { y: 20, opacity: 0, duration: 0.8, delay: 0.35 });
  gsap.from('#hero a', { y: 20, opacity: 0, duration: 0.8, delay: 0.5 });

  // 滾動動畫：服務卡片
  console.log('設定滾動動畫...');
  const serviceCards = document.querySelectorAll('.service-card');
  console.log('找到', serviceCards.length, '個服務卡片');
  
  serviceCards.forEach((card, i) => {
    gsap.from(card, {
      scrollTrigger: { 
        trigger: card, 
        start: 'top 85%',
        onEnter: () => console.log(`服務卡片 ${i+1} 動畫觸發`)
      },
      y: 20,
      opacity: 0,
      duration: 0.6,
      delay: i * 0.1
    });
  });
  
  console.log('所有動畫設定完成！');
});

// AJAX 表單提交功能
const form = document.getElementById('contactForm');
if (form) {
  const submitBtn = form.querySelector('button[type="submit"]');
  const thanks = document.getElementById('thanks');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // 防止重複提交
    submitBtn.disabled = true;
    submitBtn.textContent = '送出中...';
    
    try {
      const fd = new FormData(form);
      const resp = await fetch('backend/create.php', {
        method: 'POST',
        headers: { 
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: fd
      });
      
      const result = await resp.json();
      
      if (resp.ok && result.ok === true) {
        // 清空表單
        form.reset();
        
        // 顯示感謝訊息並添加 GSAP 動畫
        if (thanks) {
          thanks.classList.remove('hidden');
          
          // GSAP 感謝訊息動畫
          gsap.fromTo(thanks, 
            { 
              opacity: 0, 
              y: 20, 
              scale: 0.8 
            },
            { 
              opacity: 1, 
              y: 0, 
              scale: 1, 
              duration: 0.6, 
              ease: 'back.out(1.7)' 
            }
          );
          
          // 3秒後淡出感謝訊息
          gsap.to(thanks, {
            opacity: 0,
            y: -10,
            duration: 0.4,
            delay: 3,
            onComplete: () => {
              thanks.classList.add('hidden');
            }
          });
        }
      } else {
        // 顯示具體的錯誤訊息
        const errorMsg = result.msg || '送出失敗，請稍後再試';
        alert(errorMsg);
      }
    } catch (err) {
      console.error('表單提交錯誤:', err);
      // 如果是 JSON 解析錯誤，可能是後端返回了非 JSON 格式
      if (err instanceof SyntaxError) {
        alert('伺服器回應格式錯誤，請聯繫管理員');
      } else {
        alert('送出失敗，請檢查網路連線');
      }
    } finally {
      // 恢復按鈕狀態
      submitBtn.disabled = false;
      submitBtn.textContent = '送出';
    }
  });
}

