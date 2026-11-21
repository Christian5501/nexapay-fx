</div><!-- /.wrapper -->

<script>
// ===== ANIMATED PARTICLE BACKGROUND =====
(function() {
  const canvas = document.getElementById('bg-canvas');
  const ctx    = canvas.getContext('2d');
  let W, H, particles = [], lines = [];

  function resize() {
    W = canvas.width  = window.innerWidth;
    H = canvas.height = window.innerHeight;
  }
  window.addEventListener('resize', resize);
  resize();

  // Create particles
  function initParticles() {
    particles = [];
    const count = Math.floor((W * H) / 12000);
    for (let i = 0; i < count; i++) {
      particles.push({
        x: Math.random() * W,
        y: Math.random() * H,
        r: Math.random() * 1.5 + 0.3,
        vx: (Math.random() - 0.5) * 0.3,
        vy: (Math.random() - 0.5) * 0.3,
        alpha: Math.random() * 0.5 + 0.15,
        hue: Math.random() < 0.6 ? 210 : (Math.random() < 0.5 ? 190 : 160)
      });
    }
  }
  initParticles();
  window.addEventListener('resize', initParticles);

  function draw() {
    ctx.clearRect(0, 0, W, H);

    // Draw globe arc overlay (subtle)
    const cx = W * 0.72, cy = H * 0.35, rad = Math.min(W, H) * 0.32;
    const arcGrad = ctx.createRadialGradient(cx, cy, 0, cx, cy, rad);
    arcGrad.addColorStop(0,   'rgba(0,102,255,0.04)');
    arcGrad.addColorStop(0.6, 'rgba(0,102,255,0.02)');
    arcGrad.addColorStop(1,   'transparent');
    ctx.beginPath();
    ctx.arc(cx, cy, rad, 0, Math.PI * 2);
    ctx.fillStyle = arcGrad;
    ctx.fill();

    // Draw lines between nearby particles
    for (let i = 0; i < particles.length; i++) {
      for (let j = i + 1; j < particles.length; j++) {
        const dx = particles[i].x - particles[j].x;
        const dy = particles[i].y - particles[j].y;
        const dist = Math.sqrt(dx*dx + dy*dy);
        if (dist < 120) {
          const alpha = (1 - dist / 120) * 0.12;
          ctx.beginPath();
          ctx.strokeStyle = `rgba(0,160,255,${alpha})`;
          ctx.lineWidth = 0.5;
          ctx.moveTo(particles[i].x, particles[i].y);
          ctx.lineTo(particles[j].x, particles[j].y);
          ctx.stroke();
        }
      }
    }

    // Draw particles
    particles.forEach(p => {
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fillStyle = `hsla(${p.hue}, 80%, 70%, ${p.alpha})`;
      ctx.fill();

      p.x += p.vx;
      p.y += p.vy;
      if (p.x < 0 || p.x > W) p.vx *= -1;
      if (p.y < 0 || p.y > H) p.vy *= -1;
    });

    requestAnimationFrame(draw);
  }
  draw();
})();

