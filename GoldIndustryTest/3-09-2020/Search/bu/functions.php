<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

add_filter('show_admin_bar', '__return_false');

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array( 'workscout-base','workscout-responsive','workscout-font-awesome' ) );
        wp_enqueue_style('datatables-styles','https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css', array(),'1.0','screen');
        wp_enqueue_style('styles-updates',get_stylesheet_directory_uri().'/styles-update.css', array(),'1.0','screen');
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

function theme_front_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('datatables-js','https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js', array('jquery'),'1.0',true);
    wp_enqueue_script('moment-js',get_stylesheet_directory_uri().'/js/moment.min.js', array('jquery'),'1.0',true);
	wp_enqueue_script('custom-js',get_stylesheet_directory_uri().'/js/custom.js', array('jquery'),'1.0',true);
	$customArr = array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    );
    wp_localize_script( 'custom-js', 'CUSTOM_PARAMS', $customArr );
}
add_action('wp_enqueue_scripts', 'theme_front_scripts');


// Shortcode for jobs ticker
function dc_job_ticker_func($attr){
    $a = shortcode_atts( array(
        'post_id' => 0,
    ), $attr ); 

    $job_listing = new WP_Query( array( 
        'post_type' => 'job_listing' , 
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key'     => '_filled',
                'value'   => true,
                'compare' => '!=',
            ),
        ), 
    ) );

    $job_count = $job_listing->post_count;
    
    return '<div class="jobs_ticker">We have '.$job_count.' job offers for you!</div>';
}
add_shortcode( 'job-ticker', 'dc_job_ticker_func');


// END ENQUEUE PARENT ACTION

// add_filter( 'login_url', 'my_login_page', 10, 2 );
// function my_login_page( $login_url, $redirect ) {
// 	 if( is_user_logged_in() ) {
// 		 $user = wp_get_current_user();
// 		 if ( in_array( 'Candidate', (array) $user->roles ) ) {
// 		 	$redirect = "/";
// 		 }
// 	 }
//     return home_url( '/?redirect_to=' . $redirect );
// }

include 'imports/breezy-import.php';
include 'imports/equest-xml-import.php';
include 'imports/import-jobs.php';
include 'imports/import-config.php';
include 'includes/custom_post_types.php';

/* Salary */

add_filter( 'submit_job_form_fields', 'frontend_add_salary_field' );

function frontend_add_salary_field( $fields ) {
    $fields['job']['job_salary'] = array(
      'label'       => __( 'Salary ($)', 'job_manager' ),
      'type'        => 'text',
      'required'    => true,
      'placeholder' => 'e.g. $20,000',
      'priority'    => 7
    );
    return $fields;
  }

add_filter( 'job_manager_job_listing_data_fields', 'admin_add_salary_field' );

function admin_add_salary_field( $fields ) {
    $fields['_job_salary'] = array(
      'label'       => __( 'Salary ($)', 'job_manager' ),
      'type'        => 'text',
      'placeholder' => 'e.g. $20,000',
      'description' => ''
    );
    return $fields;
  }

  /* Job Info */
  add_filter( 'submit_job_form_fields', 'frontend_add_job_important_info_field' );

  function frontend_add_job_important_info_field( $fields ) {
      $fields['job']['job_important_info'] = array(
        'label'       => __( 'Job Important Info', 'job_manager' ),
        'type'        => 'text',
        'required'    => true,
        'placeholder' => 'e.g Work visa required',
        'priority'    => 7
      );
      return $fields;
    }

add_filter( 'job_manager_job_listing_data_fields', 'admin_add_job_important_info_field' );

function admin_add_job_important_info_field( $fields ) {
    $fields['_job_important_info'] = array(
      'label'       => __( 'Job Important Info', 'job_manager' ),
      'type'        => 'text',
      'placeholder' => 'e.g Work visa required',
      'description' => ''
    );
    return $fields;
  }



  /* Salary Min */
  add_filter( 'submit_job_form_fields', 'frontend_add_job_important_info_field' );

  function frontend_add_salary_min_field( $fields ) {
      $fields['job']['salary_min'] = array(
        'label'       => __( 'Salary Min', 'job_manager' ),
        'type'        => 'text',
        'required'    => true,
        'placeholder' => '$0',
        'priority'    => 7
      );
      return $fields;
    }

add_filter( 'job_manager_job_listing_data_fields', 'admin_add_job_important_info_field' );

function admin_add_salary_min_field( $fields ) {
    $fields['_salary_min'] = array(
      'label'       => __( 'Salary Min', 'job_manager' ),
      'type'        => 'text',
      'placeholder' => '$150,000',
      'description' => ''
    );
    return $fields;
  }


// Company Name Select //
  
// add_filter( 'job_manager_job_listing_data_fields', 'admin_add_company_select' );

// function admin_add_company_select($fields) {
    
//     $fields = $fields['dc_company_select']; 

//     $fields = $fields['choices'];

//     return $fields;

// }

// //

add_filter( 'submit_job_form_fields', 'custom_submit_job_form_fields' );

