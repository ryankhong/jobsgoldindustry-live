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
?>

<div class="container <?php echo esc_attr($layout); ?>">
	<article id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>
        <?php
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
        ?>
            <table id="applicationRecordsTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Job Name</th>
                        <th>Date</th>
                        <!-- <th>Time</th> -->
                        <th>Application Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ( $applicationRecords->have_posts() ) : $applicationRecords->the_post(); ?>
                        <tr>
                            <td><?php the_field('company_name'); ?></td>
                            <td><?php echo get_the_title(get_field('job_id')); ?></td>
                            <td><?php the_field('date'); ?></td>
                            <!-- <td><?php //the_field('time'); ?></td> -->
                            <td><?php the_field('apply_type'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Company Name</th>
                        <th>Job Name</th>
                        <th>Date</th>
                        <!-- <th>Time</th> -->
                        <th>Application Type</th>
                    </tr>
                </tfoot>
            </table>
        <?php 
            else:
                echo '<h2 style="text-align: center">No Records found!</h2>';
            endif; wp_reset_query(); 
        ?>
	</article>
</div>