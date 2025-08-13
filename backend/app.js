// 啟用 GSAP 與 ScrollTrigger
if (window.gsap) {
    gsap.registerPlugin(ScrollTrigger);
  
    // 進場動畫：Header、Hero
    gsap.from('header', { y: -40, opacity: 0, duration: 0.6, ease: 'power2.out' });
    gsap.from('#hero h1', { y: 20, opacity: 0, duration: 0.8, delay: 0.2 });
    gsap.from('#hero p', { y: 20, opacity: 0, duration: 0.8, delay: 0.35 });
    gsap.from('#hero a', { y: 20, opacity: 0, duration: 0.8, delay: 0.5 });
  
    // 滾動動畫：服務卡片
    gsap.utils.toArray('.service-card').forEach((card, i) => {
      gsap.from(card, {
        scrollTrigger: { trigger: card, start: 'top 85%' },
        y: 20,
        opacity: 0,
        duration: 0.6,
        delay: i * 0.05
      });
    });
  }
  
  // （可選）用 Ajax 提交表單，不換頁
  const form = document.getElementById('contactForm');
  if (form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const useAjax = submitBtn?.dataset?.ajax === '1';
    const thanks = document.getElementById('thanks');
  
    if (useAjax) {
      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        submitBtn.disabled = true;
        try {
          const fd = new FormData(form);
          const resp = await fetch('/project/backend/create.php', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: fd
          });
          const data = await resp.json();
          if (data.ok) {
            form.reset();
            if (thanks) { thanks.classList.remove('hidden'); }
          } else {
            alert(data.msg || '送出失敗');
          }
        } catch (err) {
          alert('送出失敗');
        } finally {
          submitBtn.disabled = false;
        }
      });
    }
  }