function custom_submit_job_form_fields( $fields ) {

    $currentUserID = get_current_user_id();

	$fields['job']['job_description']['description'] = "Please enter a detailed description about the job and its responsibilities here.";
    $fields['job']['job_salary']['priority'] = 5;
    $fields['job']['job_salary']['required'] = true;
    $fields['job']['job_important_info']['priority'] = 6;
    $fields['job']['job_important_info']['required'] = false;
    $fields['job']['job_important_info']['description'] = "Please enter important information here (E.g. Roster, work visa, residential position etc.)";
    
    $fields['job']['job_description']['priority'] = 7;
    $fields['job']['application']['priority'] = 8;

    $fields['job']['job_location']['value'] = get_field('dc_company_address' , 'user_'.$currentUserID);
    
    $fields['company']['company_logo']['description'] = "Maximum file size: 2 MB. Only allow JPEG images.";
    $fields['company']['company_logo']['allowed_mime_types'] = array('jpeg' => 'image/jpeg' , 'jpg' => 'image/jpeg');
    

    return $fields;
}

function workscout_login_form_fields() {
 
    ob_start(); ?>
        <div class="entry-header">
            <h3 class="headline margin-bottom-20"><?php esc_html_e('Login','workscout'); ?></h3>
        </div>
        <?php  $loginpage = Kirki::get_option( 'workscout', 'pp_login_workscout_page' );  ?>

        <?php
        // show any error messages after form submission
        workscout_show_error_messages(); ?>
 
        <form id="workscout_login_form"  class="workscout_form" action="" method="post">
            <p class="status"></p>
            <fieldset>
                <p>
                    <label for="workscout_user_Login"><?php _e('Username','workscout'); ?>
                    <i class="ln ln-icon-Male"></i><input name="workscout_user_login" id="workscout_user_login" class="required" type="text"/>
                    </label>
                </p>
                <p>
                    <label for="workscout_user_pass"><?php _e('Password','workscout'); ?>
                    <i class="ln ln-icon-Lock-2"></i><input name="workscout_user_pass" id="workscout_user_pass" class="required" type="password"/>
                    </label>
                </p>
                <p>
                    <input type="hidden" id="workscout_login_nonce" name="workscout_login_nonce" value="<?php echo wp_create_nonce('workscout-login-nonce'); ?>"/>
                    <input type="hidden" name="workscout_login_check" value="1"/>
                    <?php  wp_nonce_field( 'ajax-login-nonce', 'security' );  ?>
                    <input id="workscout_login_submit" type="submit" value="<?php esc_attr_e('Login','workscout'); ?>"/>
                </p>
                <p><?php esc_html_e('Don\'t have an account?','workscout'); ?> <a href="<?php echo get_permalink($loginpage); ?>?action=register"><?php esc_html_e('Sign up as a Job Seeker now','workscout'); ?></a>!</p>
                <p>Employer accounts available to <a href="http://www.goldindustrygroup.com.au/membership-form" target="_blank" rel="noopener noreferrer">Gold Industry Group members</a> only.</p>
                <p><a href="<?php echo wp_lostpassword_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e('Lost Password?','workscout'); ?>"><?php esc_html_e('Lost Password?','workscout'); ?></a></p>
    
            </fieldset>
        </form>
    <?php
    return ob_get_clean();
}

function workscout_registration_form_fields() {
 
    ob_start(); ?>  
        <div class="entry-header">
            <h3 class="headline margin-bottom-20"><?php esc_html_e('Job Seeker Sign up','workscout'); ?></h3>
        </div>
 
        <?php 
        // show any error messages after form submission
        workscout_show_error_messages(); ?>
 
        <form id="workscout_registration_form" class="workscout_form" action="" method="POST">
            <p class="status"></p>
            <fieldset>
                <p>
                    <label for="workscout_user_login"><?php _e('Username','workscout'); ?>
                    <i class="ln ln-icon-Male"></i><input name="workscout_user_login" id="workscout_user_login" class="required" type="text"/>
                    </label>
                </p>
                <p>
                    <label for="workscout_user_email"><?php _e('Email','workscout'); ?>
                    <i class="ln ln-icon-Mail"></i><input name="workscout_user_email" id="workscout_user_email" class="required" type="email"/>
                    </label>
                </p>
                <?php   
                /* $role_status  = Kirki::get_option( 'workscout','pp_singup_role_status', false);
                $role_revert  = Kirki::get_option( 'workscout','pp_singup_role_revert', false);
                if(!$role_status) { */?>
                <!-- <p>
                <?php 
                    /* echo '<label for="workscout_user_role">'.esc_html__('I want to register as','workscout').'</label>';
                    echo '<select name="workscout_user_role" id="workscout_user_role" class="input chosen-select">';
                    if($role_revert){
                        echo '<option value="candidate">'.esc_html__("Candidate","workscout").'</option>';
                    }
                        echo '<option value="employer">'.esc_html__("Employer","workscout").'</option>';
                    if(!$role_revert){
                        echo '<option value="candidate">'.esc_html__("Candidate","workscout").'</option>';
                    }
                    echo '</select>'; */
                ?>
                </p> -->
                <?php //} ?>
                <p>
                    <label for="workscout_agree">
                    <input name="workscout_agree" id="workscout_agree" type="checkbox" checked="checked" />
                        <?php _e('Sign up to receive gold job updates and news','workscout'); ?>
                    </label>
                </p>
                <?php $recaptcha  = Kirki::get_option( 'workscout','pp_woo_recaptcha', false);

                if($recaptcha){ ?>
                
                <p class="form-row captcha_wrapper">
                    <div class="g-recaptcha" data-sitekey="<?php echo get_option('job_manager_recaptcha_site_key'); ?>"></div>
                </p>
                <?php } ?>
                <p style="display:none">
                    <label for="confirm_email"><?php esc_html_e('Please leave this field empty','workscout'); ?></label>
                    <input type="text" name="confirm_email" id="confirm_email" class="input" value="">
                </p>
                <p>
					<input type="hidden" name="workscout_user_role" value="candidate" />
                    <input type="hidden" name="workscout_register_nonce" value="<?php echo wp_create_nonce('workscout-register-nonce'); ?>"/>
                    <input type="hidden" name="workscout_register_check" value="1"/>
                    <?php  wp_nonce_field( 'ajax-register-nonce', 'security' );  ?>
                    <input type="submit" value="<?php _e('Register Your Account','workscout'); ?>"/>
                </p>
            </fieldset>
        </form>
    <?php
    return ob_get_clean();
}

