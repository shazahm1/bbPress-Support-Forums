<?php
//add the option on the activation of this plugin
add_action( 'bbps-activation',   'bbps_add_options'  );

/*
function bbps_add_options
Creates the default options
simply extend the array to add more options.

Note: These options only get added on
activation so if your adding more options
you will need to reactivate your plugin
*/
function bbps_add_options() {

	// Default options
	$options = array (
	//user counts and titles
		// The default display for topic status we used not resolved as default
		'_bbps_default_status'  => '1',
		//enable user post count display
		'_bbps_enable_post_count'   => '1',
		//enable user rank
		'_bbps_enable_user_rank'   => '1',
		//defaults for who can change the topic status
		'_bbps_status_permissions'   => '',
		// the reply counts / boundaries for the custom forum poster titles this has no default as the user must set these
		'_bbps_reply_count'    => '',
		//the status people want to show on their topics.
		'_bbps_used_status'        => '',
		//give admin and forum moderators the ability to move topics into other forums default = enabled
		'_bbps_enable_topic_move' => '1',
		//urgent topics
		'_bbps_status_permissions_urgent' => '',
		//do a color change for resolved topics
		//'_bbps_status_color_change' => '1',
	);
	// Add default options
	foreach ( $options as $key => $value )
		add_option( $key, $value );

}

function bbps_is_post_count_enabled(){
	return get_option( '_bbps_enable_post_count' );
}

function bbps_is_user_rank_enabled(){
	return get_option( '_bbps_enable_user_rank' );
}

/*
* WEBMAN EDITED CODE STARTS HERE
* www.webmandesign.eu
*/
function bbps_is_resolved_enabled(){
	$options = get_option( '_bbps_used_status' );
	return ( isset( $options['res'] ) ) ? ( $options['res'] ) : ( false );
}

function bbps_is_not_resolved_enabled(){
	$options = get_option( '_bbps_used_status' );
	return ( isset( $options['notres'] ) ) ? ( $options['notres'] ) : ( false );
}

function bbps_is_not_support_enabled(){
	$options = get_option( '_bbps_used_status' );
	return ( isset( $options['notsup'] ) ) ? ( $options['notsup'] ) : ( false );
}

function bbps_is_moderator_enabled(){
	$options = get_option( '_bbps_status_permissions' );
	return ( isset( $options['mod'] ) ) ? ( $options['mod'] ) : ( false );
}

function bbps_is_admin_enabled(){
	$options = get_option( '_bbps_status_permissions' );
	return ( isset( $options['admin'] ) ) ? ( $options['admin'] ) : ( false );
}

function bbps_is_user_enabled(){
	$options = get_option( '_bbps_status_permissions' );
	return ( isset( $options['user'] ) ) ? ( $options['user'] ) : ( false );
}
/*
* WEBMAN EDITED CODE ENDS HERE
* www.webmandesign.eu
*/

function bbps_is_topic_move_enabled(){
	return get_option( '_bbps_enable_topic_move' );
}

function bbps_is_topic_urgent_enabled(){
	return get_option( '_bbps_status_permissions_urgent' );
}

function bbps_is_topic_claim_enabled(){
	return get_option( '_bbps_claim_topic' );
}

function bbps_is_topic_claim_display_enabled(){
	return get_option( '_bbps_claim_topic_display' );
}

function bbps_is_topic_assign_enabled(){
	return get_option( '_bbps_topic_assign' );
}

function bbps_is_user_trusted_enabled(){
	return get_option( '_bbps_enable_trusted_tag' );
}




?>