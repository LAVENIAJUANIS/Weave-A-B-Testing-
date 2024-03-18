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

        // Function to calculate metrics
        function calculate_test_metrics($test) {
            // Check if test data is available
            if (isset($test['impressions']) && isset($test['interactions'])) {
                // Calculate impressions
                $impressions = $test['impressions'];

                // Calculate interactions
                $interaction_count = count($test['interactions']);

                // Calculate conversions
                $conversion_count = 0;
                foreach ($test['interactions'] as $interaction) {
                    if (isset($interaction['converted']) && $interaction['converted']) {
                        $conversion_count++;
                    }
                }

                // Calculate conversion rates (percentage with two decimal places)
                $conversion_rate = ($impressions > 0) ? number_format(($conversion_count / $impressions) * 100, 2) : 0;

                // Return metrics for this test
                return array(
                    'impressions' => $impressions,
                    'interactions' => $interaction_count,
                    'conversions' => $conversion_count,
                    'conversion_rate' => $conversion_rate
                );
            } else {
                return false; // Return false if no test data is available
            }
        }

        // Display the results for the specified test
        echo '<div class="wrap">';
        echo '<h1>Results for ' . esc_html($test_name) . '</h1>';

        // Calculate metrics for the specified test ID
        $test_metrics = calculate_test_metrics($test_data[$test_id]);

        // Check if metrics are available for the test
        if ($test_metrics) {
            echo '<p>Here are the results of ' . esc_html($test_name) . ':</p>';

            // Display individual metrics
            echo '<h2>Impressions</h2>';
            echo '<p>Total number of times the test variations were shown to users: ' . $test_metrics['impressions'] . '</p>';

            echo '<h2>Interactions</h2>';
            echo '<p>Total number of interactions with the test variations: ' . $test_metrics['interactions'] . '</p>';

            echo '<h2>Conversions</h2>';
            echo '<p>Total number of conversions achieved: ' . $test_metrics['conversions'] . '</p>';

            echo '<h2>Conversion Rate</h2>';
            echo '<div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;">'; // Inline CSS for the graph box
            echo '<canvas id="conversionRateChart" width="400" height="200"></canvas>'; // Adjust width and height here
            echo '</div>';

            // JavaScript for rendering Chart.js graph
            echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
            echo '<script>';
            echo 'var ctx = document.getElementById("conversionRateChart").getContext("2d");';
            echo 'var myChart = new Chart(ctx, {';
            echo 'type: "bar",';
            echo 'data: {';
            echo 'labels: ["Conversion Rate"],';
            echo 'datasets: [{';
            echo 'label: "Conversion Rate (%)",';
            echo 'data: [' . $test_metrics['conversion_rate'] . '],';
            echo 'backgroundColor: "rgba(255, 99, 132, 0.2)",';
            echo 'borderColor: "rgba(255, 99, 132, 1)",';
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
        } else {
            // No metrics available for this test
            echo '<p>No metrics available for this test.</p>';
        }

        // Comparison Table
        echo '<h2>Comparison Table</h2>';
        echo '<table class="widefat">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Test Name</th>';
        echo '<th>Impressions</th>';
        echo '<th>Interactions</th>';
        echo '<th>Conversions</th>';
        echo '<th>Conversion Rate (%)</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // Loop through each test and display its metrics
        foreach ($test_data as $test_id => $test) {
            $test_metrics = calculate_test_metrics($test);
            if ($test_metrics) {
                echo '<tr>';
                echo '<td>' . esc_html($test['test_name']) . '</td>';
                echo '<td>' . $test_metrics['impressions'] . '</td>';
                echo '<td>' . $test_metrics['interactions'] . '</td>';
                echo '<td>' . $test_metrics['conversions'] . '</td>';
                echo '<td>' . $test_metrics['conversion_rate'] . '</td>';
                echo '</tr>';
            }
        }

        echo '</tbody>';
        echo '</table>';

        echo '</div>';
    } else {
        // If test ID is not provided in the URL, display an error message
        echo '<div class="wrap">';
        echo '<h1>Error: Test ID not provided</h1>';
        echo '<p>Please select a test to view results.</p>';
        echo '</div>';
    }
}
?>




?>