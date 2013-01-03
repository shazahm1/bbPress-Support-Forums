<?php 
class bbps_support_resolved_count_widget extends WP_Widget {
	
	function bbps_support_resolved_count_widget(){
		$widget_ops = array(
				'classname' => 'bbps_support_resolved_count_widget',
				'description' => 'Dsiplay a count of resolved topics in your forum'
		);
		
		$this->WP_Widget('bbps_support_resolved_count_widget', 'Resolved Topic Count', $widget_ops);
	}
	
	function form( $instance ){
	$defaults = array(
		'title' => 'Resolved Topics',
		'show_total' => '',
		'show_resolved' => '',
		'text_before_total' => '',
		'text_after_total' => '',
		'text_before_resolved' => '',
		'text_after_resolved' => ''
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = $instance['title'];
		$show_total = $instance['show_total'];
		$show_resolved = $instance['show_resolved'];
		$text_before_total = $instance['text_before_total'];
		$text_after_total = $instance['text_after_total'];
		$text_before_resolved = $instance['text_before_resolved'];
		$text_after_resolved = $instance['text_after_resolved'];
		
		if (function_exists('bbps_add_support_forum_features')){
			?>
			<p>Title: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr($title); ?>" /> </p>
			
			<p> Display Total Topic Count: <input class="checkbox" type="checkbox" <?php checked( $instance['show_total'], 'on' ); ?> name="<?php echo $this->get_field_name( 'show_total' ); ?>" /></p><p> This will display the total number of topics in your forums </p> 
			
			<p> Display Resolved Topic Count: <input class="checkbox" type="checkbox" <?php checked( $instance['show_resolved'], 'on' ); ?> name="<?php echo $this->get_field_name( 'show_resolved' ); ?>" /></p><p> This will display the total number of resolved topics in your forums </p> 
			
			<p>Text Before Total: <input class="widefat" name="<?php echo $this->get_field_name( 'text_before_total' ); ?>" type="text" value="<?php echo esc_attr($text_before_total); ?>" /> The text you would like to display before the total topics count eg: Our Forums have </p> 
			
			<p>Text After Total: <input class="widefat" name="<?php echo $this->get_field_name( 'text_after_total' ); ?>" type="text" value="<?php echo esc_attr($text_after_total); ?>" /> The text you would like to display after the total topics count eg: topics in total </p> 
			<p>Text Before Resolved Total: <input class="widefat" name="<?php echo $this->get_field_name( 'text_before_resolved' ); ?>" type="text" value="<?php echo esc_attr($text_before_resolved); ?>" /> The text you would like to display before the resolved topic count eg: We have </p> 
			
			<p>Text After Resolved Total: <input class="widefat" name="<?php echo $this->get_field_name( 'text_after_resolved' ); ?>" type="text" value="<?php echo esc_attr($text_after_resolved); ?>" /> The text you would like to display after the resolved topic count eg: topics that are resolved </p> 
			
	<?php
		}else{
			echo '<p> You need the GetShopped Support plugin to use this widget </p>';
		}
	}
	
	//save widget settings - not sure if we need this
	function update($new_instance, $old_instance){
		
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['show_total'] = $new_instance['show_total'];
		$instance['show_resolved'] = $new_instance['show_resolved'];
		$instance['text_before_total'] = $new_instance['text_before_total'];
		$instance['text_after_total'] = $new_instance['text_after_total'];
		$instance['text_before_resolved'] = $new_instance['text_before_resolved'];
		$instance['text_after_resolved'] = $new_instance['text_after_resolved'];
		
		Return $instance;
		
	}
	
	
	function widget($args, $instance){
		extract($args);
		
		echo $before_widget;
		$title = apply_filters('widget_title', $instance['title']);

		$text_before_total = empty($instance['text_before_total'] ) ? '' : $instance['text_before_total'];
		$text_after_total = empty($instance['text_after_total'] ) ? '' : $instance['text_after_total'];
		$text_before_resolved = empty($instance['text_before_resolved'] ) ? '' : $instance['text_before_resolved'];
		$text_after_resolved = empty($instance['text_after_resolved'] ) ? '' : $instance['text_after_resolved'];
		$total_resolved = bbps_get_resolved_count();
		$total_topics = bbps_get_topic_count();
		
		if(!empty($title)) { echo $before_title . $title . $after_title; };
		
		echo $text_before_total . " ";
		if ($instance['show_total'] == 'on')
			echo $total_topics . " ";
		echo $text_after_total . "<br />";
		
		echo $text_before_resolved . " ";
		if ($instance['show_resolved'] == 'on')
			echo $total_resolved . " ";
		echo $text_after_resolved . " ";
		
		echo $after_widget . " ";
	}


} // end of resolved count class

/* 
function bbps_get_resolved_count
does a simple mysql query and counts all the resolved topics - status id 2
@return the resolved topic count
 */
function bbps_get_resolved_count(){
	global $wpdb;
	$resolved_query = "SELECT `meta_id` FROM " . $wpdb->postmeta . " WHERE `meta_key` = '_bbps_topic_status' AND `meta_value` = 2";
	$resolved_count = $wpdb->get_col($resolved_query);
	return count($resolved_count);
}

/* 
function bbps_get_resolved_count
does a simple mysql query and counts all the topics
@return the total topic count
 */
function bbps_get_topic_count(){
	global $wpdb;
	$topic_query = "SELECT `ID` FROM " . $wpdb->posts . " WHERE `post_type` = 'topic' AND `post_status` = 'publish'";

	$topic_count = $wpdb->get_col($topic_query);
	return count($topic_count);
}

?>