<?php
/*
Plugin Name: Joke of the Day
Plugin URI: http://wordpress.org/extend/plugins/joke-of-the-day/
Description: Plugin "Joke of the Day" displays categorized jokes on your blog. There are over 40,000 jokes in 40 categories. Jokes are saved on our database, so you don't need to have space for all that information. 
Version: 1.1
Author: A.Kilius
Author URI: http://www.onlinerel.com/wp-social-bookmarking/
License: GPL2
*/

define(joke_day_READER_URL_RSS_DEFAULT, 'http://fun.onlinerel.com/category/jokes/feed/rss/');
define(joke_day_READER_TITLE, 'Joke of the Day');
define(joke_day_MAX_SHOWN_ITEMS, 3);




function joke_day_widget_ShowRss($args)
{
	//@ini_set('allow_url_fopen', 1);	
	if( file_exists( ABSPATH . WPINC . '/rss.php') ) {
		require_once(ABSPATH . WPINC . '/rss.php');		
	} else {
		require_once(ABSPATH . WPINC . '/rss-functions.php');
	}
	
	$options = get_option('joke_day_widget');

	if( $options == false ) {
		$options[ 'joke_day_widget_url_title' ] = joke_day_READER_TITLE;
		$options[ 'joke_day_widget_RSS_url' ] = joke_day_READER_URL_RSS_DEFAULT;
		$options[ 'joke_day_widget_RSS_count_items' ] = joke_day_MAX_SHOWN_ITEMS;
	}

 $RSSurl = joke_day_READER_URL_RSS_DEFAULT;
	$messages = fetch_rss($RSSurl);
	$image = plugins_url().'/joke-of-the-day/joke.jpg';
	$title = $options[ 'joke_day_widget_url_title' ];
	//	.'  <img src="'.$image.'" width="14" height="17" alt="Joke" /> ';
	
	$messages_count = count($messages->items);
	if($messages_count != 0){
		$output = '<ul>';	
		//	print_r($messages->items);
	for($i=0; $i<$options['joke_day_widget_RSS_count_items'] && $i<$messages_count; $i++)
		{		
		$output .= '<li>';
		$output .= '<a target ="_blank" href="'.$messages->items[$i]['link'].'">'.$messages->items[$i]['description'].'</a></span>';	
			$output .= '</li>';
	}
		$output .= '</ul> ';
	}
	
	extract($args);	
	?>
	<?php echo $before_widget; ?>
	<?php echo $before_title . $title . $after_title; ?>	
	<?php echo $output; ?>
	<?php echo $after_widget; ?>
	<?php	
}


function joke_day_widget_Admin()
{
	$options = $newoptions = get_option('joke_day_widget');	
	//default settings
	if( $options == false ) {
		$newoptions[ 'joke_day_widget_url_title' ] = joke_day_READER_TITLE;
		$newoptions[ 'joke_day_widget_RSS_url' ] = joke_day_READER_URL_RSS_DEFAULT;
		$newoptions['joke_day_widget_RSS_count_items'] = joke_day_MAX_SHOWN_ITEMS;		
	}
	if ( $_POST["joke_day_widget-submit"] ) {
		$newoptions['joke_day_widget_url_title'] = strip_tags(stripslashes($_POST["joke_day_widget_url_title"]));
		$newoptions['joke_day_widget_RSS_url'] = joke_day_READER_URL_RSS_DEFAULT;
		$newoptions['joke_day_widget_RSS_count_items'] = strip_tags(stripslashes($_POST["joke_day_widget_RSS_count_items"]));
	}	
		
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('joke_day_widget', $options);		
	}
	$joke_day_widget_url_title = wp_specialchars($options['joke_day_widget_url_title']);
	$joke_day_widget_RSS_url = joke_day_READER_URL_RSS_DEFAULT;	
	$joke_day_widget_RSS_count_items = $options['joke_day_widget_RSS_count_items'];
	
	?><form method="post" action="">	

	<p><label for="joke_day_widget_url_title"><?php _e('Title:'); ?> <input style="width: 350px;" id="joke_day_widget_url_title" name="joke_day_widget_url_title" type="text" value="<?php echo $joke_day_widget_url_title; ?>" /></label></p>
 
	<p><label for="joke_day_widget_RSS_count_items"><?php _e('Count Items To Show:'); ?> <input  id="joke_day_widget_RSS_count_items" name="joke_day_widget_RSS_count_items" size="2" maxlength="2" type="text" value="<?php echo $joke_day_widget_RSS_count_items?>" /></label></p>
	
	<br clear='all'></p>
	<input type="hidden" id="joke_day_widget-submit" name="joke_day_widget-submit" value="1" />	
	</form>
	<?php
}

add_action('admin_menu', 'joke_day_menu');

function joke_day_menu() {
	add_options_page('Joke of the Day', 'Joke of the Day', 8, __FILE__, 'joke_day_options');
}

function joke_day_options() {	
	?>
	<div class="wrap">
		<h2>Joke of the Day</h2>
<p><b>Plugin "Joke of the Day" displays categorized jokes on your blog. There are over 40,000 jokes in 40 categories. Jokes are saved on our database, so you don't need to have space for all that information. </b> </p>
<p> <h3>Add the widget "Joke of the Day"  to your sidebar from Appearance->Widgets and configure the widget options.</h3></p>
 <hr /> <hr />
		<h2>Recipe of the Day</h2>
<p><b>Plugin "Recipe of the Day" displays categorized recipes on your blog. There are over 20,000 recipes in 40 categories. Recipes are saved on our database, so you don't need to have space for all that information.</b> </p>
<h3>Get plugin <a target="_blank" href="http://wordpress.org/extend/plugins/recipe-of-the-day/">Recipe of the Day</h3></a>
 <hr /> <hr />
 <h2>WP Social Bookmarking</h2>
<p><b>WP-Social-Bookmarking plugin will add a image below your posts, allowing your visitors to share your posts with their friends, on FaceBook, Twitter, Myspace, Friendfeed, Technorati, del.icio.us, Digg, Google, Yahoo Buzz, StumbleUpon.</b></p>
<p><b>Plugin suport sharing your posts feed on <a href="http://www.onlinerel.com/">OnlineRel</a>. This helps to promote your blog and get more traffic.</b></p>

<p>Advertise your real estate, cars, items... Buy, Sell, Rent. Free promote your site:
<ul>
	<li><a target="_blank" href="http://www.onlinerel.com/">OnlineRel</a></li>
	<li><a target="_blank" href="http://www.easyfreeads.com/">Easy Free Ads</a></li>
	<li><a target="_blank" href="http://www.worldestatesite.com/">World Estate Site</a></li>
	<li><a target="_blank" href="http://www.facebook.com/pages/EasyFreeAds/106166672771355">Promote site on Facebook</a></li>	
</ul>
<h3>Get plugin <a target="_blank" href="http://wordpress.org/extend/plugins/wp-social-bookmarking/">WP Social Bookmarking</h3></a>
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