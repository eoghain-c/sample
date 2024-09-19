<?php


/***************************************************************
 * Revinate Integration using GravityForms (Email Sign-up ID: 1)
 ***************************************************************/

add_action("gform_after_submission_1", "submission_to_revinate_gform1", 10, 2);
function submission_to_revinate_gform1($entry, $form) {
	// Get form Data to pass to revinate
	global $wp;
	$endpoint = 'https://webservices.navisperformance.com/Narrowcast/ELM/ELMContactPost.aspx';
	$current_url = home_url( add_query_arg( array(), $wp->request ) );
	$post_data_object = [
		'Subject' => 'Email Signup',
		'account' => '14627',
		'_successURL' => $current_url
	];

	if (!empty($entry["1"]))  {
		$post_data_object['EmailAddress'] = $entry["1"];
	}

	// Send Data to Revinate
	$response = wp_remote_post( $endpoint, array( 'body' => $post_data_object ) );
}

/***************************************************************
 * Revinate Integration using GravityForms (Tee Time ID: 2)
 ***************************************************************/

add_action("gform_after_submission_2", "submission_to_revinate_gform2", 10, 2);
function submission_to_revinate_gform2($entry, $form) {
	// Get form Data to pass to revinate
	global $wp;
	$endpoint = 'https://webservices.navisperformance.com/Narrowcast/ELM/ELMContactPost.aspx';
	$current_url = home_url( add_query_arg( array(), $wp->request ) );
	$post_data_object = [
		'Subject' => 'Tee Time',
		'account' => '14627',
		'_successURL' => $current_url
	];

	if (!empty($entry["1"]))  {
		$post_data_object['FirstName'] = $entry["1"];
	}
	if (!empty($entry["3"]))  {
		$post_data_object['LastName'] = $entry["3"];
	}
	if (!empty($entry["11"]))  {
		$post_data_object['EmailAddress'] = $entry["11"];
	}
	if (!empty($entry["12"]))  {
		$post_data_object['CellPhone'] = $entry["12"];
	}

	// Send Data to Revinate
	$response = wp_remote_post( $endpoint, array( 'body' => $post_data_object ) );
}

/***************************************************************
 * Revinate Integration using GravityForms (Request An Appointment ID: 3)
 ***************************************************************/

add_action("gform_after_submission_3", "submission_to_revinate_gform3", 10, 2);
function submission_to_revinate_gform3($entry, $form) {
	// Get form Data to pass to revinate
	global $wp;
	$endpoint = 'https://webservices.navisperformance.com/Narrowcast/ELM/ELMContactPost.aspx';
	$current_url = home_url( add_query_arg( array(), $wp->request ) );
	$post_data_object = [
		'Subject' => 'Request An Appointment',
		'account' => '14627',
		'_successURL' => $current_url
	];
	if (!empty($entry["1"]))  {
		$post_data_object['FirstName'] = $entry["1"];
	}
	if (!empty($entry["3"]))  {
		$post_data_object['LastName'] = $entry["3"];
	}
	if (!empty($entry["12"]))  {
		$post_data_object['CellPhone'] = $entry["12"];
	}
	if (!empty($entry["13"]))  {
		$post_data_object['EmailAddress'] = $entry["13"];
	}

	// Send Data to Revinate
	$response = wp_remote_post( $endpoint, array( 'body' => $post_data_object ) );
}

/***************************************************************
* Revinate Integration using GravityForms (Group RFP ID: 4)
***************************************************************/

add_action("gform_after_submission_4", "submission_to_revinate_gform4", 10, 2);
function submission_to_revinate_gform4($entry, $form) {
	// Get form Data to pass to revinate
	global $wp;
	$endpoint = 'https://webservices.navisperformance.com/Narrowcast/ELM/ELMContactPost.aspx';
	$current_url = home_url( add_query_arg( array(), $wp->request ) );
	$post_data_object = [
		'Subject' => 'Group RFP',
		'account' => '14627',
		'_successURL' => $current_url
	];

	if (!empty($entry["8"]))  {
		$post_data_object['FirstName'] = $entry["8"];
	}
	if (!empty($entry["9"]))  {
		$post_data_object['LastName'] = $entry["9"];
	}
	if (!empty($entry["10"]))  {
		$post_data_object['Address1'] = $entry["10"];
	}
	if (!empty($entry["11"]))  {
		$post_data_object['Address2'] = $entry["11"];
	}
	if (!empty($entry["12"])) {
		$post_data_object['City'] = $entry["12"];
	}
	if (!empty($entry["13"]))  {
		$post_data_object['State'] = $entry["13"];
	}
	if (!empty($entry["15"]))  {
		$post_data_object['ZipCode'] = $entry["15"];
	}
	if (!empty($entry["16"]))  {
		$post_data_object['CellPhone'] = $entry["16"];
	}
	if (!empty($entry["17"]))  {
		$post_data_object['EmailAddress'] = $entry["17"];
	}

	// Send Data to Revinate
	$response = wp_remote_post( $endpoint, array( 'body' => $post_data_object ) );
}

