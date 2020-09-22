<?php
/**
 * Generates the content used in the notification email.
 *
 * This template can be overridden by copying it to yourtheme/wp-job-manager-alerts/content-email_job_listing.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager - Alerts
 * @category    Template
 * @version     1.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$types    = wpjm_get_the_job_types();
$location = get_the_job_location();
$company  = get_the_company_name();

echo '<div style="margin-bottom: 15px">';
echo '<p>';

// Job types
if ( $types && count( $types ) > 0 ) {
	$names = wp_list_pluck( $types, 'name' );

	$types_str = implode( ', ', $names );

	echo esc_html( $types_str ) . ' - ';
}

// Job title
echo esc_html( $post->post_title ) . "<br/>";

// Location and company
if ( $location ) {
	printf( __( 'Location: %s', 'wp-job-manager-alerts' ) . "<br/>", esc_html( strip_tags( $location ) ) );
}
if ( $company ) {
	printf( __( 'Company: %s', 'wp-job-manager-alerts' ) . "<br/>", esc_html( strip_tags( $company ) ) );
}

// Permalink
printf( __( 'View Details: %s', 'wp-job-manager-alerts' ) . "<br/>", get_the_job_permalink() );

echo '</p>';
echo "</div>";
