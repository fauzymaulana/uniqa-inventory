/* ===================================================
   company.js – Wedding by Uniqa Landing Page Scripts
   =================================================== */

document.addEventListener('DOMContentLoaded', function () {

    /* ---- Page Loader ---- */
    var loader = document.getElementById('pageLoader');
    if (loader) {
        setTimeout(function () { loader.classList.add('hidden'); }, 400);
    }

    /* ---- AOS Animation ---- */
    AOS.init({ once: true, duration: 600, offset: 80 });

    /* ---- Navbar Scroll Effect ---- */
    var nav = document.getElementById('mainNav');
    window.addEventListener('scroll', function () {
        nav.classList.toggle('scrolled', window.scrollY > 50);
    });

    /* ---- Scroll to Top Button ---- */
    var stBtn = document.getElementById('scrollTop');
    window.addEventListener('scroll', function () {
        stBtn.classList.toggle('show', window.scrollY > 400);
    });
    stBtn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    /* ---- Smooth Anchor Navigation ---- */
    document.querySelectorAll('a[href^="#"]').forEach(function (a) {
        a.addEventListener('click', function (e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                var offset = nav.offsetHeight + 10;
                var y = target.getBoundingClientRect().top + window.pageYOffset - offset;
                window.scrollTo({ top: y, behavior: 'smooth' });

                // Close mobile navbar if open
                var collapse = document.querySelector('.navbar-collapse.show');
                if (collapse) {
                    var bsCollapse = bootstrap.Collapse.getInstance(collapse);
                    if (bsCollapse) bsCollapse.hide();
                }
            }
        });
    });

    /* ---- Counter Animation ---- */
    var counters = document.querySelectorAll('.counter');
    var started  = {};

    function animateCounters() {
        counters.forEach(function (el, idx) {
            if (started[idx]) return;
            var rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                started[idx] = true;
                var target   = parseInt(el.dataset.target, 10);
                var duration = 1800;
                var startTime = null;

                function step(ts) {
                    if (!startTime) startTime = ts;
                    var progress = Math.min((ts - startTime) / duration, 1);
                    var eased    = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.floor(eased * target).toLocaleString();
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    } else {
                        el.textContent = target.toLocaleString();
                    }
                }
                requestAnimationFrame(step);
            }
        });
    }
    window.addEventListener('scroll', animateCounters);
    animateCounters();

    /* ---- Product Filter Tabs ---- */
    var tabs  = document.querySelectorAll('.filter-tab');
    var items = document.querySelectorAll('.product-item');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) { t.classList.remove('active'); });
            this.classList.add('active');

            var filter = this.dataset.filter;
            items.forEach(function (item) {
                item.style.display = (filter === 'all' || item.classList.contains(filter)) ? '' : 'none';
            });
        });
    });

});
