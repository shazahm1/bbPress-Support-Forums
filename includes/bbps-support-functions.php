<?php
/* 
bbps - support functions 
Contains all the functions that generate and update the topic status.
*/

/* @TODO rename / rework this function so it makes sense - what a noob */
function bbps_get_update_capabilities(){
	
	global $current_user;
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

	$topic_author_id = bbp_get_topic_author_id();
	$permissions = get_option('_bbps_status_permissions');
	$can_edit = "";
	//check the users permission this is easy
	if( $permissions['admin'] == 1 && current_user_can('administrator') || $permissions['mod'] == 1 && current_user_can('bbp_moderator') ){
		$can_edit = true;
	}
	//now check the current user against the topic creator are they they same person and can they cahnge the status?
	if ( $user_id == $topic_author_id && $permissions['user'] == 1 )
		$can_edit = true;

	return $can_edit;
}

/* @TODO ASAP */
/* split this function up as its getting way to big now with all these extra features */

 add_action('bbp_template_before_single_topic', 'bbps_add_support_forum_features');
function bbps_add_support_forum_features(){	
	//only display all this stuff if the support forum option has been selected.
	if (bbps_is_support_forum(bbp_get_forum_id())){
		$can_edit = bbps_get_update_capabilities();
		$topic_id = bbp_get_topic_id();
		$status = bbps_get_topic_status($topic_id);
		$forum_id = bbp_get_forum_id();
		$user_id = get_current_user_id();
		
		
		?> <div id="bbps_support_forum_options"> <?php
		//get out the option to tell us who is allowed to view and update the drop down list.
		if ( $can_edit == true ){ ?>
			<?php bbps_generate_status_options($topic_id,$status);
		}else{
		?>
			This topic is: <?php echo $status ;
		}
		?> </div> <?php
		//has the user enabled the move topic feature?
		if( (get_option('_bbps_enable_topic_move') == 1) && (current_user_can('administrator') || current_user_can('bbp_moderator')) ) { 
		?>
		<div id ="bbps_support_forum_move">
			<form id="bbps-topic-move" name="bbps_support_topic_move" action="" method="post">
				<label for="bbp_forum_id">Move topic to: </label><?php bbp_dropdown(); ?>
				<input type="submit" value="Move" name="bbps_topic_move_submit" />
				<input type="hidden" value="bbps_move_topic" name="bbps_action"/>
				<input type="hidden" value="<?php echo $topic_id ?>" name="bbps_topic_id" />
				<input type="hidden" value="<?php echo $forum_id ?>" name="bbp_old_forum_id" />
			</form>
		</div>  <?php
			
		}
	}
}

function bbps_get_topic_status($topic_id){
	$default = get_option('_bbps_default_status');
	$status = get_post_meta( $topic_id, '_bbps_topic_status', true );	
	//to do not hard code these if we let the users add their own satus
	if ($status)
		$switch = $status;
	else
		$switch = $default;
		
	switch($switch){
		case 1:
			return "not resolved";
			break;
		case 2:
			return "resolved";
			break;
		case 3:
			return "not a support question";
			break;
	}
}

//generates a drop down list with the support forum topic status only for admin and moderators tho.
function bbps_generate_status_options($topic_id){
	
	$dropdown_options = get_option( '_bbps_used_status' );
	$status = get_post_meta( $topic_id, '_bbps_topic_status', true );
	$default = get_option('_bbps_default_status');

	//only use the default value as selected if the topic doesnt ahve a status set
	if ($status)
		$value = $status;
	else
		$value = $default;
	?>
	<form id="bbps-topic-status" name="bbps_support" action="" method="post">
		<label for="bbps_support_options">This topic is: </label>
		<select name="bbps_support_option" id="bbps_support_options"> 
			<?php
			//we only want to display the options the user has selected. the long term goal is to let users add their own forum statuses
			if ( $dropdown_options['res'] == 1 ){ ?> <option value="1" <?php selected( $value,1 ) ; ?> >not resolved</option> <?php }  
			if ( $dropdown_options['notres'] == 1 ) {?> <option value="2" <?php selected( $value,2 ) ; ?> >resolved</option> <?php } 
			if ( $dropdown_options['notsup'] == 1 ) {?> <option value="3" <?php selected( $value,3 ) ; ?> >not a support question</option> <?php } ?>
		</select>
		<input type="submit" value="Update" name="bbps_support_submit" />
		<input type="hidden" value="bbps_update_status" name="bbps_action"/>
		<input type="hidden" value="<?php echo $topic_id ?>" name="bbps_topic_id" />
	</form> 
	<?php
}

