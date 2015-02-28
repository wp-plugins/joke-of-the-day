<?php
/*
Plugin Name: Joke of the Day
Plugin URI: http://www.premiumresponsive.com/wordpress-plugins/
Description: Plugin "Joke of the Day" displays categorized jokes on your blog. There are over 40,000 jokes in 40 categories. Jokes are saved on our database.
Version: 3.0
Author: A.Kilius
Author URI: http://www.premiumresponsive.com/wordpress-plugins/
*/

define(joke_day_URL_RSS_DEFAULT, 'http://www.jokerhub.com/category/jokes/feed/');
define(joke_day_TITLE, 'Joke of the Day');
define(joke_day_MAX_SHOWN_ITEMS, 3);

 add_action('admin_menu', 'joke_day_menu');
function joke_day_menu() {
add_menu_page('Joke of the Day', 'Joke of the Day', 8, __FILE__, 'joke_day_options');
}

function joke_day_widget_ShowRss($args)
{
$options = get_option('joke_day_widget');
 	if( $options == false ) {
		$options[ 'joke_day_widget_url_title' ] = joke_day_TITLE;
		$options[ 'joke_day_widget_RSS_count_items' ] = joke_day_MAX_SHOWN_ITEMS;                              
	}

 $feed = joke_day_URL_RSS_DEFAULT;
	$title = $options[ 'joke_day_widget_url_title' ];
 $rss = fetch_feed( $feed );
		if ( !is_wp_error( $rss ) ) :
			$maxitems = $rss->get_item_quantity($options['joke_day_widget_RSS_count_items'] );
			$items = $rss->get_items( 0, $maxitems );
				endif;
	 $output .= '<!-- WP plugin   joke of the Day --> <ul>';	
	if($items) { 
 			foreach ( $items as $item ) :
				// Create post object                                                           
  $titlee = trim($item->get_description()); 
  $output .= '<li> <a href="';
 $output .=  $item->get_permalink();
  $output .= '"  title="'.$titlee.'" target="_blank">';
   $output .= $titlee.'</a> ';
 	 $output .= '</li>'; 
   		endforeach;		
	}
			$output .= '</ul> ';	 			
	extract($args);	
  echo $before_widget;  
  echo $before_title . $title . $after_title;  
 echo $output;  
 echo $after_widget;      
 }

 function joke_day_widget_Admin()
{
	$options = $newoptions = get_option('joke_day_widget');	
	//default settings
	if( $options == false ) {
		$newoptions[ 'joke_day_widget_url_title' ] = joke_day_TITLE;
		$newoptions['joke_day_widget_RSS_count_items'] = joke_day_MAX_SHOWN_ITEMS;		
	}
	if ( $_POST["joke_day_widget_RSS_count_items"] ) {
		$newoptions['joke_day_widget_url_title'] = strip_tags(stripslashes($_POST["joke_day_widget_url_title"]));
		$newoptions['joke_day_widget_RSS_count_items'] = strip_tags(stripslashes($_POST["joke_day_widget_RSS_count_items"]));
	}	
		
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('joke_day_widget', $options);		
	}
	$joke_day_widget_url_title = wp_specialchars($options['joke_day_widget_url_title']);
	$joke_day_widget_RSS_count_items = $options['joke_day_widget_RSS_count_items'];
	
	?> 
	<p><label for="joke_day_widget_url_title"><?php _e('Title:'); ?> <input style="width: 350px;" id="joke_day_widget_url_title" name="joke_day_widget_url_title" type="text" value="<?php echo $joke_day_widget_url_title; ?>" /></label></p>
 
	<p><label for="joke_day_widget_RSS_count_items"><?php _e('Count Items To Show:'); ?> <input  id="joke_day_widget_RSS_count_items" name="joke_day_widget_RSS_count_items" size="2" maxlength="2" type="text" value="<?php echo $joke_day_widget_RSS_count_items?>" /></label></p>	
 
	<?php
}

add_filter("plugin_action_links", 'joke_day_ActionLink', 10, 2);
function joke_day_ActionLink( $links, $file ) {
	    static $this_plugin;		
		if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__); 
        if ( $file == $this_plugin ) {
			$settings_link = "<a href='".admin_url( "options-general.php?page=".$this_plugin )."'>". __('Settings') ."</a>";
			array_unshift( $links, $settings_link );
		}
		return $links;
	}

function joke_day_options() {	
	?> 	<div class="wrap">
		<h2>Joke of the Day</h2>
<p><b>Plugin "Joke of the Day" displays categorized jokes on your blog. There are over 40,000 jokes in 40 categories. Jokes are saved on our database, so you don't need to have space for all that information. </b> </p>
<p> <h3>Add the widget "Joke of the Day"  to your sidebar from <a href="<? echo "./widgets.php";?>"> Appearance->Widgets</a>  and configure the widget options.</h3>
<h3>More <a href="http://www.premiumresponsive.com/wordpress-plugins/" target="_blank"> WordPress Plugins</a></h3>
</p>
 	</div>
	<?php
}

function joke_day_widget_Init()
{
  register_sidebar_widget(__('Joke of the Day'), 'joke_day_widget_ShowRss');
  register_widget_control(__('Joke of the Day'), 'joke_day_widget_Admin', 500, 250);
}
add_action("plugins_loaded", "joke_day_widget_Init");
?>