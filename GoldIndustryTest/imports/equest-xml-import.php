<?php
function equest_xml_jobs_import($userID , $import_url){

    $jobIDs = array();

    if( $import_url ) {
        $equestXML = simplexml_load_file($import_url);

        if( $equestXML ){
            foreach( $equestXML->Job as $job ){
                $jobID = dc_import_jobs($job , 'equest' , $userID);
                $jobIDs[] = $jobID;
            }
        }

        return $jobIDs;
    }
    
    return false;

}