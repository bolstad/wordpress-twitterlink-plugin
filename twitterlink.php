<?php
/*
Plugin Name: TwitterLink
Plugin URI: http://karamell.net/twitterlink/
Description: Create a href-link to twitter @usernames mentioned in posts.
Version: The 1.03
Author: Christian Bolstad
Author URI: http://christianbolstad.se

 This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

function cb_twitterlink($content)
{
 $pattern = '/(^|[^a-z0-9_])@([a-z0-9_]+)/i';
 $replacement = '$1<a href="http://twitter.com/$2">@$2</a>';
 return preg_replace($pattern, $replacement, $content);
}

add_filter( "the_content", "cb_twitterlink" );


?>
