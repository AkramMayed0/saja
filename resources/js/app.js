import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/* ==========================================================================
   UI interactions — small, subtle and accessible.
   Everything is progressive enhancement: pages work fine without it.
   ========================================================================== */

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

/* 1. Scroll reveal ------------------------------------------------------- */
function initReveal() {
    const items = document.querySelectorAll('[data-reveal]');
    if (!items.length) return;

    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        items.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            io.unobserve(entry.target);
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    // Auto-stagger children inside [data-reveal-group]
    document.querySelectorAll('[data-reveal-group]').forEach((group) => {
        group.querySelectorAll(':scope > [data-reveal]').forEach((child, i) => {
            child.style.setProperty('--reveal-delay', `${Math.min(i, 8) * 70}ms`);
        });
    });

    items.forEach((el) => io.observe(el));
}

/* 2. Header: condense on scroll + reading progress bar ------------------- */
function initHeader() {
    const header = document.querySelector('[data-site-header]');
    const progress = document.querySelector('[data-scroll-progress]');
    const toTop = document.querySelector('[data-back-to-top]');
    let ticking = false;

    const update = () => {
        const y = window.scrollY;
        const max = document.documentElement.scrollHeight - window.innerHeight;
        const ratio = max > 0 ? Math.min(y / max, 1) : 0;

        header?.classList.toggle('is-scrolled', y > 8);
        if (progress) progress.style.transform = `scaleX(${ratio})`;
        if (toTop) {
            toTop.classList.toggle('is-shown', y > 600);
            toTop.style.setProperty('--p', `${ratio * 100}`);
        }
        ticking = false;
    };

    window.addEventListener('scroll', () => {
        if (!ticking) { requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
    update();

    toTop?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: prefersReducedMotion ? 'auto' : 'smooth' }));
}

/* 3. 3D tilt + glare on cards (desktop only) ----------------------------- */
function initTilt() {
    if (prefersReducedMotion || !finePointer) return;
    const MAX = 6; // degrees — keep it subtle

    document.querySelectorAll('[data-tilt]').forEach((card) => {
        let frame;
        card.addEventListener('pointermove', (e) => {
            const r = card.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width;
            const py = (e.clientY - r.top) / r.height;
            cancelAnimationFrame(frame);
            frame = requestAnimationFrame(() => {
                card.classList.add('is-tilting');
                card.style.setProperty('--ry', `${(px - 0.5) * MAX * 2}deg`);
                card.style.setProperty('--rx', `${(0.5 - py) * MAX * 2}deg`);
                card.style.setProperty('--gx', `${px * 100}%`);
                card.style.setProperty('--gy', `${py * 100}%`);
            });
        });
        card.addEventListener('pointerleave', () => {
            cancelAnimationFrame(frame);
            card.classList.remove('is-tilting');
            card.style.setProperty('--rx', '0deg');
            card.style.setProperty('--ry', '0deg');
        });
    });
}

/* 4. Mouse parallax for hero layers -------------------------------------- */
function initParallax() {
    if (prefersReducedMotion || !finePointer) return;
    const scene = document.querySelector('[data-parallax-scene]');
    if (!scene) return;
    const layers = scene.querySelectorAll('[data-depth]');
    let frame;

    scene.addEventListener('pointermove', (e) => {
        const r = scene.getBoundingClientRect();
        const x = (e.clientX - r.left) / r.width - 0.5;
        const y = (e.clientY - r.top) / r.height - 0.5;
        cancelAnimationFrame(frame);
        frame = requestAnimationFrame(() => {
            layers.forEach((layer) => {
                const d = parseFloat(layer.dataset.depth) || 0;
                layer.style.translate = `${-x * d}px ${-y * d}px`;
            });
        });
    });
    scene.addEventListener('pointerleave', () => {
        layers.forEach((layer) => { layer.style.translate = '0 0'; });
    });
}

/* 5. Click ripple on buttons --------------------------------------------- */
function initRipple() {
    if (prefersReducedMotion) return;
    document.addEventListener('pointerdown', (e) => {
        const btn = e.target.closest('.btn-primary, .btn-dark, [data-ripple]');
        if (!btn) return;
        const r = btn.getBoundingClientRect();
        const size = Math.max(r.width, r.height);
        const span = document.createElement('span');
        span.className = 'ripple';
        span.style.width = span.style.height = `${size}px`;
        span.style.left = `${e.clientX - r.left - size / 2}px`;
        span.style.top = `${e.clientY - r.top - size / 2}px`;
        btn.appendChild(span);
        span.addEventListener('animationend', () => span.remove());
    });
}

/* 6. Count-up numbers ----------------------------------------------------- */
function initCountUp() {
    const els = document.querySelectorAll('[data-count]');
    if (!els.length) return;

    const run = (el) => {
        const target = parseInt(el.dataset.count, 10) || 0;
        if (prefersReducedMotion) { el.textContent = target; return; }
        const duration = 1400;
        const start = performance.now();
        const step = (now) => {
            const t = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - t, 3);
            el.textContent = Math.round(target * eased);
            if (t < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    };

    if (!('IntersectionObserver' in window)) { els.forEach(run); return; }
    const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) { run(entry.target); io.unobserve(entry.target); }
        });
    }, { threshold: 0.6 });
    els.forEach((el) => io.observe(el));
}

/* 7. Add-to-cart button feedback ----------------------------------------- */
function initAddToCart() {
    document.addEventListener('submit', (e) => {
        const btn = e.target.querySelector('[data-add-to-cart]');
        if (!btn) return;
        btn.classList.add('is-loading');
        btn.setAttribute('aria-busy', 'true');
    });
}

/* 8. Theme toggle --------------------------------------------------------- */
function initThemeToggle() {
    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initReveal();
    initHeader();
    initTilt();
    initParallax();
    initRipple();
    initCountUp();
    initAddToCart();
    initThemeToggle();
});
