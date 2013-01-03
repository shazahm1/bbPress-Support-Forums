<?php
class bbps_support_hours_widget extends WP_Widget {

	function bbps_support_hours_widget(){
		$widget_ops = array(
				'classname' => 'bbps_support_hours_widget',
				'description' => 'Set your support times for your support forum - these will be displayed to your posters'
		);
		
		$this->WP_Widget('bbps_support_hours_widget', 'Forum Support Hours', $widget_ops);
	}

//build widget form settings
	//title widtget heading displayed to user
	//open_time - time the forums are open
	//open_img - img to display to users when forum is open
	//close_time - time the forums will close
	//close_img - image to display when the forums are closed
	//clock_html - used to display a clock for the correct time zone for the form / site code can be generated at http://www.timeanddate.com/clocks/free.html
	//forum_closed - option to close your forum manually for a period of time.
	//forum_open_text - text to display when forum is open
	//forum_closed_text - text to display when forum is closed
	
	function form( $instance ){
		$defaults = array(
			'title' => 'Support Hours',
			'open_time' => '',
			'open_img' => '',
			'close_time' => '',
			'close_img' => '',
			'clock_html' => '',
			'forum_closed' => '',
			'forum_open_text' => 'Our forums are open',
			'forum_closed_text' => 'Our forums are closed',
			'closed_weekends' => '',
			'display_hours' =>'',
			);
			
			$instance = wp_parse_args( (array) $instance, $defaults );
			
			$title = $instance['title'];
			$open_time = $instance['open_time'];
			$open_img = $instance['open_img'];
			$close_time = $instance['close_time'];
			$close_img = $instance['close_img'];
			$clock_html = $instance['clock_html'];
			$forum_closed = $instance['forum_closed'];
			$forum_closed_text = $instance['forum_closed_text'];
			$forum_open_text = $instance['forum_open_text'];
			/*
$display_hours = $instance['display_hours'];
			$closed_weekends = $instance['closed_weekends'];
*/
/* 			exit('<pre>'.print_r($forum_closed,1).'</pre>'); */
			?>
			 <p>Title: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr($title); ?>" /> </p>
			<p>Open Time: <input class="widefat" name="<?php echo $this->get_field_name( 'open_time' ); ?>" type="text" value="<?php echo esc_attr($open_time); ?>" /> Please enter the opening time for your support forum in 24 hour formate eg: 9am 09:00 </p> 
			
			<p>Close Time: <input class="widefat" name="<?php echo $this->get_field_name( 'close_time' ); ?>" type="text" value="<?php echo esc_attr($close_time); ?>" /> Please enter the closing time for your support forum in 24 hour formate eg: 5pm 17:00 </p> 
			<p>Open Image: <input class="widefat" name="<?php echo $this->get_field_name( 'open_img' ); ?>" type="text" value="<?php echo esc_attr($open_img); ?>" /> place your closing image into the following directory: <strong> <?php echo BBPS_WIDGETS_PATH ?>images </strong>  then enter the name of your opening image in here, please be careful to spell it correctly and add on the file extension. eg openimage.png </p> 

			<p>Close Image: <input class="widefat" name="<?php echo $this->get_field_name( 'close_img' ); ?>" type="text" value="<?php echo esc_attr($close_img); ?>" /> place your closing image into the following directory: <strong> <?php echo BBPS_WIDGETS_PATH ?>images </strong> then enter the name of your opening image in here, please be careful to spell it correctly and add on the file extension. eg closeimage.png </p> 
			<p>Open Text: <input class="widefat" name="<?php echo $this->get_field_name( 'forum_open_text' ); ?>" type="text" value="<?php echo esc_attr($forum_open_text); ?>" /> This will get displayed to your users when the forums are open. This text has a class of "forum_text" if you would like to style it differently</p> 
			
			<p>Closed Text: <input class="widefat" name="<?php echo $this->get_field_name( 'forum_closed_text' ); ?>" type="text" value="<?php echo esc_attr($forum_closed_text); ?>" /> This will get displayed to your users when the forums are closed. This text has a class of "forum_text" if you would like to style it differently </p> 

			
			<p>Clock HTML: <textarea class="widefat" name="<?php echo $this->get_field_name( 'clock_html' ); ?>"><?php echo esc_attr($clock_html); ?></textarea> If you would like to display a clock showing the time in your current time zone head over <a href="http://www.timeanddate.com/clocks/free.html" traget="_blank">here</a> and make one, copy the code in the text area above and we will do the rest! </p> 
			<p> Forum Closed on Weekends?: <input class="checkbox" type="checkbox" <?php checked( $instance['closed_weekends'], 'on' ); ?> name="<?php echo $this->get_field_name( 'closed_weekends' ); ?>" /></p><p> Select this if your forum is closed on the weekends </p>     
				    
			<p> Forum Closed: <input class="checkbox" type="checkbox" <?php checked( $instance['forum_closed'], 'on' ); ?> name="<?php echo $this->get_field_name( 'forum_closed' ); ?>" /></p><p> Checking this box turns your widget into closed mode until you uncheck it - perfect if your away on holiday and not maintaining your forums. </p> 
			
			<p> Display forum hours: <input class="checkbox" type="checkbox" <?php checked( $instance['display_hours'], 'on' ); ?> name="<?php echo $this->get_field_name( 'display_hours' ); ?>" /></p><p> Select this if you would like to display your forum hours in the widget. </p> 

<?php
	}
	
