<?php 
class bbps_support_urgent_topics_widget extends WP_Widget {
	
	function bbps_support_urgent_topics_widget(){
		$widget_ops = array(
				'classname' => 'bbps_support_urgent_topics_widget',
				'description' => 'Dsiplay a list of urgent topics in your forum'
		);
		
		$this->WP_Widget('bbps_support_urgent_topics_widget', 'Urgent Topics', $widget_ops);
	}
	
	function form( $instance ){
	$defaults = array(
		'title' => 'Urgent Topics',
		'show_urgent_list_admin' => '',
		'show_urgent_list_mod' => '',
		'show_urgent_list_user' => '',
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = $instance['title'];
		$show_admin = $instance['show_urgent_list_admin'];
		$show_mod = $instance['show_urgent_list_mod'];
		$show_user = $instance['show_urgent_list_user'];
				
		
			?>
			<p>Title: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr($title); ?>" /> </p>
			<p>Select the user types who will be able to see the list of urgent topics</p>
			<p> Administrators <input class="checkbox" type="checkbox" <?php checked( $instance['show_urgent_list_admin'], 'on' ); ?> name="<?php echo $this->get_field_name( 'show_urgent_list_admin' ); ?>" /></p>
			
			<p> Moderators <input class="checkbox" type="checkbox" <?php checked( $instance['show_urgent_list_mod'], 'on' ); ?> name="<?php echo $this->get_field_name( 'show_urgent_list_mod' ); ?>" /></p>
			
			<p> Site Users <input class="checkbox" type="checkbox" <?php checked( $instance['show_urgent_list_user'], 'on' ); ?> name="<?php echo $this->get_field_name( 'show_urgent_list_user' ); ?>" /></p> 

						
	<?php
	
	}
	
	function update($new_instance, $old_instance){
		
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['show_urgent_list_admin'] = $new_instance['show_urgent_list_admin'];
		$instance['show_urgent_list_mod'] = $new_instance['show_urgent_list_mod'];
		$instance['show_urgent_list_user'] = $new_instance['show_urgent_list_user'];
		
		Return $instance;
		
	}
	
	
function widget($args, $instance){
	extract($args);
	
	if ( ($instance['show_urgent_list_admin'] == 'on' && current_user_can('administrator')) || ($instance['show_urgent_list_mod'] == 'on' && current_user_can('bbp_moderator')) || ($instance['show_urgent_list_user'] == 'on' && is_user_logged_in() ) ){
		
		echo $before_widget;
		$title = apply_filters('widget_title', $instance['title']);
		if(!empty($title)) { echo $before_title . $title . $after_title; };
			get_urgent_topic_list();
		echo $after_widget . " ";
			
	}				
}


} // end of resolved count class
//urgent topics we want the oldest at the top!
function get_urgent_topic_list(){
	global $wpdb;
	$urgent_query = "SELECT `post_id` FROM " . $wpdb->postmeta . " WHERE `meta_key` = '_bbps_urgent_topic' AND `meta_value` = 1";
	$urgent_topics = $wpdb->get_col($urgent_query);
	$permalink="";
	$urgent_topic="";
	echo "<ul>";
	foreach( (array) $urgent_topics as $urgent_topic )
		echo '<li><a href="'.get_permalink($urgent_topic) .'"> ' . get_the_title($urgent_topic) . '</a></li>';
	echo "</ul>";	
}
?>