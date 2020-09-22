<?php
/**
 * Template Name: Edit Profile Page
 */
acf_form_head();
get_header();
while ( have_posts() ) : the_post(); 

    get_template_part( 'template-parts/content', 'edit-profile' );

endwhile;
get_footer(); 