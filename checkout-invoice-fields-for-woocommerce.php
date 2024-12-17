<?php
/*
Plugin Name: Checkout Invoice Fields For Woocommerce
Description: Adds custom invoice fields to WooCommerce checkout page.
Version: 1.1
Author: Georgi Bogdanov
Author URI: https://gbogdanov.com/
Text Domain: cif
Domain Path: /languages
*/

if ( ! defined( 'CIF_INCLUDE' ) ) {
    define( 'CIF_INCLUDE', plugin_dir_path( __FILE__ ) . 'includes'  );
}

require CIF_INCLUDE . '/functions.php';

function cif_load_textdomain() {
    load_plugin_textdomain('cif', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

add_action('plugins_loaded', 'cif_load_textdomain');

/**
 * Enqueue plugin assets
 */
function load_cif_back_end_assets() {
    wp_enqueue_style( 'admin-invoice-style', plugin_dir_url( __FILE__ ) . 'assets/css/admin-style.css' );
    wp_enqueue_script( 'admin-invoice-script', plugin_dir_url( __FILE__ ) . 'assets/js/admin-script.js', array('jquery'), null, true );
}

add_action( 'admin_enqueue_scripts', 'load_cif_back_end_assets' );

function load_cif_front_end_assets() {
    wp_enqueue_style( 'custom-invoice-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
    wp_enqueue_script( 'custom-invoice-script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array('jquery'), null, true );
}

add_action( 'wp_enqueue_scripts', 'load_cif_front_end_assets' );