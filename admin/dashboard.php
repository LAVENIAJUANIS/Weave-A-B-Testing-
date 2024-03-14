<?php
// Add Dashboard page
add_action('admin_menu', 'ab_testify_add_dashboard_page');

function ab_testify_add_dashboard_page() {
    add_menu_page(
        'AB Testify Dashboard', 
        'Weave A/B Testing', 
        'manage_options', 
        'ab-testify-dashboard', 
        'ab_testify_dashboard_page', 
        'dashicons-chart-bar'
    );
}

// Dashboard page
function ab_testify_dashboard_page() {
    ?>
    <div class="wrap">
        <h1>Welcome to Weave A/B Testing</h1>
        <p>This plugin allows you to conduct A/B tests on your WordPress site.</p>
        <a href="<?php echo admin_url('admin.php?page=ab-testify-add-test'); ?>" class="button-primary">Add Test</a>
    </div>
    <?php
}
?>
