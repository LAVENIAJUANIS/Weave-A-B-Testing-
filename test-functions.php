<?php
// Function to handle starting a new A/B test
function ab_testify_start_test() {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ab_testify_submit']) && $_POST['ab_testify_submit'] == 'Start Test') {
        // Sanitize and validate input data
        $creation_date = date('Y-m-d');
        $test_name = isset($_POST['test_name']) ? sanitize_text_field($_POST['test_name']) : '';
        $conversion_goals = isset($_POST['conversion_goals']) ? $_POST['conversion_goals'] : array();
        $selected_content_id = isset($_POST['content']) ? intval($_POST['content']) : 0;
        $selected_content_title = '';
        if ($selected_content_id) {
            $selected_content = get_post($selected_content_id);
            if ($selected_content) {
                $selected_content_title = $selected_content->post_title;
            }
        }
        $test_duration = isset($_POST['test_duration']) ? intval($_POST['test_duration']) : 0;

        // Prepare variations data
        $variations = array();

        if (isset($_POST['title_variation_checkbox']) && isset($_POST['title_variation_text'])) {
            $title_variations = array();
            $title_variation_text = sanitize_text_field($_POST['title_variation_text']);
            $title_variations[] = $title_variation_text;
            if (isset($_POST['second_input_field']) && !empty($_POST['second_input_field'])) {
                $second_input_field_value = sanitize_text_field($_POST['second_input_field']);
                $title_variations[] = $second_input_field_value;
            }
            // Separate variants with their own conversion rates and counts
            $variations['title'] = array();
            foreach ($title_variations as $variant) {
                $variations['title'][] = array(
                    'variant' => $variant,
                    'conversion_rate' => 0,
                    'conversion_count' => 0
                );
            }
        }

        if (isset($_POST['description_variation_checkbox']) && isset($_POST['description_variation_text'])) {
            $description_variations = array();
            $description_variation_text = sanitize_textarea_field($_POST['description_variation_text']);
            $description_variations[] = $description_variation_text;
            if (isset($_POST['second_description_field']) && !empty($_POST['second_description_field'])) {
                $second_description_field_value = sanitize_textarea_field($_POST['second_description_field']);
                $description_variations[] = $second_description_field_value;
            }
            // Separate variants with their own conversion rates and counts
            $variations['description'] = array();
            foreach ($description_variations as $variant) {
                $variations['description'][] = array(
                    'variant' => $variant,
                    'conversion_rate' => 0,
                    'conversion_count' => 0
                );
            }
        }
        

        // Handle other variations similarly...

        // Calculate impressions
        $total_variations = count($variations);
        $traffic_split = 0.5; // Splitting traffic equally between variations (50% each)
        $impressions_per_variation = ceil($traffic_split * $total_variations);

        // Track conversion events for each goal and variation
        $conversion_data = array();
        foreach ($conversion_goals as $goal) {
            $goal_data = array(
                'goal' => $goal,
                'variations' => array(),
            );

            foreach ($variations as $variation_key => $variation_data) {
                foreach ($variation_data as $variant_data) {
                    // Simulate conversion tracking by setting a random conversion rate
                    $conversion_rate = mt_rand(0, 100) / 100; // Random conversion rate between 0 and 1
                    $conversion_count = binomial_distribution($conversion_rate, $impressions_per_variation); // Simulate binomial distribution

                    // Store conversion data for each variant
                    $variant_conversion_data = array(
                        'variant' => $variant_data['variant'],
                        'conversion_rate' => $conversion_rate,
                        'conversion_count' => $conversion_count,
                    );

                    // Add variant conversion data to the goal data
                    $goal_data['variations'][$variation_key][] = $variant_conversion_data;
                }
            }

            // Add goal data to the conversion data
            $conversion_data[] = $goal_data;
        }

        // Prepare test data
        $test_data = array(
            'test_id' => uniqid(),
            'test_name' => $test_name,
            'conversion_goals' => $conversion_goals,
            'content_id' => $selected_content_id,
            'content_title' => $selected_content_title,
            'target_elements' => array(
                'title_variation' => isset($_POST['title_variation_checkbox']),
                'description_variation' => isset($_POST['description_variation_checkbox']),
                'image_variation' => isset($_POST['image_variation_checkbox']),
                'layout_variation' => isset($_POST['layout_variation_checkbox']),
            ),
            'test_duration' => $test_duration,
            'impressions_per_variation' => $impressions_per_variation,
            'variations' => $variations,
            'creation_date' => $creation_date,
            'conversion_data' => $conversion_data, // Add conversion data
        );

        // Load existing data from JSON file
        $existing_data_path = plugin_dir_path(__FILE__) . 'ab_testing_data.json';
        $existing_data = file_exists($existing_data_path) ? json_decode(file_get_contents($existing_data_path), true) : array();

        // Append new test data to existing data
        $existing_data[] = $test_data;

        // Encode and save updated data to JSON file
        $json_data = json_encode($existing_data, JSON_PRETTY_PRINT);
        if (file_put_contents($existing_data_path, $json_data) === false) {
            wp_die('Error writing to file.');
        } else {
            echo 'Data saved successfully.';
        }

        // Redirect to the dashboard after saving data
        wp_redirect(admin_url('admin.php?page=ab-testify-dashboard'));
        exit();
    }
}
