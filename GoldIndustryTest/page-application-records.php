<?php
/**
 * Template Name: Application Records Page
 */
get_header();
while ( have_posts() ) : the_post(); 

    get_template_part( 'template-parts/content', 'application-records' );

endwhile;
get_footer(); 