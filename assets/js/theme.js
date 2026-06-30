(function() {
    'use strict';

    var header = document.getElementById('masthead');
    var didScroll = false;
    var lastScrollY = 0;

    // --- 1. Sticky Header Shrink ---
    if (header) {
        window.addEventListener('scroll', function() {
            lastScrollY = window.scrollY;
            if (!didScroll) {
                didScroll = true;
                requestAnimationFrame(function() {
                    if (lastScrollY > 50) {
                        header.classList.add('py-lg-2','shadow-campus-med');
                        header.classList.remove('py-lg-3','shadow-campus-soft');
                    } else {
                        header.classList.add('py-lg-3','shadow-campus-soft');
                        header.classList.remove('py-lg-2','shadow-campus-med');
                    }
                    didScroll = false;
                });
            }
        }, { passive: true });
    }

    // --- 2. Counter Animation ---
    var counters = document.querySelectorAll('.counter-value');
    if (counters.length) {
        var runCounters = function() {
            counters.forEach(function(counter) {
                var targetStr = counter.getAttribute('data-target') || '0';
                var target = parseInt(targetStr.replace(/[^0-9]/g, ''), 10) || 0;
                var isPlus = targetStr.includes('+');
                var isPercent = targetStr.includes('%');
                counter.innerText = '0' + (isPlus ? '+' : '') + (isPercent ? '%' : '');
                var speed = Math.max(50, Math.min(400, Math.floor(4000 / target)));
                var update = function() {
                    var count = parseInt(counter.innerText.replace(/[^0-9]/g, ''), 10) || 0;
                    if (count < target) {
                        var inc = Math.ceil((target - count) / speed) || 1;
                        var next = Math.min(count + inc, target);
                        counter.innerText = next.toLocaleString('id-ID') + (isPlus ? '+' : '') + (isPercent ? '%' : '');
                        setTimeout(update, 16);
                    } else {
                        counter.innerText = targetStr;
                    }
                };
                setTimeout(update, 200);
            });
        };
        var statsSection = document.getElementById('statistics') || document.querySelector('.counter-value');
        if ('IntersectionObserver' in window && statsSection) {
            var obs = new IntersectionObserver(function(entries) {
                entries.forEach(function(e) { if (e.isIntersecting) { runCounters(); obs.unobserve(e.target); } });
            }, { threshold: 0.4 });
            obs.observe(statsSection.closest('section') || statsSection.parentElement);
        } else {
            runCounters();
        }
    }

    // --- 3. Tab Hash Link Activator ---
    var activateTabFromHash = function() {
        var hash = window.location.hash;
        if (!hash || !window.bootstrap) return;
        var trigger = document.querySelector('[data-bs-toggle="pill"][data-bs-target="' + hash + '"]');
        if (trigger) {
            new bootstrap.Tab(trigger).show();
            if (window.innerWidth < 992) {
                setTimeout(function() {
                    (document.getElementById('primary') || trigger.closest('section')).scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 200);
            }
        }
    };
    if (window.location.hash) setTimeout(activateTabFromHash, 100);
    window.addEventListener('hashchange', activateTabFromHash);

    // --- 4. Scroll Reveal (with stagger) ---
    var revealElems = document.querySelectorAll('.section-reveal, .section-reveal-left, .section-reveal-right, [data-reveal]');
    if (revealElems.length) {
        if ('IntersectionObserver' in window) {
            var revObs = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var el = entry.target;
                        el.classList.add('revealed');
                        var children = el.querySelectorAll('.reveal-item');
                        if (children.length) {
                            children.forEach(function(child, i) {
                                child.style.transitionDelay = (i * 80) + 'ms';
                            });
                        }
                        revObs.unobserve(el);
                    }
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
            revealElems.forEach(function(el) { revObs.observe(el); });
        } else {
            revealElems.forEach(function(el) { el.classList.add('revealed'); });
        }
    }

    // --- 5. Stagger reveal items inside sections ---
    var staggerParents = document.querySelectorAll('[data-stagger]');
    if (staggerParents.length && 'IntersectionObserver' in window) {
        var stagObs = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var items = entry.target.querySelectorAll('[data-stagger-item]');
                    items.forEach(function(item, i) {
                        item.style.transitionDelay = (i * 80) + 'ms';
                        item.classList.add('revealed');
                    });
                    stagObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        staggerParents.forEach(function(el) { stagObs.observe(el); });
    }

    // --- 6. Smooth scroll for anchor links ---
    document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                var offset = (document.getElementById('masthead')?.offsetHeight || 80) + 20;
                var top = target.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({ top: top, behavior: 'smooth' });
            }
        });
    });

    // --- 7. Card parallax tilt on hover ---
    document.querySelectorAll('[data-tilt]').forEach(function(card) {
        card.addEventListener('mousemove', function(e) {
            var rect = this.getBoundingClientRect();
            var x = (e.clientX - rect.left) / rect.width - 0.5;
            var y = (e.clientY - rect.top) / rect.height - 0.5;
            this.style.transform = 'perspective(600px) rotateY(' + (x * 6) + 'deg) rotateX(' + (-y * 6) + 'deg)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'perspective(600px) rotateY(0deg) rotateX(0deg)';
        });
    });

    // --- 8. Image parallax on scroll ---
    document.querySelectorAll('[data-parallax]').forEach(function(el) {
        if ('IntersectionObserver' in window) {
            var pObs = new IntersectionObserver(function(entries) {
                entries.forEach(function(e) {
                    if (e.isIntersecting) { e.target.classList.add('parallax-active'); pObs.unobserve(e.target); }
                });
            }, { threshold: 0.1 });
            pObs.observe(el);
        }
    });
    window.addEventListener('scroll', function() {
        document.querySelectorAll('.parallax-active').forEach(function(el) {
            var rect = el.getBoundingClientRect();
            var speed = parseFloat(el.getAttribute('data-parallax')) || 0.3;
            var offset = rect.top * speed;
            el.style.backgroundPositionY = 'calc(50% + ' + offset + 'px)';
        });
    }, { passive: true });

    // --- 9. Hero slider enhancement: pause on hover ---
    var heroCarousel = document.getElementById('heroCarousel');
    if (heroCarousel && window.bootstrap) {
        var bsCarousel = bootstrap.Carousel.getInstance(heroCarousel);
        if (bsCarousel) {
            heroCarousel.addEventListener('mouseenter', function() { bsCarousel.pause(); });
            heroCarousel.addEventListener('mouseleave', function() { bsCarousel.cycle(); });
        }
    }

    // --- 11. YouTube Hero Video Background (IFrame API) ---
    var heroYoutubeContainer = document.getElementById('hero-youtube-player');
    if (heroYoutubeContainer) {
        var ytId = heroYoutubeContainer.getAttribute('data-yt-id');
        if (ytId) {
            // Load YouTube IFrame API
            var tag = document.createElement('script');
            tag.src = 'https://www.youtube.com/iframe_api';
            var firstScript = document.getElementsByTagName('script')[0];
            firstScript.parentNode.insertBefore(tag, firstScript);

            window.onYouTubeIframeAPIReady = function() {
                new YT.Player('hero-youtube-player', {
                    videoId: ytId,
                    playerVars: {
                        autoplay: 1,
                        mute: 1,
                        loop: 1,
                        playlist: ytId,
                        controls: 0,
                        showinfo: 0,
                        modestbranding: 1,
                        rel: 0,
                        iv_load_policy: 3,
                        disablekb: 1,
                        enablejsapi: 1
                    },
                    events: {
                        onReady: function(e) {
                            e.target.mute();
                            e.target.playVideo();
                            // Ensure full coverage with responsive sizing
                            var resizePlayer = function() {
                                var container = document.getElementById('hero-youtube-player');
                                if (!container) return;
                                var parent = container.parentElement;
                                if (!parent) return;
                                var w = parent.offsetWidth;
                                var h = parent.offsetHeight;
                                var ratio = 16 / 9;
                                var pw, ph;
                                if (w / h > ratio) {
                                    pw = w;
                                    ph = w / ratio;
                                } else {
                                    ph = h;
                                    pw = h * ratio;
                                }
                                container.style.width = pw + 'px';
                                container.style.height = ph + 'px';
                            };
                            resizePlayer();
                            window.addEventListener('resize', resizePlayer);
                        }
                    }
                });
            };
        }
    }

    // --- 10. Active navigation highlight ---
    var currentPath = window.location.pathname.replace(/\/$/, '');
    document.querySelectorAll('#primary-menu .nav-link, #primary-menu .dropdown-item').forEach(function(link) {
        var href = link.getAttribute('href');
        if (href && href !== '#' && currentPath.endsWith(href.replace(/\/$/, ''))) {
            link.classList.add('active');
            var parent = link.closest('.nav-item.dropdown');
            if (parent) {
                var toggler = parent.querySelector('.nav-link.dropdown-toggle');
                if (toggler) toggler.classList.add('active');
            }
        }
    });

})();
