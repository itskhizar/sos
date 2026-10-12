/**
 * SOS Hermann Gmeiner School & College
 * Global JavaScript — app.js
 */

'use strict';

/* =============================================
   UTILITY HELPERS
   ============================================= */
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

/* =============================================
   NAVIGATION: Sticky header on scroll
   ============================================= */
function initStickyNav() {
  const header = $('.site-header');
  if (!header) return;
  const onScroll = () => {
    header.classList.toggle('scrolled', window.scrollY > 20);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}

/* =============================================
   NAVIGATION: Mobile menu
   ============================================= */
function initMobileNav() {
  const toggle  = $('#nav-toggle');
  const mobileNav = $('#mobile-nav');
  const overlay = $('#mobile-overlay');
  const closeBtn = $('#mobile-nav-close');
  if (!toggle || !mobileNav) return;

  function openNav() {
    mobileNav.classList.add('open');
    overlay && overlay.classList.add('open');
    toggle.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeNav() {
    mobileNav.classList.remove('open');
    overlay && overlay.classList.remove('open');
    toggle.classList.remove('open');
    document.body.style.overflow = '';
  }

  toggle.addEventListener('click', () => mobileNav.classList.contains('open') ? closeNav() : openNav());
  closeBtn && closeBtn.addEventListener('click', closeNav);
  overlay  && overlay.addEventListener('click', closeNav);

  // Close on outside link click
  $$('.mobile-nav a', mobileNav).forEach(a => a.addEventListener('click', closeNav));

  // Close on Escape
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeNav(); });
}

/* =============================================
   SCROLL REVEAL (lightweight AOS replacement)
   ============================================= */
function initScrollReveal() {
  const items = $$('[data-aos]');
  if (!items.length) return;

  const delays = {
    '0': 0, '100': 100, '200': 200, '300': 300, '400': 400, '500': 500,
    '600': 600, '700': 700, '800': 800
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const delay = entry.target.dataset.aosDelay || 0;
        setTimeout(() => {
          entry.target.classList.add('aos-animate');
        }, parseInt(delay));
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

  items.forEach(el => observer.observe(el));
}

/* =============================================
   COUNTER ANIMATION
   ============================================= */
function initCounters() {
  const counters = $$('[data-count]');
  if (!counters.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const target = parseInt(el.dataset.count);
      const suffix = el.dataset.suffix || '';
      const prefix = el.dataset.prefix || '';
      const duration = parseInt(el.dataset.duration) || 2000;
      const startTime = performance.now();

      function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        // Ease out
        const eased = 1 - Math.pow(1 - progress, 3);
        const current = Math.floor(eased * target);
        el.textContent = prefix + current.toLocaleString() + suffix;
        if (progress < 1) requestAnimationFrame(update);
      }
      requestAnimationFrame(update);
      observer.unobserve(el);
    });
  }, { threshold: 0.5 });

  counters.forEach(el => observer.observe(el));
}

/* =============================================
   ACCORDION / FAQ
   ============================================= */
function initAccordions() {
  $$('.accordion-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const item = btn.closest('.accordion-item');
      const body = item.querySelector('.accordion-body');
      const isOpen = item.classList.contains('open');

      // Close all siblings (optional: comment out for multi-open)
      const parent = item.closest('.accordion-group');
      if (parent) {
        $$('.accordion-item', parent).forEach(i => {
          i.classList.remove('open');
          i.querySelector('.accordion-body').classList.remove('open');
        });
      }

      if (!isOpen) {
        item.classList.add('open');
        body.classList.add('open');
      }
    });
  });
}

/* =============================================
   TABS
   ============================================= */
function initTabs() {
  $$('.tab-list').forEach(tabList => {
    const group = tabList.closest('[data-tab-group]') || tabList.parentElement;
    $$('.tab-btn', tabList).forEach(btn => {
      btn.addEventListener('click', () => {
        const target = btn.dataset.tab;
        // Update buttons
        $$('.tab-btn', tabList).forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        // Update panels
        $$('.tab-content', group).forEach(panel => {
          panel.classList.toggle('active', panel.dataset.tabPanel === target);
        });
      });
    });
  });
}

