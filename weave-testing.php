<?php
/*
Plugin Name: Weave A/B Testing
Description: A plugin for A/B testing in WordPress.
Version: 1.0
Author: Lavenia Juanis
Plugin URI: https://example.com/ab-testing-plugin
*/


function ab_testing_enqueue_scripts() {
    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Enqueue A/B testing script
    wp_enqueue_script('ab-testing-script', plugin_dir_url(__FILE__) . 'ab-testing.js', array('jquery'), '1.0', true);
    wp_enqueue_style('select2-style', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css', [], '4.1.0-beta.1');
    wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js', ['jquery'], '4.1.0-beta.1', true);
}

add_action('admin_enqueue_scripts', 'ab_testing_enqueue_scripts');

function ab_testing_custom_tracking_enqueue_scripts() {
    // Enqueue custom tracking script
    wp_enqueue_script('ab-testing-custom-tracking', plugin_dir_url(__FILE__) . 'custom-tracking.js', array('jquery'), '1.0', true);
}

add_action('admin_enqueue_scripts', 'ab_testing_custom_tracking_enqueue_scripts');

function ab_testing_frontend_enqueue_scripts() {
    // Enqueue scripts for frontend A/B testing
    wp_enqueue_script('jquery');

    // Enqueue A/B testing scripts for frontend
    wp_enqueue_script('ab-testing-script', plugin_dir_url(__FILE__) . 'ab-testing.js', array('jquery'), '1.0', true);
    wp_enqueue_script('ab-testing-custom-tracking', plugin_dir_url(__FILE__) . 'custom-tracking.js', array('jquery'), '1.0', true);
    wp_enqueue_script('ab-testing-frontend', plugin_dir_url(__FILE__) . 'ab_testing_frontend.js', array('jquery'), '1.0', true);
}

function enqueue_chart_js() {
    // Enqueue Chart.js library
    wp_enqueue_script('chart-js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js', array(), '3.7.0', true);
}

// Hook the function to admin_enqueue_scripts action
add_action('admin_enqueue_scripts', 'enqueue_chart_js');


add_action('wp_enqueue_scripts', 'ab_testing_frontend_enqueue_scripts');

function enqueue_ab_testing_script() {
    // Enqueue A/B testing script for frontend
    wp_enqueue_script('ab-testing-script', plugin_dir_url(__FILE__) . 'ab.js', array('jquery'), '1.0', true);
}

add_action('wp_enqueue_scripts', 'enqueue_ab_testing_script');

function weave_testing_enqueue_styles() {
    // Enqueue Weave testing stylesheet
    wp_enqueue_style( 'weave-testing-style', plugin_dir_url( __FILE__ ) . 'style.css', array(), '1.0', 'all' );
}

add_action( 'wp_enqueue_scripts', 'weave_testing_enqueue_styles' );


// AJAX
function get_page_content_callback() {

    if(isset($_POST['post_id'])) {
        $post_id = intval($_POST['post_id']);

        $post_content = get_post_field('post_content', $post_id);
        echo $post_content;
    } else {
        echo 'Error: Missing post ID parameter.';
    }
    wp_die();

}


// AJAX action to track user interaction
add_action('wp_ajax_ab_testify_track_interaction', 'ab_testify_track_interaction_callback');
function ab_testify_track_interaction_callback() {
    // Retrieve variation ID and interaction type from AJAX request
    $variation_id = isset($_POST['variation_id']) ? $_POST['variation_id'] : 0;
    $interaction_type = isset($_POST['interaction_type']) ? $_POST['interaction_type'] : '';


    wp_send_json_success('Interaction tracked successfully');
}


add_action('wp_ajax_get_page_content', 'get_page_content_callback');


add_action('wp_ajax_ab_testify_process_test_submission', 'ab_testify_process_test_submission');
add_action('wp_ajax_nopriv_ab_testify_process_test_submission', 'ab_testify_process_test_submission');

function ab_testify_activate() {
   
}


add_action('wp_ajax_get_post_thumbnail_url', 'get_post_thumbnail_url_callback');
add_action('wp_ajax_nopriv_get_post_thumbnail_url', 'get_post_thumbnail_url_callback');

function get_post_thumbnail_url_callback() {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    if ($post_id) {
        $thumbnail_url = get_the_post_thumbnail_url($post_id, 'thumbnail');
        wp_send_json_success(array('thumbnail_url' => $thumbnail_url));
    } else {
        wp_send_json_error('Invalid post ID');
    }
}


add_shortcode('ab_testify_results', 'ab_testify_results_shortcode');

// Shortcode function to display A/B testing results



register_deactivation_hook(__FILE__, 'ab_testify_deactivate');

function ab_testify_deactivate() {
   
}



function ab_testify_load_results_page() {
    include_once(plugin_dir_path(__FILE__) . 'admin/results.php');
}

require_once(plugin_dir_path(__FILE__) . 'ab_test_traffic_split.php');
require_once(plugin_dir_path(__FILE__) . 'test-functions.php');



// Include admin files
include_once(plugin_dir_path(__FILE__) . 'admin/dashboard.php');
include_once(plugin_dir_path(__FILE__) . 'admin/add-test.php');
include_once(plugin_dir_path(__FILE__) . 'admin/results.php');

include_once(plugin_dir_path(__FILE__) . 'admin/conversion_analysis.php');



?>
