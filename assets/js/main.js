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

        // Initialize gallery lightboxes
        initGalleryLightboxes();
    });

    /**
     * Initialize all gallery lightboxes on the page
     */
    function initGalleryLightboxes() {
        const lightboxes = document.querySelectorAll('.tsm-lightbox');
        
        lightboxes.forEach(function(lightbox) {
            const lightboxId = lightbox.id;
            const galleryImages = document.querySelectorAll('[data-lightbox="' + lightboxId + "]");
            
            if (galleryImages.length === 0) {
                // Fallback: look for .gallery-image elements near this lightbox
                const container = lightbox.closest('main, article, .content-area') || document.body;
                const fallbackImages = container.querySelectorAll('.gallery-image');
                if (fallbackImages.length > 0) {
                    initLightbox(lightbox, fallbackImages);
                }
            } else {
                initLightbox(lightbox, galleryImages);
            }
        });
    }

    /**
     * Initialize a single lightbox instance
     */
    function initLightbox(lightbox, galleryImages) {
        const lightboxImage = lightbox.querySelector('.tsm-lightbox-image');
        const lightboxTitle = lightbox.querySelector('.tsm-lightbox-title');
        const lightboxCounter = lightbox.querySelector('.tsm-lightbox-counter');
        const lightboxDownload = lightbox.querySelector('.tsm-lightbox-download');
        const lightboxClose = lightbox.querySelector('.tsm-lightbox-close');
        const lightboxPrev = lightbox.querySelector('.tsm-lightbox-prev');
        const lightboxNext = lightbox.querySelector('.tsm-lightbox-next');
        const thumbnails = lightbox.querySelectorAll('.tsm-lightbox-thumbnail');
        
        if (!lightboxImage) return;
        
        let currentIndex = 0;
        let imageData = [];
        
        // First, try to get image data from the lightbox data attribute (all images)
        const lightboxData = lightbox.getAttribute('data-images');
        if (lightboxData) {
            try {
                imageData = JSON.parse(lightboxData);
            } catch (e) {
                console.error('Failed to parse lightbox image data', e);
            }
        }
        
        // Fallback: collect from gallery images if data attribute not available
        if (imageData.length === 0 && galleryImages.length > 0) {
            galleryImages.forEach(function(img) {
                const imgEl = img.querySelector('img') || img;
                const fullUrl = imgEl.getAttribute('data-full') || imgEl.src;
                const alt = imgEl.getAttribute('data-alt') || imgEl.alt || '';
                
                imageData.push({
                    full: fullUrl,
                    alt: alt
                });
            });
        }
        
        if (imageData.length === 0) return;
        
        const totalImages = imageData.length;
        
        function updateLightbox(index) {
            if (index < 0 || index >= totalImages) return;
            
            currentIndex = index;
            const img = imageData[index];
            
            lightboxImage.src = img.full;
            lightboxImage.alt = img.alt;
            if (lightboxTitle) {
                lightboxTitle.textContent = img.alt;
            }
            if (lightboxCounter) {
                lightboxCounter.textContent = 'Image ' + (index + 1) + ' of ' + totalImages;
            }
            if (lightboxDownload) {
                lightboxDownload.href = img.full;
            }
            
            // Update thumbnail selection
            thumbnails.forEach(function(thumb, i) {
                if (i === index) {
                    thumb.classList.add('ring-2', 'ring-primary', 'ring-offset-2', 'ring-offset-black', 'scale-105', 'opacity-100');
                    thumb.classList.remove('opacity-40');
                } else {
                    thumb.classList.remove('ring-2', 'ring-primary', 'ring-offset-2', 'ring-offset-black', 'scale-105', 'opacity-100');
                    thumb.classList.add('opacity-40');
                }
            });
        }
        
        function openLightbox(index) {
            currentIndex = index;
            updateLightbox(index);
            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');
            lightbox.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function closeLightbox() {
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
            lightbox.style.display = 'none';
            document.body.style.overflow = '';
        }
        
        function nextImage() {
            const next = (currentIndex + 1) % totalImages;
            updateLightbox(next);
        }
        
        function prevImage() {
            const prev = (currentIndex - 1 + totalImages) % totalImages;
            updateLightbox(prev);
        }
        
        // Open lightbox on image click
        // Map visible gallery images to their index in the full gallery
        galleryImages.forEach(function(img) {
            img.addEventListener('click', function(e) {
                e.preventDefault();
                const imgEl = img.querySelector('img') || img;
                const fullUrl = imgEl.getAttribute('data-full') || imgEl.src;
                
                // Find the index of this image in the full imageData array
                let clickedIndex = 0;
                for (let i = 0; i < imageData.length; i++) {
                    if (imageData[i].full === fullUrl) {
                        clickedIndex = i;
                        break;
                    }
                }
                
                openLightbox(clickedIndex);
            });
        });
        
        // Thumbnail click
        thumbnails.forEach(function(thumb, index) {
            thumb.addEventListener('click', function() {
                updateLightbox(index);
            });
        });
        
        // Navigation buttons
        if (lightboxClose) {
            lightboxClose.addEventListener('click', closeLightbox);
        }
        if (lightboxNext) {
            lightboxNext.addEventListener('click', nextImage);
        }
        if (lightboxPrev) {
            lightboxPrev.addEventListener('click', prevImage);
        }
        
        // Keyboard navigation
        function handleKeydown(e) {
            if (lightbox.classList.contains('hidden')) return;
            
            if (e.key === 'Escape') {
                closeLightbox();
            } else if (e.key === 'ArrowRight') {
                nextImage();
            } else if (e.key === 'ArrowLeft') {
                prevImage();
            }
        }
        
        // Store handler for cleanup
        lightbox._keydownHandler = handleKeydown;
        document.addEventListener('keydown', handleKeydown);
        
        // Close on background click
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
    }

})();