/***************************************************************
 * Revinate Integration using GravityForms (Event RFP ID: 5)
 ***************************************************************/

add_action("gform_after_submission_5", "submission_to_revinate_gform5", 10, 2);
function submission_to_revinate_gform5($entry, $form) {
	// Get form Data to pass to revinate
	global $wp;
	$endpoint = 'https://webservices.navisperformance.com/Narrowcast/ELM/ELMContactPost.aspx';
	$current_url = home_url( add_query_arg( array(), $wp->request ) );
	$post_data_object = [
		'Subject' => 'Event RFP',
		'account' => '14627',
		'_successURL' => $current_url
	];

	if (!empty($entry["7"]))  {
		$post_data_object['FirstName'] = $entry["7"];
	}
	if (!empty($entry["8"]))  {
		$post_data_object['LastName'] = $entry["8"];
	}
	if (!empty($entry["10"]))  {
		$post_data_object['Address1'] = $entry["10"];
	}
	if (!empty($entry["11"]))  {
		$post_data_object['Address2'] = $entry["11"];
	}
	if (!empty($entry["12"])) {
		$post_data_object['City'] = $entry["12"];
	}
	if (!empty($entry["13"]))  {
		$post_data_object['State'] = $entry["13"];
	}
	if (!empty($entry["14"]))  {
		$post_data_object['ZipCode'] = $entry["14"];
	}
	if (!empty($entry["16"]))  {
		$post_data_object['CellPhone'] = $entry["16"];
	}
	if (!empty($entry["15"]))  {
		$post_data_object['EmailAddress'] = $entry["15"];
	}

	// Send Data to Revinate
	$response = wp_remote_post( $endpoint, array( 'body' => $post_data_object ) );
}

/***************************************************************
 * Revinate Integration using GravityForms (Reservation ID: 6)
 ***************************************************************/

add_action("gform_after_submission_6", "submission_to_revinate_gform6", 10, 2);
function submission_to_revinate_gform6($entry, $form) {
	// Get form Data to pass to revinate
	global $wp;
	$endpoint = 'https://webservices.navisperformance.com/Narrowcast/ELM/ELMContactPost.aspx';
	$current_url = home_url( add_query_arg( array(), $wp->request ) );
	$post_data_object = [
		'Subject' => 'Reservation',
		'account' => '14627',
		'_successURL' => $current_url
	];

	if (!empty($entry["1"]))  {
		$post_data_object['FirstName'] = $entry["1"];
	}
	if (!empty($entry["3"]))  {
		$post_data_object['LastName'] = $entry["3"];
	}
	if (!empty($entry["11"]))  {
		$post_data_object['EmailAddress'] = $entry["11"];
	}
	if (!empty($entry["12"]))  {
		$post_data_object['CellPhone'] = $entry["12"];
	}

	// Send Data to Revinate
	$response = wp_remote_post( $endpoint, array( 'body' => $post_data_object ) );
}

/***************************************************************
 * Revinate Integration using GravityForms (Contact Form ID: 7)
 ***************************************************************/

add_action("gform_after_submission_7", "submission_to_revinate_gform7", 10, 2);
function submission_to_revinate_gform7($entry, $form) {
	// Get form Data to pass to revinate
	global $wp;
	$endpoint = 'https://webservices.navisperformance.com/Narrowcast/ELM/ELMContactPost.aspx';
	$current_url = home_url( add_query_arg( array(), $wp->request ) );
	$post_data_object = [
		'Subject' => 'Contact Form',
		'account' => '14627',
		'_successURL' => $current_url
	];

	if (!empty($entry["1"]))  {
		$post_data_object['FirstName'] = $entry["1"];
	}
	if (!empty($entry["3"]))  {
		$post_data_object['LastName'] = $entry["3"];
	}
	if (!empty($entry["4"]))  {
		$post_data_object['EmailAddress'] = $entry["4"];
	}
	if (!empty($entry["5"]))  {
		$post_data_object['CellPhone'] = $entry["15"];
	}

	// Send Data to Revinate
	$response = wp_remote_post( $endpoint, array( 'body' => $post_data_object ) );
}