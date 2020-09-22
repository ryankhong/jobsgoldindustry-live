<?php

function import_jobs_shortcode_func($atts){

    ob_start();

    ?>
        <div class="importJobsWrapper">
            <form action="" method="post">

                <fieldset class="form">
                    <label for="import_type">Import Type</label>
                    <div class="field">
                        <select name="import_type" id="import_type" class="chosen-select-no-single">
                            <option value="Breezy">Breezy</option>
                            <option value="eQuest">eQuest</option>
                        </select>
                    </div>
                </fieldset>

                <fieldset class="form">
                    <label for="import_url">Import Url</label>
                    <div class="field">
                        <input type="text" name="import_url" id="import_url" class="input-text" />
                    </div>
                </fieldset>

                <p class="send-btn-border">
                    <input type="submit" name="import_job" class="button big" value="Import" />
                </p>

            </form>
        </div>
    <?php

    $importJobs =  ob_get_clean();

    return $importJobs;

}
add_shortcode( 'import_jobs', 'import_jobs_shortcode_func' );

function import_jobs_cron_job(){

    $jobIDSnUserIDs = array();
    $allUsers = get_users();
    foreach ( $allUsers as $user ){
        $jobIDs = array();
        if( have_rows('import_jobs' , 'user_'.$user->ID ) ): while( have_rows('import_jobs' , 'user_'.$user->ID ) ): the_row();
            if( get_row_layout() == 'jobs' ){
                if( strtolower(get_sub_field('import_type')) == 'breezy' ) {
                    $jobIDs = breezy_json_jobs_import($user->ID , get_sub_field('import_url'));
                } elseif( strtolower(get_sub_field('import_type')) == 'equest' ) {
                    $jobIDs = equest_xml_jobs_import($user->ID, get_sub_field('import_url'));
                } elseif( strtolower(get_sub_field('import_type')) == 'newcrest' ) {
                    $jobIDs = newcrest_json_jobs_import($user->ID, get_sub_field('import_url'));
                }
            }
        endwhile; endif;

        if( $jobIDs && !empty($jobIDs) ){
            $jobIDSnUserIDs[] = array( $user->ID => $jobIDs );
        }
    }

    return $jobIDSnUserIDs;

}

function check_job_exist( $jobCustomID , $importType ){

    $jobQuery = new WP_Query( array(
        'post_type' => 'job_listing',
        'meta_query' => array(
            array(
                'key'     => 'custom_id',
                'value'   => $jobCustomID,
                'compare' => '=',
            ),
            array(
                'key'     => 'import_type',
                'value'   => $importType,
                'compare' => '=',
            )
        ),
    ));

    return $jobQuery->post_count;

}

function dc_upload_img_url($url){

    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $post_id = 0;
    $desc = "Image description";

    $id = media_sideload_image($url, $post_id, $desc, 'id');

    if (!is_wp_error($id)){
        return $id;
    }else {
        return false;
    }
}