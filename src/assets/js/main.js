/**
 * Main JavaScript file
 */

(function () {
  'use strict';

  // Prevent browser's automatic scroll restoration
  if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
  }

  // Helper function to scroll to element with responsive offset for sticky header
  function scrollToElementWithOffset(element) {
    if (!element) return;

    // Wait for next frame to ensure element is rendered
    requestAnimationFrame(function () {
      // Calculate offset based on screen size: 86px mobile, 124px large (lg breakpoint)
      const isLargeScreen = window.matchMedia('(min-width: 1024px)').matches;
      const offset = isLargeScreen ? 124 : 86;

      const elementPosition = element.getBoundingClientRect().top;
      const offsetPosition = elementPosition + window.pageYOffset - offset;

      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth',
      });
    });
  }

  // Immediately prevent scroll to top if we have a form parameter
  const urlParams = new URLSearchParams(window.location.search);
  const formTypes = ['newsletter', 'contact', 'prayer', 'partner'];

  // Lock scroll position immediately if we detect a form submission return
  let scrollLocked = false;
  formTypes.forEach(function (formType) {
    if (urlParams.has(formType)) {
      scrollLocked = true;
      // Prevent any scroll to top
      const savedScroll = sessionStorage.getItem(formType + 'ScrollPosition');
      if (savedScroll) {
        const scrollPos = parseInt(savedScroll, 10);
        if (scrollPos > 0) {
          // Lock scroll immediately
          window.scrollTo(0, scrollPos);
        }
      }
    }
  });

  function restoreScrollPosition() {
    formTypes.forEach(function (formType) {
      if (urlParams.has(formType)) {
        const savedScrollPosition = sessionStorage.getItem(formType + 'ScrollPosition');
        if (savedScrollPosition) {
          // For partner form, scroll to form section instead of exact position
          if (formType === 'partner') {
            const formSection = document.getElementById('inquiry-form');
            if (formSection) {
              window.scrollTo({
                top: formSection.offsetTop - 20,
                behavior: 'instant',
              });
            } else {
              window.scrollTo(0, parseInt(savedScrollPosition, 10));
            }
          } else if (formType === 'newsletter') {
            // For newsletter form, scroll to form section
            const newsletterForm = document.getElementById('newsletter-form');
            if (newsletterForm) {
              // Calculate form position
              const rect = newsletterForm.getBoundingClientRect();
              const formTop = rect.top + window.pageYOffset;
              const targetScroll = Math.max(0, formTop - 100); // 100px offset from top

              // Use requestAnimationFrame to ensure smooth scroll
              requestAnimationFrame(function () {
                window.scrollTo({
                  top: targetScroll,
                  behavior: 'instant',
                });
                // Also try again after a frame to ensure it sticks
                requestAnimationFrame(function () {
                  if (window.pageYOffset !== targetScroll) {
                    window.scrollTo({
                      top: targetScroll,
                      behavior: 'instant',
                    });
                  }
                });
              });
            } else {
              // Fallback to saved scroll position
              const scrollPos = parseInt(savedScrollPosition, 10);
              if (scrollPos > 0) {
                requestAnimationFrame(function () {
                  window.scrollTo(0, scrollPos);
                });
              }
            }
          } else {
            const scrollPos = parseInt(savedScrollPosition, 10);
            if (scrollPos > 0) {
              window.scrollTo(0, scrollPos);
            }
          }
          sessionStorage.removeItem(formType + 'ScrollPosition');
        }
      }
    });
  }

  // Try to restore immediately (before any other scripts can scroll)
  restoreScrollPosition();

  // Also try after DOM is ready (in case elements aren't available yet)
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      restoreScrollPosition();
      // Try again after a short delay
      setTimeout(restoreScrollPosition, 50);
    });
  } else {
    // DOM already ready, try after a short delay
    setTimeout(restoreScrollPosition, 50);
  }

  // Final attempt after page fully loads (including images)
  window.addEventListener('load', function () {
    setTimeout(restoreScrollPosition, 100);
  });

  // Initialize lazy loading fallback for older browsers
  function initLazyLoadingFallback() {
    // Check if browser supports native lazy loading
    if ('loading' in HTMLImageElement.prototype) {
      // Browser supports native lazy loading, no fallback needed
      // But still handle background images
      initBackgroundImageLazyLoading();
      return;
    }

    // Fallback: Use Intersection Observer for older browsers
    if ('IntersectionObserver' in window) {
      const lazyImages = document.querySelectorAll('img[loading="lazy"]');

      const imageObserver = new IntersectionObserver(
        function (entries, observer) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              const img = entry.target;
              if (img.dataset.src) {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
              }
              img.classList.remove('lazy');
              img.classList.add('loaded');
              observer.unobserve(img);
            }
          });
        },
        {
          rootMargin: '50px', // Start loading 50px before image enters viewport
        }
      );

      lazyImages.forEach(function (img) {
        // Convert src to data-src for lazy loading
        if (img.src && !img.dataset.src) {
          img.dataset.src = img.src;
          img.src =
            'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1 1"%3E%3C/svg%3E'; // 1x1 transparent placeholder
          img.classList.add('lazy');
          imageObserver.observe(img);
        }
      });
    }

    // Also handle background images
    initBackgroundImageLazyLoading();
  }

  // Lazy load background images
  function initBackgroundImageLazyLoading() {
    if (!('IntersectionObserver' in window)) {
      return;
    }

    const elementsWithBgImages = document.querySelectorAll('[data-bg-image]');

    if (elementsWithBgImages.length === 0) {
      return;
    }

    const bgImageObserver = new IntersectionObserver(
      function (entries, observer) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            const element = entry.target;
            const bgImageUrl = element.dataset.bgImage;

            if (bgImageUrl) {
              // Check if element already has a background-image style (e.g., with gradient overlay)
              const existingBg = element.style.backgroundImage;
              const hasGradient = existingBg && existingBg.includes('linear-gradient');

              // Preload the image
              const img = new Image();
              img.onload = function () {
                // If there's a gradient overlay, preserve it
                if (hasGradient && existingBg) {
                  // Extract the gradient part and combine with new image URL
                  // Find the gradient part before the url() in the existing style
                  const gradientMatch = existingBg.match(/^(.*?)(url\([^)]+\))/);
                  if (gradientMatch && gradientMatch[1]) {
                    // Replace the URL part but keep the gradient
                    element.style.backgroundImage = gradientMatch[1] + 'url(' + bgImageUrl + ')';
                  } else {
                    // Fallback: use existing style if we can't parse it
                    element.style.backgroundImage = existingBg.replace(
                      /url\([^)]+\)/,
                      'url(' + bgImageUrl + ')'
                    );
                  }
                } else {
                  // No gradient, just set the image
                  element.style.backgroundImage = 'url(' + bgImageUrl + ')';
                }
                element.classList.add('bg-loaded');
                element.removeAttribute('data-bg-image');
              };
              img.src = bgImageUrl;
            }

            observer.unobserve(element);
          }
        });
      },
      {
        rootMargin: '100px', // Start loading 100px before element enters viewport
      }
    );

    elementsWithBgImages.forEach(function (element) {
      // Skip elements that already have background-image set (they're already loaded)
      if (element.style.backgroundImage) {
        element.removeAttribute('data-bg-image');
        return;
      }
      bgImageObserver.observe(element);
    });
  }

  // DOM ready
  document.addEventListener('DOMContentLoaded', function () {
    // Initialize lazy loading fallback
    initLazyLoadingFallback();

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
        setTimeout(function () {
          newsletterMessage.remove();
        }, 300);
      }
    }

    function showNewsletterMessage(type, message) {
      // Remove existing message if any
      const existingMessage = document.getElementById('newsletter-message');
      if (existingMessage) {
        hideMessage(existingMessage);
      }

      // Create new message element
      const messageDiv = document.createElement('div');
      messageDiv.id = 'newsletter-message';
      messageDiv.className =
        'newsletter-message newsletter-' +
        type +
        ' mb-6 p-6 rounded-2xl text-center max-w-lg mx-auto shadow-lg flex items-center justify-center gap-3';

      if (type === 'success') {
        messageDiv.classList.add(
          'bg-gradient-to-r',
          'from-green-50',
          'to-emerald-50',
          'dark:from-green-900/30',
          'dark:to-emerald-900/30',
          'border-2',
          'border-green-300',
          'dark:border-green-700',
          'text-green-800',
          'dark:text-green-200'
        );
        const icon = document.createElement('span');
        icon.className = 'material-symbols-outlined text-3xl';
        icon.textContent = 'check_circle';
        const contentDiv = document.createElement('div');
        const title = document.createElement('p');
        title.className = 'font-bold text-lg mb-1';
        title.textContent = 'Thank you for subscribing!';
        const msg = document.createElement('p');
        msg.className = 'text-sm opacity-90';
        msg.textContent = message;
        contentDiv.appendChild(title);
        contentDiv.appendChild(msg);
        messageDiv.appendChild(icon);
        messageDiv.appendChild(contentDiv);
      } else {
        messageDiv.classList.add(
          'bg-gradient-to-r',
          'from-red-50',
          'to-rose-50',
          'dark:from-red-900/30',
          'dark:to-rose-900/30',
          'border-2',
          'border-red-300',
          'dark:border-red-700',
          'text-red-800',
          'dark:text-red-200'
        );
        const icon = document.createElement('span');
        icon.className = 'material-symbols-outlined text-3xl';
        icon.textContent = 'error';
        const contentDiv = document.createElement('div');
        const title = document.createElement('p');
        title.className = 'font-bold text-lg mb-1';
        title.textContent = 'Oops!';
        const msg = document.createElement('p');
        msg.className = 'text-sm opacity-90';
        msg.textContent = message;
        contentDiv.appendChild(title);
        contentDiv.appendChild(msg);
        messageDiv.appendChild(icon);
        messageDiv.appendChild(contentDiv);
      }

      // Insert message before form
      newsletterForm.parentNode.insertBefore(messageDiv, newsletterForm);

      // Scroll to message with offset for sticky header
      scrollToElementWithOffset(messageDiv);

      // Animate in
      requestAnimationFrame(function () {
        messageDiv.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
        messageDiv.style.opacity = '1';
        messageDiv.style.transform = 'translateY(0)';
      });

      // Auto-hide success message after 30 seconds
      if (type === 'success') {
        setTimeout(function () {
          hideMessage(messageDiv);
        }, 30000);
      }

      // Make message clickable to dismiss
      messageDiv.addEventListener('click', function () {
        hideMessage(messageDiv);
        if (type === 'success') {
          newsletterForm.reset();
          formSubmitted = false;
          updateButtonState(newsletterEmail, newsletterSubmit, emailRegex, false);
        }
      });
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
          scrollPosition =
            window.scrollY || window.pageYOffset || document.documentElement.scrollTop;
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
      // Restore expanded state for all submenus (including nested ones)
      document.querySelectorAll('#mobile-nav .mobile-submenu.expanded').forEach(function (submenu) {
        // Remove max-h-0 to ensure visibility
        submenu.classList.remove('max-h-0');
        const toggle = submenu.closest('li').querySelector('.mobile-menu-toggle');
        if (toggle) {
          toggle.classList.add('active');
        }
      });

      // Also expand parent menus if they contain active menu items
      // This handles cases where WordPress doesn't add the 'expanded' class initially
      const activeItems = document.querySelectorAll(
        '#mobile-nav li.current-menu-item, #mobile-nav li.current-menu-ancestor, #mobile-nav li.current-menu-parent, #mobile-nav li.current_page_item, #mobile-nav li.current_page_ancestor, #mobile-nav li.current_page_parent'
      );

      activeItems.forEach(function (activeLi) {
        // Expand all parent submenus by traversing up the DOM tree
        let currentLi = activeLi;

        while (currentLi) {
          // Find the parent <ul> that contains this <li>
          const parentUl = currentLi.parentElement;

          if (parentUl && parentUl.classList.contains('mobile-submenu')) {
            // This <li> is inside a submenu, so find the parent <li> that contains this submenu
            const grandparentLi = parentUl.closest('li.mobile-menu-item');

            if (grandparentLi) {
              // Find the submenu and toggle button in the grandparent
              const parentSubmenu = grandparentLi.querySelector('.mobile-submenu');
              const parentToggle = grandparentLi.querySelector('.mobile-menu-toggle');

              if (parentSubmenu && parentToggle) {
                parentSubmenu.classList.add('expanded');
                parentSubmenu.classList.remove('max-h-0');
                parentToggle.classList.add('active');
              }

              // Continue traversing up
              currentLi = grandparentLi;
            } else {
              break;
            }
          } else {
            break;
          }
        }
      });

      // Handle submenu toggle buttons (supports nested submenus)
      const submenuToggles = document.querySelectorAll('#mobile-nav .mobile-menu-toggle');
      submenuToggles.forEach(function (toggle) {
        toggle.addEventListener('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
          const submenuId = this.getAttribute('data-submenu');
          const itemId = this.getAttribute('data-item-id') || submenuId.replace('submenu-', '');
          const parentLi = this.closest('li');

          // Find submenu - try multiple methods
          let submenu = null;

          // Method 1: Find the first .mobile-submenu that's a direct child of this li (most reliable)
          const directChildren = Array.from(parentLi.children);
          for (let i = 0; i < directChildren.length; i++) {
            const child = directChildren[i];
            if (child.tagName === 'UL' && child.classList.contains('mobile-submenu')) {
              submenu = child;
              break;
            }
          }

          // Method 2: Find by data-parent-id attribute (for nested menus)
          if (!submenu) {
            submenu = parentLi.querySelector('.mobile-submenu[data-parent-id="' + itemId + '"]');
          }

          // Method 3: Find any .mobile-submenu within this li (fallback)
          if (!submenu) {
            submenu = parentLi.querySelector('.mobile-submenu');
          }

          const depth = parseInt(this.getAttribute('data-depth') || '0', 10);

          if (submenu) {
            const isExpanded = submenu.classList.contains('expanded');
            const parentLi = this.closest('li');

            if (isExpanded) {
              // Closing: Close this submenu and all nested submenus within it
              submenu.classList.remove('expanded');
              // Add max-h-0 back for animation
              submenu.classList.add('max-h-0');
              this.classList.remove('active');

              // Close all nested submenus within this one
              const nestedSubmenus = submenu.querySelectorAll('.mobile-submenu.expanded');
              nestedSubmenus.forEach(function (nestedSubmenu) {
                nestedSubmenu.classList.remove('expanded');
                nestedSubmenu.classList.add('max-h-0');
                const nestedToggle = nestedSubmenu
                  .closest('li')
                  .querySelector('.mobile-menu-toggle');
                if (nestedToggle) {
                  nestedToggle.classList.remove('active');
                }
              });
            } else {
              // Opening: Close sibling submenus at the same depth level
              // This allows nested submenus to remain open when parent opens
              const parentUl = parentLi.parentElement;
              if (parentUl) {
                // For top-level menus, parentUl is the main <ul>, not a submenu
                // For nested menus, parentUl is a .mobile-submenu
                const isNestedMenu = parentUl.classList.contains('mobile-submenu');

                if (isNestedMenu || depth === 0) {
                  // Find siblings at the same level
                  const siblings = Array.from(parentUl.children);
                  siblings.forEach(function (sibling) {
                    if (sibling !== parentLi && sibling.tagName === 'LI') {
                      const siblingSubmenu = sibling.querySelector('.mobile-submenu');
                      const siblingToggle = sibling.querySelector('.mobile-menu-toggle');

                      // Only close if it's at the same depth level
                      if (siblingSubmenu && siblingToggle) {
                        const siblingDepth = parseInt(
                          siblingToggle.getAttribute('data-depth') || '0',
                          10
                        );
                        if (siblingDepth === depth) {
                          siblingSubmenu.classList.remove('expanded');
                          siblingSubmenu.classList.add('max-h-0');
                          siblingToggle.classList.remove('active');

                          // Also close any nested submenus within the sibling
                          const nestedSubmenus = siblingSubmenu.querySelectorAll(
                            '.mobile-submenu.expanded'
                          );
                          nestedSubmenus.forEach(function (nestedSubmenu) {
                            nestedSubmenu.classList.remove('expanded');
                            nestedSubmenu.classList.add('max-h-0');
                            const nestedToggle = nestedSubmenu
                              .closest('li')
                              .querySelector('.mobile-menu-toggle');
                            if (nestedToggle) {
                              nestedToggle.classList.remove('active');
                            }
                          });
                        }
                      }
                    }
                  });
                }
              }

              // Open current submenu
              submenu.classList.add('expanded');
              // Remove max-h-0 class to ensure visibility (override Tailwind)
              submenu.classList.remove('max-h-0');
              this.classList.add('active');
            }
          } else {
            // Submenu not found for toggle
          }
        });
      });

      // Close menu when clicking a link
      mobileMenuLinks.forEach(function (link) {
        link.addEventListener('click', function () {
          // Don't close if clicking a submenu toggle button
          if (!this.classList.contains('mobile-menu-toggle')) {
            closeMobileMenu();
          }
        });
      });

      // Close menu on Escape key
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && mobileMenuToggle.checked) {
          closeMobileMenu();
        }
      });

      // Close menu when window resizes to lg or above
      window.addEventListener('resize', function () {
        if (window.innerWidth >= 1024) {
          closeMobileMenu();
        }
      });
    }

    // Smooth scroll for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(function (link) {
      link.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && href.length > 1) {
          const target = document.querySelector(href);
          if (target) {
            e.preventDefault();
            target.scrollIntoView({
              behavior: 'smooth',
              block: 'start',
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
      newsletterEmail.addEventListener('input', function () {
        hideMessage(newsletterMessage);
        updateButtonState(newsletterEmail, newsletterSubmit, emailRegex, formSubmitted);
      });
      newsletterEmail.addEventListener('paste', function () {
        setTimeout(function () {
          hideMessage(newsletterMessage);
          updateButtonState(newsletterEmail, newsletterSubmit, emailRegex, formSubmitted);
        }, 10);
      });
      newsletterEmail.addEventListener('blur', function () {
        // Show errors on blur
        updateButtonState(newsletterEmail, newsletterSubmit, emailRegex, true);
      });
      newsletterEmail.addEventListener('change', function () {
        updateButtonState(newsletterEmail, newsletterSubmit, emailRegex, formSubmitted);
      });

      // Initial validation
      updateButtonState(newsletterEmail, newsletterSubmit, emailRegex, false);

      // Handle form submission via AJAX
      newsletterForm.addEventListener('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const emailValue = newsletterEmail.value.trim();
        const isValid = validateEmail(emailValue, emailRegex);

        formSubmitted = true;

        if (!isValid) {
          // Show error feedback
          newsletterEmail.focus();
          newsletterEmail.classList.add('border-red-500');
          showNewsletterMessage('error', 'Please enter a valid email address.');
          return false;
        }

        // Disable button and show loading state
        newsletterSubmit.disabled = true;
        const originalText = newsletterSubmit.innerHTML;
        newsletterSubmit.innerHTML =
          '<span class="material-symbols-outlined animate-spin !text-base mr-2">sync</span> Submitting...';
        newsletterSubmit.classList.add('opacity-75', 'cursor-not-allowed');

        // Hide any existing messages
        hideMessage(newsletterMessage);

        // Submit via AJAX
        if (window.tsmNewsletter && window.tsmNewsletter.ajaxUrl && window.tsmNewsletter.nonce) {
          const formData = new FormData();
          formData.append('action', 'tsm_newsletter_signup');
          formData.append('nonce', window.tsmNewsletter.nonce);
          formData.append('email', emailValue);

          fetch(window.tsmNewsletter.ajaxUrl, {
            method: 'POST',
            body: formData,
          })
            .then(function (response) {
              return response.json();
            })
            .then(function (data) {
              // Reset button
              newsletterSubmit.disabled = false;
              newsletterSubmit.innerHTML = originalText;
              newsletterSubmit.classList.remove('opacity-75', 'cursor-not-allowed');

              if (data.success) {
                // Show success message
                showNewsletterMessage('success', data.data.message || 'Thank you for subscribing!');
                // Reset form
                newsletterForm.reset();
                formSubmitted = false;
                updateButtonState(newsletterEmail, newsletterSubmit, emailRegex, false);
              } else {
                // Show error message
                showNewsletterMessage(
                  'error',
                  data.data.message || 'Something went wrong. Please try again.'
                );
                newsletterEmail.focus();
              }
            })
            .catch(function (error) {
              // Reset button
              newsletterSubmit.disabled = false;
              newsletterSubmit.innerHTML = originalText;
              newsletterSubmit.classList.remove('opacity-75', 'cursor-not-allowed');
              // Show error message
              showNewsletterMessage('error', 'Something went wrong. Please try again.');
              console.error('Newsletter submission error:', error);
            });
        } else {
          // Fallback: submit normally if AJAX not available
          newsletterForm.submit();
        }
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
        const hideTimeout = setTimeout(function () {
          newsletterMessage.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
          newsletterMessage.style.opacity = '0';
          newsletterMessage.style.transform = 'translateY(-20px)';

          setTimeout(function () {
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
        newsletterMessage.addEventListener('click', function () {
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

    // Contact form helper functions
    function showContactSuccessMessage(message) {
      const messageContainer = document.getElementById('contact-message-container');
      if (!messageContainer) return;

      messageContainer.classList.remove('hidden');
      const messageDiv = document.createElement('div');
      messageDiv.className =
        'p-6 rounded-2xl bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 border-2 border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 text-center flex items-center justify-center gap-3';

      const icon = document.createElement('span');
      icon.className = 'material-symbols-outlined text-3xl';
      icon.textContent = 'check_circle';
      const contentDiv = document.createElement('div');
      const title = document.createElement('p');
      title.className = 'font-bold text-lg mb-1';
      title.textContent = 'Message Sent!';
      const msg = document.createElement('p');
      msg.className = 'text-sm opacity-90';
      msg.textContent = message;
      contentDiv.appendChild(title);
      contentDiv.appendChild(msg);
      messageDiv.appendChild(icon);
      messageDiv.appendChild(contentDiv);
      messageContainer.appendChild(messageDiv);

      // Scroll to message container with offset for sticky header
      scrollToElementWithOffset(messageContainer);

      // Auto-hide after 30 seconds
      setTimeout(function () {
        messageContainer.classList.add('hidden');
        messageContainer.innerHTML = '';
      }, 30000);
    }

    function showContactErrorMessage(message) {
      const messageContainer = document.getElementById('contact-message-container');
      if (!messageContainer) return;

      messageContainer.classList.remove('hidden');
      const messageDiv = document.createElement('div');
      messageDiv.className =
        'p-4 rounded-xl bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200';
      messageDiv.textContent = message;
      messageContainer.innerHTML = '';
      messageContainer.appendChild(messageDiv);

      // Scroll to message container with offset for sticky header
      scrollToElementWithOffset(messageContainer);
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

        const validateContactForm = function (showErrors) {
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
        };

        // Validate on input, paste, and change events for all fields
        [contactName, contactEmail, contactMessage].forEach(function (field) {
          field.addEventListener('input', function () {
            validateContactForm(formSubmitted);
          });
          field.addEventListener('paste', function () {
            setTimeout(function () {
              validateContactForm(formSubmitted);
            }, 10);
          });
          field.addEventListener('blur', function () {
            // Show errors on blur
            validateContactForm(true);
          });
          field.addEventListener('change', function () {
            validateContactForm(formSubmitted);
          });
        });

        // Initial validation
        validateContactForm(false);

        // Handle form submission via AJAX
        contactForm.addEventListener('submit', function (e) {
          e.preventDefault();
          e.stopPropagation();

          formSubmitted = true;

          if (!validateContactForm(true)) {
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

          // Disable button and show loading state
          contactSubmit.disabled = true;
          const originalText = contactSubmit.innerHTML;
          contactSubmit.innerHTML =
            '<span class="material-symbols-outlined animate-spin !text-base mr-2">sync</span> Sending...';
          contactSubmit.classList.add('opacity-75', 'cursor-not-allowed');

          // Hide any existing messages
          const messageContainer = document.getElementById('contact-message-container');
          if (messageContainer) {
            messageContainer.classList.add('hidden');
            messageContainer.innerHTML = '';
          }

          // Submit via AJAX
          if (window.tsmContact && window.tsmContact.ajaxUrl && window.tsmContact.nonce) {
            const contactSubject = contactForm.querySelector('#subject');
            const formData = new FormData();
            formData.append('action', 'tsm_contact_form');
            formData.append('nonce', window.tsmContact.nonce);
            formData.append('name', contactName.value.trim());
            formData.append('email', contactEmail.value.trim());
            formData.append('subject', contactSubject ? contactSubject.value : 'general');
            formData.append('message', contactMessage.value.trim());

            fetch(window.tsmContact.ajaxUrl, {
              method: 'POST',
              body: formData,
            })
              .then(function (response) {
                return response.json();
              })
              .then(function (data) {
                // Reset button
                contactSubmit.disabled = false;
                contactSubmit.innerHTML = originalText;
                contactSubmit.classList.remove('opacity-75', 'cursor-not-allowed');

                if (data.success) {
                  // Show success message
                  showContactSuccessMessage(
                    data.data.message || "Thank you for contacting us! We'll get back to you soon."
                  );
                  // Reset form
                  contactForm.reset();
                  formSubmitted = false;
                  validateContactForm(false);
                } else {
                  // Show error message
                  showContactErrorMessage(
                    data.data.message || 'Something went wrong. Please try again.'
                  );
                }
              })
              .catch(function (error) {
                // Reset button
                contactSubmit.disabled = false;
                contactSubmit.innerHTML = originalText;
                contactSubmit.classList.remove('opacity-75', 'cursor-not-allowed');
                // Show error message
                showContactErrorMessage('Something went wrong. Please try again.');
                console.error('Contact form submission error:', error);
              });
          } else {
            // Fallback: submit normally if AJAX not available
            contactForm.submit();
          }
        });

        // Clean up URL parameter without reloading
        if (window.location.search.includes('contact=')) {
          const url = new URL(window.location);
          url.searchParams.delete('contact');
          window.history.replaceState({}, '', url);
        }
      }
    }

    // Prayer form helper functions
    function showPrayerSuccessMessage(message) {
      const messageContainer = document.getElementById('prayer-message-container');
      if (!messageContainer) return;

      messageContainer.classList.remove('hidden');
      const messageDiv = document.createElement('div');
      messageDiv.className =
        'p-6 rounded-2xl bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 border-2 border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 text-center flex items-center justify-center gap-3';

      const icon = document.createElement('span');
      icon.className = 'material-symbols-outlined text-3xl';
      icon.textContent = 'check_circle';
      const contentDiv = document.createElement('div');
      const title = document.createElement('p');
      title.className = 'font-bold text-lg mb-1';
      title.textContent = 'Prayer Request Received!';
      const msg = document.createElement('p');
      msg.className = 'text-sm opacity-90';
      msg.textContent = message;
      contentDiv.appendChild(title);
      contentDiv.appendChild(msg);
      messageDiv.appendChild(icon);
      messageDiv.appendChild(contentDiv);
      messageContainer.appendChild(messageDiv);

      // Scroll to message container with offset for sticky header
      scrollToElementWithOffset(messageContainer);

      // Auto-hide after 30 seconds
      setTimeout(function () {
        messageContainer.classList.add('hidden');
        messageContainer.innerHTML = '';
      }, 30000);
    }

    function showPrayerErrorMessage(message) {
      const messageContainer = document.getElementById('prayer-message-container');
      if (!messageContainer) return;

      messageContainer.classList.remove('hidden');
      const messageDiv = document.createElement('div');
      messageDiv.className =
        'p-4 rounded-xl bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200';
      messageDiv.textContent = message;
      messageContainer.innerHTML = '';
      messageContainer.appendChild(messageDiv);

      // Scroll to message container with offset for sticky header
      scrollToElementWithOffset(messageContainer);
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

        const validatePrayerForm = function (showErrors) {
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
        };

        // Validate on input, paste, and change events for all fields
        [prayerName, prayerEmail, prayerMessage].forEach(function (field) {
          field.addEventListener('input', function () {
            validatePrayerForm(formSubmitted);
          });
          field.addEventListener('paste', function () {
            setTimeout(function () {
              validatePrayerForm(formSubmitted);
            }, 10);
          });
          field.addEventListener('blur', function () {
            // Show errors on blur
            validatePrayerForm(true);
          });
          field.addEventListener('change', function () {
            validatePrayerForm(formSubmitted);
          });
        });

        // Initial validation
        validatePrayerForm(false);

        // Handle form submission via AJAX
        prayerForm.addEventListener('submit', function (e) {
          e.preventDefault();
          e.stopPropagation();

          formSubmitted = true;

          if (!validatePrayerForm(true)) {
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

          // Disable button and show loading state
          prayerSubmit.disabled = true;
          const originalText = prayerSubmit.innerHTML;
          prayerSubmit.innerHTML =
            '<span class="material-symbols-outlined animate-spin !text-base mr-2">sync</span> Submitting...';
          prayerSubmit.classList.add('opacity-75', 'cursor-not-allowed');

          // Hide any existing messages
          const messageContainer = document.getElementById('prayer-message-container');
          if (messageContainer) {
            messageContainer.classList.add('hidden');
            messageContainer.innerHTML = '';
          }

          // Submit via AJAX
          if (window.tsmPrayer && window.tsmPrayer.ajaxUrl && window.tsmPrayer.nonce) {
            const requestType = prayerForm.querySelector('#request-type');
            const confidential = prayerForm.querySelector('#confidential');
            const formData = new FormData();
            formData.append('action', 'tsm_prayer_request');
            formData.append('nonce', window.tsmPrayer.nonce);
            formData.append('name', prayerName.value.trim());
            formData.append('email', prayerEmail.value.trim());
            formData.append('request-type', requestType ? requestType.value : '');
            formData.append('message', prayerMessage.value.trim());
            formData.append('confidential', confidential && confidential.checked ? '1' : '0');

            fetch(window.tsmPrayer.ajaxUrl, {
              method: 'POST',
              body: formData,
            })
              .then(function (response) {
                return response.json();
              })
              .then(function (data) {
                // Reset button
                prayerSubmit.disabled = false;
                prayerSubmit.innerHTML = originalText;
                prayerSubmit.classList.remove('opacity-75', 'cursor-not-allowed');

                if (data.success) {
                  // Show success message
                  showPrayerSuccessMessage(
                    data.data.message ||
                      "Thank you for sharing your prayer request. We'll be praying for you!"
                  );
                  // Reset form
                  prayerForm.reset();
                  formSubmitted = false;
                  validatePrayerForm(false);
                } else {
                  // Show error message
                  showPrayerErrorMessage(
                    data.data.message || 'Something went wrong. Please try again.'
                  );
                }
              })
              .catch(function (error) {
                // Reset button
                prayerSubmit.disabled = false;
                prayerSubmit.innerHTML = originalText;
                prayerSubmit.classList.remove('opacity-75', 'cursor-not-allowed');
                // Show error message
                showPrayerErrorMessage('Something went wrong. Please try again.');
                console.error('Prayer form submission error:', error);
              });
          } else {
            // Fallback: submit normally if AJAX not available
            prayerForm.submit();
          }
        });

        // Clean up URL parameter without reloading
        if (window.location.search.includes('prayer=')) {
          const url = new URL(window.location);
          url.searchParams.delete('prayer');
          window.history.replaceState({}, '', url);
        }
      }
    }

    // Decision form helper functions
    function showDecisionSuccessMessage(message, downloadFile, downloadBenefit) {
      const messageContainer = document.getElementById('decision-message-container');
      if (!messageContainer) return;

      messageContainer.classList.remove('hidden');
      const messageDiv = document.createElement('div');
      messageDiv.className =
        'p-6 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-xl';

      const text = document.createElement('p');
      text.className = 'text-green-800 dark:text-green-200 mb-4 text-lg font-medium';
      text.textContent = message;
      messageDiv.appendChild(text);

      if (downloadFile && downloadBenefit) {
        const downloadLink = document.createElement('a');
        downloadLink.href = downloadFile;
        downloadLink.download = '';
        downloadLink.className =
          'inline-flex items-center gap-2 bg-primary text-white hover:text-white font-bold px-6 py-3 rounded-lg shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all';
        const icon = document.createElement('span');
        icon.className = 'material-symbols-outlined';
        icon.textContent = 'download';
        downloadLink.appendChild(icon);
        downloadLink.appendChild(document.createTextNode(downloadBenefit));
        messageDiv.appendChild(downloadLink);
      }

      messageContainer.appendChild(messageDiv);
      decisionForm.classList.add('hidden');

      // Scroll to message container with offset for sticky header
      scrollToElementWithOffset(messageContainer);
    }

    function showDecisionErrorMessage(message) {
      const messageContainer = document.getElementById('decision-message-container');
      if (!messageContainer) return;

      messageContainer.classList.remove('hidden');
      const messageDiv = document.createElement('div');
      messageDiv.className =
        'p-4 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-xl text-red-800 dark:text-red-200';
      messageDiv.textContent = message;
      messageContainer.innerHTML = '';
      messageContainer.appendChild(messageDiv);

      // Scroll to message container with offset for sticky header
      scrollToElementWithOffset(messageContainer);
    }

    // Decision form validation and handling (How to Know Jesus page)
    const decisionForm = document.getElementById('decision-form');

    if (decisionForm) {
      const decisionFirstName = decisionForm.querySelector('#first_name');
      const decisionLastName = decisionForm.querySelector('#last_name');
      const decisionEmail = decisionForm.querySelector('#email');
      const decisionSelect = decisionForm.querySelector('#decision');
      const decisionSubmit = document.getElementById('decision-submit');

      if (
        decisionFirstName &&
        decisionLastName &&
        decisionEmail &&
        decisionSelect &&
        decisionSubmit
      ) {
        // Email validation regex
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Ensure button starts disabled
        decisionSubmit.disabled = true;

        // Track if form has been attempted to submit
        let formSubmitted = false;

        const validateDecisionForm = function (showErrors) {
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
        };

        // Validate on input, paste, and change events for all fields
        [decisionFirstName, decisionLastName, decisionEmail].forEach(function (field) {
          field.addEventListener('input', function () {
            validateDecisionForm(formSubmitted);
          });
          field.addEventListener('paste', function () {
            setTimeout(function () {
              validateDecisionForm(formSubmitted);
            }, 10);
          });
          field.addEventListener('blur', function () {
            // Show errors on blur
            validateDecisionForm(true);
          });
          field.addEventListener('change', function () {
            validateDecisionForm(formSubmitted);
          });
        });

        // Handle select element separately (uses 'change' event, not 'input')
        decisionSelect.addEventListener('change', function () {
          validateDecisionForm(formSubmitted);
        });
        decisionSelect.addEventListener('blur', function () {
          // Show errors on blur
          validateDecisionForm(true);
        });

        // Initial validation
        validateDecisionForm(false);

        // Handle form submission via AJAX
        decisionForm.addEventListener('submit', function (e) {
          e.preventDefault();
          e.stopPropagation();

          formSubmitted = true;

          if (!validateDecisionForm(true)) {
            // Focus first invalid field
            if (!decisionFirstName.value.trim()) {
              decisionFirstName.focus();
            } else if (!decisionLastName.value.trim()) {
              decisionLastName.focus();
            } else if (
              !decisionEmail.value.trim() ||
              !emailRegex.test(decisionEmail.value.trim())
            ) {
              decisionEmail.focus();
            } else if (!decisionSelect.value) {
              decisionSelect.focus();
            }

            return false;
          }

          // Disable button and show loading state
          decisionSubmit.disabled = true;
          const originalText = decisionSubmit.innerHTML;
          decisionSubmit.innerHTML =
            '<span class="material-symbols-outlined animate-spin !text-base mr-2">sync</span> Submitting...';
          decisionSubmit.classList.add('opacity-75', 'cursor-not-allowed');

          // Hide any existing messages
          const messageContainer = document.getElementById('decision-message-container');
          if (messageContainer) {
            messageContainer.classList.add('hidden');
            messageContainer.innerHTML = '';
          }

          // Get download file info from form data attributes
          const downloadFile = decisionForm.dataset.downloadFile || '';
          const downloadBenefit = decisionForm.dataset.downloadBenefit || '';

          // Submit via AJAX
          if (window.tsmDecision && window.tsmDecision.ajaxUrl && window.tsmDecision.nonce) {
            const decisionMessage = decisionForm.querySelector('#message');
            const formData = new FormData();
            formData.append('action', 'tsm_decision_form');
            formData.append('nonce', window.tsmDecision.nonce);
            formData.append('first_name', decisionFirstName.value.trim());
            formData.append('last_name', decisionLastName.value.trim());
            formData.append('email', decisionEmail.value.trim());
            formData.append('decision', decisionSelect.value);
            if (decisionMessage && decisionMessage.value.trim()) {
              formData.append('message', decisionMessage.value.trim());
            }

            fetch(window.tsmDecision.ajaxUrl, {
              method: 'POST',
              body: formData,
            })
              .then(function (response) {
                return response.json();
              })
              .then(function (data) {
                // Reset button
                decisionSubmit.disabled = false;
                decisionSubmit.innerHTML = originalText;
                decisionSubmit.classList.remove('opacity-75', 'cursor-not-allowed');

                if (data.success) {
                  // Show success message with download link
                  showDecisionSuccessMessage(
                    data.data.message ||
                      "Thank you! We've received your decision and will be in touch soon.",
                    downloadFile,
                    downloadBenefit
                  );
                  // Reset form
                  decisionForm.reset();
                  formSubmitted = false;
                  validateDecisionForm(false);
                } else {
                  // Show error message
                  showDecisionErrorMessage(
                    data.data.message || 'Something went wrong. Please try again.'
                  );
                }
              })
              .catch(function (error) {
                // Reset button
                decisionSubmit.disabled = false;
                decisionSubmit.innerHTML = originalText;
                decisionSubmit.classList.remove('opacity-75', 'cursor-not-allowed');
                // Show error message
                showDecisionErrorMessage('Something went wrong. Please try again.');
                console.error('Decision form submission error:', error);
              });
          } else {
            // Fallback: submit normally if AJAX not available
            decisionForm.submit();
          }
        });

        // Clean up URL parameter without reloading (for legacy redirects)
        if (window.location.search.includes('decision=')) {
          const url = new URL(window.location);
          url.searchParams.delete('decision');
          window.history.replaceState({}, '', url);
        }
      }
    }

    // Partner form helper functions
    function showPartnerSuccessMessage(message, hasAccountDetails) {
      const messageContainer = document.getElementById('partner-message-container');
      if (!messageContainer) return;

      messageContainer.classList.remove('hidden');

      let html =
        '<div class="space-y-6 md:col-span-2">' +
        '<div class="p-6 text-center text-green-800 bg-green-100 rounded-xl border border-green-300 dark:bg-green-900/30 dark:border-green-700 dark:text-green-200">' +
        '<span class="block mb-4 text-5xl material-symbols-outlined">check_circle</span>' +
        '<h4 class="mb-2 text-xl font-bold">Thank You!</h4>' +
        '<p>' +
        message +
        '</p>' +
        '</div>';

      if (hasAccountDetails) {
        html +=
          '<div class="flex justify-center">' +
          '<button type="button" id="open-account-modal" class="inline-flex gap-2 justify-center items-center px-6 py-3 text-base font-bold text-white rounded-lg shadow-lg transition-all bg-primary hover:text-white shadow-primary/20 hover:shadow-primary/40">' +
          '<span class="material-symbols-outlined">account_balance</span>' +
          'View Account Details' +
          '</button>' +
          '</div>';
      }

      html += '</div>';
      messageContainer.innerHTML = html;

      // Re-attach modal button handler if it exists
      const openButton = document.getElementById('open-account-modal');
      if (openButton) {
        if (typeof window.openAccountModal === 'function') {
          openButton.addEventListener('click', window.openAccountModal);
        } else {
          // Try to find modal and open it directly
          const modal = document.getElementById('account-details-modal');
          if (modal) {
            openButton.addEventListener('click', function () {
              modal.classList.remove('hidden');
              modal.classList.add('flex');
              document.body.style.overflow = 'hidden';
            });
          }
        }
      }

      // Auto-open modal if account details exist
      if (hasAccountDetails) {
        setTimeout(function () {
          if (typeof window.openAccountModal === 'function') {
            window.openAccountModal();
          } else {
            // Fallback: open modal directly
            const modal = document.getElementById('account-details-modal');
            if (modal) {
              modal.classList.remove('hidden');
              modal.classList.add('flex');
              document.body.style.overflow = 'hidden';
            }
          }
        }, 300);
      }

      // Scroll to message container with offset for sticky header
      scrollToElementWithOffset(messageContainer);
    }

    function showPartnerErrorMessage(message) {
      const messageContainer = document.getElementById('partner-message-container');
      if (!messageContainer) return;

      messageContainer.classList.remove('hidden');
      messageContainer.innerHTML =
        '<div class="p-4 mb-6 text-red-800 bg-red-100 rounded-xl border border-red-300 md:col-span-2 dark:bg-red-900/30 dark:border-red-700 dark:text-red-200">' +
        message +
        '</div>';

      // Scroll to message container with offset for sticky header
      scrollToElementWithOffset(messageContainer);
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

      if (
        partnerFullname &&
        partnerEmail &&
        partnerPhone &&
        partnerLocation &&
        partnerInterest &&
        partnerSubmit
      ) {
        // Email validation regex
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Ensure button starts disabled
        partnerSubmit.disabled = true;

        // Track if form has been attempted to submit
        let formSubmitted = false;

        const validatePartnerForm = function (showErrors) {
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

          const isValid =
            isFullnameValid && isEmailValid && isPhoneValid && isLocationValid && isInterestValid;

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
        };

        // Validate on input, paste, and change events for all fields
        [partnerFullname, partnerEmail, partnerPhone, partnerLocation].forEach(function (field) {
          field.addEventListener('input', function () {
            validatePartnerForm(formSubmitted);
          });
          field.addEventListener('paste', function () {
            setTimeout(function () {
              validatePartnerForm(formSubmitted);
            }, 10);
          });
          field.addEventListener('blur', function () {
            // Show errors on blur
            validatePartnerForm(true);
          });
          field.addEventListener('change', function () {
            validatePartnerForm(formSubmitted);
          });
        });

        // Handle select element separately (uses 'change' event, not 'input')
        partnerInterest.addEventListener('change', function () {
          validatePartnerForm(formSubmitted);
        });
        partnerInterest.addEventListener('blur', function () {
          // Show errors on blur
          validatePartnerForm(true);
        });

        // Initial validation
        validatePartnerForm(false);

        // Handle form submission via AJAX
        partnerForm.addEventListener('submit', function (e) {
          e.preventDefault();
          e.stopPropagation();

          formSubmitted = true;

          if (!validatePartnerForm(true)) {
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

          // Disable button and show loading state
          partnerSubmit.disabled = true;
          const originalText = partnerSubmit.innerHTML;
          partnerSubmit.innerHTML =
            '<span class="material-symbols-outlined animate-spin !text-base mr-2">sync</span> Submitting...';
          partnerSubmit.classList.add('opacity-75', 'cursor-not-allowed');

          // Hide any existing messages
          const messageContainer = document.getElementById('partner-message-container');
          if (messageContainer) {
            messageContainer.classList.add('hidden');
            messageContainer.innerHTML = '';
          }

          // Submit via AJAX
          if (window.tsmPartner && window.tsmPartner.ajaxUrl && window.tsmPartner.nonce) {
            const formData = new FormData();
            formData.append('action', 'tsm_partner_form');
            formData.append('nonce', window.tsmPartner.nonce);
            formData.append('fullname', partnerFullname.value.trim());
            formData.append('email', partnerEmail.value.trim());
            formData.append('phone', partnerPhone.value.trim());
            formData.append('location', partnerLocation.value.trim());
            formData.append('interest', partnerInterest.value);
            formData.append('message', partnerForm.querySelector('#message').value.trim());

            fetch(window.tsmPartner.ajaxUrl, {
              method: 'POST',
              body: formData,
            })
              .then(function (response) {
                return response.json();
              })
              .then(function (data) {
                // Reset button
                partnerSubmit.disabled = false;
                partnerSubmit.innerHTML = originalText;
                partnerSubmit.classList.remove('opacity-75', 'cursor-not-allowed');

                if (data.success) {
                  // Show success message
                  if (typeof showPartnerSuccessMessage === 'function') {
                    showPartnerSuccessMessage(
                      data.data.message ||
                        "We've received your partnership inquiry and will be in touch soon.",
                      data.data.has_account_details || false
                    );
                  }
                  // Hide form
                  partnerForm.classList.add('hidden');
                } else {
                  // Show error message
                  if (typeof showPartnerErrorMessage === 'function') {
                    showPartnerErrorMessage(
                      data.data.message || 'Something went wrong. Please try again.'
                    );
                  }
                }
              })
              .catch(function (error) {
                // Reset button
                partnerSubmit.disabled = false;
                partnerSubmit.innerHTML = originalText;
                partnerSubmit.classList.remove('opacity-75', 'cursor-not-allowed');
                // Show error message
                showPartnerErrorMessage('Something went wrong. Please try again.');
                console.error('Partner form submission error:', error);
              });
          } else {
            // Fallback: submit normally if AJAX not available
            partnerForm.submit();
          }
        });
      }
    }

    // Initialize gallery lightboxes
    initGalleryLightboxes();

    // Initialize missions infinite scroll
    initMissionsInfiniteScroll();
  });

  // Global flag to prevent opening lightboxes during close
  window.tsmLightboxClosing = false;

  /**
   * Initialize all gallery lightboxes on the page
   */
  function initGalleryLightboxes() {
    const lightboxes = document.querySelectorAll('.tsm-lightbox');

    lightboxes.forEach(function (lightbox) {
      const lightboxId = lightbox.id;
      const galleryImages = document.querySelectorAll('[data-lightbox="' + lightboxId + ']');

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
    const thumbnailContainer = lightbox.querySelector('.tsm-lightbox-thumbnails');

    if (!lightboxImage) return;

    let currentIndex = 0;
    let imageData = [];

    // First, try to get image data from the lightbox data attribute (all images)
    const lightboxData = lightbox.getAttribute('data-images');
    if (lightboxData) {
      try {
        imageData = JSON.parse(lightboxData);
      } catch (e) {
        // Failed to parse lightbox image data
      }
    }

    // Fallback: collect from gallery images if data attribute not available
    if (imageData.length === 0 && galleryImages.length > 0) {
      galleryImages.forEach(function (img) {
        const imgEl = img.querySelector('img') || img;
        const fullUrl = imgEl.getAttribute('data-full') || imgEl.src;
        const alt = imgEl.getAttribute('data-alt') || imgEl.alt || '';

        imageData.push({
          full: fullUrl,
          alt: alt,
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

      // Update thumbnail selection and scroll active thumbnail into view
      thumbnails.forEach(function (thumb, i) {
        if (i === index) {
          thumb.classList.add(
            'ring-2',
            'ring-primary',
            'ring-offset-2',
            'ring-offset-black',
            'scale-105',
            'opacity-100'
          );
          thumb.classList.remove('opacity-40');

          // Scroll the active thumbnail into view if container exists
          if (thumbnailContainer) {
            // Use scrollIntoView with smooth behavior to center the active thumbnail
            thumb.scrollIntoView({
              behavior: 'smooth',
              block: 'nearest',
              inline: 'center',
            });
          }
        } else {
          thumb.classList.remove(
            'ring-2',
            'ring-primary',
            'ring-offset-2',
            'ring-offset-black',
            'scale-105',
            'opacity-100'
          );
          thumb.classList.add('opacity-40');
        }
      });
    }

    function openLightbox(index) {
      // Don't open if we're in the middle of closing
      if (window.tsmLightboxClosing) {
        return;
      }

      // Don't open if another lightbox is already open
      const openLightbox = document.querySelector('.tsm-lightbox:not(.hidden)');
      if (openLightbox && openLightbox !== lightbox) {
        return;
      }

      currentIndex = index;
      updateLightbox(index);
      lightbox.classList.remove('hidden');
      lightbox.classList.add('flex');
      lightbox.style.display = 'flex';
      document.body.style.overflow = 'hidden';
      window.tsmLightboxClosing = false; // Reset flag

      // Disable pointer events on gallery items to prevent clicks through lightbox
      const galleryItems = document.querySelectorAll('.gallery-item');
      galleryItems.forEach(function (item) {
        item.style.pointerEvents = 'none';
      });
    }

    // Store the openLightbox function on the lightbox element so it can be called from outside
    lightbox._openLightbox = openLightbox;

    function closeLightbox() {
      // Prevent duplicate close calls for THIS specific lightbox
      if (lightbox._isClosing) {
        return;
      }

      // Only prevent if another lightbox is closing (not this one)
      if (window.tsmLightboxClosing && window.tsmClosingLightboxId !== lightbox.id) {
        return;
      }

      lightbox._isClosing = true;
      window.tsmLightboxClosing = true;

      // Store reference to this specific lightbox being closed
      window.tsmClosingLightboxId = lightbox.id;

      lightbox.classList.add('hidden');
      lightbox.classList.remove('flex');
      lightbox.style.display = 'none';
      document.body.style.overflow = '';

      // Re-enable pointer events on gallery items after a delay
      setTimeout(function () {
        const galleryItems = document.querySelectorAll('.gallery-item');
        galleryItems.forEach(function (item) {
          item.style.pointerEvents = '';
        });
        lightbox._isClosing = false;
        window.tsmLightboxClosing = false;
        window.tsmClosingLightboxId = null;
      }, 500);
    }

    // Store the closeLightbox function on the lightbox element
    lightbox._closeLightbox = closeLightbox;

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
    galleryImages.forEach(function (img) {
      // Mark this image as having a direct handler to prevent gallery-item from interfering
      img.setAttribute('data-has-direct-handler', 'true');

      img.addEventListener(
        'click',
        function (e) {
          // If this click came from a gallery-item card (not a direct click on the image),
          // let the gallery-item handler manage it
          const galleryItem = img.closest('.gallery-item');
          if (
            galleryItem &&
            e.target !== img &&
            !img.contains(e.target) &&
            e.target.closest('.gallery-item') === galleryItem
          ) {
            // The click originated from the gallery-item card, not directly on the image
            // Let the gallery-item handler take care of it
            return;
          }

          e.preventDefault();
          e.stopPropagation();

          // Don't open if clicking on lightbox controls
          if (
            e.target.closest('.tsm-lightbox-close') ||
            e.target.closest('.tsm-lightbox-prev') ||
            e.target.closest('.tsm-lightbox-next') ||
            e.target.closest('.tsm-lightbox-download')
          ) {
            return;
          }

          // Don't open if we're in the middle of closing
          if (window.tsmLightboxClosing) {
            return;
          }

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
        },
        false
      ); // Use bubble phase
    });

    // Thumbnail click
    thumbnails.forEach(function (thumb, index) {
      thumb.addEventListener('click', function () {
        updateLightbox(index);
      });
    });

    // Navigation buttons - close button with capture phase to prevent bubbling
    if (lightboxClose) {
      // Remove any existing listeners by cloning
      const newCloseBtn = lightboxClose.cloneNode(true);
      lightboxClose.parentNode.replaceChild(newCloseBtn, lightboxClose);

      newCloseBtn.addEventListener(
        'click',
        function (e) {
          e.stopImmediatePropagation();
          e.stopPropagation();
          e.preventDefault();
          // Don't set the flag here - let closeLightbox() handle it
          // This ensures closeLightbox() can execute properly
          closeLightbox();
          return false;
        },
        true
      ); // Capture phase - catches before bubble phase
    }

    if (lightboxNext) {
      lightboxNext.addEventListener('click', function (e) {
        e.stopPropagation();
        nextImage();
      });
    }
    if (lightboxPrev) {
      lightboxPrev.addEventListener('click', function (e) {
        e.stopPropagation();
        prevImage();
      });
    }

    // Keyboard navigation
    function handleKeydown(e) {
      if (lightbox.classList.contains('hidden')) return;

      if (e.key === 'Escape') {
        window.tsmLightboxClosing = true;
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
    lightbox.addEventListener(
      'click',
      function (e) {
        // Only close if clicking directly on the lightbox background, not on any child elements
        if (
          e.target === lightbox &&
          !e.target.closest(
            '.tsm-lightbox-close, .tsm-lightbox-prev, .tsm-lightbox-next, .tsm-lightbox-download, .tsm-lightbox-image, .tsm-lightbox-thumbnail'
          )
        ) {
          window.tsmLightboxClosing = true;
          closeLightbox();
        }
      },
      true
    ); // Use capture phase
  }

  /**
   * Initialize missions load more button and filtering
   */
  function initMissionsInfiniteScroll() {
    const missionsFeed = document.getElementById('missions-feed');
    const loadingIndicator = document.getElementById('missions-loading');
    const loadMoreBtn = document.getElementById('missions-load-more-btn');
    const statusCounter = document.getElementById('missions-status');
    const filterPills = document.querySelectorAll('.mission-filter-pill');

    if (!missionsFeed || !window.tsmMissions) {
      return;
    }

    let currentPage = 1;
    let currentYear = 'all';
    let isLoading = false;
    let hasMore = true;
    let excludeIds = [];
    let totalMissionsLoaded = 0;
    let totalMissionsCount = 0;

    // Get exclude IDs from data attribute
    const excludeIdsAttr = missionsFeed.getAttribute('data-exclude-ids');
    if (excludeIdsAttr) {
      try {
        excludeIds = JSON.parse(excludeIdsAttr);
      } catch (e) {
        // Failed to parse exclude IDs
      }
    }

    /**
     * Load missions via AJAX
     */
    function loadMissions(reset) {
      if (isLoading || (!hasMore && !reset)) {
        return;
      }

      isLoading = true;

      if (reset) {
        currentPage = 1;
        hasMore = true;
        totalMissionsLoaded = 0;
        // Clear content smoothly without causing layout shift
        requestAnimationFrame(function () {
          missionsFeed.innerHTML = '';
          loadingIndicator.classList.remove('hidden');
          loadingIndicator.classList.add('inline-flex');
          loadMoreBtn.classList.add('hidden');
          loadMoreBtn.classList.remove('inline-flex');
          statusCounter.textContent = '';
        });
      } else {
        // Show loading indicator and hide button
        requestAnimationFrame(function () {
          loadingIndicator.classList.remove('hidden');
          loadingIndicator.classList.add('inline-flex');
          loadMoreBtn.classList.add('hidden');
          loadMoreBtn.classList.remove('inline-flex');
        });
      }

      const formData = new FormData();
      formData.append('action', 'tsm_load_missions');
      formData.append('nonce', window.tsmMissions.nonce);
      formData.append('page', currentPage);
      formData.append('year', currentYear);
      if (excludeIds.length > 0) {
        excludeIds.forEach(function (id) {
          formData.append('exclude_ids[]', id);
        });
      }

      fetch(window.tsmMissions.ajaxUrl, {
        method: 'POST',
        body: formData,
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          if (data.success && data.data.missions) {
            const missions = data.data.missions;
            hasMore = Boolean(data.data.has_more);

            // Update total count on first load
            if (reset && data.data.total_count !== undefined) {
              totalMissionsCount = parseInt(data.data.total_count, 10);
            }

            if (missions.length === 0 && reset) {
              missionsFeed.innerHTML =
                '<p class="py-8 text-center text-gray-500 dark:text-gray-400">No missions found.</p>';
              totalMissionsLoaded = 0;
              isLoading = false;
              loadingIndicator.classList.add('hidden');
              loadingIndicator.classList.remove('inline-flex');
              loadMoreBtn.classList.add('hidden');
              loadMoreBtn.classList.remove('inline-flex');
              statusCounter.textContent = '';
              return;
            }

            if (reset) {
              totalMissionsLoaded = 0;
            }

            // Use DocumentFragment to batch DOM updates and prevent layout shifts
            const fragment = document.createDocumentFragment();

            missions.forEach(function (mission, index) {
              const isLastItem = !hasMore && index === missions.length - 1;
              const missionCard = createMissionCard(mission, isLastItem);
              fragment.appendChild(missionCard);
            });

            // Re-initialize lazy loading for newly added images
            if (typeof initLazyLoadingFallback === 'function') {
              initLazyLoadingFallback();
            }

            // Batch append all cards at once using requestAnimationFrame for smooth rendering
            requestAnimationFrame(function () {
              missionsFeed.appendChild(fragment);

              totalMissionsLoaded += missions.length;

              // Update status counter
              if (totalMissionsCount > 0) {
                statusCounter.textContent =
                  'Viewing ' + totalMissionsLoaded + ' of ' + totalMissionsCount + ' missions';
              }

              // Show/hide load more button
              if (hasMore) {
                loadMoreBtn.classList.remove('hidden');
                loadMoreBtn.classList.add('inline-flex');
              } else {
                loadMoreBtn.classList.add('hidden');
                loadMoreBtn.classList.remove('inline-flex');
              }

              // Hide loading indicator after content is rendered
              isLoading = false;
              loadingIndicator.classList.add('hidden');
              loadingIndicator.classList.remove('inline-flex');

              // Only increment page if we successfully loaded missions
              if (missions.length > 0) {
                currentPage++;
              }
            });
          } else {
            // Failed to load missions
            isLoading = false;
            loadingIndicator.classList.add('hidden');
            loadingIndicator.classList.remove('inline-flex');
            loadMoreBtn.classList.remove('hidden');
            loadMoreBtn.classList.add('inline-flex');
            if (reset) {
              missionsFeed.innerHTML =
                '<p class="py-8 text-center text-red-500">Failed to load missions. Please refresh the page.</p>';
            }
          }
        })
        .catch(function (_error) {
          isLoading = false;
          loadingIndicator.classList.add('hidden');
          // Error loading missions
          if (reset) {
            missionsFeed.innerHTML =
              '<p class="py-8 text-center text-red-500">Error loading missions. Please refresh the page.</p>';
          }
        });
    }

    /**
     * Create mission card HTML element (timeline style)
     */
    function createMissionCard(mission, isLastItem) {
      const div = document.createElement('div');
      div.className = 'relative group';
      div.style.willChange = 'auto'; // Optimize for smooth rendering

      // Determine icon
      const icon = mission.icon || 'public';

      // Build thumbnail HTML
      let thumbnailHtml = '';
      if (mission.thumbnail_url) {
        thumbnailHtml =
          '<div class="overflow-hidden mb-6 rounded-xl shadow-lg" style="min-height: 320px;">' +
          '<a href="' +
          mission.permalink +
          '">' +
          '<img src="' +
          mission.thumbnail_url +
          '" alt="' +
          (mission.thumbnail_alt || mission.title) +
          '" ' +
          'loading="lazy" decoding="async" ' +
          'class="object-cover w-full h-80 transition-transform duration-500 group-hover:scale-105" ' +
          'style="will-change: transform;">' +
          '</a>' +
          '</div>';
      }

      // Build status badge HTML if status is upcoming or ongoing
      let statusBadgeHtml = '';
      if (mission.status === 'upcoming' || mission.status === 'ongoing') {
        const statusText = mission.status === 'upcoming' ? 'Upcoming' : 'Ongoing';
        statusBadgeHtml =
          '<div class="mb-3 text-primary text-xs font-bold uppercase tracking-wider">' +
          statusText +
          '</div>';
      }

      // Build quote HTML
      const quoteHtml = mission.quote
        ? '<p class="text-accent dark:text-[#8bc39d] leading-relaxed mb-4 italic">' +
          mission.quote +
          '</p>'
        : '';

      // Build summary/content HTML
      let contentHtml = '';
      if (mission.summary) {
        contentHtml =
          '<p class="text-base leading-relaxed opacity-80 text-accent">' + mission.summary + '</p>';
      } else if (mission.content) {
        contentHtml =
          '<p class="text-base leading-relaxed opacity-80 text-accent">' + mission.content + '</p>';
      }

      // Show vertical line if not the last item
      const lineHtml = !isLastItem
        ? '<div class="w-0.5 h-full bg-[#cfe7d5] dark:bg-[#2a4431] mt-4"></div>'
        : '';

      div.innerHTML =
        thumbnailHtml +
        '<div class="flex gap-6">' +
        '<div class="flex flex-col items-center">' +
        '<div class="flex justify-center items-center text-white rounded-full size-10 bg-primary shrink-0">' +
        '<span class="font-bold material-symbols-outlined">' +
        icon +
        '</span>' +
        '</div>' +
        lineHtml +
        '</div>' +
        '<div class="pb-8">' +
        statusBadgeHtml +
        '<h3 class="mb-3 text-2xl font-bold text-accent">' +
        '<a href="' +
        mission.permalink +
        '" class="transition-colors text-accent hover:text-accent">' +
        mission.title +
        '</a>' +
        '</h3>' +
        quoteHtml +
        contentHtml +
        '<a href="' +
        mission.permalink +
        '" class="flex gap-2 items-center mt-4 text-sm font-bold transition-all text-primary group-hover:gap-3">' +
        'Read More <span class="material-symbols-outlined !text-base">arrow_forward</span>' +
        '</a>' +
        '</div>' +
        '</div>';

      return div;
    }

    /**
     * Handle filter pill clicks
     */
    filterPills.forEach(function (pill) {
      pill.addEventListener('click', function () {
        const year = this.getAttribute('data-year');

        // Update active state - match books archive pill styling
        filterPills.forEach(function (p) {
          // Remove active button classes
          p.classList.remove('active', 'bg-primary', 'border-primary');
          p.classList.add(
            'bg-white',
            'dark:bg-[#162b1b]',
            'hover:bg-emerald-50',
            'dark:hover:bg-emerald-900/30',
            'border',
            'border-emerald-50',
            'dark:border-emerald-900/30'
          );

          // Update text inside <p> tag
          const pTag = p.querySelector('p');
          if (pTag) {
            pTag.classList.remove('text-white', 'font-semibold');
            pTag.classList.add('text-gray-700', 'dark:text-gray-300', 'font-medium');
          }
        });

        // Set active state
        this.classList.add('active', 'bg-primary');
        this.classList.remove(
          'bg-white',
          'dark:bg-[#162b1b]',
          'hover:bg-emerald-50',
          'dark:hover:bg-emerald-900/30',
          'border',
          'border-emerald-50',
          'dark:border-emerald-900/30'
        );

        // Update text inside <p> tag
        const activePTag = this.querySelector('p');
        if (activePTag) {
          activePTag.classList.remove('text-gray-700', 'dark:text-gray-300', 'font-medium');
          activePTag.classList.add('text-white', 'font-semibold');
        }

        // Update current year and reload
        currentYear = year;
        loadMissions(true);
      });
    });

    /**
     * Load more button click handler
     */
    if (loadMoreBtn) {
      loadMoreBtn.addEventListener('click', function () {
        if (!isLoading && hasMore) {
          loadMissions(false);
        }
      });
    }

    // Initial load
    loadMissions(true);
  }

  // Cookie Admin Plugin Style Overrides
  // This ensures styles are applied even if the plugin sets inline styles after page load
  function overrideCookieAdminStyles() {
    // Get theme colors from CSS custom properties
    const root = document.documentElement;
    const primaryColor = getComputedStyle(root).getPropertyValue('--tsm-primary').trim();
    const primaryLightColor = getComputedStyle(root).getPropertyValue('--tsm-primary-light').trim();
    const accentColor = getComputedStyle(root).getPropertyValue('--tsm-accent').trim();

    // Override slider background color
    const sliders = document.querySelectorAll('.cookieadmin_slider');
    sliders.forEach(function (slider) {
      slider.style.setProperty('background-color', primaryColor, 'important');
    });

    // Override link colors in details wrapper
    const detailsWrapper = document.querySelector('.cookieadmin_details_wrapper');
    if (detailsWrapper) {
      const links = detailsWrapper.querySelectorAll('a');
      links.forEach(function (link) {
        link.style.setProperty('color', primaryLightColor, 'important');
      });
    }

    // Override action button colors
    const actionButtons = document.querySelectorAll('.cookieadmin_act');
    actionButtons.forEach(function (button) {
      button.style.setProperty('color', primaryColor, 'important');
    });

    // Override show more link colors
    const showMoreLinks = document.querySelectorAll('.cookieadmin_showmore');
    showMoreLinks.forEach(function (link) {
      link.style.setProperty('color', primaryLightColor, 'important');
    });

    // Override preference title colors
    const preferenceTitles = document.querySelectorAll('.cookieadmin_preference_title');
    preferenceTitles.forEach(function (title) {
      title.style.setProperty('color', accentColor, 'important');
    });
  }

  // Run immediately on DOM ready
  document.addEventListener('DOMContentLoaded', function () {
    overrideCookieAdminStyles();
  });

  // Also run after a short delay to catch late-loading plugin elements
  setTimeout(function () {
    overrideCookieAdminStyles();
  }, 500);

  // Watch for dynamically added cookie admin elements using MutationObserver
  if (typeof MutationObserver !== 'undefined') {
    const observer = new MutationObserver(function (mutations) {
      let shouldOverride = false;
      mutations.forEach(function (mutation) {
        if (mutation.addedNodes.length) {
          mutation.addedNodes.forEach(function (node) {
            if (node.nodeType === 1) {
              // Element node
              if (
                node.classList &&
                (node.classList.contains('cookieadmin_slider') ||
                  node.classList.contains('cookieadmin_details_wrapper') ||
                  node.classList.contains('cookieadmin_act') ||
                  node.classList.contains('cookieadmin_showmore') ||
                  node.classList.contains('cookieadmin_preference_title') ||
                  node.querySelector('.cookieadmin_slider') ||
                  node.querySelector('.cookieadmin_details_wrapper') ||
                  node.querySelector('.cookieadmin_act') ||
                  node.querySelector('.cookieadmin_showmore') ||
                  node.querySelector('.cookieadmin_preference_title'))
              ) {
                shouldOverride = true;
              }
            }
          });
        }
      });
      if (shouldOverride) {
        overrideCookieAdminStyles();
      }
    });

    // Start observing when DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
      observer.observe(document.body, {
        childList: true,
        subtree: true,
      });
    });
  }
})();
