/**
 * Hero Slider JavaScript
 *
 * @package Puk
 */

(function() {
    'use strict';

    /**
     * Initialize Hero Slider
     */
    function initHeroSlider() {
        // Check if Swiper is loaded
        if (typeof Swiper === 'undefined') {
            console.error('Swiper library is not loaded');
            return;
        }

        // Find all hero sliders on the page
        const heroSliders = document.querySelectorAll('.hero-swiper');
        
        if (heroSliders.length === 0) {
            return;
        }

        heroSliders.forEach(function(sliderElement) {
            // Initialize Swiper
            const swiper = new Swiper(sliderElement, {
                loop: true,
                speed: 1000,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                pagination: {
                    el: sliderElement.querySelector('.swiper-pagination'),
                    clickable: true,
                },
                navigation: {
                    nextEl: sliderElement.querySelector('.swiper-button-next'),
                    prevEl: sliderElement.querySelector('.swiper-button-prev'),
                },
                // Accessibility
                a11y: {
                    prevSlideMessage: 'Previous slide',
                    nextSlideMessage: 'Next slide',
                },
            });

            // Video Control
            swiper.on('slideChange', function () {
                // Pause all videos
                const allVideos = sliderElement.querySelectorAll('.hero-video');
                allVideos.forEach(function(video) {
                    video.pause();
                });
                
                // Play video in active slide
                const activeSlide = sliderElement.querySelector('.swiper-slide-active');
                if (activeSlide) {
                    const activeVideo = activeSlide.querySelector('.hero-video');
                    if (activeVideo) {
                        activeVideo.play().catch(function(error) {
                            console.log('Video autoplay prevented:', error);
                        });
                    }
                }
            });

            // Play first video on load
            const firstSlide = sliderElement.querySelector('.swiper-slide-active');
            if (firstSlide) {
                const firstVideo = firstSlide.querySelector('.hero-video');
                if (firstVideo) {
                    firstVideo.play().catch(function(error) {
                        console.log('Video autoplay prevented:', error);
                    });
                }
            }

            // Pause autoplay when video is playing
            const videos = sliderElement.querySelectorAll('.hero-video');
            videos.forEach(function(video) {
                video.addEventListener('play', function() {
                    swiper.autoplay.stop();
                });
                
                video.addEventListener('ended', function() {
                    swiper.autoplay.start();
                });
            });
        });
    }

    /**
     * Initialize News Slider
     */
    function initNewsSlider() {
        // Check if Swiper is loaded
        if (typeof Swiper === 'undefined') {
            console.error('Swiper library is not loaded');
            return;
        }

        // Find all news sliders on the page
        const newsSliders = document.querySelectorAll('.news-swiper');
        
        if (newsSliders.length === 0) {
            return;
        }

        newsSliders.forEach(function(sliderElement) {
            // Initialize News Swiper
            const newsSwiper = new Swiper(sliderElement, {
                loop: true,
                speed: 800,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                slidesPerView: 1,
                spaceBetween: 20,
                breakpoints: {
                    // Mobile (>= 480px)
                    480: {
                        slidesPerView: 1,
                        spaceBetween: 20
                    },
                    // Tablet (>= 768px)
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 30
                    },
                    // Desktop (>= 1024px)
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30
                    },
                    // Large Desktop (>= 1200px)
                    1200: {
                        slidesPerView: 4,
                        spaceBetween: 30
                    }
                },
                // Accessibility
                a11y: {
                    prevSlideMessage: 'Previous news',
                    nextSlideMessage: 'Next news',
                },
            });
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initHeroSlider();
            initNewsSlider();
        });
    } else {
        initHeroSlider();
        initNewsSlider();
    }

})();
