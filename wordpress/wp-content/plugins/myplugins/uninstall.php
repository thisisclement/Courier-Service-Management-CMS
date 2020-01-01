<?php

if(!function_exists('jsdropcustomtable'))
	require_once "customtable.php";

jsuninstall();



function jsuninstall()
{
	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    	exit();
	
	jsdropcustomtable();
}




?>