function workscout_spotlight_jobs_shortcode( $atts ) {
    ob_start();

    extract( $atts = shortcode_atts( apply_filters( 'job_manager_output_jobs_defaults', array(
        'per_page'                  => get_option( 'job_manager_per_page' ),
        'orderby'                   => 'featured',
        'order'                     => 'DESC',
        'title'                     => 'Job Spotlight',
        'visible'                   => '1,1,1,1',
        'meta'                      => 'company,location,rate,salary',
        'autoplay'                  => "off",
        'delay'                     => 5000,
        'limit'                     => 20,
        'limitby'                   => 'words', //characters
        // Limit what jobs are shown based on category and type
        'categories'                => '',
        'job_types'                 => '',
        'job_ids'                   => '',
        'featured'                  => null, // True to show only featured, false to hide featured, leave null to show both.
        'filled'                    => false, // True to show only filled, false to hide filled, leave null to show both/use the settings.

        
    ) ), $atts ) );

    $randID = rand(1, 99); 

    if ( ! is_null( $filled ) ) {
        $filled = ( is_bool( $filled ) && $filled ) || in_array( $filled, array( '1', 'true', 'yes' ) ) ? true : false;
    }

    // Array handling
    $categories         = is_array( $categories ) ? $categories : array_filter( array_map( 'trim', explode( ',', $categories ) ) );
    $job_types          = is_array( $job_types ) ? $job_types : array_filter( array_map( 'trim', explode( ',', $job_types ) ) );
    if ( ! is_null( $featured ) ) {
      
        $featured = ( is_bool( $featured ) && $featured ) || in_array( $featured, array( '1', 'true', 'yes' ) ) ? true : false;
    }

   $query_args = array(
        'post_type'              => 'job_listing',
        'post_status'            => 'publish',
        'ignore_sticky_posts'    => 1,
        'offset'                 => 0,
        'posts_per_page'         => intval( $per_page ),
        'orderby'                => $orderby,
        'order'                  => $order,
        'fields'                 => 'all'
    );

   if(!empty($job_ids)) {
        $inc = explode(",", $job_ids);
        $query_args['post__in'] = $inc;
    }

    if ( ! is_null( $featured ) ) {
        $query_args['meta_query'][] = array(
            'key'     => '_featured',
            'value'   => '1',
            'compare' => $featured ? '=' : '!='
        );
    }

    if ( ! is_null( $filled) || 1 === absint( get_option( 'job_manager_hide_filled_positions' ) ) ) {
        $query_args['meta_query'][] = array(
            'key'     => '_filled',
            'value'   => '1',
            'compare' => $filled ? '=' : '!='
        );
    }

    if ( ! empty( $job_types ) ) {
        $query_args['tax_query'][] = array(
            'taxonomy' => 'job_listing_type',
            'field'    => 'slug',
            'terms'    => $job_types
        );
    }

    if ( ! empty( $categories ) ) {
        $field    = is_numeric( $categories[0] ) ? 'term_id' : 'slug';
        
        $operator = 'all' === get_option( 'job_manager_category_filter_type', 'all' ) && sizeof( $categories ) > 1 ? 'AND' : 'IN';
        $query_args['tax_query'][] = array(
            'taxonomy'         => 'job_listing_category',
            'field'            => $field,
            'terms'            => array_values( $categories ),
            'include_children' => $operator !== 'AND' ,
            'operator'         => $operator
        );
    }

    if ( 'featured' === $orderby ) {
        $orderby = array(
            'menu_order' => 'ASC',
            'date'       => 'DESC'
        );
    }

   $wp_query = new WP_Query( $query_args );
   if ( $wp_query->have_posts() ):
     
        ?>
        
        <div id="related-job-container"></div>
 
        <div id="related-job-container">

        <h3 class="margin-bottom-5 margin-top-55"><?php esc_html_e('Job Spotlight','workscout'); ?></h3>
    
    
        <!-- Showbiz Container -->
        <div id="related-job-spotlight" class="related-job-spotlight-car showbiz-container" data-visible="[4,2,2,1]" data-slick='{"slidesToShow": 3, "slidesToScroll": 1, "autoplay": "true"}'>
    
                <?php  while( $wp_query->have_posts() ) : 

                  $wp_query->the_post(); 

                    $id = get_the_id(); ?>
                 
                        <div class="job-spotlight">
                            <?php $job_meta = Kirki::get_option( 'workscout','pp_meta_job_list',array('company','location','rate','salary') ); ?>
                            <a href="<?php the_permalink(); ?>"><h4><?php the_title(); ?> 
                            <?php if ( get_option( 'job_manager_enable_types' ) ) { ?><?php $types = wpjm_get_the_job_types(); 

                if ( ! empty( $types ) ) : foreach ( $types as $type ) :  ?>
                    <span class="job-type <?php echo esc_attr( sanitize_title( $type->slug ) ); ?>" itemprop="employmentType"><?php echo esc_html( $type->name ); ?></span>

                <?php endforeach; endif; ?>
                    <?php } ?>
                    </h4></a>
                    
                    <div class="jobsMeta">
                        <?php if (in_array("company", $job_meta) && get_the_company_name()) { ?>
                            <span class="ws-meta-company-name"><i class="fa fa-briefcase"></i> <?php print_r(get_post_meta(get_the_id() , '_company_name_new', true)); ?></span>
                        <?php } ?>

                        
                        
                        <?php if (in_array("location", $job_meta)) { ?>
                            <span class="ws-meta-job-location"><i class="fa fa-map-marker"></i> <?php ws_job_location( false ); ?></span>
                        <?php } ?>

                        <?php 
                            $currency_position =  get_option('workscout_currency_position','before');
                            $salary =  get_post_meta( $id, '_job_salary', true );
                            if( in_array("salary", $job_meta) ) :
                                if ( !empty($salary) ) { 
                        ?>
                            <span class="ws-meta-job-location"><i class="ln ln-icon-Money-2"></i> 
                                <?php 
                                    if ( $salary ) { 
                                        if( $currency_position == 'before' ) { 
                                            echo get_workscout_currency_symbol(); 
                                        } 	
                                        echo esc_html( $salary );
                                        if( $currency_position == 'after' ) { 
                                            echo get_workscout_currency_symbol(); 
                                        }
                                    }
                                ?>
                                <?php
                                    $hours = get_post_meta( $id, '_hours', true );
                                    if( $hours ) :
                                ?>
                                    <span><i class="ln ln-icon-Clock" aria-hidden="true"></i>
                                        <?php echo $hours; ?>
                                    </span>
                                <?php endif; ?>
                            </span>
                        <?php }  endif; ?>

                        <?php
                            $terms = get_the_terms( $id, 'job_listing_category' );
                            if ( $terms && ! is_wp_error( $terms ) ){
                                $jobCategories = array();
                                foreach ( $terms as $term ) {
                                    $jobCategories[] = $term->name;
                                }
                            }
                            if( !empty($jobCategories) ):
                        ?>
                            <span class="ws-meta-job-location"><i class="ln ln-icon-Tag" aria-hidden="true"></i> <?php echo join( ", ", $jobCategories ); ?></span>
                        <?php endif; ?>

                        <?php
                            $important_info =  get_post_meta( get_the_ID(), '_job_important_info', true );
                            if( $important_info ):
                        ?>
                            <span class="ws-meta-job-location"><i class="fa fa-exclamation" aria-hidden="true"></i><?php echo wp_trim_words($important_info, 14); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <?php /*
                    <p><?php  
                        $excerpt = get_the_excerpt();
                        echo workscout_string_limit_words($excerpt,20); ?>...
                    </p>
                    */ ?>
                    <a href="<?php the_permalink(); ?>" class="button"><?php esc_html_e('Apply For This Job','workscout') ?></a>
                </div>
            
            <?php endwhile; ?>
      
        </div>
    </div>
    <?php  
        
    endif; 
    wp_reset_postdata();
    $job_listings_output =  ob_get_clean();

    return $job_listings_output;

}
add_shortcode( 'spotlight_jobs_carousel', 'workscout_spotlight_jobs_shortcode' );