/* =============================================
   SCROLL TO TOP BUTTON
   ============================================= */
function initScrollTop() {
  const btn = $('#scroll-top');
  if (!btn) return;
  window.addEventListener('scroll', () => {
    btn.classList.toggle('visible', window.scrollY > 400);
  }, { passive: true });
  btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

/* =============================================
   ACTIVE NAV LINK (Highlight current page)
   ============================================= */
function initActiveNav() {
  const currentPath = window.location.pathname.split('/').pop() || 'index.php';
  $$('.navbar-nav .nav-link, .mobile-nav a').forEach(link => {
    const href = link.getAttribute('href');
    if (href && (href === currentPath || href.startsWith(currentPath.replace('.php', '')))) {
      link.classList.add('active');
    }
  });
}

/* =============================================
   GALLERY LIGHTBOX (GLightbox integration)
   ============================================= */
function initGalleryLightbox() {
  if (typeof GLightbox === 'undefined') return;
  GLightbox({
    selector: '.glightbox',
    touchNavigation: true,
    loop: true,
    autoplayVideos: false
  });
}

/* =============================================
   SLIDER / SWIPER (global init for all swipers)
   ============================================= */
function initSwipers() {
  if (typeof Swiper === 'undefined') return;

  // News Slider
  if ($('.swiper.news-swiper')) {
    new Swiper('.news-swiper', {
      slidesPerView: 1,
      spaceBetween: 24,
      loop: true,
      autoplay: { delay: 4000, disableOnInteraction: false },
      breakpoints: {
        640: { slidesPerView: 2 },
        1024: { slidesPerView: 3 }
      },
      pagination: { el: '.news-pagination', clickable: true }
    });
  }

  // Testimonial Slider
  if ($('.swiper.testimonial-swiper')) {
    new Swiper('.testimonial-swiper', {
      slidesPerView: 1,
      spaceBetween: 24,
      loop: true,
      autoplay: { delay: 5000, disableOnInteraction: false },
      breakpoints: {
        640: { slidesPerView: 2 },
        1024: { slidesPerView: 3 }
      },
      pagination: { el: '.testimonial-pagination', clickable: true }
    });
  }

  // Gallery Slider
  if ($('.swiper.gallery-swiper')) {
    new Swiper('.gallery-swiper', {
      slidesPerView: 2,
      spaceBetween: 16,
      loop: true,
      autoplay: { delay: 3500 },
      breakpoints: {
        640: { slidesPerView: 3 },
        1024: { slidesPerView: 4 }
      }
    });
  }
}

/* =============================================
   MULTI-STEP FORM WIZARD
   ============================================= */
function initWizard() {
  const form = $('#admission-wizard');
  if (!form) return;

  let currentStep = 0;
  const steps = $$('.wizard-step', form);
  const stepCircles = $$('.step', form.closest('.wizard-wrapper'));
  const prevBtn = $('#wizard-prev');
  const nextBtn = $('#wizard-next');
  const submitBtn = $('#wizard-submit');
  const stepCounter = $('#step-counter');

  function showStep(n) {
    steps.forEach((s, i) => s.classList.toggle('active', i === n));
    stepCircles.forEach((s, i) => {
      s.classList.toggle('active', i === n);
      s.classList.toggle('done', i < n);
    });
    if (prevBtn) prevBtn.style.display = n === 0 ? 'none' : '';
    if (nextBtn) nextBtn.style.display = n === steps.length - 1 ? 'none' : '';
    if (submitBtn) submitBtn.style.display = n === steps.length - 1 ? '' : 'none';
    if (stepCounter) stepCounter.textContent = `Step ${n + 1} of ${steps.length}`;
    window.scrollTo({ top: form.offsetTop - 120, behavior: 'smooth' });
  }

  function validateStep(n) {
    const stepEl = steps[n];
    const required = $$('[required]', stepEl);
    let valid = true;
    required.forEach(field => {
      field.classList.remove('error');
      const err = field.nextElementSibling;
      if (err && err.classList.contains('form-error')) err.style.display = 'none';
      if (!field.value.trim()) {
        field.classList.add('error');
        if (err && err.classList.contains('form-error')) {
          err.style.display = 'block';
          err.textContent = 'This field is required.';
        }
        valid = false;
      }
    });
    return valid;
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      if (validateStep(currentStep)) {
        currentStep = Math.min(currentStep + 1, steps.length - 1);
        showStep(currentStep);
      }
    });
  }
  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      currentStep = Math.max(currentStep - 1, 0);
      showStep(currentStep);
    });
  }

  showStep(0);
}

