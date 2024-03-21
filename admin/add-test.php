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
                        <input type="checkbox" id="goal-cta" name="conversion_goals[]" value="cta" onchange="toggleCTADropdown()">
                        <label for="goal-cta">Clicking CTA</label><br>

                        <div id="cta-dropdown" style="display: none;">
                            <label for="cta-select">Select CTA Button:</label>
                            <select id="cta-select" name="cta_select">
                                <!-- Options will be dynamically generated here -->
                            </select>
                        </div>

                        <input type="checkbox" id="goal-inquiry" name="conversion_goals[]" value="inquiry" onchange="toggleInquiryDropdown()">
                        <label for="goal-inquiry">Contact Form Inquiries</label><br>

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
                    <h2> Choose Content</h2>
                    <label for="content-select">Select Content:</label>
                    <select id="content-select" name="content" onchange="updateTargetElement()">
                        <?php
                        $args = array(
                            'post_type' => array('post', 'page'),
                            'posts_per_page' => -1,
                        );
                        $posts = get_posts($args);
                        if ($posts) {
                            $post_categories = array();
                            foreach ($posts as $post) {
                                $category = get_the_category($post->ID);
                                if (!empty($category)) {
                                    $category_name = $category[0]->name;
                                    $post_categories[$category_name][] = $post;
                                } else {
                                    $post_categories['Page category'][] = $post;
                                }
                            }

                            foreach ($post_categories as $category_name => $category_posts) {
                                echo '<optgroup label="' . esc_attr($category_name) . '">';
                                foreach ($category_posts as $post) {
                                    $post_title = get_the_title($post->ID);
                                    $post_title = apply_filters('the_title', $post_title, $post->ID);
                                    echo '<option value="' . esc_attr($post->ID) . '">' . esc_html($post_title) . '</option>';
                                }
                                echo '</optgroup>';
                            }
                        } else {
                            echo '<option>No posts found</option>';
                        }
                        ?>
                    </select>

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

// Function to handle AJAX request for saving test data
// Function to handle AJAX request for saving test data
function ab_testify_process_test_submission() {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ab_testify_submit']) && $_POST['ab_testify_submit'] == 'Start Test')  {
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

        $variations = array();

        if (isset($_POST['title_variation_checkbox']) && isset($_POST['title_variation_text'])) {
            $title_variations = array();
            $title_variation_text = sanitize_text_field($_POST['title_variation_text']);
            $title_variations[] = $title_variation_text;
            if (isset($_POST['second_input_field']) && !empty($_POST['second_input_field'])) {
                $second_input_field_value = sanitize_text_field($_POST['second_input_field']);
                $title_variations[] = $second_input_field_value;
            }
            $combined_title_variation = implode(', ', $title_variations);
            $variations['title'] = $combined_title_variation;
        }

        if (isset($_POST['description_variation_checkbox']) && isset($_POST['description_variation_text'])) {
            $description_variations = array();
            $description_variation_text = sanitize_textarea_field($_POST['description_variation_text']);
            $description_variations[] = $description_variation_text;
            if (isset($_POST['second_description_field']) && !empty($_POST['second_description_field'])) {
                $second_description_field_value = sanitize_textarea_field($_POST['second_description_field']);
                $description_variations[] = $second_description_field_value;
            }
            $combined_description_variation = implode(', ', $description_variations);
            $variations['description'] = $combined_description_variation;
        }

        // Handle image variations separately
        if (isset($_FILES['image_variation']) && !empty($_FILES['image_variation']['tmp_name'])) {
            // Validate the uploaded image
            $image_file = $_FILES['image_variation'];
            if ($image_file['error'] === UPLOAD_ERR_OK) {
                // Read the image file into a string
                $image_data = file_get_contents($image_file['tmp_name']);

                // Convert the image data to base64 format
                $base64_image = base64_encode($image_data);

                // Add the base64 image to the test data
                $variations['image'] = $base64_image;
            }
        }

        if (isset($_POST['layout_variation_checkbox']) && isset($_POST['layout_variation'])) {
            $variations['layout'] = sanitize_text_field($_POST['layout_variation']);
        }

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
            'impressions' => 0,
            'variations' => $variations,
            'creation_date' => $creation_date,
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