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
                                <td><?php echo isset($test['impressions']) ? intval($test['impressions']) : 0; ?></td>
                                <td>
                                <?php if (isset($test['variations']) && isset($test['variations']['image'])) : ?>
                                    <?php $image_url = esc_url($test['variations']['image']); ?>
                                    <?php if (!empty($image_url)) : ?>
                                        <img src="<?php echo $image_url; ?>" style="max-width: 100px; max-height: 100px;" />
                                    <?php else : ?>
                                        <p>Error: Image not found.</p>
                                    <?php endif; ?>
                                <?php else : ?>
                                    No Image
                                <?php endif; ?>
                            </td>

                                <td><?php echo isset($test['variations']['title']) ? esc_html($test['variations']['title']) : ''; ?></td>
                                <!-- Display variation description -->
                                <td><?php echo isset($test['variations']['description']) ? esc_html($test['variations']['description']) : ''; ?></td>
                                <!-- Display creation date -->
                                <td><?php echo isset($test['creation_date']) ? date('Y-m-d', strtotime($test['creation_date'])) : ''; ?></td>
                                <!-- Add View button for each test -->
                                <td>
                                    <a href="<?php echo admin_url('admin.php?page=ab-testify-view-results&test_id=' . $key); ?>" class="button" style="background-color: lightgreen; color: black;">View</a>
                                    <a href="<?php echo admin_url('admin.php?page=ab-testify-delete-test&test_id=' . $key); ?>" class="button" style="background-color: red; color : white;">Delete</a>
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
    $test_duration_days = $test['test_duration'];
    $creation_date = strtotime($test['creation_date']);
    $end_date = strtotime("+$test_duration_days days", $creation_date);
    $current_date = time();

    $percentage = 0;
    if ($current_date < $end_date) {
        $percentage = ($current_date - $creation_date) / ($end_date - $creation_date) * 100;
    } else {
        $percentage = 100;
    }

    $percentage = round($percentage, 2);

    // Return a bar graph instead of a string
    $graph_width = min(100, $percentage);
    $status = '<div class="ab-test-status-bar-graph">';
    $status .= '<div class="ab-test-status-bar-graph-inner" style="width: ' . $graph_width . '%;"></div>';
    $status .= '</div>';
    $status .= '<div class="ab-test-status-percentage">' . $percentage . '%</div>';

    // Include CSS
    $status .= '<style>';
    $status .= '.ab-test-status-bar-graph {';
    $status .= '  width: 100px;';
    $status .= '  height: 10px;';
    $status .= '  border: 1px solid #ccc;';
    $status .= '  overflow: hidden;';
    $status .= '}';
    $status .= '.ab-test-status-bar-graph-inner {';
    $status .= '  height: 100%;';
    $status .= '  background-color: green;';
    $status .= '  transition: width 0.5s;';
    $status .= '}';
    $status .= '.ab-test-status-percentage {';
    $status .= '  display: inline-block;';
    $status .= '  margin-left: 5px;';
    $status .= '  font-size: 12px;';
    $status .= '}';
    $status .= '</style>';

    return $status;
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

