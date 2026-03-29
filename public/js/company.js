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
    var waBtn = document.getElementById('floatingWaBtn');
    window.addEventListener('scroll', function () {
        stBtn.classList.toggle('show', window.scrollY > 400);
        if (waBtn) {
            waBtn.classList.toggle('scroll-active', window.scrollY > 400);
        }
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

    /* ---- Hero 3D Marquee Carousel ---- */
    (function () {
        var track = document.getElementById('heroTrack');
        if (!track) return;

        var pos        = 0;       // current translateX in px
        var speed      = 0.55;    // px per frame (auto-scroll speed)
        var isDragging = false;
        var dragLastX  = 0;
        var RX = 15, RY = -4;    // 3D tilt angles (degrees)

        function halfWidth() {
            // track has items duplicated → half = one full set width
            return track.scrollWidth / 2;
        }

        function normalize() {
            var hw = halfWidth();
            if (hw <= 0) return;
            // wrap within [−hw, 0] for seamless loop
            while (pos < -hw) pos += hw;
            while (pos > 0)   pos -= hw;
        }

        function applyTransform() {
            track.style.transform =
                'rotateX(' + RX + 'deg) rotateY(' + RY + 'deg) translateX(' + pos + 'px)';
        }

        function tick() {
            if (!isDragging) {
                pos -= speed;
                normalize();
            }
            applyTransform();
            requestAnimationFrame(tick);
        }

        /* Mouse drag */
        track.addEventListener('mousedown', function (e) {
            isDragging = true;
            dragLastX  = e.clientX;
            track.style.cursor = 'grabbing';
            e.preventDefault();
        });
        document.addEventListener('mousemove', function (e) {
            if (!isDragging) return;
            pos      += e.clientX - dragLastX;
            dragLastX = e.clientX;
            normalize();
        });
        document.addEventListener('mouseup', function () {
            if (!isDragging) return;
            isDragging = false;
            track.style.cursor = '';
        });

        /* Touch drag */
        track.addEventListener('touchstart', function (e) {
            isDragging = true;
            dragLastX  = e.touches[0].clientX;
        }, { passive: true });
        track.addEventListener('touchmove', function (e) {
            if (!isDragging) return;
            pos      += e.touches[0].clientX - dragLastX;
            dragLastX = e.touches[0].clientX;
            normalize();
        }, { passive: true });
        track.addEventListener('touchend', function () {
            isDragging = false;
        });

        /* Kick off */
        applyTransform();
        requestAnimationFrame(tick);
    })();

});