function back_to_btn_shortcode($atts){

    $atts = shortcode_atts( array(
        'link' => '',
        'label' => ''
    ), $atts, 'back_to_btn' );

    $str = '<div class="textLeftBtnWrap"><a href="'.$atts['link'].'" class="button">'.$atts['label'].'</a></div>';

    return $str;

}
add_shortcode( 'back_to_btn', 'back_to_btn_shortcode' );

function dc_the_company_logo($job_id){
    return get_the_post_thumbnail( $job_id, 'full' );
}

function add_visit_count($jobID){

    $getCurrentCount = ( get_field('visit_count' , $jobID) ) ? get_field('visit_count' , $jobID) : '0';
    $newCount = (int)$getCurrentCount + 1;

    update_field( 'visit_count' , $newCount , $jobID );

}

function add_apply_count($jobID){

    $getCurrentCount = ( get_field('apply_count' , $jobID) ) ? get_field('apply_count' , $jobID) : '0';
    $newCount = (int)$getCurrentCount + 1;

    update_field( 'apply_count' , $newCount , $jobID );

}

add_action( 'wp_ajax_add_applyCount_ajax', 'add_applyCount_ajax' );
add_action( 'wp_ajax_nopriv_add_applyCount_ajax', 'add_applyCount_ajax' );
function add_applyCount_ajax(){

    $results = array();
    $jobID = $_POST['jobID'];

    add_apply_count($jobID);

    $currentUserID = get_current_user_id();
    $userMeta = get_userdata($currentUserID);
    $title = 'Application Records from '.$userMeta->first_name.' '.$userMeta->last_name;
    $externalLink = get_post_meta( $jobID, '_apply_link', true );
    $companyName = get_the_company_name($jobID);
    $currentDate = date('Y-m-d');
    $currentTime = $_POST['time'];
    $apply = get_the_job_application_method($jobID);

    if( $apply->type == 'url' ){
        $applyType = 'External';
    }else{
        $applyType = 'Form';
    }

    //Add Application Records
    $applicationRecords = array(
        'post_title'    => wp_strip_all_tags( $title ),
        'post_status'   => 'publish',
        'post_type' => 'application_records',
        'meta_input'   => array(
            'job_id' => $jobID,
            'company_name' => $companyName,
            'candidate' => $currentUserID,
            'date' => $currentDate,
            'time' => $currentTime,
            'apply_type' => $applyType,
        ),
    );
    wp_insert_post( $applicationRecords );

    echo json_encode($results);
    die();

}

