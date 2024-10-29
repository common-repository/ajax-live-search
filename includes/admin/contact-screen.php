

<?php
/**
 * Admin View: Statistics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$content = '';
$id = "textarea";
if(isset($_POST['textarea'])){
	
	$content = $_POST['textarea'];
	if(als_mail($_POST['textarea'])){
	echo "<div class='updated'>
	Thank you for your message.
	</div>";
} else{
	
	echo "<div class='updated error'>
	Could not send your message. Please try again.
	</div>";
	
}
} else {
	
	
	

?>


<div class="wrap about-wrap">

<h1><?php printf( esc_html__( 'Get in touch', 'als' ), '' ); ?></h1>
	<div class="about-text"><?php printf( __( 'Use the form below to send us an email, whether you need help, you want to chat or you got a feature request / issue.', 'als' ) ); ?></div>

	
<form method="post" action="">
<?php wp_editor($content, $id, array('media_buttons'=>false)); ?>
<input type="submit" value="Send" name="send" class="button button-primary">
</form><?php }?>
</div>
