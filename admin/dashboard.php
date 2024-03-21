<?php

// Add Dashboard page
add_action('admin_menu', 'ab_testify_add_dashboard_page');

function ab_testify_add_dashboard_page() {
    add_menu_page(
        'AB Testify Dashboard', 
        'Weave A/B Testing', 
        'manage_options', 
        'ab-testify-dashboard', 
        'ab_testify_dashboard_page', 
        'dashicons-chart-bar'
    );

    add_submenu_page(
        'ab-testify-dashboard', // Parent slug (dashboard page)
        'View Results', // Page title
        'View Results', // Menu title
        'manage_options', // Capability required to access the page
        'ab-testify-view-results', // Menu slug
        'ab_testify_view_results_page' // Callback function to display the page content
    );
}

// Dashboard page
function ab_testify_dashboard_page() {
    // Define the file path dynamically to be in the same directory as this plugin file
    $file_path = plugin_dir_path(__FILE__) . 'ab_testing_data.json';

    // Check if the file exists
    if (file_exists($file_path)) {
        // Retrieve test data from JSON file
        $json_data = file_get_contents($file_path);

        // Check if JSON data is valid
        $test_data = json_decode($json_data, true);

        // Check if JSON decoding was successful
        if ($test_data !== null) {
            ?>
            <div class="wrap">
                <h1>Welcome to Weave A/B Testing</h1>
                <p>This plugin allows you to conduct A/B tests on your WordPress site.</p>
                <a href="<?php echo admin_url('admin.php?page=ab-testify-add-test'); ?>" class="button-primary">Add Test</a>

                <h2>Test Data</h2>
                <table class="wp-list-table widefat striped">
                    <thead>
                        <tr>
                            <th scope="col">Test Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Page Views</th>
                            <th scope="col">Image Variation</th> <!-- Added Image Variation column -->
                            <th scope="col">Variation Title</th> <!-- Added Variation Title column -->
                            <th scope="col">Variation Description</th>
                            <th scope="col">Date Created</th> <!-- Changed column name -->
                            <th scope="col">Actions</th> <!-- Added Actions column -->
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($test_data as $key => $test) : ?>
                        <?php if (is_array($test) && isset($test['test_name'])) : ?>
                            <tr>
                                <td><?php echo esc_html($test['test_name']); ?></td>
                                <!-- Display test status -->
                                <td><?php echo get_test_status($test); ?></td>
                                <!-- Display impressions -->
                                <td><?php echo intval($test['impressions']); ?></td>
                                <td><?php echo isset($test['variations']['image']) ? '<img src="' . esc_url($test['variations']['image']) . '" style="max-width: 100px; max-height: 100px;" />' : ''; ?></td>
                                <td><?php echo isset($test['variations']['title']) ? esc_html($test['variations']['title']) : ''; ?></td>
                                <!-- Display variation description -->
                                <td><?php echo isset($test['variations']['description']) ? esc_html($test['variations']['description']) : ''; ?></td>
                                <!-- Display creation date -->
                                <td><?php echo isset($test['creation_date']) ? date('Y-m-d', strtotime($test['creation_date'])) : ''; ?></td>
                                <!-- Add View button for each test -->
                                <td>
                                    <a href="<?php echo admin_url('admin.php?page=ab-testify-view-results&test_id=' . $key); ?>" class="button" style="background-color: lightgreen; color: black;">View</a>
                                    <a href="<?php echo admin_url('admin.php?page=ab-testify-delete-test&test_id=' . $key); ?>" class="button" style="background-color: red; color: white;">Delete</a>
                                    <a href="<?php echo admin_url('admin.php?page=ab-testify-add-test&test_id=' . $key); ?>" class="button" style="background-color: lightblue; color: black;">Edit</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
                </table>
            </div>
            <?php
        } else {
            echo '<p>Error decoding JSON data.</p>';
        }
    } else {
        echo '<p>File not found: ' . esc_html($file_path) . '</p>';
    }
}


function get_test_status($test) {
    // Determine test status based on your criteria
    // For example, you can check if the test has reached a certain duration, or if enough data has been collected
    // Return appropriate status message
    return 'Active'; // Example status message
}

// Process deletion request
add_action('admin_init', 'ab_testify_process_delete_test');

function ab_testify_process_delete_test() {
    // Check if the user has the necessary capability
    if (!current_user_can('manage_options')) {
        wp_die(__('Sorry, you are not allowed to access this page.'));
    }

    // Check if the delete test action is triggered
    if (isset($_GET['action']) && $_GET['action'] === 'delete_test') {
        // Check if the test ID is provided
        if (isset($_GET['test_id'])) {
            $test_id = $_GET['test_id'];

            // Perform deletion logic here
            // For example, you can delete the test data associated with the provided test ID

            // After deleting the test, you may want to redirect the user to the dashboard or another appropriate page
            wp_redirect(admin_url('admin.php?page=ab-testify-dashboard'));
            exit();
        }
    }
}






?>
