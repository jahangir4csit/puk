<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package redapple
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
get_header(); ?>



<main class="page-404">
    <div class="container">
        <div class="page-404__inner">

            <p class="page-404__number">404</p>

            <div class="page-404__divider"></div>

            <h1 class="page-404__heading">Page not found</h1>

            <p class="page-404__text">
                The page you are looking for might have been removed,<br>
                had its name changed, or is temporarily unavailable.
            </p>

            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="page-404__btn">
                Back to Home
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M2 8H14M14 8L9 3M14 8L9 13" stroke="#000" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>

        </div>
    </div>
</main>

<?php
get_footer();
