<?php
/**
 * Admin View: Statistics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>


<div class="wrap about-wrap">

<h1><?php printf( esc_html__( 'Welcome to Ajax Live Search Lite', 'als' ), '' ); ?></h1>
	<div class="about-text"><?php printf( __( 'Thank you for chosing Ajax Live Search. If you encounter trouble using it, you can always check out our <a href="https://ajaxlivesearch.xyz/getting-started?utm_source=plugin-welcome">documentation</a>. We also have a <a href="https://ajaxlivesearch.xyz/download?utm_source=plugin-welcome&utm_content=first#pro">pro version</a> with extra functionality. <br /> Sorry if it took too long to create the index. From now on everything will go on smoothly.', 'als' ) ); ?></div>
	<h2>You can always use the following parameters when searching!</h2>
	<ol>
		<li><?php _e('(+) A leading plus sign indicates that this word must be present.', 'als')?></li>
		<li><?php _e('(-) A leading minus sign indicates that this word must not be present in any of the return rows.', 'als')?></li>
		<li><?php _e('(>) Indicates that this word should be given more weight.', 'als')?></li>
		<li><?php _e('(~) Indicates that this words weight should be negated but not removed. See 2 above.', 'als')?></li>
		<li><?php _e('(*) When you append an asterisk to a word; we match all words beginning with the value before the asterisk.', 'als')?></li>
		<li><?php _e('("") Returns results that contain the phrase in the parenthesis and in the same order.', 'als')?></li>
		
		<li><?php _e('(author_in:) Comma separated Ids of authors whose posts should be searched.', 'als')?></li>
		<li><?php _e('(author: or @) The username of an author whose posts should be searched.', 'als')?></li>
		<li><?php _e('(author_not_in:) Comma separated Ids of authors whose posts should be excluded.', 'als')?></li>
		<li><?php _e('(cat: or category_in) Comma separated list ids of categories to search ', 'als')?></li>
		<li><?php _e('(category:) Comma, "-" or "+" separated list of category slugs to search in. Use comma to get posts from any of the categories. Use "+" to fetch posts that appear in all categories. Use "-" to exclude posts from the given category.', 'als')?></li>
		<li><?php _e('(tagged:) Comma or "+" separated list of tag slugs to search in. Use comma to get posts with any of those tags. Use "+" to fetch posts that have all those tags.', 'als')?></li>
		<li><?php _e('(post_types:) comma separated list of post types to search. The post types given should be the same as those stated in your settings page as searchable.', 'als')?></li>
		<li><?php _e('(before:) An english date format to use, e.g, last week, 2 months ago, January 1st, 2013 etc. Enclose in quotes. Checkout a list of all availble <a href="http://php.net/manual/en/datetime.formats.php">formats here</a>. This query returns posts published before the given date', 'als')?></li>
		<li><?php _e('(after:) An english date format to use, e.g, last week, 2 months ago, January 1st, 2013 etc. Enclose in quotes. Checkout a list of all availble <a href="http://php.net/manual/en/datetime.formats.php">formats here</a>. This query returns posts published after the given date', 'als')?></li>

	</ol>
	
	<p> <?php _e('Here is an example. <code>+apple -juice was founded by "steve jobs"</code>', 'als')?></p>
	<p> <?php _e('All the returned results will contain apple, but none of them will contain -juice. They will also contain the phrase "Steve jobs" in that order. We don\'t want to fetch articles that match "steve austin" just because he got the same first name as the great steve jobs. If any steve is found, it has to be followed by Jobs.', 'als')?><p>
	<p> <?php _e('This is just a small demo of what aLs has to offer. So make sure to checkout our <a href="https://ajaxlivesearch.xyz/getting-started?utm_source=plugin-welcome&utm_content=bottom">documentation</a> for more info.', 'als')?></p>
	<p> <?php _e('I was serious about wanting to chat to you. So make sure you email me at picocodes@gmail.com, even if it\'s just saying Hi!', 'als')?></p>
</div>
