<?php
// Include necessary files
require_once(__DIR__ . '/ab-testing-functions.php');

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
            echo '<td>' . esc_html($variation_data) . '</td>'; 
            
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
        foreach ($variations as $variation_key => $variation_title) {
            echo '<tr>';
            echo '<td>' . esc_html($variation_title) . '</td>';
        
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


            echo '<h2>Statistical Significance</h2>';
            echo '<p>';
            
            // Perform a simple check for statistical significance (this is just an example)
            if (count($variations) > 1) {
              // Calculate the standard error of the mean for the control variation
              $control_variation_key = array_keys($variations)[0]; // Assuming the first variation is the control
              if (!isset($test_data[$test_id]['conversion_data'][$control_variation_key])) {
                echo 'Error: Control variation data not found.';
                return; // Exit if control data is missing
              }
            
              $control_variation_data = $test_data[$test_id]['conversion_data'][$control_variation_key];
              $control_conversion_rate = $control_variation_data['conversion_rate'] ?? 0; // Use default 0 if missing
              $control_sample_size = $control_variation_data['sample_size'] ?? 0; // Use default 0 if missing
            
              // Check for zero sample size to avoid division by zero
              if ($control_sample_size === 0) {
                echo 'Error: Control variation has zero sample size.';
                return; // Exit if control sample size is zero
              }
            
              $control_standard_error = sqrt(($control_conversion_rate * (1 - $control_conversion_rate)) / $control_sample_size);
            
              // Compare other variations to the control using a z-test
              foreach ($variations as $variation_key => $variation_data) {
                if ($variation_key !== $control_variation_key) {
                  if (!isset($test_data[$test_id]['conversion_data'][$variation_key])) {
                    echo 'Error: Variation ' . $variation_key . ' data not found.';
                    continue; // Skip to next variation if data is missing
                  }
            
                  $variation_conversion_data = $test_data[$test_id]['conversion_data'][$variation_key];
                  $variation_conversion_rate = $variation_conversion_data['conversion_rate'] ?? 0;
                  $variation_sample_size = $variation_conversion_data['sample_size'] ?? 0;
            
                  // Check for zero sample size to avoid division by zero
                  if ($variation_sample_size === 0) {
                    echo 'Error: Variation ' . $variation_key . ' has zero sample size.';
                    continue; // Skip to next variation if sample size is zero
                  }
            
                  $z_score = ($variation_conversion_rate - $control_conversion_rate) / sqrt(($control_standard_error ** 2) + ($variation_conversion_rate * (1 - $variation_conversion_rate)) / $variation_sample_size);
            
                  // Check if the z-score exceeds a critical value for significance (e.g., 1.96 for 95% confidence)
                  if (abs($z_score) >= 1.96) {
                    echo 'Variation ' . $variation_key . ' is statistically significant.';
                  } else {
                    echo 'Variation ' . $variation_key . ' is not statistically significant.';
                  }
                  echo '<br>';
                }
              }
            } else {
              echo 'Statistical significance calculation requires multiple variations.';
            }
            
            echo '</p>';
            


        // Display Graphical Representations
        echo '<h2>Graphical Representations</h2>';
        echo '<div style="display: flex; flex-wrap: wrap;">';
        foreach ($variations as $variation_key => $variation_data) {
            // Render the chart for each variation
            render_variation_chart($variation_key, $test_data[$test_id]['conversion_data'] ?? []);
        }
        echo '</div>'; // End of wrap div

        echo '</div>'; // End of wrap div
    }
}

// Function to render a variation chart
function render_variation_chart($variation_key, $conversion_data) {
    // Initialize arrays to store data for the chart
    $labels = [];
    $conversionRates = [];
    // Populate arrays with data for the chart
    foreach ($conversion_data as $goal_data) {
        foreach ($goal_data['variations'] ?? [] as $variation_conversion_data) {
            if ($variation_conversion_data['variation_key'] === $variation_key) {
                $labels[] = $goal_data['goal'] ?? '';
                $conversionRates[] = $variation_conversion_data['conversion_rate'] ?? '';
            }
        }
    }
    // Chart configuration
    $chartConfig = [
        'type' => 'bar',
        'data' => [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => ucfirst($variation_key),
                    'data' => $conversionRates,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1
                ]
            ]
        ],
        'options' => [
            'scales' => [
                'y' => [
                    'beginAtZero' => true
                ]
            ]
        ]
    ];

    // Render the chart
    echo '<div style="width: 50%;">';
    echo '<h3>' . ucfirst($variation_key) . ' Conversion Rates</h3>';
    echo '<canvas id="' . $variation_key . 'Chart"></canvas>';
    echo '</div>';
    echo '<script>';
    echo 'var ctx = document.getElementById("' . $variation_key . 'Chart").getContext("2d");';
    echo 'var myChart = new Chart(ctx, ' . json_encode($chartConfig) . ');';
    echo '</script>';
}
?>
