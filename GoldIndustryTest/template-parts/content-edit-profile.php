<!-- Titlebar
================================================== -->
<?php 

$titlebar = get_post_meta( $post->ID, 'pp_page_titlebar', true ); 
$submit_job_page = get_option('job_manager_submit_job_form_page_id');
$resume_job_page = get_option('resume_manager_submit_resume_form_page_id');

if($titlebar == 'off') {
	// no titlebar
} else {
	if (!empty($submit_job_page) && is_page($submit_job_page) || !empty($resume_job_page) && is_page($resume_job_page)) { ?>
		<!-- Titlebar
		================================================== -->
		
		<?php $header_image = get_post_meta($post->ID, 'pp_job_header_bg', TRUE); 
		if(!empty($header_image)) { ?>
			<div id="titlebar" class="photo-bg single submit-page" style="background: url('<?php echo esc_url($header_image); ?>')">
		<?php } else { ?>
			<div id="titlebar" class="single submit-page">
		<?php } ?>
			<div class="container">

				<div class="sixteen columns">
					<h2><i class="fa fa-plus-circle"></i> <?php the_title(); ?></h2>
				</div>

			</div>
		</div>
	<?php } else { ?>
		<?php $header_image = get_post_meta($post->ID, 'pp_job_header_bg', TRUE); 
		if(!empty($header_image)) { 			
			$transparent_status = get_post_meta($post->ID, 'pp_transparent_header', TRUE); 	
			if($transparent_status == 'on'){ ?>
			<div id="titlebar" class="photo-bg single with-transparent-header" style="background: url('<?php echo esc_url($header_image); ?>')">
		<?php } else { ?>
			<div id="titlebar" class="photo-bg" style="background: url('<?php echo esc_url($header_image); ?>')">
		<?php } ?>
	<?php } else { ?>
		<div id="titlebar" class="single">
		<?php } ?>
			<div class="container">

				<div class="sixteen columns">
					<h1><?php the_title(); ?></h1>
					<?php if(function_exists('bcn_display')) { ?>
			        <nav id="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
						<ul>
				        	<?php bcn_display_list(); ?>
				        </ul>
					</nav>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php 
	}
}
$layout  = get_post_meta( $post->ID, 'pp_sidebar_layout', true ); if ( empty( $layout ) ) { $layout = 'full-width'; }
$class = ($layout !="full-width") ? "eleven columns" : "sixteen columns"; 
$currentUserID = get_current_user_id();
$updated = (isset($_GET['updated'])) ? $_GET['updated'] : '' ;
?>

<div class="container <?php echo esc_attr($layout); ?>">
	<article id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>

		<?php
			if( $updated == 'true' ) {
				echo '<div class="status success">Your Profile has been updated.</div>';
			}
		?>

        <?php 
            $options = array(
                'post_id' => 'user_'.$currentUserID,
                'field_groups' => array(1012),
                'form' => true, 
                'uploader' => 'basic',
                'return' => add_query_arg( 'updated', 'true', get_permalink() ), 
                'html_before_fields' => '',
                'html_after_fields' => '',
                'submit_value' => 'Update' 
            );
            acf_form( $options );
        ?>
		<?php echo do_shortcode('[edit_profile_btns]'); ?>
	</article>
</div>