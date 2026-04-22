<?php
/**
 * Hero Slider Assets Enqueue
 * 
 * Enqueues Swiper library and custom hero slider CSS/JS
 * 
 * @package Puk
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Enqueue Hero Slider Assets
 */
function puk_enqueue_hero_slider_assets() {
    // Swiper CSS
    wp_enqueue_style(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        array(),
        '11.0.0'
    );
    
    // Hero Slider Custom CSS
    wp_enqueue_style(
        'puk-hero-slider',
        get_template_directory_uri() . '/assets/css/hero-slider.css',
        array('swiper'),
        '1.0.0'
    );
    
    // Swiper JS
    wp_enqueue_script(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        array(),
        '11.0.0',
        true
    );
    
    // Hero Slider Custom JS
    wp_enqueue_script(
        'puk-hero-slider',
        get_template_directory_uri() . '/assets/js/hero-slider.js',
        array('swiper'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'puk_enqueue_hero_slider_assets');

/**
 * Enqueue Swiper in Block Editor
 */
function puk_enqueue_hero_slider_editor_assets() {
    // Swiper CSS for block editor
    wp_enqueue_style(
        'swiper-editor',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        array(),
        '11.0.0'
    );
    
    // Hero Slider CSS for block editor
    wp_enqueue_style(
        'puk-hero-slider-editor',
        get_template_directory_uri() . '/assets/css/hero-slider.css',
        array('swiper-editor'),
        '1.0.0'
    );
    
    // Swiper JS for block editor
    wp_enqueue_script(
        'swiper-editor',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        array(),
        '11.0.0',
        true
    );
    
    // Hero Slider JS for block editor
    wp_enqueue_script(
        'puk-hero-slider-editor',
        get_template_directory_uri() . '/assets/js/hero-slider.js',
        array('swiper-editor'),
        '1.0.0',
        true
    );
}
add_action('enqueue_block_editor_assets', 'puk_enqueue_hero_slider_editor_assets');
