<?php 
class bbps_support_recently_resolved_widget extends WP_Widget {
	
	function bbps_support_recently_resolved_widget(){
		$widget_ops = array(
				'classname' => 'bbps_support_recently_resolved_widget',
				'description' => 'Dsiplay a list of recently resolved topics in your forum'
		);
		
		$this->WP_Widget('bbps_support_recently_resolved_widget', 'Recently Resolved', $widget_ops);
	}
	
	function form( $instance ){
	$defaults = array(
		'title' => 'Recently Resolved',
		'number_of_topics' => '10',
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = $instance['title'];
		$number_topics = $instance['number_of_topics'];
	
			?>
			<p>Title: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr($title); ?>" /> </p>
			<p>Topic to show<input class="widefat" name="<?php echo $this->get_field_name( 'number_of_topics' ); ?>" type="text" value="<?php echo esc_attr($number_topics); ?>" /></p> 
			<p>How many resolved topics would you like to display?</p>					
	<?php
	
	}
	
	function update($new_instance, $old_instance){
		
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['number_of_topics'] = $new_instance['number_of_topics'];
		return $instance;
		
	}
	
	
function widget($args, $instance){
	extract($args);	
	$number_topics = 	$instance['number_of_topics'];
		echo $before_widget;
		$title = apply_filters('widget_title', $instance['title']);
		if(!empty($title)) { echo $before_title . $title . $after_title; };
			get_resolved_topic_list($number_topics);
		echo $after_widget . " ";		
}


} // end of resolved count class


function get_resolved_topic_list($number_topics){
	global $wpdb;
	
	$resolved_query = "SELECT `meta_id`, `post_id` FROM " . $wpdb->postmeta . " WHERE `meta_key` = '_bbps_topic_status' AND `meta_value` = 2 ORDER BY meta_id DESC LIMIT " . $number_topics ;
	
	$resolved_topics = $wpdb->get_results($resolved_query);
	$permalink="";
	echo "<ul>";
	foreach( (array) $resolved_topics as $resolved_topic )
		echo '<li><a href="'.get_permalink($resolved_topic->post_id) .'"> ' . get_the_title($resolved_topic->post_id) . '</a></li>';
	echo "</ul>";	
}

?>