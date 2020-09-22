<?php

if( isset($_POST['action']) ){
    if ($_POST['action'] == 'print_csv'){
        $currentUserID = get_current_user_id();
        $userMeta = get_userdata($currentUserID);
        $statCountArr = array();
    
        if( current_user_can( 'manage_options' ) ) {
            $args = array( 
                'posts_per_page'   => -1,
                'post_type'     => 'job_listing',
            );
        }else{
            $args = array( 
                'posts_per_page'   => -1,
                'post_type'     => 'job_listing',
                'author'        => $currentUserID,
            );
        }
        $allJobs = new WP_Query( $args );
    
        if ( $allJobs->have_posts() ) {
            while ( $allJobs->have_posts() ) : $allJobs->the_post();
    
            $visitCount = ( get_field('visit_count') ) ? get_field('visit_count') : '0';
            $clickCount = ( get_field('apply_count') ) ? get_field('apply_count') : '0';
    
                $statCountArr[] = array(
                    'title' => get_the_title(get_the_ID() ),
                    'visitCount' => $visitCount,
                    'clickCount' => $clickCount,
                    'companyName' => get_post_meta( get_the_ID()  , '_company_name' , true ),
                    'created' => get_the_author_meta('display_name')
                );
                
            endwhile;
        } wp_reset_query();
    
        // output headers so that the file is downloaded rather than displayed
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="statistics-'.date('Y-m-d').'.csv"');
        
        // do not cache the file
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // create a file pointer connected to the output stream
        $file = fopen('php://output', 'w');
        
        // send the column headers
        fputcsv($file, array('Company Name', 'Created' , 'Job Name', 'Visit Count', 'Click Count'));
        
        
        if( is_array($statCountArr) && !empty($statCountArr) ){
            foreach( $statCountArr as $statCount ){
                $row = array(
                    $statCount['companyName'],
                    $statCount['created'],
                    $statCount['title'],
                    $statCount['visitCount'],
                    $statCount['clickCount']
                );
                fputcsv($file, $row);
            }
        }
        
        exit();
    }
}

/**
 * Template Name: Job Stat Page
 */
get_header();
while ( have_posts() ) : the_post(); 

    get_template_part( 'template-parts/content', 'stat' );

endwhile;
get_footer(); 