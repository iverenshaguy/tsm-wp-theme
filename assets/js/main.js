/**
 * Main JavaScript file
 */

(function() {
    'use strict';

    // Restore scroll position immediately if returning from form submission
    const urlParams = new URLSearchParams(window.location.search);
    const formTypes = ['newsletter', 'contact', 'prayer', 'partner'];
    
    formTypes.forEach(function(formType) {
        if (urlParams.has(formType)) {
            const savedScrollPosition = sessionStorage.getItem(formType + 'ScrollPosition');
            if (savedScrollPosition) {
                window.scrollTo(0, parseInt(savedScrollPosition, 10));
                sessionStorage.removeItem(formType + 'ScrollPosition');
            }
        }
    });

    // DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        // Newsletter form validation functions (declared at function body root)
        function validateEmail(email, emailRegex) {
            if (!email || typeof email !== 'string') {
                return false;
            }
            return email.trim() !== '' && emailRegex.test(email.trim());
        }

        function updateButtonState(newsletterEmail, newsletterSubmit, emailRegex, showErrors) {
            const emailValue = newsletterEmail.value || '';
            const isValid = validateEmail(emailValue, emailRegex);
            
            // Disable button if email is invalid or empty
            newsletterSubmit.disabled = !isValid;
            
            // Update input border color based on validation (only show errors if showErrors is true)
            if (emailValue.trim() === '') {
                newsletterEmail.classList.remove('border-red-500');
            } else if (isValid) {
                newsletterEmail.classList.remove('border-red-500');
            } else if (showErrors) {
                newsletterEmail.classList.add('border-red-500');
            } else {
                newsletterEmail.classList.remove('border-red-500');
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

        // Mobile menu toggle - only active below lg breakpoint
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileNav = document.getElementById('mobile-nav');
        const mobileNavBackdrop = document.getElementById('mobile-nav-backdrop');
        const closeMobileMenuBtn = document.querySelector('.close-mobile-menu');
        const mobileMenuLinks = document.querySelectorAll('#mobile-nav a');
        const body = document.body;
        
        let scrollPosition = 0;
        const page = document.getElementById('page');
        
        function updateMenuState() {
            if (mobileMenuToggle && body && page) {
                if (mobileMenuToggle.checked) {
                    // Store current scroll position BEFORE locking
                    scrollPosition = window.scrollY || window.pageYOffset || document.documentElement.scrollTop;
                    // Prevent body scroll
                    body.classList.add('menu-open');
                    // Lock page position
                    page.style.top = '-' + scrollPosition + 'px';
                    // Disable interaction on page content
                    page.style.pointerEvents = 'none';
                } else {
                    // Restore body scroll
                    body.classList.remove('menu-open');
                    // Restore page position
                    page.style.top = '';
                    page.style.pointerEvents = '';
                    // Restore scroll position
                    if (scrollPosition !== undefined && scrollPosition !== null) {
                        window.scrollTo(0, scrollPosition);
                        scrollPosition = 0;
                    }
                }
            }
        }
        
        function closeMobileMenu() {
            if (mobileMenuToggle) {
                mobileMenuToggle.checked = false;
                updateMenuState();
            }
        }
        
        if (mobileMenuToggle && mobileNav) {
            // Ensure menu is hidden by default
            mobileMenuToggle.checked = false;
            updateMenuState();
            
            // Watch for checkbox changes
            mobileMenuToggle.addEventListener('change', updateMenuState);
            
            // Close menu when clicking close button (X)
            if (closeMobileMenuBtn) {
                closeMobileMenuBtn.addEventListener('click', closeMobileMenu);
            }
            
            // Close menu when clicking backdrop
            if (mobileNavBackdrop) {
                mobileNavBackdrop.addEventListener('click', closeMobileMenu);
            }
            
            // Expand submenus that should be open by default (when on submenu page)
            document.querySelectorAll('#mobile-nav .mobile-submenu.expanded').forEach(function(submenu) {
                const toggle = submenu.closest('li').querySelector('.mobile-menu-toggle');
                if (toggle) {
                    toggle.classList.add('active');
                }
            });
            
            // Handle submenu toggle buttons
            const submenuToggles = document.querySelectorAll('#mobile-nav .mobile-menu-toggle');
            submenuToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const submenuId = this.getAttribute('data-submenu');
                    const submenu = this.closest('li').querySelector('.mobile-submenu');
                    
                    if (submenu) {
                        const isExpanded = submenu.classList.contains('expanded');
                        
                        // Close all other submenus
                        document.querySelectorAll('#mobile-nav .mobile-submenu.expanded').forEach(function(menu) {
                            if (menu !== submenu) {
                                menu.classList.remove('expanded');
                                const otherToggle = menu.closest('li').querySelector('.mobile-menu-toggle');
                                if (otherToggle) {
                                    otherToggle.classList.remove('active');
                                }
                            }
                        });
                        
                        // Toggle current submenu
                        if (isExpanded) {
                            submenu.classList.remove('expanded');
                            this.classList.remove('active');
                        } else {
                            submenu.classList.add('expanded');
                            this.classList.add('active');
                        }
                    }
                });
            });
            
            // Close menu when clicking a link
            mobileMenuLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    // Don't close if clicking a submenu toggle button
                    if (!this.classList.contains('mobile-menu-toggle')) {
                        closeMobileMenu();
                    }
                });
            });
            
            // Close menu on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mobileMenuToggle.checked) {
                    closeMobileMenu();
                }
            });
            
            // Close menu when window resizes to lg or above
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    closeMobileMenu();
                }
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

            // Track if form has been attempted to submit
            let formSubmitted = false;

            // Validate on input, paste, and change events
            newsletterEmail.addEventListener('input', function() {
                hideMessage(newsletterMessage);
                updateButtonState(newsletterEmail, newsletterSubmit, emailRegex, formSubmitted);
            });
            newsletterEmail.addEventListener('paste', function() {
                setTimeout(function() {
                    hideMessage(newsletterMessage);
                    updateButtonState(newsletterEmail, newsletterSubmit, emailRegex, formSubmitted);
                }, 10);
            });
            newsletterEmail.addEventListener('blur', function() {
                // Show errors on blur
                updateButtonState(newsletterEmail, newsletterSubmit, emailRegex, true);
            });
            newsletterEmail.addEventListener('change', function() {
                updateButtonState(newsletterEmail, newsletterSubmit, emailRegex, formSubmitted);
            });

            // Initial validation
            updateButtonState(newsletterEmail, newsletterSubmit, emailRegex, false);

            // Prevent form submission if email is invalid
            newsletterForm.addEventListener('submit', function(e) {
                const emailValue = newsletterEmail.value.trim();
                const isValid = validateEmail(emailValue, emailRegex);
                
                formSubmitted = true;
                
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
            
            // Clean up URL parameter without reloading
            if (window.location.search.includes('newsletter=')) {
                const url = new URL(window.location);
                url.searchParams.delete('newsletter');
                window.history.replaceState({}, '', url);
            }

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
                            formSubmitted = false;
                            updateButtonState(newsletterEmail, newsletterSubmit, emailRegex, false);
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
                            formSubmitted = false;
                            updateButtonState(newsletterEmail, newsletterSubmit, emailRegex, false);
                        }
                    }, 500);
                });
            }
        }

        // Contact form validation and handling
        const contactForm = document.getElementById('contact-form');
        
        if (contactForm) {
            const contactName = contactForm.querySelector('#name');
            const contactEmail = contactForm.querySelector('#email');
            const contactMessage = contactForm.querySelector('#message');
            const contactSubmit = document.getElementById('contact-submit');

            if (contactName && contactEmail && contactMessage && contactSubmit) {
            // Email validation regex
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Ensure button starts disabled
            contactSubmit.disabled = true;

            // Track if form has been attempted to submit
            let formSubmitted = false;

            function validateContactForm(showErrors) {
                const nameValue = contactName.value.trim();
                const emailValue = contactEmail.value.trim();
                const messageValue = contactMessage.value.trim();
                
                const isNameValid = nameValue.length > 0;
                const isEmailValid = emailValue.length > 0 && emailRegex.test(emailValue);
                const isMessageValid = messageValue.length > 0;
                
                const isValid = isNameValid && isEmailValid && isMessageValid;
                
                // Update button state
                contactSubmit.disabled = !isValid;
                
                // Update input border colors (only show errors if showErrors is true)
                if (nameValue === '') {
                    contactName.classList.remove('border-red-500');
                } else if (!isNameValid && showErrors) {
                    contactName.classList.add('border-red-500');
                } else {
                    contactName.classList.remove('border-red-500');
                }
                
                if (emailValue === '') {
                    contactEmail.classList.remove('border-red-500');
                } else if (!isEmailValid && showErrors) {
                    contactEmail.classList.add('border-red-500');
                } else {
                    contactEmail.classList.remove('border-red-500');
                }
                
                if (messageValue === '') {
                    contactMessage.classList.remove('border-red-500');
                } else if (!isMessageValid && showErrors) {
                    contactMessage.classList.add('border-red-500');
                } else {
                    contactMessage.classList.remove('border-red-500');
                }
                
                return isValid;
            }

            // Validate on input, paste, and change events for all fields
            [contactName, contactEmail, contactMessage].forEach(function(field) {
                field.addEventListener('input', function() {
                    validateContactForm(formSubmitted);
                });
                field.addEventListener('paste', function() {
                    setTimeout(function() {
                        validateContactForm(formSubmitted);
                    }, 10);
                });
                field.addEventListener('blur', function() {
                    // Show errors on blur
                    validateContactForm(true);
                });
                field.addEventListener('change', function() {
                    validateContactForm(formSubmitted);
                });
            });

            // Initial validation
            validateContactForm(false);

            // Prevent form submission if form is invalid
            contactForm.addEventListener('submit', function(e) {
                formSubmitted = true;
                
                if (!validateContactForm(true)) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Focus first invalid field
                    if (!contactName.value.trim()) {
                        contactName.focus();
                    } else if (!contactEmail.value.trim() || !emailRegex.test(contactEmail.value.trim())) {
                        contactEmail.focus();
                    } else if (!contactMessage.value.trim()) {
                        contactMessage.focus();
                    }
                    
                    return false;
                }
                
                // Store scroll position before submission
                sessionStorage.setItem('contactScrollPosition', window.scrollY.toString());
            });
            
            // Clean up URL parameter without reloading
            if (window.location.search.includes('contact=')) {
                const url = new URL(window.location);
                url.searchParams.delete('contact');
                window.history.replaceState({}, '', url);
            }
            }
        }

        // Prayer request form validation and handling
        const prayerForm = document.getElementById('prayer-form');
        
        if (prayerForm) {
            const prayerName = prayerForm.querySelector('#name');
            const prayerEmail = prayerForm.querySelector('#email');
            const prayerMessage = prayerForm.querySelector('#message');
            const prayerSubmit = document.getElementById('prayer-submit');

            if (prayerName && prayerEmail && prayerMessage && prayerSubmit) {
                // Email validation regex
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                // Ensure button starts disabled
                prayerSubmit.disabled = true;

                // Track if form has been attempted to submit
                let formSubmitted = false;

                function validatePrayerForm(showErrors) {
                    const nameValue = prayerName.value.trim();
                    const emailValue = prayerEmail.value.trim();
                    const messageValue = prayerMessage.value.trim();
                    
                    const isNameValid = nameValue.length > 0;
                    const isEmailValid = emailValue.length > 0 && emailRegex.test(emailValue);
                    const isMessageValid = messageValue.length > 0;
                    
                    const isValid = isNameValid && isEmailValid && isMessageValid;
                    
                    // Update button state
                    prayerSubmit.disabled = !isValid;
                    
                    // Update input border colors (only show errors if showErrors is true)
                    if (nameValue === '') {
                        prayerName.classList.remove('border-red-500');
                    } else if (!isNameValid && showErrors) {
                        prayerName.classList.add('border-red-500');
                    } else {
                        prayerName.classList.remove('border-red-500');
                    }
                    
                    if (emailValue === '') {
                        prayerEmail.classList.remove('border-red-500');
                    } else if (!isEmailValid && showErrors) {
                        prayerEmail.classList.add('border-red-500');
                    } else {
                        prayerEmail.classList.remove('border-red-500');
                    }
                    
                    if (messageValue === '') {
                        prayerMessage.classList.remove('border-red-500');
                    } else if (!isMessageValid && showErrors) {
                        prayerMessage.classList.add('border-red-500');
                    } else {
                        prayerMessage.classList.remove('border-red-500');
                    }
                    
                    return isValid;
                }

                // Validate on input, paste, and change events for all fields
                [prayerName, prayerEmail, prayerMessage].forEach(function(field) {
                    field.addEventListener('input', function() {
                        validatePrayerForm(formSubmitted);
                    });
                    field.addEventListener('paste', function() {
                        setTimeout(function() {
                            validatePrayerForm(formSubmitted);
                        }, 10);
                    });
                    field.addEventListener('blur', function() {
                        // Show errors on blur
                        validatePrayerForm(true);
                    });
                    field.addEventListener('change', function() {
                        validatePrayerForm(formSubmitted);
                    });
                });

                // Initial validation
                validatePrayerForm(false);

                // Prevent form submission if form is invalid
                prayerForm.addEventListener('submit', function(e) {
                    formSubmitted = true;
                    
                    if (!validatePrayerForm(true)) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Focus first invalid field
                        if (!prayerName.value.trim()) {
                            prayerName.focus();
                        } else if (!prayerEmail.value.trim() || !emailRegex.test(prayerEmail.value.trim())) {
                            prayerEmail.focus();
                        } else if (!prayerMessage.value.trim()) {
                            prayerMessage.focus();
                        }
                        
                        return false;
                    }
                    
                    // Store scroll position before submission
                    sessionStorage.setItem('prayerScrollPosition', window.scrollY.toString());
                });
                
                // Clean up URL parameter without reloading
                if (window.location.search.includes('prayer=')) {
                    const url = new URL(window.location);
                    url.searchParams.delete('prayer');
                    window.history.replaceState({}, '', url);
                }
            }
        }

        // Decision form validation and handling (How to Know Jesus page)
        const decisionForm = document.getElementById('decision-form');
        
        if (decisionForm) {
            const decisionFirstName = decisionForm.querySelector('#first_name');
            const decisionLastName = decisionForm.querySelector('#last_name');
            const decisionEmail = decisionForm.querySelector('#email');
            const decisionSelect = decisionForm.querySelector('#decision');
            const decisionSubmit = document.getElementById('decision-submit');

            if (decisionFirstName && decisionLastName && decisionEmail && decisionSelect && decisionSubmit) {
                // Email validation regex
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                // Ensure button starts disabled
                decisionSubmit.disabled = true;

                // Track if form has been attempted to submit
                let formSubmitted = false;

                function validateDecisionForm(showErrors) {
                    const firstNameValue = decisionFirstName.value.trim();
                    const lastNameValue = decisionLastName.value.trim();
                    const emailValue = decisionEmail.value.trim();
                    const decisionValue = decisionSelect.value;
                    
                    const isFirstNameValid = firstNameValue.length > 0;
                    const isLastNameValid = lastNameValue.length > 0;
                    const isEmailValid = emailValue.length > 0 && emailRegex.test(emailValue);
                    const isDecisionValid = decisionValue.length > 0;
                    
                    const isValid = isFirstNameValid && isLastNameValid && isEmailValid && isDecisionValid;
                    
                    // Update button state
                    decisionSubmit.disabled = !isValid;
                    
                    // Update input border colors (only show errors if showErrors is true)
                    if (firstNameValue === '') {
                        decisionFirstName.classList.remove('border-red-500');
                    } else if (!isFirstNameValid && showErrors) {
                        decisionFirstName.classList.add('border-red-500');
                    } else {
                        decisionFirstName.classList.remove('border-red-500');
                    }
                    
                    if (lastNameValue === '') {
                        decisionLastName.classList.remove('border-red-500');
                    } else if (!isLastNameValid && showErrors) {
                        decisionLastName.classList.add('border-red-500');
                    } else {
                        decisionLastName.classList.remove('border-red-500');
                    }
                    
                    if (emailValue === '') {
                        decisionEmail.classList.remove('border-red-500');
                    } else if (!isEmailValid && showErrors) {
                        decisionEmail.classList.add('border-red-500');
                    } else {
                        decisionEmail.classList.remove('border-red-500');
                    }
                    
                    if (decisionValue === '') {
                        decisionSelect.classList.remove('border-red-500');
                    } else if (!isDecisionValid && showErrors) {
                        decisionSelect.classList.add('border-red-500');
                    } else {
                        decisionSelect.classList.remove('border-red-500');
                    }
                    
                    return isValid;
                }

                // Validate on input, paste, and change events for all fields
                [decisionFirstName, decisionLastName, decisionEmail].forEach(function(field) {
                    field.addEventListener('input', function() {
                        validateDecisionForm(formSubmitted);
                    });
                    field.addEventListener('paste', function() {
                        setTimeout(function() {
                            validateDecisionForm(formSubmitted);
                        }, 10);
                    });
                    field.addEventListener('blur', function() {
                        // Show errors on blur
                        validateDecisionForm(true);
                    });
                    field.addEventListener('change', function() {
                        validateDecisionForm(formSubmitted);
                    });
                });

                // Handle select element separately (uses 'change' event, not 'input')
                decisionSelect.addEventListener('change', function() {
                    validateDecisionForm(formSubmitted);
                });
                decisionSelect.addEventListener('blur', function() {
                    // Show errors on blur
                    validateDecisionForm(true);
                });

                // Initial validation
                validateDecisionForm(false);

                // Prevent form submission if form is invalid
                decisionForm.addEventListener('submit', function(e) {
                    formSubmitted = true;
                    
                    if (!validateDecisionForm(true)) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Focus first invalid field
                        if (!decisionFirstName.value.trim()) {
                            decisionFirstName.focus();
                        } else if (!decisionLastName.value.trim()) {
                            decisionLastName.focus();
                        } else if (!decisionEmail.value.trim() || !emailRegex.test(decisionEmail.value.trim())) {
                            decisionEmail.focus();
                        } else if (!decisionSelect.value) {
                            decisionSelect.focus();
                        }
                        
                        return false;
                    }
                    
                    // Store scroll position before form submission so we can restore it after redirect
                    sessionStorage.setItem('decisionScrollPosition', window.scrollY.toString());
                });
                
                // Handle success state - hide form, restore scroll position, and show download button
                if (window.location.search.includes('decision=success')) {
                    // Restore scroll position immediately
                    const savedScrollPosition = sessionStorage.getItem('decisionScrollPosition');
                    if (savedScrollPosition) {
                        // Use requestAnimationFrame to ensure DOM is ready
                        requestAnimationFrame(function() {
                            window.scrollTo(0, parseInt(savedScrollPosition, 10));
                            sessionStorage.removeItem('decisionScrollPosition');
                        });
                    }
                    
                    const form = document.getElementById('decision-form');
                    if (form) {
                        form.classList.add('hidden');
                    }
                    // Clean up URL parameter without reloading
                    const url = new URL(window.location);
                    url.searchParams.delete('decision');
                    window.history.replaceState({}, '', url);
                } else if (window.location.search.includes('decision=')) {
                    // Clean up URL parameter for error case too
                    const url = new URL(window.location);
                    url.searchParams.delete('decision');
                    window.history.replaceState({}, '', url);
                }
            }
        }

        // Partner form validation and handling
        const partnerForm = document.getElementById('partner-form');
        
        if (partnerForm) {
            const partnerFullname = partnerForm.querySelector('#fullname');
            const partnerEmail = partnerForm.querySelector('#email');
            const partnerPhone = partnerForm.querySelector('#phone');
            const partnerLocation = partnerForm.querySelector('#location');
            const partnerInterest = partnerForm.querySelector('#interest');
            const partnerSubmit = document.getElementById('partner-submit');

            if (partnerFullname && partnerEmail && partnerPhone && partnerLocation && partnerInterest && partnerSubmit) {
                // Email validation regex
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                // Ensure button starts disabled
                partnerSubmit.disabled = true;

                // Track if form has been attempted to submit
                let formSubmitted = false;

                function validatePartnerForm(showErrors) {
                    const fullnameValue = partnerFullname.value.trim();
                    const emailValue = partnerEmail.value.trim();
                    const phoneValue = partnerPhone.value.trim();
                    const locationValue = partnerLocation.value.trim();
                    const interestValue = partnerInterest.value;
                    
                    const isFullnameValid = fullnameValue.length > 0;
                    const isEmailValid = emailValue.length > 0 && emailRegex.test(emailValue);
                    const isPhoneValid = phoneValue.length > 0;
                    const isLocationValid = locationValue.length > 0;
                    const isInterestValid = interestValue.length > 0;
                    
                    const isValid = isFullnameValid && isEmailValid && isPhoneValid && isLocationValid && isInterestValid;
                    
                    // Update button state
                    partnerSubmit.disabled = !isValid;
                    
                    // Update input border colors (only show errors if showErrors is true)
                    if (fullnameValue === '') {
                        partnerFullname.classList.remove('border-red-500');
                    } else if (!isFullnameValid && showErrors) {
                        partnerFullname.classList.add('border-red-500');
                    } else {
                        partnerFullname.classList.remove('border-red-500');
                    }
                    
                    if (emailValue === '') {
                        partnerEmail.classList.remove('border-red-500');
                    } else if (!isEmailValid && showErrors) {
                        partnerEmail.classList.add('border-red-500');
                    } else {
                        partnerEmail.classList.remove('border-red-500');
                    }
                    
                    if (phoneValue === '') {
                        partnerPhone.classList.remove('border-red-500');
                    } else if (!isPhoneValid && showErrors) {
                        partnerPhone.classList.add('border-red-500');
                    } else {
                        partnerPhone.classList.remove('border-red-500');
                    }
                    
                    if (locationValue === '') {
                        partnerLocation.classList.remove('border-red-500');
                    } else if (!isLocationValid && showErrors) {
                        partnerLocation.classList.add('border-red-500');
                    } else {
                        partnerLocation.classList.remove('border-red-500');
                    }
                    
                    if (interestValue === '') {
                        partnerInterest.classList.remove('border-red-500');
                    } else if (!isInterestValid && showErrors) {
                        partnerInterest.classList.add('border-red-500');
                    } else {
                        partnerInterest.classList.remove('border-red-500');
                    }
                    
                    return isValid;
                }

                // Validate on input, paste, and change events for all fields
                [partnerFullname, partnerEmail, partnerPhone, partnerLocation].forEach(function(field) {
                    field.addEventListener('input', function() {
                        validatePartnerForm(formSubmitted);
                    });
                    field.addEventListener('paste', function() {
                        setTimeout(function() {
                            validatePartnerForm(formSubmitted);
                        }, 10);
                    });
                    field.addEventListener('blur', function() {
                        // Show errors on blur
                        validatePartnerForm(true);
                    });
                    field.addEventListener('change', function() {
                        validatePartnerForm(formSubmitted);
                    });
                });

                // Handle select element separately (uses 'change' event, not 'input')
                partnerInterest.addEventListener('change', function() {
                    validatePartnerForm(formSubmitted);
                });
                partnerInterest.addEventListener('blur', function() {
                    // Show errors on blur
                    validatePartnerForm(true);
                });

                // Initial validation
                validatePartnerForm(false);

                // Prevent form submission if form is invalid
                partnerForm.addEventListener('submit', function(e) {
                    formSubmitted = true;
                    
                    if (!validatePartnerForm(true)) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Focus first invalid field
                        if (!partnerFullname.value.trim()) {
                            partnerFullname.focus();
                        } else if (!partnerEmail.value.trim() || !emailRegex.test(partnerEmail.value.trim())) {
                            partnerEmail.focus();
                        } else if (!partnerPhone.value.trim()) {
                            partnerPhone.focus();
                        } else if (!partnerLocation.value.trim()) {
                            partnerLocation.focus();
                        } else if (!partnerInterest.value) {
                            partnerInterest.focus();
                        }
                        
                        return false;
                    }
                    
                    // Store scroll position before submission
                    sessionStorage.setItem('partnerScrollPosition', window.scrollY.toString());
                });
                
                // Clean up URL parameter without reloading
                if (window.location.search.includes('partner=')) {
                    const url = new URL(window.location);
                    url.searchParams.delete('partner');
                    window.history.replaceState({}, '', url);
                }
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
