<?php
//http://leveluptuts.com/tutorials
//https://wordpress.org/plugins/global-content-blocks/
//http://codex.wordpress.org/Shortcode_API
//remove the auto insertion of <p> and <br> tags
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );


function sharedfunctions(){

	wp_enqueue_script(
	'googlemaps-js',
	'https://maps.googleapis.com/maps/api/js?key=AIzaSyA3kpV4iGNiJkHc5xsEsEa6bXq6tmUh9JU&libraries=geometry&sensor=false', array( 'jquery' ));

	wp_enqueue_style(
	'bootstrap-css',
	get_stylesheet_directory_uri().'/css/bootstrap.min.css');

}

add_action( 'wp_enqueue_scripts', 'sharedfunctions' );


//clement functions
function clemfunctions(){

	if(is_page('4') || $post->post_parent == '4')
	{
		//css
		wp_enqueue_style(
		'datepicker-css',
		get_stylesheet_directory_uri().'/css/datepicker3.css');

		wp_enqueue_style(
		'validator-css',
		get_stylesheet_directory_uri().'/css/bootstrapValidator.min.css');

		//js scripts
		wp_enqueue_script(
		'datepicker-js',
		get_stylesheet_directory_uri().'/js/bootstrap-datepicker.js', array( 'jquery' ));

		wp_enqueue_script(
		'validator-js',
		// get_stylesheet_directory_uri().'/bootstrapvalidator-dist-0.4.5/dist/js/bootstrapValidator.js', array( 'jquery' ));
		get_stylesheet_directory_uri().'/js/bootstrapValidator.min.js', array( 'jquery' ));
		
		wp_enqueue_script(
		'bootstrap-js',
		get_stylesheet_directory_uri().'/js/bootstrap.min.js', array( 'jquery' ));

	
		/*wp_enqueue_style(
		'disable-comments-css',
		get_stylesheet_directory_uri().'/css/disable-comments.css');*/

		wp_enqueue_script(
		'pickupForm-js',
		get_stylesheet_directory_uri().'/js/pickupForm.js', array( 'jquery', 'datepicker-js', 'validator-js', 'bootstrap-js', 'googlemaps-js'));

		wp_localize_script( 'pickupForm-js', 'pickupForm', 
			array( 
			'sendsmsurl' => get_stylesheet_directory_uri().'/misc/sendSMS.php',
			'verifykeyurl' => get_stylesheet_directory_uri().'/misc/verifyKey.php',
			'formprocessurl' => get_stylesheet_directory_uri().'/misc/formProcess.php',
			'jobprocessurl' => get_stylesheet_directory_uri().'/data/jobsProcess.json',
			'districturl' => get_stylesheet_directory_uri().'/data/district.json',
		
			) 
		);

	}
	
	
}

// add_filter( 'wp_mail_from', 'my_mail_from' );
// function my_mail_from( $email )
// {
//     return "clem0007@e.ntu.edu.sg";
// }

add_filter( 'wp_mail_from_name', 'my_mail_from_name' );
function my_mail_from_name( $name )
{
    return "Courier";
}

// add_filter( 'wp_mail_content_type', 'set_html_content_type');


// function set_html_content_type() {
//     return 'text/html';
// }


//add_action( 'plugins_loaded', 'renderHTML' );//why need this?
add_action( 'wp_enqueue_scripts', 'clemfunctions' );