//Create CSV
function prefix_admin_print_csv() {

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
add_action( 'admin_post_print_csv', 'prefix_admin_print_csv' );

function edit_profile_btn_shortcode($atts){

    $currentUserID = get_current_user_id();
    $userMeta = get_userdata($currentUserID);
    $userRoles = $userMeta->roles;
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $str = '';
    if(is_user_logged_in() && ( in_array( 'administrator', $userRoles) || in_array( 'employer', $userRoles) ) ){
        $str .= '<div class="editProfileBtn">';
            if( $actual_link != home_url('employers/manage-gold-jobs/submit-job/') ){
                $str .= '<a class="button" href="'.home_url('employers/submit-job/').'">Create a Job</a>';
            }
            if( $actual_link != home_url('employers/manage-gold-jobs/') ){
                $str .= '<a class="button" href="'.home_url('employers/manage-gold-jobs/').'">Manage Jobs</a>';
            }
            if( $actual_link != home_url('statistics/') ){
                $str .= '<a class="button" href="'.home_url('statistics/').'">Visits vs Clicks</a>';
            }
        $str .= '</div>';
    }

    return $str;

}
add_shortcode( 'edit_profile_btns', 'edit_profile_btn_shortcode' );

add_action('vc_before_init', 'dc_current_job_vacancies_in_vc');
function dc_current_job_vacancies_in_vc() {
    vc_map(
            array(
                "name" => __("Current Job Vacancies", "crc-dev"), // Element name
                "base" => "dc_current_job_vacancies", // Element shortcode
                "class" => "current-job-vacancies",
                "category" => __('Jobs', 'crc-dev'),
                "params" => array(
                    array(
                        "type" => "textfield",
                        "holder" => "div",
                        "class" => "",
                        "heading" => __("Company Name", "crc-dev"),
                        "param_name" => "dc_current_job_vacancies_keyword",
                        "value" => __("", "crc-dev"),
                        "description" => __('Add a Company Name.', "crc-dev")
                    ),
                )
            )
    );
}

/* Current Job Vacancies */

add_shortcode('dc_current_job_vacancies', 'dc_current_job_vacancies_func');
function dc_current_job_vacancies_func($atts){

    $atts = shortcode_atts( array(
        'dc_current_job_vacancies_keyword' => '',
    ), $atts, 'dc_current_job_vacancies' );

    ob_start();
    $jobsQuery = new WP_Query( array(
        'post_type' => 'job_listing',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key'     => '_company_name',
                'value'   => $atts['dc_current_job_vacancies_keyword'],
                // 'compare' => 'LIKE',
                'exact' => 'true'
            ),
            array(
                'key'     => '_filled',
                'value'   => true,
                'compare' => '!='
            )
        ),
    ) ); ?>

    <!--<h3>Current Job Vacancies</h3>-->
    <div class="job_listings">
        <ul class="job_listings job-list full new-layout">
            <?php if($jobsQuery->have_posts()) :
                while ( $jobsQuery->have_posts() ) : $jobsQuery->the_post(); ?>
                    <?php get_job_manager_template_part( 'content', 'job_listing' ); ?>
                <?php endwhile;
            else : ?>
                <p class="no_job_listings_found">There are currently no vacancies.</p>
            <?php endif; ?>
        </ul>
    </div>
    <?php wp_reset_query();
    $str = ob_get_contents();
	ob_end_clean();

    return $str;

}

