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
$userMeta = get_userdata($currentUserID);
$userRoles = $userMeta->roles;
?>

<div class="container <?php echo esc_attr($layout); ?>">
	<article id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>
        <?php
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
        ?>
        <?php if( is_array($statCountArr) && !empty($statCountArr) ): ?>
            <?php //if( in_array( 'administrator', $userRoles) ): ?>
                <div class="exportcsvWrap">
                    <form target="_blank" action="<?php echo get_permalink(); ?>" method="POST">
                        <input type="hidden" name="action" value="print_csv">
                        <input class="dc-btn-wide" type="submit" value="Download CSV">
                    </form>
                </div>
            <?php //endif; ?>
            <table id="jobStatTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Created</th>
                        <th>Job Name</th>
                        <th>Visit Count</th>
                        <th>Click Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $statCountArr as $statCount ): ?>
                        <tr>
                            <td><?php echo $statCount['companyName']; ?></td>
                            <td><?php echo $statCount['created']; ?></td>
                            <td><?php echo $statCount['title']; ?></td>
                            <td><?php echo $statCount['visitCount']; ?></td>
                            <td><?php echo $statCount['clickCount']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Company Name</th>
                        <th>Created</th>
                        <th>Job Name</th>
                        <th>Visit Count</th>
                        <th>Click Count</th>
                    </tr>
                </tfoot>
            </table>
        <?php endif; ?>
        <?php the_content(); ?>
	</article>
</div>