<?php

/**
 * Register Custom Post Type: project
 */
function puk_register_project_post_type() {

    $labels = array(
        'name'                  => _x( 'Projects', 'Post type general name', 'puk' ),
        'singular_name'         => _x( 'Project', 'Post type singular name', 'puk' ),
        'menu_name'             => _x( 'Projects', 'Admin Menu text', 'puk' ),
        'name_admin_bar'        => _x( 'Project', 'Add New on Toolbar', 'puk' ),
        'add_new'               => __( 'Add New Project', 'puk' ),
        'add_new_item'          => __( 'Add New Project', 'puk' ),
        'new_item'              => __( 'New Project', 'puk' ),
        'edit_item'             => __( 'Edit Project', 'puk' ),
        'view_item'             => __( 'View Project', 'puk' ),
        'all_items'             => __( 'All Projects', 'puk' ),
        'search_items'          => __( 'Search Projects', 'puk' ),
        'parent_item_colon'     => __( 'Parent Projects:', 'puk' ),
        'not_found'             => __( 'No Projects found.', 'puk' ),
        'not_found_in_trash'    => __( 'No Projects found in Trash.', 'puk' ),
        'featured_image'        => _x( 'Project Featured Image', 'Overrides the "Featured Image" phrase', 'puk' ),
        'set_featured_image'    => _x( 'Set featured image', 'Overrides the "Set featured image" phrase', 'puk' ),
        'remove_featured_image' => _x( 'Remove featured image', 'Overrides the "Remove featured image" phrase', 'puk' ),
        'use_featured_image'    => _x( 'Use as featured image', 'Overrides the "Use as featured image" phrase', 'puk' ),
        'archives'              => _x( 'Project archives', 'The post type archive label', 'puk' ),
        'insert_into_item'      => _x( 'Insert into project', 'Overrides the "Insert into post" phrase', 'puk' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this project', 'Overrides the "Uploaded to this post" phrase', 'puk' ),
        'filter_items_list'     => _x( 'Filter projects list', 'Screen reader text for the filter links', 'puk' ),
        'items_list_navigation' => _x( 'Projects list navigation', 'Screen reader text for the pagination', 'puk' ),
        'items_list'            => _x( 'Projects list', 'Screen reader text for the items list', 'puk' ),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'project' ),
        'capability_type'       => 'post',
        'has_archive'           => 'projects',
        'hierarchical'          => false,
        'menu_position'         => 8,
        'menu_icon'             => 'dashicons-portfolio',
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
        'show_in_rest'          => true,
    );

    register_post_type( 'project', $args );
}
add_action( 'init', 'puk_register_project_post_type' );

/**
 * Register Custom Taxonomy: project_category
 */
function puk_register_project_category_taxonomy() {

    $labels = array(
        'name'                       => _x( 'Project Categories', 'Taxonomy general name', 'puk' ),
        'singular_name'              => _x( 'Project Category', 'Taxonomy singular name', 'puk' ),
        'search_items'               => __( 'Search Project Categories', 'puk' ),
        'popular_items'              => __( 'Popular Project Categories', 'puk' ),
        'all_items'                  => __( 'All Project Categories', 'puk' ),
        'parent_item'                => __( 'Parent Project Category', 'puk' ),
        'parent_item_colon'          => __( 'Parent Project Category:', 'puk' ),
        'edit_item'                  => __( 'Edit Project Category', 'puk' ),
        'update_item'                => __( 'Update Project Category', 'puk' ),
        'add_new_item'               => __( 'Add New Project Category', 'puk' ),
        'new_item_name'              => __( 'New Project Category Name', 'puk' ),
        'separate_items_with_commas' => __( 'Separate project categories with commas', 'puk' ),
        'add_or_remove_items'        => __( 'Add or remove project categories', 'puk' ),
        'choose_from_most_used'      => __( 'Choose from the most used project categories', 'puk' ),
        'not_found'                  => __( 'No project categories found.', 'puk' ),
        'menu_name'                  => __( 'Project Categories', 'puk' ),
        'back_to_items'              => __( '&larr; Back to Project Categories', 'puk' ),
    );

    $args = array(
        'labels'                => $labels,
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'show_in_nav_menus'     => true,
        'show_tagcloud'         => true,
        'show_in_rest'          => true,
        'rewrite'               => array( 'slug' => 'projects' ),
    );

    register_taxonomy( 'project_category', array( 'project' ), $args );
}
add_action( 'init', 'puk_register_project_category_taxonomy' );