if ( ! function_exists( 'workscout_wp_new_user_notification' ) ) :
 function workscout_wp_new_user_notification($user_id, $plaintext_pass) {

    global $wpdb;
    $user = get_userdata( $user_id );

    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text arena of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $loginpage = Kirki::get_option( 'workscout', 'pp_login_workscout_page' );


    $message  = sprintf(__('New user registration on your site %s:','workscout'), $blogname) . "\r\n\r\n";
    $message .= sprintf(__('Username: %s','workscout'), $user->user_login) . "\r\n\r\n";
    $message .= sprintf(__('E-mail: %s','workscout'), $user->user_email) . "\r\n";

    @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration','workscout'), $blogname), $message);

    $message = 'Thank you for creating a Gold Jobs account. Your new login details are below:' . "\r\n\r\n";

    $message .= sprintf(__('Username: %s','workscout'), $user->user_login) . "\r\n\r\n";
    $message .= sprintf(__('Password: %s','workscout'), $plaintext_pass) . "\r\n\r\n";

    if(!empty($loginpage)){
        $message .= __('You can access your account area and change your password here ','workscout') . get_permalink($loginpage) . "\r\n\r\n";
    } else {
        $message .= __('To login to Gold Jobs please use this link ','workscout') . site_url() . "\r\n\r\n";
        
    }
    $message .= sprintf( __('If you have any questions or require assistance, please contact us at %s. ','workscout'), 'info@goldindustrygroup.com.au' ) . "\r\n\r\n";
    $message .= __('Thank you!','workscout') . "\r\n\r\n";

    $headers[]= "From: Gold Jobs <info@goldindustrygroup.com.au>";

    wp_mail($user->user_email, sprintf(__('Gold Jobs: your username and password','workscout'), $blogname), $message, $headers);
    }
endif;

// Mailchimp user type
add_filter( 'mc4wp_user_sync_subscriber_data', function( $subscriber, $user ) {
	$subscriber->merge_fields['USERTYPE'] = $user->usertype;
    return $subscriber;
}, 10, 2 );

//Job Alerts Cron
function job_alerts_cron(){

    $args = array(
        'post_type'  => 'job_alert',
        'posts_per_page' => -1
    );
    $postslist = get_posts( $args );

    if($postslist){
        foreach( $postslist as $alert ) {

            $force = false;
            $alertPost = get_post( $alert->ID );

            if ( ! $alertPost || $alertPost->post_type !== 'job_alert' ) {
                return;
            }

            $user  = get_user_by( 'id', $alertPost->post_author );
            $jobs  = WP_Job_Manager_Alerts_Notifier::get_matching_jobs( $alertPost, $force );

            if ( $jobs->found_posts || ! get_option( 'job_manager_alerts_matches_only' ) ) {

                $email = WP_Job_Manager_Alerts_Notifier::format_email( $alertPost, $user, $jobs );

                if ( $email ) {
                    wp_mail( $user->user_email, apply_filters( 'job_manager_alerts_subject', sprintf( __( 'Job Alert Results Matching "%s"', 'wp-job-manager-alerts' ), $alertPost->post_title ), $alertPost ), $email );
                }

            }

            // Inc sent count
            update_post_meta( $alertPost->ID, 'send_count', 1 + absint( get_post_meta( $alertPost->ID, 'send_count', true ) ) );
            
        }
    }

}

add_filter( 'job_manager_alerts_login_url', 'custom_job_manager_alerts_login_url' );
function custom_job_manager_alerts_login_url() {
    return home_url('?action=login');
}

add_action('vc_before_init', 'dc_current_job_vacancies_location_in_vc');
function dc_current_job_vacancies_location_in_vc() {
    vc_map(
            array(
                "name" => __("Current Job Vacancies (Location)", "crc-dev"), // Element name
                "base" => "dc_current_job_vacancies_location", // Element shortcode
                "class" => "current-job-vacancies",
                "category" => __('Jobs', 'crc-dev'),
                "params" => array(
                    array(
                        "type" => "textfield",
                        "holder" => "div",
                        "class" => "",
                        "heading" => __("Location", "crc-dev"),
                        "param_name" => "dc_current_job_vacancies_location_keyword",
                        "value" => __("", "crc-dev"),
                        "description" => __('Add a Location.', "crc-dev")
                    ),
                )
            )
    );
}

add_shortcode('dc_current_job_vacancies_location', 'dc_current_job_vacancies_location_func');
function dc_current_job_vacancies_location_func($atts){

    $atts = shortcode_atts( array(
        'dc_current_job_vacancies_location_keyword' => '',
    ), $atts, 'dc_current_job_vacancies_location' );

    ob_start();
    $jobsQuery = new WP_Query( array(
        'post_type' => 'job_listing',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key'     => '_job_location',
                'value'   => $atts['dc_current_job_vacancies_location_keyword'],
                'compare' => 'LIKE',
            ),
            array(
                'key'     => '_filled',
                'value'   => true,
                'compare' => '!='
            )
        ),
    ) ); ?>
    <!--<h3>Current Job Vacancies</h3>-->
    <div class="job_listings">
        <ul class="job_listings job-list full new-layout">
            <?php if($jobsQuery->have_posts()) :
                while ( $jobsQuery->have_posts() ) : $jobsQuery->the_post(); ?>
                    <?php get_job_manager_template_part( 'content', 'job_listing' ); ?>
                <?php endwhile;
            else : ?>
                <p class="no_job_listings_found">There are currently no vacancies.</p>
            <?php endif; ?>
        </ul>
    </div>
    <?php wp_reset_query();
    $str = ob_get_contents();
	ob_end_clean();

    return $str;

}

