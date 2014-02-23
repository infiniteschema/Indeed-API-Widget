<?php
/**
 * Plugin Name: Indeed API Widget
 * Plugin URI: https://github.com/infiniteschema/Indeed-API-Widget
 * Description: Display Indeed API plugin output in widget.
 * Version: 1.0
 * Author: Calen Fretts
 * Author URI: http://infiniteschema.com
 * License: GPLv2
 * GitHub Plugin URI: https://github.com/infiniteschema/Indeed-API-Widget
 */
 
/* 
Copyright (C) 2014 Calen Fretts

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

add_action('wp_enqueue_scripts', 'indeed_api_wp_enqueue_scripts');
function indeed_api_wp_enqueue_scripts() {
	wp_enqueue_style('indeed-api-widget', plugins_url('style.css', __FILE__));
}

add_action('widgets_init', 'indeed_api_widgets_init');
function indeed_api_widgets_init() {
	register_widget('IndeedAPI_Widget');
}

class IndeedAPI_Widget extends WP_Widget {

	function __construct(){
		parent::WP_Widget( 'indeed-api-widget', 'Indeed API', array( 'classname' => 'indeed-api-widget', 'description' => __('Display Indeed API plugin output in widget.') ) );
	}
	
	function widget($args, $instance){
		extract($args);
		$output = $this->output($instance);
		if(!$output)
			echo '';
		else{
			$title = apply_filters('widget_title', $instance['title']);
			echo $before_widget;
			if ($title)
				echo $before_title . $title . $after_title;
			echo $output;
			if ($instance['html_after'])
				echo $instance['html_after'];
			echo $after_widget;
		}
	}
	
	function output($options){
		$id = 'indeed-api-widget_' . uniqid();	
		$output .= <<<EOT
<div id="{$id}" class="indeed-api-widget-div">

[indeedsearchstyle]
[indeedsearchform]
[indeedsearchresults]

<span class="indeed_at"><a href="http://www.indeed.com/?indpubnum=9744949221201476" target="_blank">jobs</a> by <a title="Job Search" href="http://www.indeed.com/?indpubnum=9744949221201476" target="_blank"><img alt="Indeed job search" style="border: 0; vertical-align: middle; display: inherit;" src="http://www.indeed.com/p/jobsearch.gif"></a></span>

<div class="indeed_em">
<strong>Employers</strong>
<br />
<a href="http://www.indeed.com/hire?indpubnum=9744949221201476" target="_blank">Post Job</a>
<a href="http://www.indeed.com/resumes?indpubnum=9744949221201476&co=US" target="_blank">Search Resumes</a>
</div>

</div>
EOT;
		return indeed_search_filter($output);
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] 			= strip_tags($new_instance['title']);
		return $instance;
	}
	
	function form($instance){
		$defaults = array(
			'title' 		=> ''
		);
		$instance = wp_parse_args((array)$instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
	<?php
	}
}
?>
