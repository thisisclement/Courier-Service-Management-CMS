<?php
//http://leveluptuts.com/tutorials
//https://wordpress.org/plugins/global-content-blocks/
//http://codex.wordpress.org/Shortcode_API





function testfunction(){

	//css
	wp_enqueue_style(
	'datepicker-css',
	get_stylesheet_directory_uri().'/eternicode-bootstrap-datepicker/css/datepicker3.css');

	wp_enqueue_style(
	'validator-css',
	get_stylesheet_directory_uri().'/bootstrapvalidator-dist-0.4.5/dist/css/bootstrapValidatorDev.min.css');

	wp_enqueue_style(
	'bootstrap-css',
	get_stylesheet_directory_uri().'/bootstrap-dist-3.1.1/css/bootstrap.min.css');


	//js scripts
	wp_enqueue_script(
	'datepicker-js',
	get_stylesheet_directory_uri().'/eternicode-bootstrap-datepicker/js/bootstrap-datepicker.js', array( 'jquery' ));

	wp_enqueue_script(
	'validator-js',
	// get_stylesheet_directory_uri().'/bootstrapvalidator-dist-0.4.5/dist/js/bootstrapValidator.js', array( 'jquery' ));
	get_stylesheet_directory_uri().'/bootstrapvalidator-dist-0.5.0/dist/js/bootstrapValidator.js', array( 'jquery' ));
	
	wp_enqueue_script(
	'bootstrap-js',
	get_stylesheet_directory_uri().'/bootstrap-dist-3.1.1/js/bootstrap.min.js', array( 'jquery' ));

	wp_enqueue_script(
	'googlemaps-js',
	'https://maps.googleapis.com/maps/api/js?key=AIzaSyA3kpV4iGNiJkHc5xsEsEa6bXq6tmUh9JU&libraries=geometry&sensor=false', array( 'jquery' ));

	if(is_page('4') || $post->post_parent == '4'){
		wp_enqueue_script(
		'test-js',
		get_stylesheet_directory_uri().'/test.js', array( 'jquery', 'datepicker-js', 'validator-js', 'bootstrap-js', 'googlemaps-js'));
	}
	
}

// add_filter( 'wp_mail_from', 'my_mail_from' );
// function my_mail_from( $email )
// {
//     return "clem0007@e.ntu.edu.sg";
// }

// add_filter( 'wp_mail_from_name', 'my_mail_from_name' );
// function my_mail_from_name( $name )
// {
//     return "Courier";
// }

// add_filter( 'wp_mail_content_type', 'set_html_content_type');


// function set_html_content_type() {
//     return 'text/html';
// }


add_action( 'plugins_loaded', 'renderHTML' );
add_action( 'wp_enqueue_scripts', 'testfunction' );

