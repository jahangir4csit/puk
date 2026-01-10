<?php
/**
 * Template Name: Home Page
 *
 * Custom home page template with hero slider
 *
 * @package Puk
 */

// Exit if accessed directly
defined('ABSPATH') || exit; 
get_header();
?>

<main id="home-page" class="home-page">

    <!-- Hero Slider Section -->
    <section class="hero-slider-section">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">

                <!-- Slide 1 - Video -->
                <div class="swiper-slide">
                    <div class="hero-slide-video">
                        <video class="hero-video" autoplay muted loop playsinline>
                            <source src="<?php echo get_template_directory_uri(); ?>/assets/videos/hero-video.mp4"
                                type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>

                </div>

                <!-- Slide 2 - Image -->
                <div class="swiper-slide">
                    <div class="hero-slide-image"
                        style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920');">
                    </div>
                </div>

                <!-- Slide 3 - Image -->
                <div class="swiper-slide">
                    <div class="hero-slide-image"
                        style="background-image: url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920');">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-grid">

                <div class="intro-grid-left gs-reveal-left">
                    <article class="intro-content gs-text-reveal">
                        <p>
                            <strong class="d-block mb-3">Light and design. Our lighting says everything about our quality.</strong>
                            <strong class="d-block mb-4">Lighting is at the heart of the way we think and build.</strong>
                            Outdoor lighting requires products and services of the highest standards: innovative
                            solutions,
                            technical excellence, design flexibility
                            and consistent performance.
                            Our products bring Italian excellence to the most futuristic architecture in the world.
                        </p>
                    </article>
                    <div class="intro-product-card gs-scale-image-wrap">
                        <div class="gs-split-reveal-wrap">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/product-spotlight.jpg"
                                alt="Product Spotlight" class="gs-scale-image">
                        </div>
                        <a href="#"
                            class="intro-card-link d-inline-flex align-items-center justify-content-between gs-link-reveal">Explore
                            Products <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30"
                                fill="none">
                                <path
                                    d="M20.0719 9.18568L19.4146 9.18477L13.929 9.18477L13.9441 10.4862L17.8585 10.4848L8.68663 19.6567L9.60089 20.571L18.7723 11.3996L18.7714 15.3135L20.0733 15.3281L20.0733 9.84258L20.0719 9.18568Z"
                                    fill="black" />
                            </svg></a>
                    </div>
                </div>
                <div class="intro-grid-right gs-reveal-right">
                    <div class="intro-project-card gs-scale-image-wrap">
                        <div class="gs-split-reveal-wrap">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/our-projects.jpg"
                                alt="Our Projects" class="gs-scale-image">
                        </div>
                        <a href="#" class="intro-card-link d-inline-flex align-items-center justify-content-between gs-link-reveal">Our
                            Projects <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30"
                                fill="none">
                                <path
                                    d="M20.0719 9.18568L19.4146 9.18477L13.929 9.18477L13.9441 10.4862L17.8585 10.4848L8.68663 19.6567L9.60089 20.571L18.7723 11.3996L18.7714 15.3135L20.0733 15.3281L20.0733 9.84258L20.0719 9.18568Z"
                                    fill="black" />
                            </svg></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Made to Matter Section -->
    <section class="made-to-matter-section">
        <div class="container">
            <h2 class="gs-reveal">Made to matter</h2>
            <div class="matter-row row justify-content-md-between">
                <div class="col-lg-12 col-xl-6 gs-reveal-left">
                    <div class="matter-column matter-text">
                        <article class="matter-text">
                            <p>The evolution process is part of human nature,<br />
                                and as a result, everything crafted by humans evolves<br />
                                alongside it. Materials are treated as architectural elements,<br />
                                whose individual characteristics should be showcased<br />
                                and appreciated.</p>
                        </article>
                    </div>
                </div>
                <div class="col-lg-12 col-xl-4">
                    <div class="matter-grid gs-stagger-reveal-up">
                        <div class="matter-column">
                            <div class="gs-split-reveal-wrap">
                                <p class="matter-text-sm">Each product<br />
                                    seamlessly<br />
                                    blends into nature,<br />
                                    form, function, radiant<br /> presence
                                    within<br /> the space</p>
                            </div>
                        </div>
                        <div class="matter-column">
                            <div class="gs-split-reveal-wrap">
                                <p class="matter-text-sm">Scent completes the<br />
                                    sensory journey, creating an<br />
                                    ambiance reminiscent of<br /> the changing seasons. <br />
                                    A PLACE for discovery,<br /> encounters, and awareness.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Stories Section -->
    <section class="latest-stories-section">
        <div class="container">
            <h3 class="section-label gs-reveal">Latest Stories -</h3>
            <div class="hstories-grid gs-stagger-container">
                <div class="hstory-card gs-stagger-item">
                    <a href="#" class="story-link">
                        <div class="story-image gs-scale-image-wrap">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/story-1.jpg"
                                alt="The vibrant oranges of blossoming birds of paradise catch your eye" class="gs-scale-image">
                        </div>
                        <div class="story-content">
                            <h3 class="story-tag">SABBIA</h3>
                            <h4 class="story-title">The vibrant oranges of blossoming birds of paradise catch your eye
                            </h4>
                        </div>
                    </a>
                </div>
                <div class="hstory-card even gs-stagger-item">
                    <a href="#" class="story-link">
                        <div class="story-image gs-scale-image-wrap">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/story-2.jpg"
                                alt="The meticulous geometry is the first thing that strikes you" class="gs-scale-image">
                        </div>
                        <div class="story-content">
                            <h3 class="story-tag">ITALIAN GARDEN</h3>
                            <h4 class="story-title">The meticulous geometry is the first thing that strikes you</h4>
                        </div>
                    </a>
                </div>
                <div class="hstory-card gs-stagger-item">
                    <a href="#" class="story-link">
                        <div class="story-image gs-scale-image-wrap">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/story-3.jpg"
                                alt="The evolution process is part of human nature" class="gs-scale-image">
                        </div>
                        <div class="story-content">
                            <h3 class="story-tag">LIGHT BUILDING</h3>
                            <h4 class="story-title">The evolution process is part of human nature</h4>
                        </div>
                    </a>
                </div>
                <div class="hstory-card even gs-stagger-item">
                    <a href="#" class="story-link">
                        <div class="story-image gs-scale-image-wrap">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/story-4.jpg"
                                alt="The vibrant oranges of blossoming birds of paradise catch your eye" class="gs-scale-image">
                        </div>
                        <div class="story-content">
                            <h3 class="story-tag">TROPICAL</h3>
                            <h4 class="story-title">The vibrant oranges of blossoming birds of paradise catch your eye
                            </h4>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News Section -->
    <section class="latest-news-section">
        <div class="container">
            <h3 class="section-label gs-reveal">Latest BLOG -</h3>
            <div class="news-grid gs-news-reveal">
                <div class="swiper news-swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="news-card">
                                <a href="#" class="news-link">
                                    <div class="news-image">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/news-1.jpg"
                                            alt="Vertical luminous, timeless Breakdance">
                                    </div>
                                    <div class="news-title-wrap">
                                        <h4 class="news-title">Vertical luminous, timeless Breakdance</h4>
                                    </div>
                                    <div class="news-content">
                                        <p class="news-excerpt">The new recessed linear ground light for outdoor use</p>
                                        <span class="news-date">29 September 2025</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="news-card">
                                <a href="#" class="news-link">
                                    <div class="news-image">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/news-2.jpg"
                                            alt="PUK announces the entrance of PUK into the Starlight Group">
                                    </div>
                                    <div class="news-title-wrap">
                                        <h4 class="news-title">PUK announces the entrance of PUK into the Starlight
                                            Group
                                        </h4>
                                    </div>
                                    <div class="news-content">
                                        <p class="news-excerpt">This is an important step that allows us to join a solid
                                            and
                                            structured group</p>
                                        <span class="news-date">29 September 2025</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="news-card">
                                <a href="#" class="news-link">
                                    <div class="news-image">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/news-3.jpg"
                                            alt="Like AGRICLUB: the new recessed linear ground light for outdoor lighting from PUK">
                                    </div>
                                    <div class="news-title-wrap">
                                        <h4 class="news-title">Like AGRICLUB: the new recessed linear ground light for
                                            outdoor
                                            lighting from PUK</h4>
                                    </div>
                                    <div class="news-content">
                                        <p class="news-excerpt">A solution that redefines the rules of outdoor lighting
                                        </p>
                                        <span class="news-date">29 September 2025</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="news-card">
                                <a href="#" class="news-link">
                                    <div class="news-image">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/news-1.jpg"
                                            alt="PUK for Chiostri and main Contemporary museum">
                                    </div>
                                    <div class="news-title-wrap">
                                        <h4 class="news-title">PUK for "Chiostri and main Contemporary museum</h4>
                                    </div>
                                    <div class="news-content">
                                        <p class="news-excerpt">Emanuele Giannelli awaits us at Fabbrica del Vapore with
                                            Rewriters
                                        </p>
                                        <span class="news-date">29 September 2025</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="news-card">
                                <a href="#" class="news-link">
                                    <div class="news-image">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/news-2.jpg"
                                            alt="PUK announces the entrance of PUK into the Starlight Group">
                                    </div>
                                    <div class="news-title-wrap">
                                        <h4 class="news-title">PUK announces the entrance of PUK into the Starlight
                                            Group
                                        </h4>
                                    </div>
                                    <div class="news-content">
                                        <p class="news-excerpt">This is an important step that allows us to join a solid
                                            and
                                            structured group</p>
                                        <span class="news-date">29 September 2025</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    // Additional content from page editor
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            the_content();
        }
    }
    ?>

</main>

<?php
get_footer();