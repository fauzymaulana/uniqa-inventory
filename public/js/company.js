/* ===================================================
   company.js – Wedding by Uniqa Landing Page Scripts
   =================================================== */

function companyInit() {

    /* ---- Page Loader ---- */
    var loader = document.getElementById('pageLoader');
    if (loader) {
        setTimeout(function () { loader.classList.add('hidden'); }, 400);
    }

    /* ---- AOS Animation ---- */
    AOS.init({ once: true, duration: 600, offset: 80 });

    /* ---- Navbar Scroll Effect ---- */
    var nav = document.getElementById('c3Navbar');
    window.addEventListener('scroll', function () {
        if (nav) nav.classList.toggle('scrolled', window.scrollY > 50);
    });

    /* ---- Scroll to Top Button ---- */
    var stBtn = document.getElementById('scrollTop');
    var waBtn = document.getElementById('floatingWaBtn');
    window.addEventListener('scroll', function () {
        if (stBtn) {
            stBtn.classList.toggle('show', window.scrollY > 400);
        }
        if (waBtn) {
            waBtn.classList.toggle('scroll-active', window.scrollY > 400);
        }
    });
    if (stBtn) {
        stBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

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
            
            // Apply scale to individual items based on their distance from viewport center
            var items = track.querySelectorAll('.hero-carousel-item');
            var viewportCenter = window.innerWidth / 2;
            
            items.forEach(function(item) {
                var rect = item.getBoundingClientRect();
                var itemCenter = rect.left + rect.width / 2;
                var distFromCenter = Math.abs(itemCenter - viewportCenter);
                
                // Bell curve scaling: center = biggest (1.4), edges = smallest (0.65)
                // Using quadratic easing for smooth falloff
                var maxDist = viewportCenter;
                var normalized = Math.min(1, distFromCenter / maxDist); // 0 at center, 1 at edges
                var scale = 1.4 - (normalized * normalized * 0.75); // Quadratic falloff
                
                item.style.transform = 'scale(' + Math.max(0.65, scale) + ')';
            });
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

    /* ---- Product Detail Modal ---- */
    (function () {
        var modal     = document.getElementById('productModal');
        var pmMedia   = document.getElementById('pmMedia');
        var pmName    = document.getElementById('pmName');
        var pmDesc    = document.getElementById('pmDesc');
        var pmPrice   = document.getElementById('pmPrice');
        var pmActions = document.getElementById('pmActions');

        if (!modal) return;

        var bsModal = new bootstrap.Modal(modal);

        // Stop video when modal closes
        modal.addEventListener('hidden.bs.modal', function () {
            var vid = pmMedia.querySelector('video');
            if (vid) { vid.pause(); vid.currentTime = 0; }
        });

        document.querySelectorAll('.product-card-clickable').forEach(function (card) {
            var isCetakCard = card.dataset.isCekat === '1' || card.dataset.isCetak === '1';

            function openModal() {
                var name    = card.dataset.name    || '';
                var desc    = card.dataset.desc    || '';
                var price   = card.dataset.price   || '';
                var video   = card.dataset.video   || '';
                var image   = card.dataset.image   || '';
                var link    = card.dataset.link    || '';
                var wa      = card.dataset.wa      || '';

                // Populate name / desc / price
                pmName.textContent  = name;
                pmDesc.textContent  = desc;
                pmDesc.style.display = desc ? '' : 'none';
                pmPrice.textContent = price ? 'Rp ' + price : '';

                // Build actions
                pmActions.innerHTML = '';
                if (wa) {
                    pmActions.innerHTML +=
                        '<a href="https://wa.me/6285362533619?text=' + wa + '" target="_blank" rel="noopener" class="pm-btn-wa">'
                        + '<i class="fab fa-whatsapp"></i> Pesan Sekarang</a>';
                }
                if (link) {
                    pmActions.innerHTML +=
                        '<a href="' + link + '" target="_blank" rel="noopener" class="pm-btn-preview">'
                        + '<i class="fas fa-eye"></i> Lihat Demo</a>';
                }

                // Build media
                pmMedia.innerHTML = '';
                if (video) {
                    // Video player
                    var vid = document.createElement('video');
                    vid.controls  = true;
                    vid.autoplay  = true;
                    vid.playsinline = true;
                    vid.loop      = true;
                    var src = document.createElement('source');
                    src.src  = video;
                    src.type = 'video/mp4';
                    vid.appendChild(src);
                    pmMedia.appendChild(vid);
                } else if (image) {
                    var wrap = document.createElement('div');
                    wrap.className = 'pm-image-preview-wrap';

                    var imgEl = document.createElement('img');
                    imgEl.src = image;
                    imgEl.alt = name;
                    imgEl.className = 'pm-image-preview';
                    wrap.appendChild(imgEl);

                    var zoomLevel = 1;
                    function setZoom(value) {
                        zoomLevel = Math.min(3, Math.max(1, value));
                        imgEl.style.transform = 'scale(' + zoomLevel + ')';
                    }

                    var controls = document.createElement('div');
                    controls.className = 'pm-image-controls';

                    var btnZoomOut = document.createElement('button');
                    btnZoomOut.type = 'button';
                    btnZoomOut.className = 'pm-control-btn';
                    btnZoomOut.textContent = '−';
                    btnZoomOut.addEventListener('click', function () { setZoom(zoomLevel - 0.2); });

                    var btnReset = document.createElement('button');
                    btnReset.type = 'button';
                    btnReset.className = 'pm-control-btn';
                    btnReset.textContent = 'Reset';
                    btnReset.addEventListener('click', function () { setZoom(1); });

                    var btnZoomIn = document.createElement('button');
                    btnZoomIn.type = 'button';
                    btnZoomIn.className = 'pm-control-btn';
                    btnZoomIn.textContent = '+';
                    btnZoomIn.addEventListener('click', function () { setZoom(zoomLevel + 0.2); });

                    controls.appendChild(btnZoomOut);
                    controls.appendChild(btnReset);
                    controls.appendChild(btnZoomIn);

                    wrap.addEventListener('wheel', function (e) {
                        e.preventDefault();
                        setZoom(zoomLevel + (e.deltaY < 0 ? 0.15 : -0.15));
                    }, { passive: false });

                    imgEl.addEventListener('dblclick', function () {
                        setZoom(1);
                    });

                    pmMedia.appendChild(wrap);
                    pmMedia.appendChild(controls);
                } else {
                    pmMedia.innerHTML = '<div class="pm-media-placeholder"><i class="fas fa-image"></i></div>';
                }

                bsModal.show();
            }

            card.addEventListener('click', openModal);
            var thumb = card.querySelector('.product-thumb');
            if (thumb) {
                thumb.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    openModal();
                });
                var thumbImg = thumb.querySelector('img');
                if (thumbImg) {
                    thumbImg.style.cursor = 'pointer';
                    thumbImg.addEventListener('click', function (event) {
                        event.preventDefault();
                        event.stopPropagation();
                        openModal();
                    });
                }
            }
            card.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openModal(); }
            });
        });
    })();

}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', companyInit);
} else {
    companyInit();
}

