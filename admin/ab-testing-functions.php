<?php 

function load_test_data() {
    // If using JSON file:
    $test_data_path = plugin_dir_path(__FILE__) . 'ab_testing_data.json';

    // Check if the JSON file exists
    if (!file_exists($test_data_path)) {
        // Handle the case where the JSON file does not exist
        error_log("Test data JSON file not found: $test_data_path");
        return false; // Or handle the error differently
    }

    // Read the JSON file contents
    $test_data_json = file_get_contents($test_data_path);

    // Check if reading the file failed
    if ($test_data_json === false) {
        error_log("Failed to read test data from JSON file: $test_data_path");
        return false;
    }

    // Decode the JSON data
    $decoded_data = json_decode($test_data_json, true);

    // Check if JSON decoding failed
    if ($decoded_data === null) {
        $json_error_message = json_last_error_msg();
        error_log("Failed to decode JSON data: $json_error_message");
        return false;
    }

    return $decoded_data;
}

function calculate_conversion_rate($impressions, $variation_data) {
    // Ensure $variation_data is an array
    if (!is_array($variation_data)) {
        return 0; // Return 0 if $variation_data is not an array
    }

    // Check if the 'conversion_count' key exists in $variation_data
    if (array_key_exists('conversion_count', $variation_data)) {
        $conversion_count = $variation_data['conversion_count'];

        // Ensure $impressions and $conversion_count are numeric
        if (is_numeric($impressions) && is_numeric($conversion_count) && $impressions > 0) {
            // Calculate conversion rate
            $conversion_rate = ($conversion_count / $impressions) * 100;
            return $conversion_rate;
        }
    }

    // Return 0 if any condition fails
    return 0;
}





  
?>
