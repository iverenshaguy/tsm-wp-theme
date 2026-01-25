/**
 * Main JavaScript file
 */

(function() {
    'use strict';

    // DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle (if needed)
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mainNavigation = document.querySelector('.main-navigation');
        
        if (mobileMenuToggle && mainNavigation) {
            mobileMenuToggle.addEventListener('click', function() {
                mainNavigation.classList.toggle('menu-open');
                this.classList.toggle('active');
            });
        }

        // Smooth scroll for anchor links
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        anchorLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.length > 1) {
                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
    });

})();
