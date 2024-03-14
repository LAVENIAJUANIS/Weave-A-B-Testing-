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
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($test_data as $key => $test) : ?>
                        <?php if (is_array($test) && isset($test['test_name'])) : ?>
                            <tr>
                                <td><?php echo esc_html($test['test_name']); ?></td>
                                <td><?php echo calculate_test_status($test); ?></td>
                                <td><?php echo intval($test['impressions']); ?></td>
                                <td><?php echo isset($test['creation_date']) ? date('Y-m-d', strtotime($test['creation_date'])) : ''; ?></td>
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



?>