//Update Profile
function prefix_admin_edit_seeker_profile() {

    $userID = (isset($_POST['userID'])) ? $_POST['userID'] : '' ;
    $userData = get_userdata($currentUserID);

    $fname = (isset($_POST['fname'])) ? $_POST['fname'] : $userData->first_name ;
    $lname = (isset($_POST['lname'])) ? $_POST['lname'] : $userData->last_name ;
    $email = (isset($_POST['email'])) ? $_POST['email'] : $userData->user_email ;
    $password = (isset($_POST['password'])) ? $_POST['password'] : '' ;
    $cpassword = (isset($_POST['cpassword'])) ? $_POST['cpassword'] : '' ;

    if( $userID ){
        if( $password && $cpassword ) {
            if ( $cpassword == $cpassword ) {
                $args  = array( 
                    'ID' => $userID, 
                    'first_name' => $fname,
                    'last_name' => $lname,
                    'user_email' => $email,
                    'user_pass' => $password
                );
            }
        } else {
            $args  = array( 
                'ID' => $userID, 
                'first_name' => $fname,
                'last_name' => $lname,
                'user_email' => $email
            );
        }

        $user_data = wp_update_user( $args );

        if ( is_wp_error( $user_data ) ) {
            wp_redirect( home_url('edit-profile/profile?error=1') ); 
            exit;
        } else {
            wp_redirect( home_url('edit-profile/profile?success=1') ); 
            exit;
        }
    }

}
add_action( 'admin_post_edit_seeker_profile', 'prefix_admin_edit_seeker_profile' );

add_action('vc_before_init', 'dc_user_preference_in_vc');
function dc_user_preference_in_vc() {
    vc_map(
        array(
            "name" => __("User Preference", "crc-dev"), // Element name
            "base" => "dc_user_preference", // Element shortcode
            "class" => "user_preference",
            "category" => __('Jobs', 'crc-dev'),
            "params" => array(
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "class" => "",
                    "heading" => __("Text", "crc-dev"),
                    "param_name" => "dc_user_preference_keyword",
                    "value" => __("", "crc-dev"),
                    "description" => __('Add a Text.', "crc-dev")
                ),
            )
        )
    );
}

add_shortcode('dc_user_preference', 'dc_user_preference_func');
function dc_user_preference_func($atts){
    
    $atts = shortcode_atts( array(
        'dc_user_preference_keyword' => '',
    ), $atts, 'dc_user_preference' );


    $currentUserID = get_current_user_id();
    $preference = get_field( 'preference' , 'user_'.$currentUserID );
    $preferenceArr = array('Student' , 'Graduate' , 'Gold employee' , 'Job seeker' , 'Employer' , 'Teacher');

    ob_start();
    ?>
    <div class="user_preference">
        <h4><?php echo $atts['dc_user_preference_keyword']; ?></h4>
        <form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
            <input type="hidden" name="action" value="user_preference" />
            <select name="usertype" class="wpcf7-form-control wpcf7-select" aria-invalid="false">
                <?php 
                    foreach( $preferenceArr as $pref ):
                        $selected = ( $pref == $preference ) ? 'selected="selected"' : '' ;
                ?>
                    <option value="<?php echo $pref; ?>" <?php echo $selected; ?>><?php echo $pref; ?></option>
                <?php endforeach; ?>
            </select>
            <input class="dc-btn-wide" type="submit" value="Update">
        </form>
    </div>
    <?php wp_reset_query();
    $str = ob_get_contents();
	ob_end_clean();

    return $str;

}

function prefix_admin_user_preference() {

    $currentUserID = get_current_user_id();
    $usertype = (isset($_POST['usertype'])) ? $_POST['usertype'] : '' ;

    update_field('preference' , $usertype , 'user_'.$currentUserID);
	
	$current_user = wp_get_current_user();
	$roles = $current_user->roles;
	$role = array_shift( $roles ); 
	
	if ($role == "administrator" || $role == "employer"){
    	wp_redirect( home_url('/employers/employer-dashboard') ); 
	} else {
		wp_redirect( home_url('search-jobs/dashboard') ); 
	}
    exit;

}
add_action( 'admin_post_user_preference', 'prefix_admin_user_preference' );

function crc_dev_sidebars() {
    register_sidebar(
        array (
            'name' => __( 'CTA Signup', 'crc-dev' ),
            'id' => 'cta-signup-side-bar',
            'description' => __( 'CTA Signup', 'crc-dev' ),
            'before_widget' => '<div class="widget-content">',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );
}
add_action( 'widgets_init', 'crc_dev_sidebars' );

//Create Theme Option Page ACF
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme Options',
		'menu_title'	=> 'Theme Options',
		'menu_slug' 	=> 'theme-options',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
}

//Adding the Open Graph in the Language Attributes
function add_opengraph_doctype( $output ) {
        return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
    }
add_filter('language_attributes', 'add_opengraph_doctype');
 
//Lets add Open Graph Meta Info
 
