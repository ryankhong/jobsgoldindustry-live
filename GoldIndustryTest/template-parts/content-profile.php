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
$userData = get_userdata($currentUserID);
$error = (isset($_GET['error'])) ? $_GET['error'] : '' ;
$success = (isset($_GET['success'])) ? $_GET['success'] : '' ;
?>

<div class="container <?php echo esc_attr($layout); ?>">
	<article id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>

		<?php
			if( $success ) {
				echo '<div class="status success">Your Profile has been updated.</div>';
			}
		?>

		<form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
			<input type="hidden" name="action" value="edit_seeker_profile" />
			<input type="hidden" name="userID" value="<?php echo $currentUserID; ?>" />

			<div class="acf-field acf-field-text">
				<div class="acf-label"><label for="fname">First Name</label></div>
				<div class="acf-input">
					<div class="acf-input-wrap">
						<input type="text" id="fname" name="fname" value="<?php echo $userData->first_name; ?>">
					</div>
				</div>
			</div>

			<div class="acf-field acf-field-text">
				<div class="acf-label"><label for="lname">Last Name</label></div>
				<div class="acf-input">
					<div class="acf-input-wrap">
						<input type="text" id="lname" name="lname" value="<?php echo $userData->last_name; ?>">
					</div>
				</div>
			</div>

			<div class="acf-field acf-field-text">
				<div class="acf-label"><label for="email">Email Address</label></div>
				<div class="acf-input">
					<div class="acf-input-wrap">
						<input type="email" id="email" name="email" value="<?php echo $userData->user_email; ?>">
					</div>
				</div>
			</div>

			<h4>Change Password</h4>

			<div class="acf-field acf-field-text">
				<div class="acf-label"><label for="password">Password</label></div>
				<div class="acf-input">
					<div class="acf-input-wrap">
						<input type="password" id="password" name="password">
					</div>
				</div>
			</div>

			<div class="acf-field acf-field-text">
				<div class="acf-label"><label for="cpassword">Confirm Password</label></div>
				<div class="acf-input">
					<div class="acf-input-wrap">
						<input type="password" id="cpassword" name="cpassword">
					</div>
				</div>
			</div>

			<input class="dc-btn-wide" type="submit" value="Update">
		</form>
	</article>
</div>