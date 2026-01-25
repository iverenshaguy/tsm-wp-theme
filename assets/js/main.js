/**
 * Main JavaScript file
 */

(function() {
    'use strict';

    // Restore scroll position immediately if returning from newsletter submission
    if (window.location.search.includes('newsletter=')) {
        const savedScrollPosition = sessionStorage.getItem('newsletterScrollPosition');
        if (savedScrollPosition) {
            window.scrollTo(0, parseInt(savedScrollPosition, 10));
            sessionStorage.removeItem('newsletterScrollPosition');
        }
    }

    // DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        // Newsletter form validation functions (declared at function body root)
        function validateEmail(email, emailRegex) {
            if (!email || typeof email !== 'string') {
                return false;
            }
            return email.trim() !== '' && emailRegex.test(email.trim());
        }

        function updateButtonState(newsletterEmail, newsletterSubmit, emailRegex) {
            const emailValue = newsletterEmail.value || '';
            const isValid = validateEmail(emailValue, emailRegex);
            
            // Disable button if email is invalid or empty
            newsletterSubmit.disabled = !isValid;
            
            // Update input border color based on validation
            if (emailValue.trim() === '') {
                newsletterEmail.classList.remove('border-red-500', 'border-green-500');
            } else if (isValid) {
                newsletterEmail.classList.remove('border-red-500');
                newsletterEmail.classList.add('border-green-500');
            } else {
                newsletterEmail.classList.remove('border-green-500');
                newsletterEmail.classList.add('border-red-500');
            }
        }

        function hideMessage(newsletterMessage) {
            if (newsletterMessage) {
                newsletterMessage.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                newsletterMessage.style.opacity = '0';
                newsletterMessage.style.transform = 'translateY(-10px)';
                setTimeout(function() {
                    newsletterMessage.remove();
                }, 300);
            }
        }

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

        // Newsletter form validation and handling
        const newsletterForm = document.getElementById('newsletter-form');
        const newsletterEmail = document.getElementById('newsletter-email');
        const newsletterSubmit = document.getElementById('newsletter-submit');
        const newsletterMessage = document.getElementById('newsletter-message');

        if (newsletterForm && newsletterEmail && newsletterSubmit) {
            // Email validation regex
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Ensure button starts disabled
            newsletterSubmit.disabled = true;

            // Validate on input, paste, and change events
            newsletterEmail.addEventListener('input', function() {
                hideMessage(newsletterMessage);
                updateButtonState(newsletterEmail, newsletterSubmit, emailRegex);
            });
            newsletterEmail.addEventListener('paste', function() {
                setTimeout(function() {
                    hideMessage(newsletterMessage);
                    updateButtonState(newsletterEmail, newsletterSubmit, emailRegex);
                }, 10);
            });
            newsletterEmail.addEventListener('blur', function() {
                updateButtonState(newsletterEmail, newsletterSubmit, emailRegex);
            });
            newsletterEmail.addEventListener('change', function() {
                updateButtonState(newsletterEmail, newsletterSubmit, emailRegex);
            });

            // Initial validation
            updateButtonState(newsletterEmail, newsletterSubmit, emailRegex);

            // Prevent form submission if email is invalid
            newsletterForm.addEventListener('submit', function(e) {
                const emailValue = newsletterEmail.value.trim();
                const isValid = validateEmail(emailValue, emailRegex);
                
                if (!isValid) {
                    e.preventDefault();
                    e.stopPropagation();
                    // Show error feedback
                    newsletterEmail.focus();
                    newsletterEmail.classList.add('border-red-500');
                    return false;
                }
                
                // Store scroll position in sessionStorage only if valid
                sessionStorage.setItem('newsletterScrollPosition', window.scrollY.toString());
            });

            // Handle success/error message auto-hide
            if (newsletterMessage) {
                // Auto-hide after 30 seconds
                const hideTimeout = setTimeout(function() {
                    newsletterMessage.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    newsletterMessage.style.opacity = '0';
                    newsletterMessage.style.transform = 'translateY(-20px)';
                    
                    setTimeout(function() {
                        newsletterMessage.remove();
                        
                        // Reset form if it was a success message
                        if (newsletterMessage.classList.contains('newsletter-success')) {
                            newsletterForm.reset();
                            updateButtonState(newsletterEmail, newsletterSubmit, emailRegex);
                        }
                    }, 500);
                }, 30000);

                // Clear timeout if message is manually removed
                newsletterMessage.addEventListener('click', function() {
                    clearTimeout(hideTimeout);
                    this.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    this.style.opacity = '0';
                    this.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        this.remove();
                        if (this.classList.contains('newsletter-success')) {
                            newsletterForm.reset();
                            updateButtonState(newsletterEmail, newsletterSubmit, emailRegex);
                        }
                    }, 500);
                });
            }

            // Clean up URL parameter without reloading
            if (window.location.search.includes('newsletter=')) {
                const url = new URL(window.location);
                url.searchParams.delete('newsletter');
                window.history.replaceState({}, '', url);
            }
        }
    });

})();
