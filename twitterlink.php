<?php
/*
Plugin Name: TwitterLink
Plugin URI: http://karamell.net/twitterlink/
Description: Create a href-link to twitter @usernames mentioned in posts.
Version: The 1.0
Author: Christian Bolstad
Author URI: http://christianbolstad.se
*/

function cb_twitterlink($content)
{

$pattern = '/@(\w.*)? /U'; 
$replacement = '<a href="http://twitter.com/${1}">@${1}</a> ';
return preg_replace($pattern, $replacement, $content);
}

add_filter( "the_content", "cb_twitterlink" );


?>
