<?php

function preview_title() {
    // Retrieve title variation and content ID from AJAX request
    $title_variation = isset($_POST['title_variation']) ? sanitize_text_field($_POST['title_variation']) : '';
    $content_id = isset($_POST['content_id']) ? intval($_POST['content_id']) : 0;

    // Update the title (assuming you're updating a post title)
    $post_data = array(
        'ID'         => $content_id,
        'post_title' => $title_variation
    );
    wp_update_post($post_data);

    // Generate the preview URL (customize this according to your needs)
    $preview_url = home_url('/preview-page.php?content_id=' . $content_id);

    // Return the preview URL as a JSON object
    wp_send_json_success(array('preview_url' => $preview_url));
}
add_action('wp_ajax_preview_title', 'preview_title');
add_action('wp_ajax_nopriv_preview_title', 'preview_title');
