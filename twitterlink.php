<?php
/*
Plugin Name: TwitterLink
Plugin URI: http://karamell.net/twitterlink/
Description: Create a href-link to twitter @usernames mentioned in posts.
Version: The 1.03
Author: Christian Bolstad
Author URI: http://christianbolstad.se
*/

// default settings 

$cb_comment_links = 1;
$cb_content_links = 1;
$cb_edit_override_nofollow = 1;
$cb_nofollow_template = 'rel="nofollow" ';
$cb_blank_template = 'target="blank"';
$cb_target_blank = 1;

function cb_twitify($content,$nofollow = '')
{
 $pattern = '/(^|[^a-z0-9_])@([a-z0-9_]+)/i';
 $blank = '';
 global $cb_blank_template;
 global $cb_target_blank;
 if ($cb_target_blank) $blank = $cb_blank_template; 
 $replacement = '$1<a '.$nofollow.$blank.' href="http://twitter.com/$2">@$2</a>';
 return preg_replace($pattern, $replacement, $content);
}

function cb_twitify_search($content,$nofollow = '')
{
 $pattern = '/(^|[^a-z0-9_])#([a-z0-9_]+)/i';
 $blank = '';
 global $cb_blank_template;
 global $cb_target_blank;
 if ($cb_target_blank) $blank = $cb_blank_template; 
 $replacement = '$1<a '.$nofollow.$blank.' href="http://search.twitter.com/search?q=%23$2">#$2</a>';
 return preg_replace($pattern, $replacement, $content);
}

function cb_twitterlink($content)
{
  return cb_twitify($content);
}

function cb_twitterlink_comment($content)
{
  global $comment;
  global $cb_nofollow_template; 
  global $cb_edit_override_nofollow;
  $nofollow = $cb_nofollow_template;
  if (current_user_can('edit_post', $comment->comment_post_ID) && $cb_edit_override_nofollow) $nofollow = '';
  return cb_twitify($content,$nofollow);
}

$cb_search_links = 1;

if ($cb_content_links) add_filter( "the_content", "cb_twitterlink" );
if ($cb_search_links) add_filter( "the_content","cb_twitify_search");
if ($cb_comment_links) add_filter( "comment_text", "cb_twitterlink_comment" ); 

?>
