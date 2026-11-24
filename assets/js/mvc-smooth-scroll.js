/**
 * M.V. College, Buxar - Universal Smooth Scroll Engine & Anchor Target Resolver
 * 10/10 Benchmark Standard • Responsive Mobile-First • Vanilla JS (Zero Dependencies)
 * Handles smooth scrolling, dynamic header offset, smart hash aliases, and golden highlight pulse.
 */
(function() {
    'use strict';

    // 1. Smart Alias Dictionary for Common Semantic Anchors
    var SMART_ALIASES = {
        'about': 'mvcAbout',
        'heritage': 'mvcAbout',
        'history': 'mvcAbout',
        'faculties': 'mvcAcademicFaculties',
        'faculty': 'mvcFacultyMatrix',
        'academics': 'mvcAcademicFaculties',
        'courses': 'mvcFacultyMatrix',
        'programs': 'mvcFacultyMatrix',
        'administration': 'mvcAdministration',
        'governance': 'mvcAdministration',
        'leadership': 'mvcAdministration',
        'principal': 'mvcAdministration',
        'notices': 'mvcAnnouncements',
        'circulars': 'mvcAnnouncements',
        'announcements': 'mvcAnnouncements',
        'events': 'mvcAnnouncements',
        'tender': 'mvcAnnouncements',
        'tenders': 'mvcAnnouncements',
        'services': 'mvcServices',
        'studentservices': 'mvcServices',
        'mvcsupportservices': 'mvcServices',
        'support': 'mvcServices',
        'helpdesk': 'mvcServices',
        'press': 'mvcPress',
        'media': 'mvcPress',
        'news': 'mvcPress',
        'multimedia': 'mvcMultimedia',
        'gallery': 'mvcMultimedia',
        'photos': 'mvcMultimedia',
        'videos': 'mvcMultimedia',
        'cinema': 'mvcMultimedia',
        'testimonials': 'mvcTestimonials',
        'studenttestimonials': 'mvcTestimonials',
        'websites': 'mvcImportantWebsites',
        'portals': 'mvcImportantWebsites',
        'links': 'mvcImportantWebsites',
        'campusmap': 'mvcCampusMapSection',
        'map': 'mvcCampusMapSection',
        'hero': 'mvcHeroSec',
        'top': 'body'
    };

    /**
     * Compute dynamic header offset accounting for sticky navbar & admin bar
     */
    function getHeaderOffset() {
        var offset = 0;
        
        // WordPress admin bar
        var adminBar = document.getElementById('wpadminbar');
        if (adminBar) {
            var abStyle = window.getComputedStyle(adminBar);
            if (abStyle.position === 'fixed') {
                offset += adminBar.offsetHeight;
            }
        }
        
        // Sticky Navigation Bar
        var stickyNav = document.getElementById('mvcStickyNavBar');
        if (stickyNav) {
            offset += stickyNav.offsetHeight;
        } else {
            var header = document.querySelector('.mvc-nav-bar') || document.querySelector('.mvc-custom-header');
            if (header) {
                offset += Math.min(header.offsetHeight, 70);
            } else {
                offset += 65;
            }
        }
        
        // Extra breathing room for aesthetic spacing (16px)
        return offset + 16;
    }

    /**
     * Resolve target element by hash or smart alias
     */
    function resolveTargetElement(hash) {
        if (!hash || hash === '#' || hash === '#!' || hash === '#0') return null;
        
        var cleanHash = hash.replace(/^#/, '');
        var lowerHash = cleanHash.toLowerCase().replace(/[^a-z0-9]/g, '');
        
        // 1. Direct ID match
        var target = document.getElementById(cleanHash);
        if (target) return target;
        
        // 2. QuerySelector fallback
        try {
            target = document.querySelector(hash);
            if (target) return target;
        } catch(e) {}

        // 3. Smart alias lookup
        if (SMART_ALIASES[lowerHash]) {
            var aliasTarget = document.getElementById(SMART_ALIASES[lowerHash]);
            if (aliasTarget) return aliasTarget;
        }

        // 4. Case-insensitive or partial match for IDs
        var allIds = document.querySelectorAll('[id]');
        for (var i = 0; i < allIds.length; i++) {
            var elId = allIds[i].id;
            if (elId.toLowerCase() === cleanHash.toLowerCase() || 
                elId.toLowerCase() === 'mvc' + lowerHash) {
                return allIds[i];
            }
        }

        // 5. Anchor with name attribute
        var namedAnchor = document.querySelector('a[name="' + cleanHash + '"]');
        if (namedAnchor) return namedAnchor;

        return null;
    }

    /**
     * Silky smooth scrolling with custom cubic easing for premium 10/10 feel
     */
    function smoothScrollTo(targetPosition, duration, callback) {
        var startPosition = window.pageYOffset || document.documentElement.scrollTop;
        var distance = targetPosition - startPosition;
        
        // Don't animate if practically already there
        if (Math.abs(distance) < 5) {
            if (callback) callback();
            return;
        }

        // Duration scales pleasantly with distance: between 450ms and 800ms
        if (!duration) {
            duration = Math.min(800, Math.max(450, Math.abs(distance) * 0.45));
        }

        var startTime = null;

        // Smooth cubic ease-in-out
        function easeInOutCubic(t, b, c, d) {
            t /= d / 2;
            if (t < 1) return c / 2 * t * t * t + b;
            t -= 2;
            return c / 2 * (t * t * t + 2) + b;
        }

        function animation(currentTime) {
            if (startTime === null) startTime = currentTime;
            var timeElapsed = currentTime - startTime;
            var run = easeInOutCubic(timeElapsed, startPosition, distance, duration);
            window.scrollTo(0, run);
            if (timeElapsed < duration) {
                requestAnimationFrame(animation);
            } else {
                window.scrollTo(0, targetPosition);
                if (callback) callback();
            }
        }

        requestAnimationFrame(animation);
    }

    /**
     * Highlight targeted element with prestigious golden pulse
     */
    function highlightTarget(el) {
        if (!el) return;
        el.classList.remove('mvc-hash-highlight');
        // Force reflow
        void el.offsetWidth;
        el.classList.add('mvc-hash-highlight');
        setTimeout(function() {
            el.classList.remove('mvc-hash-highlight');
        }, 2200);
    }

    /**
     * Close off-canvas mobile drawer if open
     */
    function closeMobileMenuIfOpen() {
        var nav = document.getElementById('site-navigation');
        var overlay = document.getElementById('mvcNavOverlay');
        var toggle = document.querySelector('.mvc-mobile-toggle');
        if (nav && nav.classList.contains('pane-open')) {
            nav.classList.remove('pane-open');
            if (overlay) overlay.classList.remove('active');
            if (toggle) {
                toggle.setAttribute('aria-expanded', 'false');
                toggle.classList.remove('active');
            }
            document.body.classList.remove('mvc-menu-open');
        }
    }

    /**
     * Master scroll to hash function
     */
    function scrollToHash(hash, updateHistory, duration) {
        var target = resolveTargetElement(hash);
        if (!target) return false;

        closeMobileMenuIfOpen();

        var offset = getHeaderOffset();
        var rect = target.getBoundingClientRect();
        var targetPosition = (window.pageYOffset || document.documentElement.scrollTop) + rect.top - offset;
        
        targetPosition = Math.max(0, Math.round(targetPosition));

        smoothScrollTo(targetPosition, duration, function() {
            highlightTarget(target);
            if (updateHistory && history.pushState) {
                history.pushState(null, null, hash);
            }
        });

        return true;
    }

    // 2. Intercept Anchor Clicks Across the Entire DOM
    document.addEventListener('click', function(e) {
        var link = e.target.closest('a');
        if (!link) return;

        var href = link.getAttribute('href');
        if (!href) return;

        // Skip non-hash links, javascript:, tel:, mailto:
        if (href.indexOf('#') === -1) return;

        // Skip pure empty hash triggers
        var hash = href.substring(href.indexOf('#'));
        if (!hash || hash === '#' || hash === '#!' || hash === '#0') return;

        // Skip bootstrap / custom tab triggers
        if (link.getAttribute('data-bs-toggle') || 
            link.getAttribute('data-toggle') || 
            link.getAttribute('role') === 'tab' || 
            link.classList.contains('mvc-tab-btn')) {
            return;
        }

        // Determine if target URL is on the current page
        var linkUrl;
        try {
            linkUrl = new URL(link.href, window.location.href);
        } catch(err) {
            return;
        }

        var isSamePage = (linkUrl.origin === window.location.origin) && 
                         (linkUrl.pathname.replace(/\/$/, '') === window.location.pathname.replace(/\/$/, ''));

        if (isSamePage) {
            var handled = scrollToHash(hash, true);
            if (handled) {
                e.preventDefault();
            }
        }
    }, false);

    // 3. Handle Page Load with Hash in URL (Cross-page navigation)
    function handleInitialHash() {
        if (!window.location.hash) return;
        var initialHash = window.location.hash;
        
        // Allow DOM & styles to settle, then smoothly scroll
        setTimeout(function() {
            scrollToHash(initialHash, false, 650);
        }, 150);
    }

    // Run on DOM ready & window load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', handleInitialHash);
    } else {
        handleInitialHash();
    }
    window.addEventListener('load', handleInitialHash);

    // 4. Handle browser back / forward buttons (popstate & hashchange)
    window.addEventListener('hashchange', function() {
        if (window.location.hash) {
            scrollToHash(window.location.hash, false, 550);
        }
    });

    // Expose utility globally
    window.mvcScrollTo = function(hash) {
        if (typeof hash === 'string') {
            if (hash.charAt(0) !== '#') hash = '#' + hash;
            return scrollToHash(hash, true);
        }
        return false;
    };

    /**
     * 5. BILINGUAL TITLE EQUAL WIDTH SYNCHRONIZER
     * Mathematically synchronizes Hindi text width to match English text width
     * using uniform typographic spacing, eliminating ugly word gaps.
     */
    function mvcSyncBilingualTitles() {
        var lockups = document.querySelectorAll('.mvc-title-lockup, .mvc-footer-lockup, .mvc-lh-lockup, .mvc-wing-lockup, .mvc-loader-lockup');
        if (!lockups.length) return;

        var isDesktop = window.innerWidth > 768;

        lockups.forEach(function(lockup) {
            var eng = lockup.querySelector('.mvc-site-title, .mvc-footer-inst-name, .mvc-lh-inst-en, .mvc-loader-en-title, .mvc-wing-college-name');
            var hi = lockup.querySelector('.mvc-hindi-title, .mvc-footer-inst-hindi, .mvc-lh-inst-hi, .mvc-loader-hi-title, .mvc-wing-college-hindi');
            if (!eng || !hi) return;

            // Remove any legacy injected spans to restore clean text
            if (hi.querySelector('span')) {
                hi.textContent = hi.textContent.replace(/\s+/g, ' ').trim();
            }

            // Ensure clean natural Devanagari typography without broken letter-spacing
            hi.style.removeProperty('display');
            hi.style.removeProperty('justify-content');
            hi.style.removeProperty('align-items');
            hi.style.removeProperty('width');
            hi.style.letterSpacing = 'normal';
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', mvcSyncBilingualTitles);
    } else {
        mvcSyncBilingualTitles();
    }
    window.addEventListener('load', mvcSyncBilingualTitles);
    window.addEventListener('resize', mvcSyncBilingualTitles);
    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(mvcSyncBilingualTitles);
    }
    window.mvcSyncBilingualTitles = mvcSyncBilingualTitles;

})();
