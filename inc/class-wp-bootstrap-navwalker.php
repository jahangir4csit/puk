<?php

class Mega_Menu_Walker_ACF extends Walker_Nav_Menu {

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $classes = ($depth === 0) ? ' class="mega-menu"' : '';
        $output .= "\n$indent<ul$classes>\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
    $classes = empty($item->classes) ? [] : (array) $item->classes;
    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
    $output .= '<li class="' . esc_attr($class_names) . '">';

    $is_heading = empty($item->url) || $item->url == '#';

    if ($depth == 1 && $is_heading) {
        $output .= '<h4>' . esc_html($item->title) . '</h4>';
    } else {
        $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
    }

    // Get ACF menu image
    $image_field = get_field('menu_image', $item->ID);
    if ($image_field) {
        if (is_array($image_field)) {
            $image_url = $image_field['url'];
        } elseif (is_numeric($image_field)) {
            $image_url = wp_get_attachment_url($image_field);
        } else {
            $image_url = $image_field;
        }

        $output .= '<div class="mega-menu-image">
                        <img src="' . esc_url($image_url) . '" alt="' . esc_attr($item->title) . '">
                    </div>';
    }
   }


    function end_el(&$output, $item, $depth = 0, $args = array()) {
    $output .= "</li>\n";

    // Add search icon only after the last top-level menu item
    if ($depth == 0 && $item->menu_order == $args->menu->count) {

        $output .= '
        <li class="menu-item search-icon">
            <a href="#" class="search-trigger">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15"
					 fill="none"><path d="M14.0002 14.0002L10.7429 10.7429M10.7429 10.7429C11.3001 10.1857 11.742 9.52429 12.0436 8.79631C12.3451    8.06834 12.5003 7.28811 12.5003 6.50015C12.5003 5.7122 12.3451 4.93197 12.0436 4.20399C11.742 3.47602 11.3001 2.81457 10.7429 2.2574C10.1857 1.70024 9.52429 1.25827 8.79631 0.956735C8.06834 0.655199 7.28811 0.5 6.50015 0.5C5.7122 0.5 4.93197 0.655199 4.20399 0.956735C3.47602 1.25827 2.81457 1.70024 2.2574 2.2574C1.13216 3.38265 0.5 4.90881 0.5 6.50015C0.5 8.09149 1.13216 9.61766 2.2574 10.7429C3.38265 11.8682 4.90881 12.5003 6.50015 12.5003C8.09149 12.5003 9.61766 11.8682 10.7429 10.7429Z" stroke="black" stroke-linecap="round" stroke-linejoin="round" />
                 </svg>
            </a>
        </li>';
    }
   }


    function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "</ul>\n";
    }
}
