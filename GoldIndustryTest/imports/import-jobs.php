<?php
function dc_import_jobs($jobItem , $type , $userID){

    $company_name = get_field('company_name' , 'user_'.$userID);
    $company_address = get_field('company_address' , 'user_'.$userID);
    $company_website = get_field('company_website' , 'user_'.$userID);
    $company_tag_line = get_field('company_tag_line' , 'user_'.$userID);
    $twitter_username = get_field('twitter_username' , 'user_'.$userID);
    $company_logo = get_field('company_logo' , 'user_'.$userID);
    
    if( $type == 'breezy' ) {
        $jobType = (isset($jobItem->type->name)) ? strtolower($jobItem->type->name) : '' ;
        $city = (isset($jobItem->location->city)) ? $jobItem->location->city : '' ;
        $state = (isset($jobItem->location->state->name)) ? $jobItem->location->state->name : '' ;
        $country = (isset($jobItem->location->country->name)) ? $jobItem->location->country->name : '' ;
        $jobLocation = (isset($jobItem->location)) ? $city.' '.$state.' '.$country : $company_address ;
        $jobCompanyName = (isset($jobItem->company->name)) ? $jobItem->company->name : $company_name ;
        $jobDescription = (isset($jobItem->description)) ? $jobItem->description : '' ;
        $jobListingTypeIdsArr = array();
        $customID = $jobItem->id;
        $jobName = (isset($jobItem->name)) ? $jobItem->name.' at '.$jobCompanyName : '' ;

        $jobLogoUrl = $jobItem->company->logo_url;
        $jobLogoID = dc_upload_img_url($jobLogoUrl);

        $jobCompanyLogo = ($jobLogoID) ? $jobLogoID : $company_logo ;

        $jobPostArg = array(
            'post_type' => 'job_listing',
            'post_title'   => $jobName,
            'post_content' => $jobDescription,
            'post_status'  => 'draft',
            'meta_input'   => array(
                '_job_location' => $jobLocation,
                '_company_name' => $jobCompanyName,
                'custom_id' => $customID[0],
                'import_type' => 'breezy',
                '_company_twitter' => $twitter_username,
                '_company_website' => $company_website,
                '_company_tagline' => $company_tag_line,
            ),
        );

        if( !check_job_exist( $customID[0] , 'breezy' ) ){
            $jobID = wp_insert_post( $jobPostArg );

            if(!is_wp_error($jobID)){
                set_post_thumbnail( $jobID, $jobCompanyLogo );

                if( $jobType ){
                    $term = term_exists( $jobType, 'job_listing_type' );
                    if ( $term !== 0 && $term !== null ) {
                        wp_set_object_terms( $jobID, $term['term_id'], 'job_listing_type' );
                    }
                }

                return $jobID;
            }else{
                return false;
            }
        }else {
            return false;
        }

    } else if( $type == 'equest' ) {

        $jobType = (isset($jobItem->Position->Classification->Type['Code'][0])) ? strtolower($jobItem->Position->Classification->Type['Code'][0]) : '' ;

        $StreetAddress = (array)$jobItem->Company->Address->StreetAddress;
        $City = (array)$jobItem->Company->Address->City;
        $Country = (array)$jobItem->Company->Address->Country['Code'];

        $jobLocation = (isset($jobItem->Company->Address)) ? $StreetAddress[0].' '.$City[0].' '.$Country[0] : $company_address ;
        $jobCompanyName = (isset($jobItem->Company->Name)) ? (array)$jobItem->Company->Name : $company_name ;
        $jobDescription = (isset($jobItem->Company->Description)) ? (array)$jobItem->Company->Description : '' ;
        $customID = (array)$jobItem['InternalID'];
        $jobListingTypeIdsArr = array();
        $jobName = (isset($jobItem->Position->Title[0])) ? $jobItem->Position->Title[0].' at '.$jobCompanyName : '' ;
        
        //$jobCompanyLogo = (isset($jobItem->company->logo_url)) ? $jobItem->company->logo_url : $company_logo ;
        $jobCompanyLogo = $company_logo ;

        $jobPostArg = array(
            'post_type' => 'job_listing',
            'post_title'   => $jobName,
            'post_content' => $jobDescription[0],
            'post_status'  => 'draft',
            'meta_input'   => array(
                '_job_location' => $jobLocation,
                '_company_name' => $jobCompanyName[0],
                'custom_id' => $customID[0],
                'import_type' => 'equest',
                '_company_twitter' => $twitter_username,
                '_company_website' => $company_website,
                '_company_tagline' => $company_tag_line,
            ),
        );

        if( !check_job_exist( $customID[0] , 'equest' ) ){
            $jobID = wp_insert_post( $jobPostArg );

            if(!is_wp_error($jobID)){
                set_post_thumbnail( $jobID, $jobCompanyLogo );

                if( $jobType ){
                    $term = term_exists( $jobType, 'job_listing_type' );
                    if ( $term !== 0 && $term !== null ) {
                        wp_set_object_terms( $jobID, $term['term_id'], 'job_listing_type' );
                    }
                }

                return $jobID;
            }else{
                return false;
            }
        }else {
            return false;
        }

    } else if( $type == 'newcrest' ) {
        $jobType = (isset($jobItem->Categories)) ? strtolower($jobItem->Categories) : '' ;
        $jobLocation = (isset($jobItem->LocationList[0])) ? $jobItem->LocationList[0] : $company_address ;
        $jobCompanyName = (isset($jobItem->company->name)) ? $jobItem->company->name : $company_name ;
        $jobDescription = (isset($jobItem->Overview)) ? $jobItem->Overview : '' ;
        $jobListingTypeIdsArr = array();
        $customID = $jobItem->Id;
        $jobName = (isset($jobItem->Title)) ? $jobItem->Title.' at '.$jobCompanyName : '' ;
        $jobApplyUrl = (isset($jobItem->ApplyUrl)) ? $jobItem->ApplyUrl : '' ;

        //$jobLogoUrl = $jobItem->company->logo_url;
        //$jobLogoID = dc_upload_img_url($jobLogoUrl);

        $jobCompanyLogo = ($jobLogoID) ? $jobLogoID : $company_logo ;

        /* if( $jobType ){
            $term = term_exists( $jobType, 'job_listing_type' );
            if ( $term !== 0 && $term !== null ) {
                $jobListingTypeIdsArr[] = $term['term_id'];
            }
        } */

        $jobPostArg = array(
            'post_type' => 'job_listing',
            'post_title'   => $jobName,
            'post_content' => $jobDescription,
            'post_status'  => 'draft',
            'meta_input'   => array(
                '_job_location' => $jobLocation,
                '_company_name' => $jobCompanyName,
                'custom_id' => $customID,
                'import_type' => 'newcrest',
                '_company_twitter' => $twitter_username,
                '_company_website' => $company_website,
                '_company_tagline' => $company_tag_line,
                '_apply_link' => $jobApplyUrl
            ),
        );

        if( !check_job_exist( $customID , 'newcrest' ) ){
            $jobID = wp_insert_post( $jobPostArg );

            if(!is_wp_error($jobID)){
                set_post_thumbnail( $jobID, $jobCompanyLogo );

                return $jobID;
            }else{
                return false;
            }
        }else {
            return false;
        }
    }

}