function bbps_update_status(){
	$topic_id = $_POST['bbps_topic_id'];
	$status = $_POST['bbps_support_option'];
	//check if the topic already has resolved meta - if it does then delete it before readding
	//we do this so that any topic updates will have a new meta id for sorting recently resolved etc
	$has_status = get_post_meta($topic_id, '_bbps_topic_status',true);
	$is_urgent = get_post_meta($topic_id, '_bbps_urgent_topic',true);
	$is_claimed = get_post_meta($topic_id, '_bbps_topic_claimed',true);
	
	if($has_status)
		delete_post_meta($topic_id, '_bbps_topic_status');
	
	//if the status is going to resolved we need to check for claimed and urgent meta and delete this to
	// 2 == resolved status :)
	if ($status == 2){
		if($is_urgent)
			delete_post_meta($topic_id, '_bbps_urgent_topic');
		if($is_claimed)
			delete_post_meta($topic_id, '_bbps_topic_claimed');
		
	}
	
	update_post_meta( $topic_id, '_bbps_topic_status', $status );
}

function bbps_move_topic(){
	global $wpdb;
	$topic_id = $_POST['bbps_topic_id'];
	$new_forum_id = $_POST['bbp_forum_id'];
	$old_forum_id = $_POST['bbp_old_forum_id'];
		
	//move the topics we will need to run a recount to after this is done
	if ($topic_id != '' && $new_forum_id !=''){
		$wpdb->update( 'wp_posts', array('post_parent' => $new_forum_id), array('ID' => $topic_id) );
		update_post_meta( $topic_id, '_bbp_forum_id', $new_forum_id );	
		// update all the forum meta and counts for the old forum and the new forum
		bbp_update_forum( array('forum_id' => $new_forum_id) );
		bbp_update_forum( array('forum_id' => $old_forum_id) );
	}
}



//Urgent topic code starts
/* 
function bbps_urgent_topic_link 
Checks the status of the option and generates and displays 
a link based on if the topic is already marked as urgent
*/
function bbps_urgent_topic_link(){
	//bail if option not set or user permission not up to scratch or if the forum has not been set as a support forum
	if( (get_option('_bbps_status_permissions_urgent') == 1) && (current_user_can('administrator') || current_user_can('bbp_moderator')) && (bbps_is_support_forum(bbp_get_forum_id())) ) {
	$topic_id = bbp_get_topic_id();
		//1 = urgent topic 0 or nothing is topic not urgent so we give the admin / mods the chance to make it urgent
		if ( get_post_meta($topic_id, '_bbps_urgent_topic', true) != 1 ){
			$urgent_uri = add_query_arg( array( 'action' => 'bbps_make_topic_urgent', 'topic_id' => $topic_id ) );
			echo '<span class="bbp-admin-links bbps-links"><a href="' . $urgent_uri . '">Urgent</a> | </span>';
		}

	}
	return;
}

add_action('bbp_theme_after_reply_admin_links', 'bbps_urgent_topic_link');

//check if the url generated above has been clicked and generated
if ( (isset($_GET['action']) && isset($_GET['topic_id']) && $_GET['action'] == 'bbps_make_topic_urgent')  )
	bbps_urgent_topic();

