<?php
function create_post_type_application_records() {

    register_post_type( 'application_records',
        array(
            'labels' => array(
                'name' => __( 'Application Records' ),
                'singular_name' => __( 'Application Record' ),
                'add_new' => __('Add New'),
                'add_new_item' => __('Add New Application Record'),
                'edit' => __('Edit'),
                'edit_item' => __('Edit Application Record'),
                'new_item' => __('New Application Record'),
                'view' => __('View Application Record'),
                'view_item' => __('View Application Record'),
                'search_items' => __('Search Application Record'),
                'not_found' => __('No Application Record found'),
                'not_found_in_trash' => __('No Application Record found in Trash')
            ),
            'public' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'rewrite'  => array( 'slug' => 'application_records', 'with_front' => false ),
            'supports' => array('title'),
            'can_export' => true,
        )
    );
  
} 
add_action( 'init', 'create_post_type_application_records' );