//jun sheng functions
function jsfunctions(){


	if(is_page('31'))
	{

		//js
		wp_enqueue_script(
		'googlecharts-js',
		'https://www.google.com/jsapi', array( 'jquery' ));

		wp_enqueue_script(
		'graphresults-js',
		get_stylesheet_directory_uri().'/js/graphresults.js', array( 'googlecharts-js' ),'',true);//load in footer

		wp_localize_script( 'graphresults-js', 'graphresults', array( 'calavgurl' => get_stylesheet_directory_uri().'/junsheng/graphs/calavgfromfile.php' ) );


	}


	if(is_page('39'))
	{

		//js

		//jquery ui
		wp_deregister_script('jquery-ui-core');

		wp_enqueue_script(
		'jqueryui-js',
		'https://code.jquery.com/ui/1.10.4/jquery-ui.min.js', array( 'jquery' ));

		wp_enqueue_style(
		'jqueryui-css',
		'https://code.jquery.com/ui/1.9.2/themes/flick/jquery-ui.css');

		wp_enqueue_script(
		'sickapp-js',
		get_stylesheet_directory_uri().'/js/sickapp.js', array( 'jqueryui-js' ),'',true);//load in footer

		wp_localize_script( 'sickapp-js', 'sickapp', 
			array( 
				'calendarurl' => get_stylesheet_directory_uri().'/images/icons/calendar.gif',
				'couriersupdatejsurl' => get_stylesheet_directory_uri().'/data/couriersupdatetest.json',
				'couriersupdatephpurl' => get_stylesheet_directory_uri().'/junsheng/sickapp/updatejsoncouriers.php',
				'topermalink' => get_permalink('39')
				
				) 
			);


	}


	if(is_page('44'))
	{

		//js
		wp_enqueue_script(
		'algo-js',
		get_stylesheet_directory_uri().'/js/algo.js', array( 'jquery', 'googlemaps-js' ),'',true);//load in footer

		wp_localize_script( 'algo-js', 'algo', 
			array( 
				'iconsurl' => get_stylesheet_directory_uri().'/images/icons/',
				'readfileurl' => get_stylesheet_directory_uri().'/junsheng/algo/readfile.php'
				) 
			);


	}


}

add_action( 'wp_enqueue_scripts', 'jsfunctions' );



function testajaxfunction()
{

	if(is_page('61'))
	{

		//js
		wp_enqueue_script(
		'algodemo-js',
		get_stylesheet_directory_uri().'/junsheng/algodemo/algodemo.js', array( 'jquery' ),'',true);//load in footer

		wp_localize_script( 'algodemo-js', 'algodemo', 
			array( 
				'iconsurl' => get_stylesheet_directory_uri().'/images/icons/',
				'readfileurl' => get_stylesheet_directory_uri().'/junsheng/algodemo/readfile.php',
				'ajaxurl' => admin_url( 'admin-ajax.php' )
				) 
			);


	}
}

add_action( 'wp_enqueue_scripts', 'testajaxfunction' );//for user pages (front end side) only
//add_action( 'admin_enqueue_scripts', 'testajaxfunction' );//for admin pages (back end side) only

function readfile_callback() {

	include get_stylesheet_directory().'/junsheng/algodemo/readfile.php';
	exit();//die(); //need this if using wordpress style of ajax call in functions.php
}

$suffix = "readfile";//the php filename to read

//naming conventions
add_action( 'wp_ajax_'.$suffix, $suffix.'_callback' );//the for login users
add_action( 'wp_ajax_nopriv_'.$suffix, $suffix.'_callback' );//for non login users


//common functions
function computeEuclideanDistanceBetween($latLngFrom, $latLngTo)//source, destination
{
    // convert from degrees to radians
    $latFrom = deg2rad($latLngFrom->lat);
    $lngFrom = deg2rad($latLngFrom->lng);
    $latTo = deg2rad($latLngTo->lat);
    $lngTo = deg2rad($latLngTo->lng);

    //$latDelta = pow($latTo - $latFrom, 2);
    //$lngDelta = pow($lngTo - $lngFrom, 2);

    $latDelta = ($latTo - $latFrom)*($latTo - $latFrom);
    $lngDelta = ($lngTo - $lngFrom)*($lngTo - $lngFrom);

    //great circle euclidean distance formula:
    $dis = sqrt($latDelta + $lngDelta);

    return $dis;
}



//all our common functions
//filereader function
function jsonfilereader($url, $bool=false)
{
    
    $contents = file_get_contents($url);//if no file_get_contents, use curl
    //$contents = wp_remote_get($url); 
    //$results = json_decode($contents, true); //without true it is stdClass
    $results = json_decode($contents, $bool);

    return $results;
}






