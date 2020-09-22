<?php
function breezy_json_jobs_import($userID , $import_url){

    $jobIDs = array();

    if( $import_url ) {
        $response = wp_remote_get($import_url);
        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
            $jobsContent = json_decode($response['body']);

            if( is_array($jobsContent) && !empty($jobsContent) ){
                foreach( $jobsContent as $jobItem ){
                    $jobID = dc_import_jobs($jobItem , 'breezy' , $userID);
                    $jobIDs[] = $jobID;
                }
            }
        }

        return $jobIDs;
    }
    
    return false;

}

function newcrest_json_jobs_import($userID , $import_url){

    $jobIDs = array();

    if( $import_url ) {
        $response = wp_remote_get($import_url);
        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
            $jobsContent = json_decode($response['body']);

            if( is_array($jobsContent) && !empty($jobsContent) ){
                foreach( $jobsContent as $jobItem ){
                    $jobID = dc_import_jobs($jobItem , 'newcrest' , $userID);
                    $jobIDs[] = $jobID;
                }
            }
        }

        return $jobIDs;
    }
    
    return false;

}