/**
 * Handle saving projects description
 */
function puk_save_projects_description() {
    if ( isset( $_POST['puk_projects_description_nonce'] ) &&
         wp_verify_nonce( $_POST['puk_projects_description_nonce'], 'puk_save_projects_description' ) ) {

        $description = isset( $_POST['puk_projects_description'] ) ? wp_kses_post( $_POST['puk_projects_description'] ) : '';
        update_option( 'puk_projects_description', $description );

        // Redirect to avoid resubmission
        wp_redirect( add_query_arg( 'puk_saved', '1', wp_get_referer() ) );
        exit;
    }
}
add_action( 'admin_init', 'puk_save_projects_description' );

/**
 * Show success notice after saving
 */
function puk_projects_description_admin_notice() {
    $screen = get_current_screen();
    if ( $screen->id === 'edit-project' && isset( $_GET['puk_saved'] ) ) {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Description saved.', 'puk' ) . '</p></div>';
    }
}
add_action( 'admin_notices', 'puk_projects_description_admin_notice' );

/**
 * Add rich text editor below projects list in admin
 */
function puk_projects_list_footer_form() {
    $screen = get_current_screen();

    // Only on projects list page
    if ( $screen->id !== 'edit-project' ) {
        return;
    }

    // Get saved content
    $description = get_option( 'puk_projects_description', '' );

    ?>
    <div id="puk-projects-description-wrapper" style="display: none; margin-top: 20px; background: #fff; padding: 20px; border: 1px solid #c3c4c7; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
        <h2 style="margin-top: 0;"><?php esc_html_e( 'Projects Page Description', 'puk' ); ?></h2>
        <p class="description"><?php esc_html_e( 'This content will be displayed on the projects archive page.', 'puk' ); ?></p>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'puk_save_projects_description', 'puk_projects_description_nonce' ); ?>

            <textarea id="puk_projects_description" name="puk_projects_description" rows="10" style="width: 100%;"><?php echo esc_textarea( $description ); ?></textarea>

            <p style="margin-top: 15px;">
                <button type="submit" class="button button-primary"><?php esc_html_e( 'Save Description', 'puk' ); ?></button>
            </p>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var $wrapper = $('#puk-projects-description-wrapper');
        var $wrap = $('.wrap');

        // Move the wrapper inside .wrap at the end
        if ($wrap.length && $wrapper.length) {
            $wrapper.appendTo($wrap).show();

            // Initialize TinyMCE
            if (typeof wp !== 'undefined' && wp.editor) {
                wp.editor.initialize('puk_projects_description', {
                    tinymce: {
                        wpautop: true,
                        plugins: 'charmap colorpicker hr lists paste tabfocus textcolor fullscreen wordpress wpautoresize wpeditimage wpemoji wpgallery wplink wptextpattern',
                        toolbar1: 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv',
                        toolbar2: 'strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help'
                    },
                    quicktags: true,
                    mediaButtons: true
                });
            }
        }
    });
    </script>
    <?php
}
add_action( 'admin_footer-edit.php', 'puk_projects_list_footer_form' );

/**
 * Enqueue editor scripts on projects list page
 */
function puk_enqueue_editor_on_projects_list( $hook ) {
    if ( $hook === 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'project' ) {
        wp_enqueue_editor();
        wp_enqueue_media();
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script(
            'puk-project-order',
            get_template_directory_uri() . '/assets/js/admin-project-order.js',
            array( 'jquery', 'jquery-ui-sortable' ),
            filemtime( get_template_directory() . '/assets/js/admin-project-order.js' ),
            true
        );
        wp_localize_script( 'puk-project-order', 'pukProjectOrder', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'puk_project_order_nonce' ),
        ) );
    }
}
add_action( 'admin_enqueue_scripts', 'puk_enqueue_editor_on_projects_list' );

