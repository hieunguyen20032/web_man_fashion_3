/**
 * AI Product Recommendation Carousel JavaScript
 * Handles carousel navigation and animations
 */

(function () {
    'use strict';

    // Store carousel states
    var carouselStates = {};

    /**
     * Initialize carousel for a section
     */
    function initCarousel(sectionId) {
        var section = document.getElementById(sectionId);
        if (!section) return;

        var track = document.getElementById(sectionId + '-track');
        var dots = document.getElementById(sectionId + '-dots');

        if (!track) return;

        var cards = track.querySelectorAll('.ai-product-card');
        if (cards.length === 0) return;

        // Calculate cards per view based on screen width
        var cardsPerView = getCardsPerView();
        var totalSlides = Math.ceil(cards.length / cardsPerView);

        carouselStates[sectionId] = {
            currentSlide: 0,
            totalSlides: totalSlides,
            cardsPerView: cardsPerView,
            totalCards: cards.length
        };

        // Update dots
        if (dots) {
            updateDots(sectionId, 0);
        }

        // Add touch support for mobile
        addTouchSupport(sectionId, track);
    }

    /**
     * Get number of cards per view based on screen width
     */
    function getCardsPerView() {
        var width = window.innerWidth;
        if (width <= 576) return 1;
        if (width <= 992) return 2;
        if (width <= 1200) return 3;
        return 4;
    }

    /**
     * Slide products
     */
    window.slideAIProducts = function (direction, sectionId) {
        var state = carouselStates[sectionId];
        if (!state) {
            initCarousel(sectionId);
            state = carouselStates[sectionId];
        }

        if (!state) return;

        var track = document.getElementById(sectionId + '-track');
        if (!track) return;

        var cards = track.querySelectorAll('.ai-product-card');
        if (cards.length === 0) return;

        // Recalculate cards per view (in case of resize)
        state.cardsPerView = getCardsPerView();
        state.totalSlides = Math.ceil(state.totalCards / state.cardsPerView);

        // Calculate new slide
        if (direction === 'next') {
            state.currentSlide = (state.currentSlide + 1) % state.totalSlides;
        } else if (direction === 'prev') {
            state.currentSlide = (state.currentSlide - 1 + state.totalSlides) % state.totalSlides;
        }

        // Apply transform
        applyTransform(sectionId, state.currentSlide);

        // Update dots
        updateDots(sectionId, state.currentSlide);
    };

    /**
     * Go to specific slide
     */
    window.goToAISlide = function (slideIndex, sectionId) {
        var state = carouselStates[sectionId];
        if (!state) {
            initCarousel(sectionId);
            state = carouselStates[sectionId];
        }

        if (!state) return;

        state.currentSlide = slideIndex;
        applyTransform(sectionId, slideIndex);
        updateDots(sectionId, slideIndex);
    };

    /**
     * Apply transform to track
     */
    function applyTransform(sectionId, slideIndex) {
        var track = document.getElementById(sectionId + '-track');
        if (!track) return;

        var state = carouselStates[sectionId];
        if (!state) return;

        var cards = track.querySelectorAll('.ai-product-card');
        if (cards.length === 0) return;

        var cardWidth = cards[0].offsetWidth;
        var gap = 20; // Gap between cards

        var offset = slideIndex * (cardWidth + gap) * state.cardsPerView;
        var maxOffset = (cards.length - state.cardsPerView) * (cardWidth + gap);

        // Don't scroll past the last cards
        offset = Math.min(offset, maxOffset);
        offset = Math.max(offset, 0);

        track.style.transform = 'translateX(-' + offset + 'px)';
    }

    /**
     * Update carousel dots
     */
    function updateDots(sectionId, activeIndex) {
        var dots = document.getElementById(sectionId + '-dots');
        if (!dots) return;

        var dotElements = dots.querySelectorAll('.dot');
        dotElements.forEach(function (dot, index) {
            if (index === activeIndex) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }

    /**
     * Add touch support for mobile swiping
     */
    function addTouchSupport(sectionId, track) {
        var startX = 0;
        var currentX = 0;
        var isDragging = false;

        track.addEventListener('touchstart', function (e) {
            startX = e.touches[0].clientX;
            isDragging = true;
        }, { passive: true });

        track.addEventListener('touchmove', function (e) {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
        }, { passive: true });

        track.addEventListener('touchend', function () {
            if (!isDragging) return;
            isDragging = false;

            var diff = startX - currentX;
            var threshold = 50;

            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    slideAIProducts('next', sectionId);
                } else {
                    slideAIProducts('prev', sectionId);
                }
            }
        });
    }

    /**
     * Auto-play functionality
     */
    function startAutoPlay(sectionId, interval) {
        interval = interval || 5000;

        setInterval(function () {
            var section = document.getElementById(sectionId);
            if (!section) return;

            // Only auto-play if section is visible
            var rect = section.getBoundingClientRect();
            var isVisible = rect.top < window.innerHeight && rect.bottom > 0;

            if (isVisible) {
                slideAIProducts('next', sectionId);
            }
        }, interval);
    }

    /**
     * Handle window resize
     */
    function handleResize() {
        Object.keys(carouselStates).forEach(function (sectionId) {
            var state = carouselStates[sectionId];
            if (state) {
                state.cardsPerView = getCardsPerView();
                state.totalSlides = Math.ceil(state.totalCards / state.cardsPerView);

                // Ensure current slide is valid
                if (state.currentSlide >= state.totalSlides) {
                    state.currentSlide = state.totalSlides - 1;
                }

                applyTransform(sectionId, state.currentSlide);
            }
        });
    }

    /**
     * Initialize on DOM ready
     */
    function init() {
        // Find all AI recommendation sections
        var sections = document.querySelectorAll('.ai-recommendation-section');
        sections.forEach(function (section) {
            var sectionId = section.id;
            if (sectionId) {
                initCarousel(sectionId);

                // Optional: Start auto-play
                // startAutoPlay(sectionId, 5000);
            }
        });

        // Handle resize
        var resizeTimeout;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(handleResize, 250);
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Also initialize when jQuery is ready (for PHP-rendered content)
    if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(init);
    }

})();
