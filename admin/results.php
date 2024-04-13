<?php
// Include necessary files
require_once(__DIR__ . '/ab-testing-functions.php');
require_once(__DIR__ . '/add-test.php');

// Define view results page function
function ab_testify_view_results_page() {
    // Fetch the test data
    $test_data = load_test_data();
    
    // Check if test ID is provided in the URL
    if (isset($_GET['test_id'])) {
        $test_id = $_GET['test_id'];
        $test_name = $test_data[$test_id]['test_name'] ?? '';
        
        // Display the results for the specified test
        echo '<div class="wrap">';
        echo '<h1>Results for ' . esc_html($test_name) . '</h1>';
        echo '<h1>Title: ' . esc_html($test_data[$test_id]['content_title']) . '</h1>';

        // Fetch impressions and variations data
        $impressions = $test_data[$test_id]['impressions_per_variation'] ?? 0; // Fetch impressions per variation
        $variations = $test_data[$test_id]['variations'] ?? [];

       
       // Display Conversion Rates table
        echo '<h2>Conversion Rates</h2>';
        echo '<table class="widefat">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Variation</th>';
        echo '<th>Conversion Rate (%)</th>';
        echo '<th>Impressions</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($variations as $variation_key => $variation_data) {
            echo '<tr>';
            // Display the appropriate variation content based on the variation type
            if (strpos($variation_key, 'title_') === 0) {
                // Title variation
                $title_variation_number = substr($variation_key, 6);
                echo '<td>Title Variation ' . $title_variation_number . ': ' . esc_html($variation_data) . '</td>';
            } elseif (strpos($variation_key, 'description_') === 0) {
                // Description variation
                $description_variation_number = substr($variation_key, 12);
                echo '<td>Description Variation ' . $description_variation_number . ': ' . nl2br(esc_html($variation_data)) . '</td>';
            } elseif ($variation_key === 'image_1' && isset($variation_data['image_variation_url'])) {
                // Image variation
                echo '<td><img src="' . esc_url($variation_data['image_variation_url']) . '" style="max-width: 100px;" alt="Variation Image"></td>';
            } else {
                // Default case
                echo '<td>N/A</td>';
            }

            // Find the conversion rate and impressions for the current variation key
            $conversion_rate = null;
            $impressions_count = 0; // Initialize impressions count
            foreach ($test_data[$test_id]['conversion_data'] ?? [] as $goal_data) {
                foreach ($goal_data['variations'] ?? [] as $variation_conversion_data) {
                    if ($variation_conversion_data['variation_key'] === $variation_key) {
                        $conversion_rate = $variation_conversion_data['conversion_rate'];
                        $impressions_count = $variation_conversion_data['conversion_count']; // Fetch impressions count
                        break 2; // Break both loops once conversion rate and impressions count are found
                    }
                }
            }
            echo '<td>' . ($conversion_rate !== null ? $conversion_rate : 'N/A') . '</td>'; // Display conversion rate or "N/A" if not found
            echo '<td>' . $impressions_count . '</td>'; // Display impressions count
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';

        // Display Goal Completion table
        echo '<h2>Goal Completion</h2>';
        echo '<table class="widefat">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Variation</th>';
        // Display goal names as table headers
        foreach ($test_data[$test_id]['conversion_goals'] ?? [] as $goal) {
            echo '<th>' . esc_html($goal) . '</th>';
        }
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($variations as $variation_key => $variation_data) {
            echo '<tr>';
            // Display the appropriate variation content based on the variation type
            if (strpos($variation_key, 'title_') === 0) {
                // Title variation
                $title_variation_number = substr($variation_key, 6);
                echo '<td>Title Variation ' . $title_variation_number . ': ' . esc_html($variation_data) . '</td>';
            } elseif (strpos($variation_key, 'description_') === 0) {
                // Description variation
                $description_variation_number = substr($variation_key, 12);
                echo '<td>Description Variation ' . $description_variation_number . ': ' . nl2br(esc_html($variation_data)) . '</td>';
            } elseif ($variation_key === 'image_1' && isset($variation_data['image_variation_url'])) {
                // Image variation
                echo '<td><img src="' . esc_url($variation_data['image_variation_url']) . '" style="max-width: 100px;" alt="Variation Image"></td>';
            } else {
                // Default case
                echo '<td>N/A</td>';
            }

            // Check if the test data and conversion data are set
            if (isset($test_data[$test_id]) && isset($test_data[$test_id]['conversion_data'])) {
                // Loop through each conversion data for the current variation
                foreach ($test_data[$test_id]['conversion_data'][0]['variations'] as $variation_data) {
                    // Check if the variation key matches the current variation
                    if ($variation_data['variation_key'] === $variation_key) {
                        // Display the conversion count if available, otherwise display 'N/A'
                        echo '<td>' . ($variation_data['conversion_count'] ?? 'N/A') . '</td>';
                        break; // Break the loop since we found the matching variation
                    }
                }
            } else {
                // If test data or conversion data is not set, display 'N/A'
                echo '<td>N/A</td>';
            }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';



        // Call the function to render control version and variant boxes
        if (isset($test_data[$test_id]['content_id'])) {
            $control_screenshot_url = get_the_post_thumbnail_url($test_data[$test_id]['content_id']);
            $variant_screenshot_url = get_the_post_thumbnail_url($test_data[$test_id]['content_id']);
            render_control_and_variation_boxes($test_id, $test_data[$test_id], $control_screenshot_url, $variant_screenshot_url);
        }

        echo '</div>'; // End of wrap div
    }
}


// Function to render control and variation boxes
function render_control_and_variation_boxes($test_id, $test_data, $control_screenshot_url, $variant_screenshot_url) {
    // Output the HTML for comparison boxes
    echo '<div style="display: flex; justify-content: space-around; margin-top: 20px;">';

    // Box A for Control Version
    echo '<div style="width: 40%; border: 1px solid #ccc; padding: 5px;">'; // Modified width and padding
    echo '<h3>Control Version</h3>';

    // Output the screenshot or content preview of the control version
    if (!empty($control_screenshot_url)) {
        echo '<img src="' . esc_url($control_screenshot_url) . '" style="max-width: 100%;" alt="Control Version">';
    } else {
        echo '<p>No screenshot available</p>';
    }

    // Get Page Views, Conversions, and Conversion Rate for Control Version
    $control_metrics = render_variation_metrics($test_data['conversion_data'], 'control');

    // Output Page Views, Conversions, and Conversion Rate for Control Version
    echo $control_metrics;

    // Output a link to the control version's page
    echo '<p><a href="' . esc_url(get_permalink($test_data['content_id'])) . '">View Control Version</a></p>';
    echo '</div>';

    // Box B for Variation
    echo '<div style="width: 40%; border: 1px solid #ccc; padding: 5px;">'; // Modified width and padding
    echo '<h3>Variation</h3>';

    // Ensure the array keys exist and handle null values for title variation
    $title_variation = isset($test_data['variations']['title_1']) ? esc_html($test_data['variations']['title_1']) : '';
    // Output the title variation
    echo '<h4>Title Variation: ' . $title_variation . '</h4>';

    // Ensure the array keys exist and handle null values for description variation
    $description_variation = isset($test_data['variations']['description_1']) ? esc_html($test_data['variations']['description_1']) : '';
    // Output the description variation
    echo '<p>Description Variation: ' . $description_variation . '</p>';

    // Output the screenshot or content preview of the variation
    if (!empty($variant_screenshot_url)) {
        echo '<img src="' . esc_url($variant_screenshot_url) . '" style="max-width: 100%;" alt="Variation">';
    } else {
        echo '<p>No screenshot available</p>';
    }

    // Get Page Views, Conversions, and Conversion Rate for Variation
    $variation_metrics = render_variation_metrics($test_data['conversion_data'], 'variation');

    // Output Page Views, Conversions, and Conversion Rate for Variation
    echo $variation_metrics;

    // Get the permalink of the content page/post if the 'content_url' key exists and is not null
    $content_permalink = isset($test_data['content_url']) ? esc_url($test_data['content_url']) : '';

    // Append a different query parameter to the permalink for the variation link
    if (!empty($content_permalink)) {
        // Replace the original title with the variation title in the permalink for title variation
        $title_variation_link = esc_url(add_query_arg('ab_variation', urlencode($title_variation), $content_permalink));
        // Replace the original title with the variation description in the permalink for description variation
        $description_variation_link = esc_url(add_query_arg('ab_variation', urlencode($description_variation), $content_permalink));

        // Output a link to the content page/post with the variation applied for title variation
        echo '<p><a href="' . $title_variation_link . '">View Variation (Title)</a></p>';
        // Output a link to the content page/post with the variation applied for description variation
        echo '<p><a href="' . $description_variation_link . '">View Variation (Description)</a></p>';
    } else {
        echo '<p>No content available</p>';
    }

    echo '</div>';
    echo '</div>';
}


// Function to render Page Views, Conversions, and Conversion Rate for Control Version and Variation
function render_variation_metrics($conversion_data, $variation_type) {
    // Initialize variables to store metrics
    $page_views = 0;
    $conversions = 0;
    $conversion_rate = 0;

    // Loop through conversion data to find the specified variation type and fetch its metrics
    foreach ($conversion_data as $goal_data) {
        foreach ($goal_data['variations'] as $variation) {
            // Check if the variation key matches the specified variation type
            if ($variation['variation_key'] === $variation_type) {
                // Fetch the metrics for the specified variation
                $page_views = $variation['impressions'];
                $conversions = $variation['conversion_count'];
                $conversion_rate = $variation['conversion_rate'];
                break 2; // Break both loops once the variation is found
            }
        }
    }

    // Return Page Views, Conversions, and Conversion Rate for the specified variation type
    return '<p>Page Views (' . ucfirst($variation_type) . '): ' . $page_views . '</p>' .
           '<p>Conversions (' . ucfirst($variation_type) . '): ' . $conversions . '</p>' .
           '<p>Conversion Rate (' . ucfirst($variation_type) . '): ' . number_format($conversion_rate * 100, 2) . '%</p>';
}







?>
