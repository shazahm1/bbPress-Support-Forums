<?php

//this file contains all the functions realting to the restricted premium forums
//this file doesnt really need to be on its own - was going to include the getyshopped
//extra stuff in here but have taken it out and made another plugin
//this code is not used in the beta version as it needs more testing and work
add_filter('bbp_has_topics_query','bbps_lock_to_author');

function bbps_lock_to_author($bbp_t){
global $wp_query;

	//return if we are at a prem forum or the user is an admin or moderator	and we are not looking at a users profile page!
	if ((bbps_is_premium_forum(bbp_get_forum_id()) == false || current_user_can('administrator') || current_user_can('bbp_moderator')) && !bbp_is_single_user()){
		return $bbp_t;
	}	
	
	// is someone looking at a user page? if they are then we want to exclude all premium posts 
	//and change the post author to be the users who profile it is
	if ( bbp_is_single_user() ){
		$premium_topics = bbps_get_all_premium_topic_ids();
		$user_id = bbp_get_displayed_user_id();
		$bbp_t['post_author'] = $user_id;
		$bbp_t['author'] = $user_id;
		$bbp_t['post__not_in'] = $premium_topics;
		$bbp_t['post_type'] = 'topic';
		return $bbp_t;
	}else{
		//there is one problem with this - if the users ID is 0 then it still shows all topics
		//setting userid to -1 seems to do the trick .. better way perhapes?
		//ops no it doesnt do the trick so for lauch I will make it a huge number this will need to be revisited ASAP
		global $current_user;
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		if ( $user_id == 0 )
			$user_id = 99999999;
		//create the new query we only want to display topics from that user
		$bbp_t['post_author'] = $user_id;
		$bbp_t['author'] = $user_id;
		$bbp_t['post_type'] = 'topic';
		$bbp_t['show_stickies'] = 0;
		$bbp_t['posts_per_page'] = 30;
	//	exit('<pre>'.print_r($bbp_t,1).'</pre>');
		return $bbp_t;
	}
}

//This function will remove the authors name and link from the freshness
// if the user is not an admin or a mod have I over written the functionality of private forums tho? Must test this
function bbps_hide_author_link($author_link, $args = 0){

$retval = '';
if (bbps_is_premium_forum(bbp_get_forum_id()) == false || current_user_can('administrator') || current_user_can('bbp_moderator'))
	$retval = $author_link;

return $retval;
}
add_filter('bbp_suppress_private_author_link','bbps_hide_author_link',5,2);

//Do the same ofr all the forum meta replace it with - if we are in premium forums admin and mods can see all info at all times
function bbps_hide_forum_meta($retval, $forum_id = 0) {
	if (bbps_is_premium_forum(bbp_get_forum_id()) == false || current_user_can('administrator') || current_user_can('bbp_moderator'))
		return $retval;
	else
	return $retval = '-';	
}
add_filter( 'bbp_suppress_private_forum_meta', 'bbps_hide_forum_meta',10,2 );
?>