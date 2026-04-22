<?php
/**
 * The template for displaying Project archive
 *
 * @package Puk
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

// Get all project categories
$project_categories = get_terms( array(
    'taxonomy'   => 'project_category',
    'hide_empty' => false,
) );


// Get projects page description from admin
$description = get_option( 'puk_projects_description', '' );

// Get all projects with pagination
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = array(
    'post_type'      => 'project',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'paged'          => $paged,
);

$projects_query = new WP_Query( $args );
?>
<div class="r_puk_container">
<main class="projects_page">
    <!-- projects section one start  -->
    <section class="prjct_pg_1">
        <div class="container">
            <div class="row">

                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                    <div class="prjct_pg_1_left">
                        <!-- left side categories  -->
                        <div class="prjct_pg_1_left_cat">
                            <ul>
                                <li>
                                    <a class="is_active" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>">
                                        <?php esc_html_e( 'All', 'puk' ); ?>
                                    </a>
                                </li>
                                <?php if ( ! empty( $project_categories ) && ! is_wp_error( $project_categories ) ) : ?>
                                    <?php foreach ( $project_categories as $category ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
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
                                <?php echo wp_kses_post( wpautop( $description ) ); ?>
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
                            ?>
                                <!-- project box  -->
                                <div class="prjct_pg_1_img_bx">
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
                                    data-page="<?php echo esc_attr( $paged ); ?>"
                                    data-max-pages="<?php echo esc_attr( $projects_query->max_num_pages ); ?>"
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
</div>

<?php
get_footer();
