
<?php
echo '<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>';

// Include necessary files
require_once(__DIR__ . '/ab-testing-functions.php');


// Placeholder function for calculating p-value
function calculate_p_value($test_data) {
    // Perform some dummy calculation or return a default value
    return 0.05; // Assuming a p-value of 0.05 for demonstration purposes
}
// Define function to render a variation chart
function render_variation_chart($variation_data, $conversion_data) {
    // Initialize arrays to store data for the chart
    $labels = [];
    $conversionRates = [];

    // Populate arrays with data for the chart
    foreach ($conversion_data as $goal_data) {
        foreach ($goal_data['variations'] ?? [] as $variation_conversion_data) {
            // Ensure $variation_conversion_data is an array and contains necessary keys
            if (is_array($variation_conversion_data) && isset($variation_conversion_data['variation_key'], $variation_conversion_data['conversion_rate'])) {
                // Check if variation_key matches the desired $variation_data
                if ($variation_conversion_data['variation_key'] === $variation_data) {
                    $labels[] = $goal_data['goal'] ?? '';
                    $conversionRates[] = $variation_conversion_data['conversion_rate'];
                }
            }
        }
    }

    // Capitalize $variation_data only if it's a string
    if (is_string($variation_data)) {
        $variation_label = ucfirst($variation_data);
    } else {
        $variation_label = $variation_data; // Use the original value if not a string
    }

    // Check if $variation_data is a string before using it in the HTML output
    if (is_string($variation_data)) {
        // Render the chart
        echo '<div style="width: 50%;">';
        echo '<h3>' . $variation_label . ' Conversion Rates</h3>';
        echo '<canvas id="' . $variation_data . 'Chart"></canvas>';
        echo '</div>';
        echo '<script>';
        echo 'var ctx = document.getElementById("' . $variation_data . 'Chart").getContext("2d");';
        echo 'var myChart = new Chart(ctx, ' . json_encode($chartConfig) . ');';
        echo '</script>';
    } else {
        // Display a warning message for invalid variation data
        echo '<p>Warning: Invalid variation data.</p>';
    }
}


// Define view results page function
function ab_testify_view_results_page() {
    // Check if test ID is provided in the URL
    if (isset($_GET['test_id'])) {
        $test_id = $_GET['test_id'];

        // Fetch the test data
        $test_data = load_test_data();
        $test_name = $test_data[$test_id]['test_name'] ?? '';

        // Display the results for the specified test
        echo '<div class="wrap">';
        echo '<h1>Results for ' . esc_html($test_name) . '</h1>';

        // Fetch impressions and variations data
        $impressions = $test_data[$test_id]['impressions'] ?? 0;
        $variations = $test_data[$test_id]['variations'] ?? [];

   // 1. Conversion Rates
    echo '<h2>Conversion Rates</h2>';
    echo '<table class="widefat">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Variation</th>';
    echo '<th>Conversion Rate (%)</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($variations as $variation_key => $variants) {
        foreach ($variants as $variant) {
            // Calculate conversion rate for each variant
            $conversion_rate = $variant['conversion_rate'];
            echo '<tr>';
            echo '<td>' . ucfirst($variant['variant']) . '</td>';
            echo '<td>' . $conversion_rate . '</td>';
            echo '</tr>';
        }
    }
    echo '</tbody>';
    echo '</table>';

        // 2. Conversion Counts
            echo '<h2>Conversion Counts</h2>';
            echo '<table class="widefat">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Variation</th>';
            // Add table headers for each goal
            foreach ($test_data[$test_id]['conversion_goals'] as $goal) {
                echo '<th>' . $goal . '</th>';
            }
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($variations as $variation_key => $variants) {
                foreach ($variants as $variant) {
                    echo '<tr>';
                    echo '<td>' . ucfirst($variant['variant']) . '</td>';
                    // Iterate over each goal to display its completion count for the current variant
                    foreach ($test_data[$test_id]['conversion_data'] as $goal_data) {
                        foreach ($goal_data['variations'][$variation_key] as $variation_data) {
                            if ($variation_data['variant'] === $variant['variant']) {
                                echo '<td>' . $variation_data['conversion_count'] . '</td>';
                            }
                        }
                    }
                    echo '</tr>';
                }
            }
            echo '</tbody>';
            echo '</table>';


       // 3. Statistical Significance Calculation
        echo '<h2>Statistical Significance</h2>';
        $p_value = calculate_p_value($test_data); // Implement calculate_p_value function based on chosen statistical test
        $significance_level = 0.05;
        if ($p_value < $significance_level) {
            echo '<p>Results are statistically significant.</p>';
        } else {
            echo '<p>Results are not statistically significant.</p>';
        }

        // 4. Graphical Representations
        echo '<h2>Graphical Representations</h2>';
        echo '<div style="display: flex; flex-wrap: wrap;">';

        // Loop through all variations and render charts for each one
        foreach ($variations as $variation_data) {
            render_variation_chart($variation_data, $test_data[$test_id]['conversion_data'] ?? []);
        }

}
}
?>