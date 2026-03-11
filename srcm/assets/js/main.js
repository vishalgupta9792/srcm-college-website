// ============================================
// SRCM Inter College - Main JavaScript
// ============================================

// SLIDER
(function() {
  let current = 0;
  const slidesEl = document.getElementById('slides');
  if (!slidesEl) return;
  const totalSlides = slidesEl.children.length;

  window.changeSlide = function(dir) {
    current = (current + dir + totalSlides) % totalSlides;
    updateSlider();
  };
  window.goToSlide = function(n) {
    current = n;
    updateSlider();
  };
  function updateSlider() {
    slidesEl.style.transform = `translateX(-${current * 100}%)`;
    document.querySelectorAll('.dot').forEach((d, i) => d.classList.toggle('active', i === current));
  }
  setInterval(() => changeSlide(1), 5000);
})();

// THOUGHT ROTATOR
(function() {
  const el = document.getElementById('thought-text');
  if (!el) return;
  const thoughts = el.dataset.thoughts ? JSON.parse(el.dataset.thoughts) : [];
  if (thoughts.length < 2) return;
  let idx = 0;
  setInterval(() => {
    idx = (idx + 1) % thoughts.length;
    el.style.opacity = '0';
    setTimeout(() => { el.textContent = thoughts[idx]; el.style.opacity = '1'; }, 400);
  }, 4000);
})();

// MOBILE NAV
document.querySelectorAll('.dropdown > span').forEach(el => {
  el.addEventListener('click', function() {
    if (window.innerWidth <= 900) this.parentElement.classList.toggle('open');
  });
});

// COUNTER ANIMATION
function animateCounters() {
  document.querySelectorAll('[data-count]').forEach(el => {
    const target = parseInt(el.dataset.count);
    const suffix = el.dataset.suffix || '';
    let count = 0;
    const step = target / 60;
    const timer = setInterval(() => {
      count = Math.min(count + step, target);
      el.querySelector('.count-num').textContent = Math.floor(count) + suffix;
      if (count >= target) clearInterval(timer);
    }, 30);
  });
}
const statsSection = document.querySelector('.stats-section');
if (statsSection) {
  const obs = new IntersectionObserver(entries => {
    if (entries[0].isIntersecting) { animateCounters(); obs.disconnect(); }
  });
  obs.observe(statsSection);
}

// LIGHTBOX for gallery
document.querySelectorAll('.gallery-item[data-src]').forEach(item => {
  item.addEventListener('click', function() {
    const src = this.dataset.src;
    const title = this.dataset.title || '';
    const lb = document.createElement('div');
    lb.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.9);z-index:9999;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:15px;cursor:pointer;';
    lb.innerHTML = `<img src="${src}" style="max-width:90vw;max-height:80vh;border-radius:8px;box-shadow:0 0 40px rgba(0,0,0,0.5);">
      <p style="color:white;font-size:16px;font-weight:600;">${title}</p>
      <div style="color:#aaa;font-size:13px;">Click anywhere to close</div>`;
    lb.addEventListener('click', () => lb.remove());
    document.body.appendChild(lb);
  });
});

// ADMIN SIDEBAR TOGGLE (mobile)
const toggleBtn = document.getElementById('admin-toggle');
const sidebar = document.querySelector('.admin-sidebar');
if (toggleBtn && sidebar) {
  toggleBtn.addEventListener('click', () => sidebar.classList.toggle('open'));
}

// AUTO DISMISS ALERTS
document.querySelectorAll('.alert-auto').forEach(el => {
  setTimeout(() => { el.style.opacity = '0'; setTimeout(() => el.remove(), 300); }, 3000);
});

// CONFIRM DELETE
document.querySelectorAll('[data-confirm]').forEach(el => {
  el.addEventListener('click', function(e) {
    if (!confirm(this.dataset.confirm || 'Are you sure?')) e.preventDefault();
  });
});
