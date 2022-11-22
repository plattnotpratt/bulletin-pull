<?php
/*
Plugin Name:  Bulletin Pull
Description:  Displays all bulletins posted
Version:      1.1
Author:       Chris Platt
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  https://cplattdesign.com
Domain Path:  /Bulletin Pull
*/

function get_bulletin_init()
{
	function display_bulletin_shortcode($atts = [], $content = null){
		$parish_id = "0347";
		//EMPTY VARIABLE FOR RETURN
		$value = "";

		//CHECK COUNT FOR LENGTH
		$count = 3;
		$bu_atts = shortcode_atts(["count" => 3], $atts, $tag);
		
		if($bu_atts['count'] == -1 || $bu_atts['count'] >= 20){
			$count = 30;
		}else{
			$count = $bu_atts['count'];
		}
		
		$current_day = date('l');
        $prefix = "";
        if( $current_day == "Wednesday" || $current_day == "Thursday" || $current_day == "Friday" || $current_day == "Saturday"){
            $prefix = "next";
        }
        else if($current_day == "Monday" || $current_day == "Tuesday"){
            $prefix = "last";
        }
        else{
            $prefix = "last";
        } 
        

		$date_string = array();
		array_push($date_string, array(), array());
		for($i = 0; $i < $count; $i++){
			if($i === 0){
				array_push($date_string[0], date('Ymd',strtotime($prefix .' sunday')));
				array_push($date_string[1], date('l jS F',strtotime($prefix. ' sunday')));
            }
			else{
				array_push($date_string[0], date('Ymd', strtotime($prefix . ' sunday - '.$i.' weeks')));
				array_push($date_string[1], date('l jS F',strtotime($prefix .' sunday - '.$i.' weeks')));
			}
			
		} 
		$value = '<div class="fusion-posts-container fusion-posts-container-no fusion-no-meta-info fusion-blog-layout-grid fusion-blog-layout-grid-1 isotope" data-pages="1" data-grid-col-space="40">';
		for($i = 0; $i < $count; $i++){
			$date_string_url = $date_string[0][$i];
			$date_string_info = $date_string[1][$i];
			/* BUILD THE BULLETIN CARDS */
			$value .= display_bulletin(array(
				'date_url' => $date_string_url, 
				'date_string' => $date_string_info, 
				'parish_id' => $parish_id,
			));
		} 
		$value .= '</div>';
		return $value;
	}
	add_shortcode('bulletin', 'display_bulletin_shortcode');
}
add_action('init', 'get_bulletin_init');

function display_bulletin($args){
  $date_string_url = $args['date_url'];
  $date_string_info = $args['date_string'];
  $parish_id = $args['parish_id'];
	$value = '<article class="fusion-post-grid post-1 post type-post status-publish format-standard hentry category-parish-news" style="width: 528px;margin-bottom: 20px;">
		<div class="fusion-post-wrapper">
			<div class="fusion-post-content-wrapper">
				<div class="fusion-post-content post-content">';
	$value .= "<img style='float: left; margin: 0 20px 30px 0;' src='https://container.parishesonline.com/bulletins/05/${parish_id}/tn_${date_string_url}B.jpg' alt=''>";
	$value .= '<h2 class="blog-shortcode-post-title entry-title" data-fontsize="18" data-lineheight="27">';
	$value .= "<a href='https://container.parishesonline.com/bulletins/05/${parish_id}/${date_string_url}B.pdf'>${date_string_info}</a>";
	$value .= '</h2>
				<div class="fusion-post-content-container">';
					
	$value .= '</div>
				</div>
			</div>
			<div class="fusion-clearfix"></div>
		</div>
	</article>';
	return $value;
}