if ( (isset($_GET['action']) && isset($_GET['topic_id']) && $_GET['action'] == 'bbps_make_topic_not_urgent')  )
	bbps_not_urgent_topic();
		
		
function bbps_urgent_topic(){
	$topic_id = $_GET['topic_id'];
	update_post_meta($topic_id, '_bbps_urgent_topic', 1);
}

function bbps_not_urgent_topic(){
	$topic_id = $_GET['topic_id'];
	delete_post_meta($topic_id, '_bbps_urgent_topic');
}

//display a message to all admin on the single topic view so they know a topic is urgent also give them a link to check it as not urgent
function display_urgent_message(){
	//only display to the correct people
	if( (get_option('_bbps_status_permissions_urgent') == 1) && (current_user_can('administrator') || current_user_can('bbp_moderator') ) &&  (bbps_is_support_forum( bbp_get_forum_id() )) ) {
		$topic_id = bbp_get_topic_id();
		//topic is urgent so make a link
		if(get_post_meta($topic_id, '_bbps_urgent_topic', true) == 1){
			$urgent_uri = add_query_arg( array( 'action' => 'bbps_make_topic_not_urgent', 'topic_id' => $topic_id ) );
			echo "<div class='bbps-support-forums-message'> This topic is currently marked as urgent change the status to " . '<a href="' . $urgent_uri . '">Not Urgent?</a></div>';
		}
	}

}
add_action( 'bbp_template_before_single_topic' , 'display_urgent_message' );


//Topic Claim code starts here

function bbps_claim_topic_link(){
	//bail if option not set or user permission not up to scratch or if the forum has not been set as a support forum
	if( (get_option('_bbps_claim_topic') == 1) && (current_user_can('administrator') || current_user_can('bbp_moderator')) && (bbps_is_support_forum(bbp_get_forum_id())) ) {
	$topic_id = bbp_get_topic_id();
	global $current_user;
    get_currentuserinfo();
	$user_id = $current_user->ID;
		//anything greater than one will be claimed as it saves the claimed user id and will set this back to 0 if a topic is unclaimed
		if ( get_post_meta($topic_id, '_bbps_topic_claimed', true) < 1 ){
			$urgent_uri = add_query_arg( array( 'action' => 'bbps_claim_topic', 'topic_id' => $topic_id, 'user_id' => $user_id ) );
			echo '<span class="bbp-admin-links bbps-links"><a href="' . $urgent_uri . '">Claim </a> | </span>';
		}

	}
	return;
}

add_action('bbp_theme_after_reply_admin_links', 'bbps_claim_topic_link');

//check for the link to be clicked
if ( (isset($_GET['action']) && isset($_GET['topic_id']) && isset($_GET['user_id']) && $_GET['action'] == 'bbps_claim_topic')  )
	bbps_claim_topic();
	
	if ( (isset($_GET['action']) && isset($_GET['topic_id']) && isset($_GET['user_id']) && $_GET['action'] == 'bbps_unclaim_topic')  )
	bbps_unclaim_topic();
	
function bbps_claim_topic(){
	$user_id = $_GET['user_id'];
	$topic_id = $_GET['topic_id'];
	//subscribe the user to the topic - this is a bbpress function
	bbp_add_user_subscription( $user_id, $topic_id );
	//record who has claimed the topic in postmeta for use within this plugin
	update_post_meta($topic_id, '_bbps_topic_claimed', $user_id);
}

function bbps_unclaim_topic(){
	$user_id = $_GET['user_id'];
	$topic_id = $_GET['topic_id'];
	//subscribe the user to the topic - this is a bbpress function
	bbp_remove_user_subscription( $user_id, $topic_id );
	//reupdate the postmeta with an id of 0 this is unclaimed now
	delete_post_meta($topic_id, '_bbps_topic_claimed' );
}

