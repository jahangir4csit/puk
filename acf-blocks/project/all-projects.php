<?php
/**
 * Block Template: All Projects
 *
 * @package Puk
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get ACF fields
$description = get_field( 'description' );

// Get all project categories
$project_categories = get_terms( array(
    'taxonomy'   => 'project_category',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
) );

// Get all projects with pagination
$args = array(
    'post_type'      => 'project',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
);

$projects_query = new WP_Query( $args );

// Block preview placeholder in admin
if ( isset( $is_preview ) && $is_preview && ! $projects_query->have_posts() ) {
    echo '<div style="padding: 20px; background: #f0f0f0; border: 2px dashed #ccc; text-align: center;">';
    echo '<p>' . __( 'All Projects Block - Add projects to see them here', 'puk' ) . '</p>';
    echo '</div>';
    return;
}
?>

<main id="<?php echo esc_attr( $block_id ?? '' ); ?>" class="<?php echo esc_attr( $block_class ?? '' ); ?> projects_page">
    <!-- projects section one start  -->
    <section class="prjct_pg_1">
        <div class="container-fluid">
            <div class="row">

                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                    <div class="prjct_pg_1_left">
                        <!-- left side categories  -->
                        <div class="prjct_pg_1_left_cat">
                            <ul>
                                <li>
                                    <a class="is_active" href="#" data-filter="all">
                                        <?php esc_html_e( 'All', 'puk' ); ?>
                                    </a>
                                </li>
                                <?php if ( ! empty( $project_categories ) && ! is_wp_error( $project_categories ) ) : ?>
                                    <?php foreach ( $project_categories as $category ) : ?>
                                        <li>
                                            <a href="#" data-filter="<?php echo esc_attr( $category->slug ); ?>">
                                                <?php echo esc_html( $category->name ); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <!-- left side paragraph  -->
                        <?php if ( $description ) : ?>
                            <div class="prjct_pg_1_left_desc">
                                <p><?php echo wp_kses_post( nl2br( $description ) ); ?></p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                    <div id="puk-projects-container" class="prjct_pg_1_irow">

                        <?php if ( $projects_query->have_posts() ) : ?>
                            <?php while ( $projects_query->have_posts() ) : $projects_query->the_post();
                                // Get project data
                                $project_id = get_the_ID();
                                $project_title = get_the_title();
                                $project_link = get_permalink();
                                $featured_image_url = get_the_post_thumbnail_url( $project_id, 'large' );
                                $place = get_field( 'place', $project_id ); 

                                // Get project categories for filtering
                                $project_cats = get_the_terms( $project_id, 'project_category' );
                                $cat_slugs = '';
                                if ( ! empty( $project_cats ) && ! is_wp_error( $project_cats ) ) {
                                    $cat_slugs = implode( ' ', wp_list_pluck( $project_cats, 'slug' ) );
                                }
                            ?>
                                <!-- project box  -->
                                <div class="prjct_pg_1_img_bx" data-categories="<?php echo esc_attr( $cat_slugs ); ?>">
                                    <a href="<?php echo esc_url( $project_link ); ?>">
                                        <div class="prjct_pg_1_img_bx_img">
                                            <?php if ( $featured_image_url ) : ?>
                                                <img src="<?php echo esc_url( $featured_image_url ); ?>" alt="<?php echo esc_attr( $project_title ); ?>">
                                            <?php else : ?>
                                                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/placeholder.jpg' ); ?>" alt="<?php echo esc_attr( $project_title ); ?>">
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                    <a href="<?php echo esc_url( $project_link ); ?>"><?php echo esc_html( $project_title ); ?></a>
                                    <?php if ( $place ) : ?>
                                        <p><?php echo esc_html( $place ); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <p><?php esc_html_e( 'No projects found.', 'puk' ); ?></p>
                        <?php endif; ?>

                    </div>

                    <?php if ( $projects_query->max_num_pages > 1 ) : ?>
                        <div class="puk-load-more-wrapper" style="text-align: center; margin-top: 40px;">
                            <button id="puk-load-more-projects" class="btn btn-primary" 
                                    data-page="1" 
                                    data-max-pages="<?php echo $projects_query->max_num_pages; ?>"
                                    data-action="load_more_projects"
                                    data-container="#puk-projects-container">
                                <?php esc_html_e( 'Load More', 'puk' ); ?>
                            </button>
                        </div>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>

            </div>
        </div>
    </section>
    <!-- projects section one end  -->
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterLinks = document.querySelectorAll('.prjct_pg_1_left_cat a');
    
    function applyFilter(filter) {
        const projectBoxes = document.querySelectorAll('.prjct_pg_1_img_bx');
        projectBoxes.forEach(function(box) {
            if (filter === 'all') {
                box.style.display = 'block';
            } else {
                const categories = box.getAttribute('data-categories');
                if (categories && categories.includes(filter)) {
                    box.style.display = 'block';
                } else {
                    box.style.display = 'none';
                }
            }
        });
    }

    filterLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove active class from all links
            filterLinks.forEach(function(l) {
                l.classList.remove('is_active');
            });

            // Add active class to clicked link
            this.classList.add('is_active');

            const filter = this.getAttribute('data-filter');
            applyFilter(filter);
        });
    });

    // Expose applyFilter to be used by AJAX load more
    window.pukApplyProjectFilter = function() {
        const activeFilter = document.querySelector('.prjct_pg_1_left_cat a.is_active')?.getAttribute('data-filter') || 'all';
        applyFilter(activeFilter);
    };
});
</script>
