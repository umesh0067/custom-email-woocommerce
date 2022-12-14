<?php
/**
 * Plugin Name: Custom Email
 * Description: Demo plugin for sending email via ajax
 * Author: Atlas Softweb
 * Author URI: https://www.atlassoftweb.com
 * Version: 0.1
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 *  get plugin path
 *
 * @since 0.1
 */
function um_ajax_email_plugin_path(){
    return untrailingslashit( plugin_dir_path( __FILE__ ) ); 
}

/**
 *  Add a custom email to the list of emails WooCommerce should load
 *
 * @since 0.1
 * @param array $email_classes available email classes
 * @return array filtered available email classes
 */
function um_add_custom_email( $email_classes ) {

    // include our custom email class
    require_once( 'includes/class-wc-test-ajax-email.php' );

    // add the email class to the list of email classes that WooCommerce loads
    $email_classes['WC_Test_Ajax_Email'] = new WC_Test_Ajax_Email();

    return $email_classes;
}
add_filter( 'woocommerce_email_classes', 'um_add_custom_email' );


/**
 *  Enqueue the scripts with apprpriate vars
 *
 * @since 0.1
 */
function um_ajax_email_js(){
    wp_enqueue_script( 'um_ajax_email', plugins_url( 'js/script.js', __FILE__ ), array('jquery'), '1.0.0', true );
    wp_localize_script( 'um_ajax_email', 'um_ajax_email', array( 
                'ajax_url'                  => WC()->ajax_url(),
                'wc_ajax_url'               => WC_AJAX::get_endpoint( "test_email" ) ) );
}
add_action( 'wp_enqueue_scripts', 'um_ajax_email_js', 20 );


/**
 *  AJAX callback
 *
 * @since 0.1
 */
function um_ajax_email_callback() {
    $mailer = WC()->mailer();

    do_action( 'um_trigger_ajax_email' );

    //echo '<pre>'; var_dump(do_action( 'um_trigger_ajax_email' )); echo '</pre>'; 

    die('ajax finished'); // this is required to terminate immediately and return a proper response
}
add_action( 'wc_ajax_test_email', 'um_ajax_email_callback' );


/**
 *  Register action as one that sends emails
 *
 * @since 0.1
 */
function um_ajax_email_action( $actions ){
    $actions[] = 'um_trigger_ajax_email';
    return $actions;
    
}
add_action( 'woocommerce_email_actions', 'um_ajax_email_action' );


/**
 *  Add a dummy button to product page
 *
 * @since 0.1
 */
function um_ajax_email_button(){
    echo '<button class="email-trigger">' . __( 'Email Trigger', 'kia-ajax-email' ). '</button>';
}
add_action('woocommerce_before_single_product_summary', 'um_ajax_email_button');