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
	<div class="about-text"><?php printf( __( 'View <a href="https://ajaxlivesearch.xyz/getting-started?utm_source=plugin-admin&utm_content=first">documentation</a>. ', 'als' ) ); ?></div>
	<h2>You just gave your blog Google-like superpowers!</h2>
	<div class="about-text"> Please read up to the end.</div>
	<h3> Intro </h3>
	<p> Ajax Live Search was born to solve the problems people faced with other search plugins. We dissected and then reconstructed around 35 searching software, only keeping the best parts. </p>
	<p> We then went through hundreds of search related academic papers, focusing on the ones that talked about machine learning, which happens to be the future of everything.</p>
	<p> Using that knowledge, we built Ajax Live Search.</p>
	
	<h3> How it compares to other search systems </h3>

	<h4> The WordPress Default Search (10% relevancy | Average Speed) </h4>
	<p> If you ask WordPress to search pages containing the phrase "I like eating raw tomatoes"; It will go from page one to the last page, reading each individual word and checking if it contains either of the words. i.e, It will fetch every article containing either the word "i", "like", "eating" or tomatoes. </p>
	<p> It will then output the results in the order in which they were found. This is slow and returns lot's of irrelevant results. Almost all of the pages will match because they contain the phrase "I", but may lack the more meaningful phrase Tomatoes.</p>

	<h3> Other search plugins (60% relevancy | Slow)</h3>
	<p> Most search plugins will take the phrase then strip out "I" and "like" which are stop words. This leaves them with "eating raw tomatoes". They then fetch the results using the WordPress way and calculate the relevancy of a post by counting how many times the keyword appears in a given post. </p>
	
	<h3> Ajax Live Search (90% relevancy | Very Fast)</h3>
	<p> We start by indexing your published posts the same way Google indexes websites. That way, we don't have to check through all posts whenever searching for a given term.</p>
	<p> When searching, we use a technique known as stemming to convert words into their root form. That way, when a user searches for eating, we also return results containing eat, eaten etc.</p>
	<p> We  then compare this to our previous searches log table then realise that people who search for phrases containing the word raw tend to exclude the word "-cooked". We also notice that they tend to include the word "onion" in the same query. </p>
	<p> With this data; we reconstruct our query to look like this "-cook raw eat* tomato onion".</p>
	<p> For each word, we use our index to get all pages that contain it, taking note of some factors such as how many times it appears in a given post and how old the given post is.</p>

<p>This data is used to calculate the relevancy of each page. And since we use an index, relevant results are fetched at a fraction of the time taken by WordPress.</p>

	<p>For users of the pro version, we use linear regression (machine learning) to calculate the relevancy. This is x10 faster than the technique used by the lite version. </p>

	<p> We tested several plugins on a database containing 1 million wikipedia articles running on an old computer with 512 mb ram. The pro version took an average of 135 milliseconds to search while the lite version took an average of 1.5 seconds. Most plugins were taking up to 2 days to index the data and 30 mins to fetch results.</p>
	<p>The pro version caches previous results and uses machine learning making it the fastest search plugin on the market.</p>

<p>I intended to include all features with the free version, but decided to release a pro version and instead use the proceeds from the pro version to support a local charity run by my mom.</p>

<p>Don't worry, I reduced the price to ensure that everyone can afford it.</p>

<p>So please consider <a href="https://ajaxlivesearch.xyz/download?utm_source=plugin-admin&utm_content=first#pro">upgrading to the pro version</a>. It costs a cup of coffee, and in doing so you will help send needy children to school and also get the following benefits:</p>
	<ol>
		<li> Earn money by displaying sponsored results at the top of regular search results.</li>
		<li> Download past searches and import them into your favorite keyword research tool.</li>
		<li> Keep your visitors happy by speeding search results up to x10.</li>
		<li> Use basic machine learning to show more relevant results to your users. </li>
		<li> Fetch more relevant search autocompletes from Google or YouTube and reduce the load on your server. </li>
	</ol>
	
<div class="about-text">Would you rather drink coffee today or send poor kids to school tomorrow?</div>
	<a href="https://ajaxlivesearch.xyz/download?utm_source=plugin-admin&utm_content=bottom#pro" class="button button-primary">Upgrade to premium</a>
</div>
