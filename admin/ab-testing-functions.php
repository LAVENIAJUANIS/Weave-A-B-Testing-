<?php 
function load_test_data() {
    // If using JSON file:
    $test_data_path = plugin_dir_path(__FILE__) . 'ab_testing_data.json';

    // Check if the JSON file exists
    if (file_exists($test_data_path)) {
        // Read the JSON file contents
        $test_data_json = file_get_contents($test_data_path);

        // Check if reading the file failed
        if ($test_data_json === false) {
            error_log("Failed to read test data from JSON file: " . $test_data_path);
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
    } else {
        // Handle the case where the JSON file does not exist
        return false; // Or handle the error differently
    }
}

  
?>