function insert_fb_in_head() {
    global $post;
    if ( !is_singular()) //if it is not a post or a page
        return;
        // echo '<meta property="fb:admins" content="YOUR USER ID"/>';
        echo '<meta property="og:title" content="' . get_the_title() . ' - '.get_bloginfo('description').'"/>';
        echo '<meta property="og:type" content="article"/>';
        echo '<meta property="og:url" content="' . get_permalink() . '"/>';
        echo '<meta property="og:site_name" content="'.get_bloginfo('name').'"/>';
        echo '<meta name="twitter:title" content="' . get_the_title() . ' - '.get_bloginfo('description').'">';
        echo '<meta name="twitter:description" content="'.substr(get_post_meta($post->ID, '_yoast_wpseo_metadesc', true), 0, 100).'">';
        echo '<meta name="twitter:card" content="summary_large_image">';
    if(!has_post_thumbnail( $post->ID )) { //the post does not have featured image, use a default image
        $default_image="https://jobs.goldindustrygroup.com.au/wp-content/uploads/2020/05/Gold-Jobs-Cover.jpg"; //replace this with a default image on your server or an image in your media library
        echo '<meta property="og:image" content="' . $default_image . '"/>';
        echo '<meta name="twitter:image" content="' . $default_image . '">';
    }
    else{
        $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
        echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
        echo '<meta name="twitter:image" content="' . esc_attr( $thumbnail_src[0] ) . '">';
    }
    echo "";
}
add_action( 'wp_head', 'insert_fb_in_head', 5 );

add_action('vc_before_init', 'dc_records_links_in_vc');
function dc_records_links_in_vc() {
    vc_map(
        array(
            "name" => __("Application Records Link", "crc-dev"), // Element name
            "base" => "dc_records_links", // Element shortcode
            "class" => "records_links",
            "category" => __('Jobs', 'crc-dev'),
            "params" => array(
                array(
                    "type" => "vc_link",
                    "holder" => "div",
                    "class" => "",
                    "heading" => __("Link", "crc-dev"),
                    "param_name" => "dc_records_links_keyword",
                    "value" => __("", "crc-dev"),
                    "description" => __('Add a Link.', "crc-dev")
                ),
            )
        )
    );
}

add_shortcode('dc_records_links', 'dc_records_links_func');
function dc_records_links_func($atts){
    
    $atts = shortcode_atts( array(
        'dc_records_links_keyword' => '',
    ), $atts, 'dc_records_links' ); 

    $currentUserID = get_current_user_id();
    $args = array( 
        'posts_per_page'   => -1,
        'post_type'     => 'application_records',
        'meta_query' => array(
            array(
                'key'     => 'candidate',
                'value'   => $currentUserID,
                'compare' => '='
            )
        ),
    );

    $applicationRecords = new WP_Query( $args );
    if ( $applicationRecords->have_posts() ):    
    endif; wp_reset_query();
        $href = vc_build_link( $atts['dc_records_links_keyword'] );
        ob_start();
    ?>
    <div class="recordsLinks">
        <a class="dc-btn-wide" href="<?php echo $href['url']; ?>" title="<?php echo $href['title']; ?>"><?php echo $href['title']; ?></a>
    </div>
    <?php 
        $str = ob_get_contents();
        ob_end_clean();  

    return $str;

}

/* Removed Unused JS AND CSS */


function wpdocs_selectively_enqueue_admin_script( $hook ) {
    wp_enqueue_script( 'my_custom_script',  get_stylesheet_directory_uri(). '/js/adminscript.js', array(), '1.0' );
}
add_action( 'admin_enqueue_scripts', 'wpdocs_selectively_enqueue_admin_script' );

add_action('admin_init', 'wpse273289_remove_profile_fields' ); 
function wpse273289_remove_profile_fields() { 
    global $pagenow;
    // apply only to user profile or user edit pages
    if( $pagenow =='profile.php' || $pagenow =='user-edit.php' ) {
        
        if($pagenow =='profile.php') {
            $user_id= get_current_user_id();
        } else {
            $user_id= $_REQUEST['user_id'];    
        }
        
    
    $__data= get_post_meta(4024,'options',true);
    $selected=get_user_meta($user_id,'dc_company_name_new',true);
    $out='';
    foreach ($__data as $__value) {
      if($selected==$__value) {
        $out.='<option value="'.$__value.'" selected>'.$__value.'</option>';
      } else {
        $out.='<option value="'.$__value.'">'.$__value.'</option>';
      }
      
    }
    ?>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script>
        jQuery(document).ready(function() {
            setTimeout(function(){
                jQuery(".acf-input select").html('<?php echo $out; ?>');
            }, 2000);
        });
</script>
    <?php
    }
}

/* Search Function */

add_action('wp_ajax_myfilter', 'search_function'); 
add_action('wp_ajax_nopriv_myfilter', 'search_function');

function search_function() {

    $search = $_POST['search'];

    $query = new WP_Query(
        array(
            's' => $search,
            'posts_per_page' => 10
        )
    );

    if( $query->have_posts() ) :
            while( $query->have_posts() ): $query->the_post();
             ?>
             <a href="<?php the_permalink(); ?>">
                <div class="search-result">
                    <div id="search-content" class="content">
                        <div class="image">
                            <?php the_post_thumbnail(); ?>
                        </div>
                        <div class="text">
                            <h2><?php the_title(); ?></h2>
                            <?php the_excerpt(); ?>
                        </div>
                    </div>
                </div>
             </a>
             <?php
            endwhile;
            wp_reset_postdata();
        else :
            echo 'No posts found';
        endif;
    
        die();
    }

?>