/**
 * Scroll Animations Controller
 * Uses GSAP and ScrollTrigger
 */

(function ($) {
    "use strict";

    $(document).ready(function () {
        // Register ScrollTrigger
        gsap.registerPlugin(ScrollTrigger);

        // 1. Reveal Animations (Fade in and slide up)
        const revealElements = $(".gs-reveal, .gs-reveal-left, .gs-reveal-right");

        revealElements.each(function (i, el) {
            let x = 0;
            let y = 40;

            if ($(el).hasClass("gs-reveal-left")) {
                x = -50;
                y = 0;
            } else if ($(el).hasClass("gs-reveal-right")) {
                x = 50;
                y = 0;
            }

            gsap.fromTo(el,
                {
                    opacity: 0,
                    x: x,
                    y: y
                },
                {
                    opacity: 1,
                    x: 0,
                    y: 0,
                    duration: 1.2,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: el,
                        start: "top 85%",
                        toggleActions: "play none none none"
                    }
                }
            );
        });

        // 1.1 Merge Title Animation (Made to matter)
        const mergeTitle = $(".gs-merge-reveal");
        if (mergeTitle.length) {
            const madeTo = mergeTitle.find(".gs-made-to");
            const matter = mergeTitle.find(".gs-matter");
        
            // Skip merge title animations on mobile (below 768px)
            ScrollTrigger.matchMedia({
                "(min-width: 768px)": function () {
        
                    gsap.fromTo(madeTo,
                        { y: -600, x: 0 },
                        {
                            y: 0, x: 0,
                            duration: 200, delay: 100,
                            ease: "power3.out",
                            scrollTrigger: {
                                trigger: ".matter-title",
                                start: "top 95%", end: "top 30%",
                                scrub: 3, once: true
                            }
                        }
                    );
        
                    gsap.fromTo(matter,
                        { y: 200, opacity: 0 },
                        {
                            y: 0, opacity: 1,
                            duration: 2,
                            ease: "power3.out",
                            scrollTrigger: {
                                trigger: ".matter-title",
                                start: "top 95%", end: "top 30%",
                                scrub: 3, once: true
                            }
                        }
                    );
        
                    const projectImage = $(".gs-project-slide-image");
                    if (projectImage.length) {
                        gsap.fromTo(projectImage,
                            { y: -300 },
                            {
                                y: 0,
                                duration: 200, delay: 100,
                                ease: "power3.out",
                                scrollTrigger: {
                                    trigger: ".matter-title",
                                    start: "top 95%", end: "top 30%",
                                    scrub: 3, once: true
                                }
                            }
                        );
                    }
        
                    const introLinks = $(".intro-section .gs-link-reveal");
                    if (introLinks.length) {
                        gsap.fromTo(introLinks,
                            { opacity: 0, autoAlpha: 0, scale: 0.7, transformOrigin: "center center" },
                            {
                                opacity: 1, autoAlpha: 1, scale: 1,
                                delay: 0.2, duration: 0.3,
                                stagger: 0.2,
                                ease: "back.out(1.7)",
                                scrollTrigger: {
                                    trigger: ".matter-title",
                                    start: "top 95%", end: "top 30%",
                                    scrub: 3, once: true,
                                    onEnter: () => gsap.set(introLinks, { pointerEvents: "auto" })
                                }
                            }
                        );
                    }
        
                } // end matchMedia desktop
            });
        }

        // Intro Grid Specific: 2-Step Reveal (Fade @ 50% height -> Expand to 100%)
        // Excluding newly refactored cards that use the unified title trigger
        const introCards = $(".intro-product-card.gs-scale-image-wrap, .intro-project-card.gs-scale-image-wrap");
        introCards.each(function (i, card) {
            const wrap = $(card).find(".gs-split-reveal-wrap");
            const img = $(card).find(".gs-scale-image");
            const link = $(card).find(".gs-link-reveal");

            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: card,
                    start: "top 80%",
                    toggleActions: "play none none none"
                }
            });

            // Step 1: Fade in at 50% height (clip-path is inset(25% 0 25% 0) in CSS)
            tl.fromTo(wrap,
                { opacity: 0 },
                { opacity: 1, duration: 1.2, ease: "power2.out" }
            )
                // Step 2: Expand to 100% height (Upwards)
                .to(wrap, {
                    clipPath: "inset(0% 0% 0% 0%)",
                    duration: 1.2, // Faster expansion
                    ease: "expo.inOut"
                }, "+=0.1") // Shorter pause after fade
                // Subtle zoom on the image itself during expansion
                .fromTo(img,
                    { scale: 1.15 },
                    { scale: 1, duration: 1.6, ease: "power2.out" },
                    "-=1.2"
                )
                // Step 3: Reveal link
                .fromTo(link,
                    { opacity: 0, y: 20 },
                    { opacity: 1, y: 0, duration: 0.8, ease: "power2.out" },
                    "-=0.4"
                );
        });

        // 3. Staggered Items (e.g., list of items, grid cards)
        const staggerContainers = $(".gs-stagger-container");

        staggerContainers.each(function (i, container) {
            // General slide reveal
            const moveItems = $(container).find(".gs-stagger-item");
            if (moveItems.length) {
                gsap.fromTo(moveItems,
                    { opacity: 0, y: 30 },
                    {
                        opacity: 1,
                        y: 0,
                        duration: 0.8,
                        stagger: 0.2,
                        ease: "power2.out",
                        scrollTrigger: {
                            trigger: container,
                            start: "top 80%",
                            toggleActions: "play none none none"
                        }
                    }
                );
            }

            // Scale-up reveal (Specific for Made to Matter grid)
            const scaleItems = $(container).find(".gs-stagger-scale");
            if (scaleItems.length) {
                gsap.fromTo(scaleItems,
                    {
                        opacity: 0,
                        scale: 0.7
                    },
                    {
                        opacity: 1,
                        scale: 1,
                        duration: 1.2,
                        stagger: 0.15,
                        ease: "expo.out",
                        scrollTrigger: {
                            trigger: container,
                            start: "top 85%",
                            toggleActions: "play none none none"
                        }
                    }
                );
            }
        });

        // Upward Expansion Stagger (Specific for Made to Matter grid)
        const staggerUpContainers = $(".gs-stagger-reveal-up");
        staggerUpContainers.each(function (i, container) {
            const wraps = $(container).find(".gs-split-reveal-wrap");

            gsap.fromTo(wraps,
                {
                    opacity: 0,
                    scaleY: 0.5,
                    y: 20,
                    transformOrigin: "bottom center"
                },
                {
                    opacity: 1,
                    scaleY: 1,
                    y: 0,
                    duration: 1.5,
                    stagger: 0.2,
                    ease: "back.out(1.7)", // Springy "pop" effect
                    scrollTrigger: {
                        trigger: container,
                        start: "top 85%",
                        toggleActions: "play none none none"
                    }
                }
            );

            // Reset clip-path if it was set by previous iterations
            gsap.set(wraps, { clipPath: "none" });
        });

        // 4. Hero Parallax
        if ($(".hero-slider-section").length) {
            gsap.to(".hero-video, .hero-slide-image", {
                yPercent: 30,
                ease: "none",
                scrollTrigger: {
                    trigger: ".hero-slider-section",
                    start: "top top",
                    end: "bottom top",
                    scrub: true
                }
            });
        }

        // 5. Text Line Mask Effect (Premium feel)
        const splitTextReveal = $(".gs-text-reveal");
        splitTextReveal.each(function (i, el) {
            // This is a simplified version of split-text reveal
            gsap.fromTo(el,
                {
                    clipPath: "inset(0 0 100% 0)",
                    y: 20
                },
                {
                    clipPath: "inset(0 0 0% 0)",
                    y: 0,
                    duration: 1.5,
                    ease: "power4.out",
                    scrollTrigger: {
                        trigger: el,
                        start: "top 90%"
                    }
                }
            );
        });

        // 6. Delayed News Grid Reveal (Faster Staggered Reveal)
        const newsRevealContainers = $(".gs-news-reveal");
        newsRevealContainers.each(function (i, container) {
            const slides = $(container).find(".swiper-slide");

            gsap.fromTo(slides,
                {
                    opacity: 0,
                    y: 60
                },
                {
                    opacity: 1,
                    y: 0,
                    duration: 1.0,
                    stagger: 0.15,
                    ease: "expo.out",
                    scrollTrigger: {
                        trigger: container,
                        start: "top 90%",
                        toggleActions: "play none none none"
                    },
                    delay: 0.3 // Snappier delay after heading
                }
            );
        });

        // 7. Header Dynamic Animations (Jonite style)
        const header = $("header");
        const heroSection = $(".hero-slider-section");

        // Initial reveal on load
        gsap.to(header, {
            y: "0%",
            duration: 1.5,
            ease: "expo.out",
            delay: 0.5
        });

        // Scroll-triggered shrink and hide
        const handleScroll = () => {
            const scrollY = window.scrollY;
            const heroHeight = heroSection.length ? heroSection.outerHeight() : window.innerHeight;
            const hideThreshold = heroHeight / 2;

            // 1. Shrink logic (padding and logo resize)
            // Use 20px threshold for more sensitivity to returning top
            if (scrollY > 50) {
                header.addClass("header-scrolled");
            } else {
                header.removeClass("header-scrolled");
            }

            // 2. Hide logic (Arriving at hero section middle position)
            
            // if (scrollY > hideThreshold) {
            //     header.addClass("header-hidden");
            // } else {
            //     header.removeClass("header-hidden");
            // }
        };

        window.addEventListener("scroll", handleScroll, { passive: true });
        handleScroll(); // Initial check

        // 8. Footer Reveal
        const footer = $(".gs-footer-reveal");
        if (footer.length) {
            ScrollTrigger.create({
                trigger: footer,
                start: "top bottom", // Trigger as soon as it enters from the bottom
                onEnter: () => footer.addClass("is-visible"),
                onLeaveBack: () => footer.removeClass("is-visible")
            });
        }

    });

})(jQuery);
