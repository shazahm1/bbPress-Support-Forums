<?php 
class bbps_claimed_topics_widget extends WP_Widget {
	
	function bbps_claimed_topics_widget(){
		$widget_ops = array(
				'classname' => 'bbps_claimed_topics_widget',
				'description' => 'Dsiplay a list of the users claimed topics'
		);
		
		$this->WP_Widget('bbps_claimed_topics_widget', 'Claimed Topics', $widget_ops);
	}
	
	function form( $instance ){
	$defaults = array(
		'title' => 'My Claimed Topics',
		'number_of_claimed_topics' => '10',
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = $instance['title'];
		$number_topics = $instance['number_of_claimed_topics'];
	
			?>
			<p>Title: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr($title); ?>" /> </p>
			<p>Topic to show<input class="widefat" name="<?php echo $this->get_field_name( 'number_of_claimed_topics' ); ?>" type="text" value="<?php echo esc_attr($number_topics); ?>" /></p> 
			<p>How many resolved topics would you like to display?</p>					
	<?php
	
	}
	
	function update($new_instance, $old_instance){
		
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['number_of_claimed_topics'] = $new_instance['number_of_claimed_topics'];
		return $instance;
		
	}
	
	
	function widget($args, $instance){
		extract($args);	
		$number_claimed_topics = $instance['number_of_claimed_topics'];
			echo $before_widget;
			$title = apply_filters('widget_title', $instance['title']);
			if(!empty($title)) { echo $before_title . $title . $after_title; };
				get_users_claimed_topics($number_claimed_topics);
			echo $after_widget . " ";		
	}

} 
// end of claimed topics class
function get_users_claimed_topics($number_claimed_topics){
	global $wpdb, $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;
	
	$claimed_query = "SELECT `meta_id`, `post_id` FROM " . $wpdb->postmeta . " WHERE `meta_key` = '_bbps_topic_claimed' AND `meta_value` = ".$user_id ." ORDER BY meta_id DESC LIMIT " . $number_claimed_topics ;
	$claimed_topics = $wpdb->get_results($claimed_query);
	$permalink="";
	echo "<ul>";
	foreach( (array) $claimed_topics as $claimed_topic )
		echo '<li><a href="'.get_permalink($claimed_topic->post_id) .'"> ' . get_the_title($claimed_topic->post_id) . '</a></li>';
	echo "</ul>";	
}
?>