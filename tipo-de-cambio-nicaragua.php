<?php
/*
Plugin Name: Tipo de cambio Nicaragua
Text Domain: tipo-de-cambio-nicaragua
Domain Path: /languages
Description: Tipo de cambio de dólar a córdoba nicaragüense. Información sobre el tipo de cambios de hoy, ayer, mañana y promedio del mes actual. 
Version: 1.1.0
Author: Binary Lemon
Author URI: https://www.binarylemon.net
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

function tc_nicaragua_widget()
{
    include_once(plugin_dir_path( __FILE__ ).'/includes/widget.php');
    register_widget('tc_nicaragua_widget');
}

add_action('widgets_init','tc_nicaragua_widget');

function tc_nicaragua_scripts() 
{
    wp_enqueue_style( 'style-citrus-api', plugin_dir_url( __FILE__ ).'includes/css/tipo-cambio-nicaragua.css', array(), '0.1');    
}

add_action( 'wp_enqueue_scripts', 'tc_nicaragua_scripts' );

function tc_nicaragua_languages() 
{
    load_plugin_textdomain( 'tipo-de-cambio-nicaragua', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'tc_nicaragua_languages' );