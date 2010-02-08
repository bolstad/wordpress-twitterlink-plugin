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


$cb_comment_links = get_option('qtl_comment_link',1);
$cb_content_links = get_option('qtl_content_link',1);	

$cb_edit_override_nofollow = 1;
$cb_nofollow_template = 'rel="nofollow" ';
$cb_blank_template = 'target="blank"';
$cb_target_blank = 1;

$hidden_field_name = 'mt_submit_hidden';

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


add_action('admin_menu', 'quicktwitterlink_plugin_menu');

function quicktwitterlink_plugin_menu() {
  add_options_page('Options for Quick Twitter Link', 'Quick Twitter Link', 'manage_options', 'quicktwitterlink-yadayada', 'quicktwitterlink_options');
}

function checkbox($id, $label) 
{
                        $is_checked = get_option($id,'on');             
			if ($is_checked == 'on')  { $checkboxcode = 'checked'; } 
                        return '<input type="checkbox" id="'.$id.'" name="'.$id.'"'.$checkboxcode.'/>  
                         <label for="'.$id.'">'.$label.'</label><br/>';
}

function quicktwitterlink_options() {

    // variables for the field and option names 
    $opt_name = 'mt_favorite_food';
    global $hidden_field_name;
    $data_field_name = 'mt_favorite_food';
			
    $qtl_fields = array(
			'qtl_content_link','qtl_content_link_nofollow','qtl_content_link_blank',	
			'qtl_comment_link','qtl_comment_link_nofollow','qtl_comment_link_blank',
			'qtl_content_hashtag','qtl_content_hashtag_nofollow','qtl_content_hashtag_blank',	
			'qtl_comment_hashtag','qtl_comment_hashtag_nofollow','qtl_comment_hashtag_blank'
	);

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'

	
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );

        // Put an options updated message on the screen

	foreach ($qtl_fields as $field)
		{
			print "doing $field\n<br>";
		        $opt_val = $_POST[ $field ];
		        update_option( $field, $opt_val );
			print " $field: $opt_val <br>";
		}
?>
<div class="updated"><p><strong><?php _e('Options saved.', 'quick-twitter-link-lang' ); ?></strong></p></div>
<?php

    }

    // Now display the options editing screen

    echo '<div class="wrap">';
    echo '<h2>'. __('Options for Quick Twitter Link','quick-twitter-link-lang').'</h2><br>';
    _e('Options and settings goes here','quick-twitter-link-lang');
  
    // header


    // options form
    
    ?>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<h3><?php _e("Username linking",'quick-twitter-link-lang'); ?></h3>
					
<?php echo checkbox("qtl_content_link", __('Link @username in posts and pages (the_content)','quick-twitter-link-lang')); ?>
<?php echo checkbox("qtl_content_link_nofollow", __('Use nofollow on @username links in posts and pages','quick-twitter-link-lang')); ?>
<?php echo checkbox("qtl_content_link_blank", __('In posts and pages, open @username links a new window using target="_blank"','quick-twitter-link-lang')); ?>
<br>
<?php echo checkbox("qtl_comment_link", __('Link username in comments','quick-twitter-link-lang')); ?>
<?php echo checkbox("qtl_comment_link_nofollow", __('Use nofollow on username links in comments','quick-twitter-link-lang')); ?>
<?php echo checkbox("qtl_comment_link_blank", __('In comments, open @username links in a new window using target="_blank"','quick-twitter-link-lang')); ?>
													

<h3><?php _e("#Hashtag linking",'quick-twitter-link-lang'); ?></h3>
					
<?php echo checkbox("qtl_content_hashtag", __('Link #hashtag in posts and pages (the_content)','quick-twitter-link-lang')); ?>
<?php echo checkbox("qtl_content_hashtag_nofollow", __('Use nofollow on #hashtag links in posts and pages','quick-twitter-link-lang')); ?>
<?php echo checkbox("qtl_content_hashtag_blank", __('In posts and pages, open #hashtag links a new window using target="_blank"','quick-twitter-link-lang')); ?>
<br>
<?php echo checkbox("qtl_comment_hashtag", __('Link #hashtag in comments','quick-twitter-link-lang')); ?>
<?php echo checkbox("qtl_comment_hashtag_nofollow", __('Use nofollow on #hashtag links in comments','quick-twitter-link-lang')); ?>
<?php echo checkbox("qtl_comment_hashtag_blank", __('In comments, open #hashtag links in a new window using target="_blank"','quick-twitter-link-lang')); ?>

</p><hr />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'quick-twitter-link-lang' ) ?>" />
</p>

</form>
</div>

<?php
}

?>