function bbps_display_claimed_message(){
	$topic_author_id = bbp_get_topic_author_id();
	global $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;
	//we want to display the claimed topic message to the topic owner to
	if( (get_option('_bbps_claim_topic') == 1) && (current_user_can('administrator') || current_user_can('bbp_moderator') || $topic_author_id == $user_id ) && (bbps_is_support_forum(bbp_get_forum_id())) ) {
		
		$topic_id = bbp_get_topic_id();
		$claimed_user_id = get_post_meta($topic_id, '_bbps_topic_claimed', true);
		if($claimed_user_id > 0){
			$user_info = get_userdata ($claimed_user_id);
			$claimed_user_name = $user_info->user_login;
		}
		if($claimed_user_id > 0 && $claimed_user_id != $user_id){
			echo "<div class='bbps-support-forums-message'>This topic is currently claimed by " .$claimed_user_name .", they will be working on it now. </div>";
		}
		//the person who claimed it can unclaim it this will also unsubscribe them when they do
		if ($claimed_user_id == $user_id){
			$urgent_uri = add_query_arg( array( 'action' => 'bbps_unclaim_topic', 'topic_id' => $topic_id, 'user_id' => $user_id ) );
			echo '<div class="bbps-support-forums-message"> You currently own this topic would you like to <a href="' . $urgent_uri . '">Unclame</a> it?</div>';
		}
	}
}

add_action( 'bbp_template_before_single_topic' , 'bbps_display_claimed_message' );	

//asign to another user code here:
/*
	$user_id = $_GET['user_id'];
	$topic_id = $_GET['topic_id'];
	//subscribe the user to the topic - this is a bbpress function
	bbp_add_user_subscription( $user_id, $topic_id );
	//record who has claimed the topic in postmeta for use within this plugin
	update_post_meta($topic_id, '_bbps_topic_claimed', $user_id);
*/
function bbps_assign_topic_form(){

	if( (get_option('_bbps_topic_assign') == 1) && (current_user_can('administrator') || current_user_can('bbp_moderator')) ) { 
		$topic_id = bbp_get_topic_id();
		$topic_assigned = get_post_meta($topic_id, 'bbps_topic_assigned', true);
		global $current_user;
		get_currentuserinfo();
		$current_user_id = $current_user->ID;
	?>	<div id="bbps_support_forum_options"> <?php
			
			$user_login = $current_user->user_login;
			if(!empty($topic_assigned)){
				if($topic_assigned == $current_user_id){
					?> <div class='bbps-support-forums-message'> This topic is assigned to you!</div><?php
				}
				else{
					$user_info = get_userdata ($topic_assigned);
					$assigned_user_name = $user_info->user_login;
				?> <div class='bbps-support-forums-message'> This topic is already assigned to: <?php echo $assigned_user_name; ?></div><?php	
				}
		}
	
		?>
		<div id ="bbps_support_topic_assign">
			<form id="bbps-topic-assign" name="bbps_support_topic_assign" action="" method="post">
			<?php	bbps_user_assign_dropdown(); ?>
				<input type="submit" value="Assign" name="bbps_support_topic_assign" />
				<input type="hidden" value="bbps_assign_topic" name="bbps_action"/>
				<input type="hidden" value="<?php echo $topic_id ?>" name="bbps_topic_id" />
			</form>
		</div></div>  <?php
		
		
	
	}
	
}

add_action( 'bbp_template_before_single_topic' , 'bbps_assign_topic_form' );	

