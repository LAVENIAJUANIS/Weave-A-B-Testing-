<?php

add_action('admin_menu', 'ab_testify_add_test_page');

function ab_testify_add_test_page() {
    add_submenu_page('ab-testify-dashboard', 'Add Test', 'Add Test', 'manage_options', 'ab-testify-add-test', 'ab_testify_test_page');
}

function ab_testify_test_page() {
    ?>
    <div class="wrap">
        <h1>Add Test</h1>
        <div class="card">       

            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="ab_testify_start_test">
                <h2>Test information</h2>
                <label for="test_name">Test Name:</label><br>
                <input type="text" id="test_name" name="test_name" required placeholder="Add your test name here..."><br>
                <h2>Conversion Goals</h2>
                <div class="card">
                    <div id="action-section">
                        <h3>Action</h3>
                       
                <label for="custom_conversion_goals">Custom Conversion Goals:</label><br>
                <input type="text" id="custom_conversion_goals" name="conversion_goals[]" placeholder="Enter custom conversion goals separated by commas"><br>

                        <div id="cta-dropdown" style="display: none;">
                            <label for="cta-select">Select CTA Button:</label>
                            <select id="cta-select" name="cta_select">
                                <!-- Options will be dynamically generated here -->
                            </select>
                        </div>

                        <div id="inquiry-dropdown" style="display: none;">
                            <label for="inquiry-select">Select Contact Form:</label>
                            <select id="inquiry-select" name="inquiry_select">
                                <?php
                                // Retrieve contact forms dynamically
                                $contact_forms = get_posts(array(
                                    'post_type' => 'wpcf7_contact_form', // Assuming using contact Form 7 plugin
                                    'posts_per_page' => -1,
                                    'fields' => 'ids'
                                ));
                                if ($contact_forms) {
                                    foreach ($contact_forms as $form_id) {
                                        $form_name = get_the_title($form_id);
                                        echo '<option value="' . esc_attr($form_id) . '">' . esc_html($form_name) . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No contact forms found</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card">
                <h2>Choose Content</h2>
                <label for="content-select">Select Content:</label>
                <select id="content-select" name="content" class="js-example-basic-single">
                    <?php
                    $args = array(
                        'post_type' => array('post', 'page'),
                        'posts_per_page' => -1,
                    );
                    $posts = get_posts($args);
                    if ($posts) {
                        foreach ($posts as $post) {
                            $post_title = get_the_title($post->ID);
                            $post_permalink = get_permalink($post->ID);
                            $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'thumbnail');
                            echo '<option value="' . esc_attr($post->ID) . '" data-thumbnail-url="' . esc_url($thumbnail_url) . '">' . esc_html($post_title) . '</option>';
                        }
                    } else {
                        echo '<option>No posts found</option>';
                    }
                    ?>
                </select>



                    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

                    <h2>Target Elements</h2>
                    <input type="checkbox" id="title_variation_checkbox" name="title_variation_checkbox" value="1" onchange="toggleTitleInput()">
                    <label for="title_variation_checkbox">Title</label><br>

                    <div id="title_variation_input" style="display: none;">
                        <label for="title_variation_text">Title Variation:</label><br>
                        <input type="text" id="title_variation_text" name="title_variation_text" placeholder="Add title variation here..."><br>
                        
                    <br><input type="text" id="second_input_field" name="second_input_field" placeholder="Add second title variation here...">
                    </div>




                    <br><input type="checkbox" id="description_variation_checkbox" name="description_variation_checkbox" value="1" onchange="toggleDescriptionInput()">
                    <label for="description_variation_checkbox">Description</label><br>

                    <div id="description_variation_input" style="display: none;">
                        <label for="description_variation_text">Description Variation:</label><br>
                        <textarea id="description_variation_text" name="description_variation_text" placeholder="Add description variation here..."></textarea><br>
                        
                        <textarea id="second_description_field" name="second_description_field" placeholder="Second Description Field"></textarea><br>
                    </div>


                    <br><input type="checkbox" id="image_variation_checkbox" name="image_variation_checkbox" value="1" onchange="toggleImageInput()">
                <label for="image_variation_checkbox">Image</label><br>

                



                    <br><input type="checkbox" id="layout_variation_checkbox" name="layout_variation_checkbox" value="1">
                    <label for="layout_variation_checkbox">Layout</label><br>

                    <h2>Test Duration</h2>
                    <select name="test_duration">
                        <option value="1">1 day</option>
                        <option value="7">1 week</option>
                        <option value="30">1 month</option>
                    </select><br><br>
                    <button type="submit" name="ab_testify_submit" value="Start Test" style="background-color: green; color: white;">Save</button>
                </div>
            </form>
        </div>
    </div>
    <?php
}

add_action('admin_post_ab_testify_start_test', 'ab_testify_process_test_submission');

// Function to handle form submission and process test data
function ab_testify_process_test_submission() {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ab_testify_submit']) && $_POST['ab_testify_submit'] == 'Start Test') {
        // Sanitize and validate input data
        $creation_date = date('Y-m-d');
        $test_name = isset($_POST['test_name']) ? sanitize_text_field($_POST['test_name']) : '';
        $conversion_goals = isset($_POST['conversion_goals']) ? $_POST['conversion_goals'] : array();
        $selected_content_id = isset($_POST['content']) ? intval($_POST['content']) : 0;
        // Fetch the selected content title and URL from the test data

        $selected_content_id = isset($_POST['content']) ? intval($_POST['content']) : 0;
        $selected_content_image_url = '';
        if ($selected_content_id) {
            $selected_content_image_url = get_the_post_thumbnail_url($selected_content_id, 'thumbnail');
        }

        $selected_content_image_url = '';
        if ($selected_content_id) {
            $selected_content_image_url = get_the_post_thumbnail_url($selected_content_id, 'thumbnail');
        }

        // Fetch the selected content title and URL from the test data
        $selected_content_title = get_the_title($selected_content_id);
        $selected_content_url = get_permalink($selected_content_id);



        $test_duration = isset($_POST['test_duration']) ? intval($_POST['test_duration']) : 0;

        // Prepare variations data
        $control_variation = ''; // Initialize control variation
        $variations = array();

        // Handle control variation
        if (isset($_POST['control_variation'])) {
            $control_variation = sanitize_text_field($_POST['control_variation']);
            $variations['control'] = $control_variation; // Store control variation separately
        }

        // Handle title variation
        if (isset($_POST['title_variation_checkbox']) && isset($_POST['title_variation_text'])) {
            $title_variations = array();
            $title_variation_text = sanitize_text_field($_POST['title_variation_text']);
            $title_variations[] = $title_variation_text;
            if (isset($_POST['second_input_field']) && !empty($_POST['second_input_field'])) {
                $second_input_field_value = sanitize_text_field($_POST['second_input_field']);
                $title_variations[] = $second_input_field_value;
            }
            // Store each title variation separately in the $variations array
            foreach ($title_variations as $index => $variation) {
                $variations['title_' . ($index + 1)] = $variation;
            }
        }

        // Handle description variation
        if (isset($_POST['description_variation_checkbox']) && isset($_POST['description_variation_text'])) {
            $description_variations = array();
            $description_variation_text = sanitize_textarea_field($_POST['description_variation_text']);
            $description_variations[] = $description_variation_text;
            if (isset($_POST['second_description_field']) && !empty($_POST['second_description_field'])) {
                $second_description_field_value = sanitize_textarea_field($_POST['second_description_field']);
                $description_variations[] = $second_description_field_value;
            }
            // Store each description variation separately in the $variations array
            foreach ($description_variations as $index => $variation) {
                $variations['description_' . ($index + 1)] = $variation;
            }
        }

        // Calculate impressions per variation, excluding control variation
        $total_variations = count($variations);
        $traffic_split = 0.7; 
        $impressions_per_variation = ceil($traffic_split * ($total_variations - 1)); // Exclude control variation

        // Track conversion events for each goal and variation
        $conversion_data = array();
        foreach ($conversion_goals as $goal) {
            $goal_data = array(
                'goal' => $goal,
                'variations' => array(),
            );

            // Track conversion data for variants, excluding control
            foreach ($variations as $variation_key => $variation_data) {
                if ($variation_key !== 'control') {
                    // Simulate conversion tracking by setting a random conversion rate
                    $conversion_rate = mt_rand(0, 100) / 100; // Random conversion rate between 0 and 1
                    $conversion_count = binomial_distribution($conversion_rate, $impressions_per_variation); // Simulate binomial distribution

                    // Store conversion data for each variation
                    $variation_conversion_data = array(
                        'variation_key' => $variation_key,
                        'conversion_rate' => $conversion_rate,
                        'conversion_count' => $conversion_count,
                    );

                    // Add variation conversion data to the goal data
                    $goal_data['variations'][] = $variation_conversion_data;
                }
            }

            // Add goal data to the conversion data
            $conversion_data[] = $goal_data;
        }

        // Prepare test data
        $test_data = array(
            'test_id' => uniqid(),
            'test_name' => $test_name,
            'control_variation' => $control_variation,
            'conversion_goals' => $conversion_goals,
            'content_id' => $selected_content_id,
            'content_title' => $selected_content_title,
            'content_url' => $selected_content_url,
            'test_duration' => $test_duration,
            'impressions_per_variation' => $impressions_per_variation,
            'variations' => $variations,
            'creation_date' => $creation_date,
            'conversion_data' => $conversion_data,
            'content_image_url' => $selected_content_image_url,

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

// Function to generate binomial distribution
function binomial_distribution($probability, $trials) {
    $successes = 0;
    for ($i = 0; $i < $trials; $i++) {
        if (mt_rand() / mt_getrandmax() < $probability) {
            $successes++;
        }
    }
    return $successes;
}