/* =============================================
   APPLICATION TRACKING
   ============================================= */
function initAppTracker() {
  const form = $('#track-form');
  if (!form) return;
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const appId = $('#app-id-input').value.trim();
    if (!appId) return;
    const resultArea = $('#track-result');
    if (!resultArea) return;
    resultArea.innerHTML = '<div class="skeleton" style="height:200px;border-radius:12px;"></div>';
    resultArea.style.display = 'block';
    // AJAX call to backend
    $.ajax && $.ajax({
      url: 'process.php',
      method: 'POST',
      data: { track_application: 1, app_id: appId },
      success: function(res) {
        resultArea.innerHTML = res;
      },
      error: function() {
        resultArea.innerHTML = '<p style="color:#dc2626">Could not fetch status. Please try again.</p>';
      }
    });
    // Fallback if jQuery not loaded
    if (typeof jQuery === 'undefined') {
      setTimeout(() => {
        resultArea.innerHTML = `
          <div class="alert alert-info">
            <i class="fa fa-info-circle"></i>
            Application ID <strong>${appId}</strong> — Status tracking will be available once the backend is connected.
          </div>`;
      }, 1000);
    }
  });
}

/* =============================================
   CONTACT FORM (AJAX)
   ============================================= */
function initContactForm() {
  const form = $('#contact-form');
  if (!form) return;
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = form.querySelector('[type=submit]');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Sending...';
    btn.disabled = true;

    const data = new FormData(form);
    fetch('process.php', { method: 'POST', body: data })
      .then(r => r.text())
      .then(res => {
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Sent!';
        form.reset();
        setTimeout(() => { btn.innerHTML = originalText; btn.disabled = false; }, 3000);
      })
      .catch(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        alert('Failed to send. Please try again.');
      });
  });
}

/* =============================================
   SMOOTH ANCHOR LINKS
   ============================================= */
function initSmoothScroll() {
  $$('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const target = $(a.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });
}

/* =============================================
   SEARCH TOGGLE
   ============================================= */
function initSearch() {
  const toggleBtn = $('#search-toggle');
  const searchPanel = $('#search-panel');
  const closeBtn = $('#search-close');
  const input = $('#search-input');
  if (!toggleBtn || !searchPanel) return;

  toggleBtn.addEventListener('click', () => {
    searchPanel.classList.toggle('open');
    if (searchPanel.classList.contains('open')) {
      setTimeout(() => input && input.focus(), 100);
    }
  });
  closeBtn && closeBtn.addEventListener('click', () => searchPanel.classList.remove('open'));
}

/* =============================================
   FILTER / CATEGORY TABS (News, Gallery etc.)
   ============================================= */
function initFilterTabs() {
  $$('[data-filter-group]').forEach(group => {
    const btns  = $$('[data-filter]', group);
    const items = $$('[data-category]', group.nextElementSibling || group.parentElement);
    btns.forEach(btn => {
      btn.addEventListener('click', () => {
        const filter = btn.dataset.filter;
        btns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        items.forEach(item => {
          const show = filter === 'all' || item.dataset.category === filter;
          item.style.display = show ? '' : 'none';
        });
      });
    });
  });
}

/* =============================================
   INIT ALL
   ============================================= */
document.addEventListener('DOMContentLoaded', () => {
  initStickyNav();
  initMobileNav();
  initScrollReveal();
  initCounters();
  initAccordions();
  initTabs();
  initScrollTop();
  initActiveNav();
  initGalleryLightbox();
  initSwipers();
  initWizard();
  initAppTracker();
  initContactForm();
  initSmoothScroll();
  initSearch();
  initFilterTabs();
});
