<?php
/**
 * Template Name: Full Width Template
 * Template Post Type: page
 * 
 * Full width page template without sidebar
 *
 * @package puk
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();
?>
<main>
    <div id="post-<?php the_ID(); ?>" <?php post_class('page-wrapper fullwidth-template'); ?>>
        <?php
                    while (have_posts()) {
                        the_post();
                                the_content();
                        ?>



        <?php
                        
                    }
                    ?>

    </div>
</main>

<?php
get_footer();