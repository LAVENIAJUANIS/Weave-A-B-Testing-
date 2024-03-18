<?php
/*
Plugin Name: Weave A/B Testing
Description: A plugin for A/B testing in WordPress.
Version: 1.0
Author: Lavenia Juanis
Plugin URI: https://example.com/ab-testing-plugin
*/


function ab_testing_enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('ab-testing-script', plugin_dir_url(__FILE__) . 'ab-testing.js', array('jquery'), '1.0', true);
}

add_action('admin_enqueue_scripts', 'ab_testing_enqueue_scripts');

function ab_testing_custom_tracking_enqueue_scripts() {
    wp_enqueue_script('ab-testing-custom-tracking', plugin_dir_url(__FILE__) . 'custom-tracking.js', array('jquery'), '1.0', true);
}

add_action('admin_enqueue_scripts', 'ab_testing_custom_tracking_enqueue_scripts');

function ab_testing_frontend_enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('ab-testing-script', plugin_dir_url(__FILE__) . 'ab-testing.js', array('jquery'), '1.0', true);
    wp_enqueue_script('ab-testing-custom-tracking', plugin_dir_url(__FILE__) . 'custom-tracking.js', array('jquery'), '1.0', true);
}

add_action('wp_enqueue_scripts', 'ab_testing_frontend_enqueue_scripts');

function enqueue_ab_testing_script() {
    wp_enqueue_script('ab-testing-script', plugin_dir_url(__FILE__) . 'ab.js', array('jquery'), '1.0', true);
}

add_action('wp_enqueue_scripts', 'enqueue_ab_testing_script');


register_activation_hook(__FILE__, 'ab_testify_activate');


function weave_testing_enqueue_styles() {
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


// Function to handle AJAX request for saving test data
function save_test_data() {
    // Check if data is received via POST request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json_data = file_get_contents('php://input');
        
        // Validate JSON data
        $decoded_data = json_decode($json_data, true);

        if ($decoded_data === null) {
            http_response_code(400); 
            echo json_encode(array('error' => 'Invalid JSON data'));
            exit();
        }

        $decoded_data['creation_date'] = current_time('Y-m-d H:i:s');

        
        $file_path = plugin_dir_path(__FILE__) . 'ab_testing_data.json'; 
        
        
        $existing_data = file_exists($file_path) ? json_decode(file_get_contents($file_path), true) : array();

        
        $existing_data[] = $decoded_data;

       
        $encoded_data = json_encode($existing_data, JSON_PRETTY_PRINT);

       
        if (file_put_contents($file_path, $encoded_data) !== false) {
            echo json_encode(array('success' => true));
            exit();
        } else {
            http_response_code(500); 
            echo json_encode(array('error' => 'Failed to save data'));
            exit();
        }
    } else {
        
        http_response_code(405); 
        echo json_encode(array('error' => 'Invalid request method'));
        exit();
    }
}

// Add action hook for handling AJAX request
add_action('wp_ajax_save_test_data', 'save_test_data');


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

add_shortcode('ab_testify_results', 'ab_testify_results_shortcode');

// Shortcode function to display A/B testing results



register_deactivation_hook(__FILE__, 'ab_testify_deactivate');

function ab_testify_deactivate() {
   
}



function ab_testify_load_results_page() {
    include_once(plugin_dir_path(__FILE__) . 'admin/results.php');
}

require_once(plugin_dir_path(__FILE__) . 'ab_test_traffic_split.php');



// Include admin files
include_once(plugin_dir_path(__FILE__) . 'admin/dashboard.php');
include_once(plugin_dir_path(__FILE__) . 'admin/add-test.php');
include_once(plugin_dir_path(__FILE__) . 'admin/results.php');
include_once(plugin_dir_path(__FILE__) . 'admin/ab_test_metrics.php');



?>
