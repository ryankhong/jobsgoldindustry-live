<?php
/**
 * Template Name: Profile Page
 */
acf_form_head();
get_header();
while ( have_posts() ) : the_post(); 

    get_template_part( 'template-parts/content', 'profile' );

endwhile;
get_footer(); 