function bbps_user_assign_dropdown(){

	//http://codex.wordpress.org/Class_Reference/WP_User_Query
	$args = array();
	$args[0] = 'ID';
	$args[1] = 'user_login';
	$args[2] = 'user_email';
	
	$wp_user_search = new WP_User_Query( array( 'role' => 'administrator', 'fields' => $args ) );
	$admins = $wp_user_search->get_results();
	
	$wp_user_search = new WP_User_Query( array( 'role' => 'bbp_moderator', 'fields' => $args ) );
	$moderators = $wp_user_search->get_results();
	
	$all_users = array_merge($moderators,$admins);
	$topic_id = bbp_get_topic_id();
	$claimed_user_id = get_post_meta($topic_id, 'bbps_topic_assigned', true);
	
	if ( !empty($all_users) ){
		if ( $claimed_user_id > 0 ){
			$text = "Reassign topic to: ";
		}else{
			$text = "Assign topic to: ";
		}
	
		echo $text;
			?>
		<select name="bbps_assign_list" id="bbps_support_options"> 
		<option value="">Unassigned</option><?php
		foreach ($all_users as $user){
		?>
			<option value="<?php echo $user->ID; ?>"> <?php echo $user->user_login; ?></option>
		<?php
		}
		?> </select> <?php
	}

}


function bbps_assign_topic(){
	$user_id = $_POST['bbps_assign_list'];
	$topic_id = $_POST['bbps_topic_id'];
	
	if ($user_id > 0){
		$userinfo = get_userdata($user_id);
		$user_email = $userinfo->user_email;
		$post_link = get_permalink( $topic_id );
		//add the user as a subscriber to the topic and send them an email to let them know they have been assigned to a topic
		bbp_add_user_subscription( $user_id, $topic_id );
		/*update the post meta with the assigned users id*/
		$assigned = update_post_meta($topic_id, 'bbps_topic_assigned', $user_id);
		$message = <<< EMAILMSG
		You have been assigned to the following topic, by another forum moderator or the site administrator. Please take a look at it when you get a chance.
		$post_link
EMAILMSG;
		if ($assigned == true){
			wp_mail($user_email,'A forum topic has been assigned to you', $message);
		}
	}
}

// I believe this Problem is because your Plugin is loading at the wrong time, and can be fixed by wrapping your plugin in a wrapper class.
//need to find a hook or think of the best way to do this
	if (!empty($_POST['bbps_support_topic_assign'])){
		bbps_assign_topic($_POST);
	}
	
	if (!empty($_POST['bbps_support_submit'])){
		bbps_update_status($_POST);
	}
	
	if (!empty($_POST['bbps_topic_move_submit'])){
		bbps_move_topic($_POST);
	}

// adds a class and status to the front of the topic title
function bbps_modify_title($title, $topic_id = 0){
	$topic_id = bbp_get_topic_id( $topic_id );
	$title = "";
	$topic_author_id = bbp_get_topic_author_id();
	global $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;
	
	$claimed_user_id = get_post_meta($topic_id, '_bbps_topic_claimed', true);
		if($claimed_user_id > 0){
			$user_info = get_userdata ($claimed_user_id);
			$claimed_user_name = $user_info->user_login;
		}

	//2 is the resolved status ID
	if (get_post_meta( $topic_id, '_bbps_topic_status', true ) == 2)
		echo '<span class="resolved"> [Resolved] </span>';
	//we only want to display the urgent topic status to admin and moderators
	if (get_post_meta( $topic_id, '_bbps_urgent_topic', true ) == 1 && (current_user_can('administrator') || current_user_can('bbp_moderator')))
		echo '<span class="urgent"> [Urgent] </span>';
	//claimed topics also only get shown to admin and moderators and the person who owns the topic
	if (get_post_meta( $topic_id, '_bbps_topic_claimed', true ) > 0 && (current_user_can('administrator') || current_user_can('bbp_moderator') || $topic_author_id == $user_id ) ){
		//if this option == 1 we display the users name not [claimed]
		if( get_option( '_bbps_claim_topic_display' ) == 1)
			echo '<span class="claimed">['. $claimed_user_name . ']</span>';
		else
			echo '<span class="claimed"> [Claimed] </span>';
	}
}

	
add_action('bbp_theme_before_topic_title', 'bbps_modify_title');
?>