// ===== PAGE NAVIGATION =====
function showPage(pageId) {
  document.querySelectorAll('.page-section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));

  const page = document.getElementById('page-' + pageId);
  if (page) page.classList.add('active');

  document.querySelectorAll('.nav-item').forEach(n => {
    if (n.getAttribute('onclick') && n.getAttribute('onclick').includes("'" + pageId + "'")) {
      n.classList.add('active');
    }
  });

  // Update breadcrumb
  const labels = {
    dashboard:'Dashboard', send:'Send Money', fx:'FX Rates',
    transactions:'Transactions', wallets:'Wallets', mobilemoney:'Mobile Money',
    bank:'Bank Transfer', settings:'Settings', api:'API Access'
  };
  const bc = document.getElementById('breadcrumb-current');
  if (bc) bc.textContent = labels[pageId] || pageId;

  // Update topbar title
  const tt = document.getElementById('topbar-title');
  if (tt) tt.textContent = labels[pageId] || pageId;

  // Close sidebar on mobile
  if (window.innerWidth < 900) {
    document.getElementById('sidebar').classList.remove('open');
  }

  // Init page-specific JS
  if (pageId === 'dashboard' || pageId === 'fx') initCharts();
}

// ===== TOAST NOTIFICATION =====
function showToast(title, msg, type = 'info') {
  const icons = { success: '✅', error: '❌', info: '💡', warning: '⚠️' };
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `
    <span class="toast-icon">${icons[type] || '💡'}</span>
    <div class="toast-text">
      <div class="toast-title">${title}</div>
      ${msg ? `<div class="toast-msg">${msg}</div>` : ''}
    </div>`;
  document.getElementById('toast-container').appendChild(toast);
  setTimeout(() => { toast.style.opacity='0'; toast.style.transform='translateX(20px)';
    toast.style.transition='all 0.3s'; setTimeout(()=>toast.remove(),300); }, 3500);
}

// ===== MODAL =====
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.addEventListener('click', e => {
  if (e.target.classList.contains('modal-overlay')) e.target.classList.remove('open');
});

// ===== MOBILE SIDEBAR =====
document.addEventListener('DOMContentLoaded', () => {
  const menuBtn = document.getElementById('menu-btn');
  if (menuBtn) {
    menuBtn.addEventListener('click', () => {
      document.getElementById('sidebar').classList.toggle('open');
    });
  }
});

// ===== LIVE FX TICKER =====
function animateTicker() {
  const items = document.querySelectorAll('.ticker-rate');
  items.forEach(item => {
    const base = parseFloat(item.dataset.base);
    const variance = base * 0.0003;
    const newVal = base + (Math.random() - 0.5) * variance * 2;
    item.dataset.base = newVal;
    item.textContent = newVal.toFixed(item.dataset.decimals || 4);
  });
}
setInterval(animateTicker, 3000);

// ===== CHART.JS CHARTS =====
let chartInstances = {};

function initCharts() {
  setTimeout(() => {
    initVolumeChart();
    initMiniCharts();
  }, 100);
}

function initVolumeChart() {
  const canvas = document.getElementById('volume-chart');
  if (!canvas) return;
  if (chartInstances['volume']) chartInstances['volume'].destroy();

  const labels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
  const data1  = [42000, 58000, 51000, 74000, 66000, 89000, 95000];
  const data2  = [30000, 42000, 38000, 55000, 48000, 67000, 72000];

  const ctx = canvas.getContext('2d');

  const grad1 = ctx.createLinearGradient(0, 0, 0, 300);
  grad1.addColorStop(0, 'rgba(0,102,255,0.4)');
  grad1.addColorStop(1, 'rgba(0,102,255,0.02)');

  const grad2 = ctx.createLinearGradient(0, 0, 0, 300);
  grad2.addColorStop(0, 'rgba(0,212,255,0.3)');
  grad2.addColorStop(1, 'rgba(0,212,255,0.02)');

  chartInstances['volume'] = new Chart(ctx, {
    type: 'line',
    data: {
      labels,
      datasets: [
        {
          label: 'Total Volume (USD)',
          data: data1,
          borderColor: '#0066ff',
          backgroundColor: grad1,
          borderWidth: 2.5,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: '#0066ff',
          pointRadius: 4,
          pointHoverRadius: 6,
        },
        {
          label: 'Settled (USD)',
          data: data2,
          borderColor: '#00d4ff',
          backgroundColor: grad2,
          borderWidth: 2,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: '#00d4ff',
          pointRadius: 3,
          pointHoverRadius: 5,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        legend: {
          labels: { color: '#8892b0', font: { size: 12 }, usePointStyle: true, pointStyle: 'circle' }
        },
        tooltip: {
          backgroundColor: 'rgba(13,18,40,0.95)',
          borderColor: 'rgba(0,102,255,0.3)',
          borderWidth: 1,
          titleColor: '#f0f4ff',
          bodyColor: '#8892b0',
          padding: 12,
          callbacks: {
            label: ctx => ' $' + ctx.parsed.y.toLocaleString()
          }
        }
      },
      scales: {
        x: {
          grid: { color: 'rgba(255,255,255,0.04)' },
          ticks: { color: '#4a5568', font: { size: 12 } }
        },
        y: {
          grid: { color: 'rgba(255,255,255,0.04)' },
          ticks: {
            color: '#4a5568', font: { size: 12 },
            callback: v => '$' + (v/1000).toFixed(0) + 'k'
          }
        }
      }
    }
  });
}

function initMiniCharts() {
  document.querySelectorAll('.mini-chart-canvas').forEach(canvas => {
    const id = canvas.id;
    if (chartInstances[id]) chartInstances[id].destroy();

    const data = JSON.parse(canvas.dataset.points || '[0]');
    const trend = canvas.dataset.trend;
    const color = trend === 'up' ? '#00c896' : '#ff4757';

    const ctx = canvas.getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 40);
    grad.addColorStop(0, color + '40');
    grad.addColorStop(1, color + '00');

    chartInstances[id] = new Chart(ctx, {
      type: 'line',
      data: {
        labels: data.map((_, i) => i),
        datasets: [{
          data, borderColor: color, backgroundColor: grad,
          borderWidth: 1.5, fill: true, tension: 0.4,
          pointRadius: 0
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { enabled: false } },
        scales: { x: { display: false }, y: { display: false } }
      }
    });
  });
}

// ===== FX CALCULATOR =====
function calcFX() {
  const amount = parseFloat(document.getElementById('calc-amount')?.value || 0);
  const from   = document.getElementById('calc-from')?.value;
  const to     = document.getElementById('calc-to')?.value;
  if (!amount || !from || !to) return;

  fetch(`api.php?action=convert&amount=${amount}&from=${from}&to=${to}`)
    .then(r => r.json())
    .then(data => {
      if (data.result !== undefined) {
        document.getElementById('calc-result').textContent = data.formatted;
        document.getElementById('calc-rate').textContent   = `1 ${from} = ${data.rate} ${to}`;
        document.getElementById('calc-fee').textContent    = `Fee: $${data.fee}`;
      }
    })
    .catch(() => {
      // Fallback client-side
      const rates = {USD:1,RWF:1350,EUR:0.918,GBP:0.785,KES:132.5,UGX:3750,GHS:12.8,NGN:1580,XOF:601,ZAR:18.9};
      const rate  = rates[to] / rates[from];
      const result = amount * rate;
      document.getElementById('calc-result').textContent = result.toFixed(2) + ' ' + to;
      document.getElementById('calc-rate').textContent   = `1 ${from} = ${rate.toFixed(4)} ${to}`;
    });
}

// Auto-calc on input
document.addEventListener('DOMContentLoaded', () => {
  const inputs = ['calc-amount','calc-from','calc-to'];
  inputs.forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('change', calcFX);
    if (el) el.addEventListener('input',  calcFX);
  });

  // Init dashboard
  showPage('dashboard');
});

// ===== SEND MONEY FLOW =====
let sendStep = 1;

function nextStep() {
  if (sendStep >= 3) return;
  sendStep++;
  updateSteps();
}

function prevStep() {
  if (sendStep <= 1) return;
  sendStep--;
  updateSteps();
}

function updateSteps() {
  document.querySelectorAll('.step-panel').forEach((p, i) => {
    p.style.display = (i + 1 === sendStep) ? 'block' : 'none';
  });
  document.querySelectorAll('.step').forEach((s, i) => {
    s.classList.remove('active','done');
    if (i + 1 === sendStep) s.classList.add('active');
    if (i + 1 < sendStep)   s.classList.add('done');
  });
}

function submitTransfer() {
  const btn = document.getElementById('submit-btn');
  btn.innerHTML = '<span class="spinner"></span> Processing...';
  btn.disabled = true;

  setTimeout(() => {
    btn.innerHTML = '✅ Payment Sent!';
    showToast('Payment Sent!', 'Your transfer is being processed.', 'success');
    setTimeout(() => {
      btn.innerHTML = 'Send Money';
      btn.disabled = false;
      sendStep = 1;
      updateSteps();
      showPage('transactions');
    }, 1500);
  }, 2500);
}
</script>
</body>
</html>