	//save widget settings 
	function update($new_instance, $old_instance){
		
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['open_time'] = $new_instance['open_time'];
		$instance['open_img'] = $new_instance['open_img'];
		$instance['close_time'] = $new_instance['close_time'];
		$instance['close_img'] = $new_instance['close_img'];
		$instance['clock_html'] = $new_instance['clock_html'];
		$instance['forum_closed'] = $new_instance['forum_closed'];
		$instance['forum_closed_text'] = $new_instance['forum_closed_text'];
		$instance['forum_open_text'] = $new_instance['forum_open_text'];
		$instance['display_hours'] = $new_instance['display_hours'];
		$instance['closed_weekends'] = $new_instance['closed_weekends'];
		
		Return $instance;
		
	}
	
	function widget($args, $instance){
		extract($args);
		
		echo $before_widget;
		$title = apply_filters('widget_title', $instance['title']);
		$open_time = empty($instance['open_time']) ? '' : $instance['open_time'];
		$open_img = empty($instance['open_img'] ) ? '' : $instance['open_img'];
		$close_time = empty($instance['close_time'] ) ? '' : $instance['close_time'];
		$close_img = empty($instance['close_img'] ) ? '' : $instance['close_img'];
		$clock_html = empty($instance['clock_html'] ) ? '&nbsp' : $instance['clock_html'];
		$forum_closed = empty($instance['forum_closed'] ) ? '&nbsp' : $instance['forum_closed'];
		$forum_closed_text = empty($instance['forum_closed_text'] ) ? '&nbsp' : $instance['forum_closed_text'];
		$forum_open_text = empty($instance['forum_open_text'] ) ? '&nbsp' : $instance['forum_open_text'];
		$display_hours = empty($instance['display_hours'] ) ? '&nbsp' : $instance['display_hours'];
		$closed_weekends = empty($instance['closed_weekends'] ) ? '&nbsp' : $instance['closed_weekends'];
		$gmt = 0;
		$closed='';
		$open = '';
		//formate all the times ready to compaire them
		$time= current_time_fixed('mysql', $gmt);
		$day= bbps_current_date('mysql', $gmt);
		$time = str_replace (':' , '', $time);
		$open_time_raw = str_replace (':' , '', $open_time);
		$close_time_raw = str_replace (':' , '', $close_time);
		echo '<div id="supportwrapper">';
		if(!empty($title)) { echo $before_title . $title . $after_title; };
		// need to compare open and close times first becuse the open number can be bigger than the close time and the other way around - both have a different condition to check.
				if( $forum_closed == 'on' || ($closed_weekends == 'on' && $day == 'Saturday' ) || ($closed_weekends == 'on' && $day == 'Sunday' )){
					$closed = true;
				}else{
					if( ($open_time_raw < $close_time_raw)  && ($time >= $open_time_raw && !($time >= $close_time_raw)) || ($open_time_raw > $close_time_raw) && ( $time >= $open_time_raw && !($time <= $close_time_raw) ) )
						$open = true;
					else
						$closed = true;
		}
		if($open == true){
			echo '<span class="forum_text">'.$forum_open_text.'</span><br />';
			if ($open_img != '')
				echo '<img src="'.BBPS_WIDGETS_URL .'/images/'.$open_img.'">';
			if($display_hours == 'on')
				echo '<div class="forum_hours"> Our forum hours are: <br />'. bbps_format_time($open_time_raw) .' - '. bbps_format_time($close_time_raw) . '</div>';
		}
		
		if ($closed == true){
			echo '<span class="forum_text">'.$forum_closed_text.'</span><br />';
			if ($close_img != '')
			echo '<img src="'.BBPS_WIDGETS_URL.'/images/'.$close_img.'">';
			if($display_hours == 'on')
				echo '<div class="forum_hours"> Our forum hours are: <br />'. bbps_format_time($open_time_raw) .' - '. bbps_format_time($close_time_raw) . '</div>';
		}
		
		echo '<div id="html_clock">' . $clock_html. '</div></div>';
		echo $after_widget;

		//has the user manually set the forums to closed?
	/*
	if( $forum_closed == 'on' ){
			echo '<span class="forum_text">'.$forum_closed_text .'</span>';
			echo  '<img src="'.WP_PLUGIN_URL .'/support-hours/images/'.$close_img.'">';
		}else{
		
			if($open_time < $close_time){
				if ($time >= $open_time && !($time >= $close_time) ){
					echo '<span class="forum_text">'.$forum_open_text.'</span>';
					echo '<img src="'.WP_PLUGIN_URL .'/support-hours/images/'.$open_img.'">';
				}else{
					echo '<span class="forum_text">'.$forum_closed_text .'</span>';
					echo  '<img src="'.WP_PLUGIN_URL .'/support-hours/images/'.$close_img.'">';
				}
			} 
			
			if ($open_time > $close_time){
				if ($time >= $open_time && !($time <= $close_time) ){
					echo '<span class="forum_text">'.$forum_open_text .'</span>';
					echo '<img src="'.WP_PLUGIN_URL .'/support-hours/images/'.$open_img.'">';
				}else{
					echo '<span class="forum_text">'. $forum_closed_text .'</span>';
					echo  '<img src="'.WP_PLUGIN_URL .'/support-hours/images/'.$close_img.'">';
				}
			}
		
		
		}
*/
		

		
	}

}  // end of support hours widget class

