<?php
/*
Template Name: Two Column Layout
*/
get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <div class="row">

            <div class="col-md-6">
                <?php
                // Start the loop.
                while (have_posts()) : the_post();

                    // Include the single post/content template.
                    get_template_part('template-parts/content', 'single');

                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;

                    // End of the loop.
                endwhile;
                ?>
            </div><!-- .col-md-6 -->

            <div class="col-md-6">
                <!-- Your secondary content here -->
            </div><!-- .col-md-6 -->

        </div><!-- .row -->

    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
