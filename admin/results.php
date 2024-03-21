<?php

// Include necessary files
require_once(__DIR__ . '/ab-testing-functions.php');

// Define view results page function
function ab_testify_view_results_page() {
    // Check if test ID and test name are provided in the URL
    if (isset($_GET['test_id'])) {
        $test_id = $_GET['test_id'];

        // Fetch the test data
        $test_data = load_test_data();
        $test_name = isset($test_data[$test_id]['test_name']) ? $test_data[$test_id]['test_name'] : '';

        // Function to calculate conversion rate for a variation
        function calculate_conversion_rate($impressions, $variations) {
            $conversion_count = 0;

            // Check if $variations is an array to avoid the error
            if (is_array($variations)) {
                foreach ($variations as $variation) {
                    if (isset($variation['converted']) && $variation['converted']) {
                        $conversion_count++;
                    }
                }
            } else {
                // Handle the case where $variations is not an array (optional)
                // For example, you could return a default value or log an error
            }

            return ($impressions > 0) ? number_format(($conversion_count / $impressions) * 100, 2) : 0;
        }

        // Display the results for the specified test
        echo '<div class="wrap">';
        echo '<h1>Results for ' . esc_html($test_name) . '</h1>';

        // Display the selected content
        $content_id = isset($test_data[$test_id]['content_id']) ? $test_data[$test_id]['content_id'] : '';
        $content_title = isset($test_data[$test_id]['content_title']) ? $test_data[$test_id]['content_title'] : '';
        if ($content_id && $content_title) {
            echo '<h2>Selected Content</h2>';
            echo '<p>Post/Page ID: ' . $content_id . '</p>';
            echo '<p>Title: ' . $content_title . '</p>';
        }

        // Fetch impressions and variations data
        $impressions = $test_data[$test_id]['impressions'];
        $variations = $test_data[$test_id]['variations'];

        // Include Chart.js library
        echo '<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>';

        // Display individual metrics for each variation
       // Fetch impressions and variations data
$impressions = $test_data[$test_id]['impressions'];
$title_variations = isset($test_data[$test_id]['variations']['title']) ? explode(', ', $test_data[$test_id]['variations']['title']) : [];

// Display individual metrics for each title variation
echo '<h2>Variations Conversion Rates</h2>';
echo '<div class="variation-metrics">';
if (!empty($title_variations)) {
    echo '<div class="variation-box-container" style="display: flex;">';
    foreach ($title_variations as $index => $title_variation) {
        $variation_name = 'title_variation_' . ($index + 1);
        echo '<div class="variation-box" style="border: 1px solid #6c757d; padding: 10px; margin-right: 10px;">'; // Added margin-right
        echo '<h3>Conversion Rate for ' . $title_variation . '</h3>'; // Display the actual title variation
        echo '<div class="variation-chart" id="' . $variation_name . 'ConversionRateChartContainer">';
        echo '<canvas id="' . $variation_name . 'ConversionRateChart" width="400" height="150"></canvas>'; // Increased canvas dimensions
        echo '</div>';
        echo '</div>';
        // Calculate conversion rate for this variation (assuming you have a function for this)
        $conversion_rate = calculate_conversion_rate($impressions, $title_variation);
        // JavaScript for rendering Chart.js graph
        echo '<script>';
        echo 'var ctx = document.getElementById("' . $variation_name . 'ConversionRateChart").getContext("2d");';
        echo 'var myChart = new Chart(ctx, {';
        echo 'type: "line",';
        echo 'data: {';
        echo 'labels: ["Conversion Rate"],';
        echo 'datasets: [{';
        echo 'label: "Conversion Rate (%)",';
        echo 'data: [' . $conversion_rate . '],';
        echo 'backgroundColor: "rgba(255, 99, 132, 0.2)",';
        echo 'borderColor: "rgba(108, 117, 125, 1)",'; // Changed border color to dark grey
        echo 'borderWidth: 1';
        echo '}]';
        echo '},';
        echo 'options: {';
        echo 'scales: {';
        echo 'yAxes: [{';
        echo 'ticks: {';
        echo 'beginAtZero: true';
        echo '}';
        echo '}]';
        echo '}';
        echo '}';
        echo '});';
        echo '</script>';
    }
    echo '</div>'; // .variation-box-container
} else {
    echo '<p>No variations data found.</p>';
}
echo '</div>'; // .variation-metrics


        // Comparison Table
        echo '<h2>Comparison Table</h2>';
        echo '<table class="widefat">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Test Name</th>';
        echo '<th>Variation</th>';
        echo '<th>Impressions</th>';
        echo '<th>Conversions</th>';
        echo '<th>Conversion Rate (%)</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

       // Display metrics for each variation in the test
        foreach ($variations as $variation_name => $variation_data) {
            echo '<tr>';
            echo '<td>' . esc_html($test_name) . '</td>';
            echo '<td>' . ucfirst($variation_name) . '</td>';
            echo '<td>' . $impressions . '</td>';
        
            // Check if $variation_data is an array before counting elements
            if (is_array($variation_data)) {
            echo '<td>' . count($variation_data) . '</td>';
            } else {
            // Handle the case where $variation_data is not an array (optional)
            // For example, you could echo a message like "Data unavailable"
            echo '<td>-</td>';
            }
        
            echo '<td>' . calculate_conversion_rate($impressions, $variation_data) . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        
        echo '</div>';
    }
}
  
?>