function bbps_format_time($raw_time){
	
	//if the closing time is 2400 (12 midnight) we want to display am not pm so it has its own case
	//dont need to do the char count etc because we we know that there will be 4
	if($raw_time == 2400){
	$formate_time = $raw_time - 1200;
	$time_length = str_split($formate_time);
	$formate_time = $time_length[0]. $time_length[1] . ':'.$time_length[2] .$time_length[3].' AM';
	}
	else{
	
	if ($raw_time > 1300 && $raw_time < 2400 ){
		$formate_time = $raw_time - 1200;
		
		//need to know the length to know where to put the :
		$time_length = str_split($formate_time);
		//does the time have 3 or 4 digits
		$count = count($time_length);
		if ( $count == 3 )
			$formate_time = $time_length[0].':'.$time_length[1] .$time_length[2].' PM';
		else
			$formate_time = $time_length[0]. $time_length[1] . ':'.$time_length[2] .$time_length[3].' PM';
	} else {
	//the AM times dont need formating as they ahve not been rewritten into 12 hour time
		$time_length = str_split($raw_time);
		//does the time have 3 or 4 digits
		$count = count($time_length);
		if ( $count == 3 )
			$formate_time = $time_length[0].':'.$time_length[1] .$time_length[2].' AM';
		else
			$formate_time = $time_length[0]. $time_length[1] . ':'.$time_length[2] .$time_length[3].' AM';
	}
	}
	return $formate_time;
	
}
//we want to display our forum stuff in the widget area this function here deals with the time

function current_time_fixed( $type, $gmt=0 ) {
	$t =  ( $gmt ) ? gmdate( 'Y-m-d H:i:s' ) : gmdate( 'H:i', ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ) );
	switch ( $type ) {
		case 'mysql':
			return $t;
			break;
		case 'timestamp':
			return strtotime($t);
			break;
	}
}

//returns the current date based on wordpress date settings for the site.
function bbps_current_date( $type, $gmt = 0 ) {
	$t =  ( $gmt ) ? gmdate( 'Y-m-d H:i:s' ) : gmdate( 'H:i', ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ) );
	$current_time_stamp = strtotime($t);
	return date('l', $current_time_stamp);
}


?>