/**
 * Save project drag-drop order via AJAX
 */
function puk_save_project_order() {
    check_ajax_referer( 'puk_project_order_nonce', 'security' );

    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_send_json_error();
    }

    $id      = absint( $_POST['id'] );
    $prev_id = isset( $_POST['previd'] ) ? absint( $_POST['previd'] ) : 0;
    $next_id = isset( $_POST['nextid'] ) ? absint( $_POST['nextid'] ) : 0;

    if ( ! $id ) {
        wp_send_json_error();
    }

    $all = get_posts( array(
        'post_type'      => 'project',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
        'fields'         => 'ids',
    ) );

    $all = array_values( array_filter( $all, function( $pid ) use ( $id ) {
        return $pid !== $id;
    } ) );

    if ( $prev_id && in_array( $prev_id, $all ) ) {
        $pos = array_search( $prev_id, $all ) + 1;
    } elseif ( $next_id && in_array( $next_id, $all ) ) {
        $pos = array_search( $next_id, $all );
    } else {
        $pos = count( $all );
    }

    array_splice( $all, $pos, 0, array( $id ) );

    foreach ( $all as $index => $pid ) {
        wp_update_post( array( 'ID' => $pid, 'menu_order' => $index ) );
    }

    wp_send_json_success();
}
add_action( 'wp_ajax_puk_save_project_order', 'puk_save_project_order' );

/**
 * Sort the admin projects list by menu_order so drag-drop DOM order matches DB order.
 */
function puk_admin_projects_order( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }
    $screen = get_current_screen();
    if ( $screen && $screen->post_type === 'project' && $screen->base === 'edit' ) {
        if ( ! isset( $_GET['orderby'] ) ) {
            $query->set( 'orderby', 'menu_order' );
            $query->set( 'order', 'ASC' );
        }
    }
}
add_action( 'pre_get_posts', 'puk_admin_projects_order' );

/**
 * Handle "Initialize order by date" admin action.
 */
function puk_init_project_order() {
    if ( ! isset( $_GET['puk_init_order'] ) || ! current_user_can( 'edit_posts' ) ) {
        return;
    }
    check_admin_referer( 'puk_init_project_order' );

    $posts = get_posts( array(
        'post_type'      => 'project',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'fields'         => 'ids',
    ) );

    foreach ( $posts as $index => $post_id ) {
        wp_update_post( array( 'ID' => $post_id, 'menu_order' => $index ) );
    }

    wp_redirect( add_query_arg( array( 'post_type' => 'project', 'puk_order_set' => '1' ), admin_url( 'edit.php' ) ) );
    exit;
}
add_action( 'admin_init', 'puk_init_project_order' );

/**
 * Show "Initialize Order" button and success notice on the projects list page.
 */
function puk_project_order_admin_ui() {
    $screen = get_current_screen();
    if ( ! $screen || $screen->id !== 'edit-project' ) {
        return;
    }

    if ( isset( $_GET['puk_order_set'] ) ) {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Project order initialized by date (newest first).', 'puk' ) . '</p></div>';
    }

    $url = wp_nonce_url(
        add_query_arg( array( 'post_type' => 'project', 'puk_init_order' => '1' ), admin_url( 'edit.php' ) ),
        'puk_init_project_order'
    );
    echo '<div class="notice notice-info"><p>';
    echo esc_html__( 'Set initial drag-drop order: ', 'puk' );
    echo '<a href="' . esc_url( $url ) . '" class="button">' . esc_html__( 'Initialize Order by Date (Newest First)', 'puk' ) . '</a>';
    echo '</p></div>';
}
add_action( 'admin_notices', 'puk_project_order_admin_ui' );
