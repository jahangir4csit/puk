<?php

/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package redapple
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
	 <?php 
		$favicon = get_field('site_favicon', 'option');
		if( $favicon ) : ?>
			<link rel="icon" href="<?php echo esc_url($favicon['url']); ?>" >
	<?php endif; ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class() ?>>

    <header>
        <div class="main-header" id="main-header">
            <div class="container-fluid">
                <div class="header-flex">
                   <div class="logo-box">
						<a href="<?php echo esc_url(home_url('/')); ?>">
							<?php 
							$logo = get_field('site_logo', 'option'); 
							if( $logo ) : 
								echo '<img src="' . esc_url($logo['url']) . '" alt="' . esc_attr($logo['alt']) . '">';
							endif;
							?>
						</a>

					</div>

                    <div class="main-header-items">
                         <?php
							if (has_nav_menu('main_menu')) {
								wp_nav_menu([
									'theme_location' => 'main_menu',
									'container' => false,
									'menu_id' => 'main-menu',
									'menu_class' => '',
									'walker' => new Mega_Menu_Walker_ACF(), 
								]);
							} else {
								echo '<ul><li><a href="#">Add Menu Items</a></li></ul>';
							}
							?>
                            <a class="header-search" href="#">
								<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
									<path
										d="M14.0002 14.0002L10.7429 10.7429M10.7429 10.7429C11.3001 10.1857 11.742 9.52429 12.0436 8.79631C12.3451 8.06834 12.5003 7.28811 12.5003 6.50015C12.5003 5.7122 12.3451 4.93197 12.0436 4.20399C11.742 3.47602 11.3001 2.81457 10.7429 2.2574C10.1857 1.70024 9.52429 1.25827 8.79631 0.956735C8.06834 0.655199 7.28811 0.5 6.50015 0.5C5.7122 0.5 4.93197 0.655199 4.20399 0.956735C3.47602 1.25827 2.81457 1.70024 2.2574 2.2574C1.13216 3.38265 0.5 4.90881 0.5 6.50015C0.5 8.09149 1.13216 9.61766 2.2574 10.7429C3.38265 11.8682 4.90881 12.5003 6.50015 12.5003C8.09149 12.5003 9.61766 11.8682 10.7429 10.7429Z"
									stroke="black" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</a>
                    </div>
                    <div class="mobile-menu-trigger">
						<span></span>
						<span></span>
						<span></span>
					</div>
                </div>
            </div>

        </div>
    </header>