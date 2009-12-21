<?php
/*
Plugin Name: TwitterLink
Plugin URI: http://karamell.net/twitterlink/
Description: Create a href-link to twitter @usernames mentioned in posts.
Version: The 1.03
Author: Christian Bolstad
Author URI: http://christianbolstad.se
*/

function cb_twitterlink($content)
{
 $pattern = '/(^|[^a-z0-9_])@([a-z0-9_]+)/i';
 $replacement = '$1<a href="http://twitter.com/$2">@$2</a>';
 return preg_replace($pattern, $replacement, $content);
}

add_filter( "the_content", "cb_twitterlink" );


?>
