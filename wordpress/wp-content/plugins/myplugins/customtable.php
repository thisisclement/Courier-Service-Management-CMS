<?php
/**
 * Plugin Name: JS Custom Table Plugin
 * Plugin URI: http://localhost/wordpress/
 * Description: Create and drop custom table from wordpress database
 * Version: 1.0
 * Author: JS
 * Author URI: http://localhost/wordpress/
 * License: open source and non-commercial license
 */


global $jsct_db_version;
$jsct_db_version = '1.0';

//insert table upon activation
function jscreatecustomtable()
{

	global $wpdb;
	global $jsct_db_version;

	$table_name = $wpdb->prefix . 'jsct';
	
	if($wpdb->get_var('SHOW TABLES LIKE ' . $table_name) != $table_name)
	{
		/*
		 * We'll set the default character set and collation for this table.
		 * If we don't do this, some characters could end up being converted 
		 * to just ?'s when saved in our table.
		 */
		$charset_collate = '';

		if ( ! empty( $wpdb->charset ) ) {
		  $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		}

		if ( ! empty( $wpdb->collate ) ) {
		  $charset_collate .= " COLLATE {$wpdb->collate}";
		}



		$sql = "CREATE TABLE $table_name (
			id int(4) UNSIGNED NOT NULL AUTO_INCREMENT,
			tid VARCHAR(255) DEFAULT '' NOT NULL,
			name VARCHAR(255) DEFAULT '' NOT NULL,
			tel VARCHAR(255) DEFAULT '' NOT NULL,
			pickupContactName VARCHAR(255) DEFAULT '' NOT NULL,
			pickupContactNo VARCHAR(255) DEFAULT '' NOT NULL,
			deliveryContactName VARCHAR(255) DEFAULT '' NOT NULL,
			deliveryContactNo VARCHAR(255) DEFAULT '' NOT NULL,
			email VARCHAR(255) DEFAULT '' NOT NULL,
			postalcode VARCHAR(255) DEFAULT '' NOT NULL,
			latlng VARCHAR(255) DEFAULT '' NOT NULL,
			lat VARCHAR(255) DEFAULT '' NOT NULL,
			lng VARCHAR(255) DEFAULT '' NOT NULL,
			address VARCHAR(255) DEFAULT '' NOT NULL,
			zone VARCHAR(255) DEFAULT '' NOT NULL,
			floorNo VARCHAR(255) DEFAULT '' NOT NULL,
			unitNo VARCHAR(255) DEFAULT '' NOT NULL,
			pickupDate VARCHAR(255) DEFAULT '' NOT NULL,
			pickupTime VARCHAR(255) DEFAULT '' NOT NULL,
			deliveryDate VARCHAR(255) DEFAULT '' NOT NULL,
			deliveryTime VARCHAR(255) DEFAULT '' NOT NULL,
			type VARCHAR(255) DEFAULT '' NOT NULL,
			date VARCHAR(255) DEFAULT '' NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'jsct_db_version', $jsct_db_version );

	}
}

register_activation_hook( __FILE__, 'jscreatecustomtable' );


function jsinsertdummydata() {
	global $wpdb;
	
	$obj = new stdClass();
	$obj->pickup = new stdClass();
	$obj->delivery = new stdClass();
	$obj->pickup->lat = "1.429749";
	$obj->pickup->lng = "103.775955";
	$obj->delivery->lat = "1.391988";
	$obj->delivery->lng = "103.898586";
	$latlng = json_encode($obj);

	$lat = new stdClass();
	$lat->pickup = $obj->pickup->lat = "1.429749";
	$lat->delivery = $obj->delivery->lat = "1.391988";
	$lat = json_encode($lat);

	$lng = new stdClass();
	$lng->pickup = $obj->pickup->lng = "103.775955";
	$lng->delivery = $obj->delivery->lng = "103.898586";
	$lng = json_encode($lng);

	$addr = new stdClass();
	$addr->pickup = "309 Woodlands Avenue 1";
	$addr->delivery = "248 Compassvale Road";
	$addr = json_encode($addr);


	$zone = new stdClass();
	$zone->pickup = "North";
	$zone->delivery = "North-East";
	$zone = json_encode($zone);

	$floor = new stdClass();
	$floor->pickup = "25";
	$floor->delivery = "49";
	$floor = json_encode($floor);

	$unit = new stdClass();
	$unit->pickup = "74";
	$unit->delivery = "87";
	$unit = json_encode($unit);

	
	$table_name = $wpdb->prefix . 'jsct';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'tid' => 'T1', 
			'name' => 'Name1', 
			'tel' => '98723694', 
			'pickupContactName' => 'pickupContactName0', 
			'pickupContactNo' => '93869417', 
			'deliveryContactName' => 'deliveryName0', 
			'deliveryContactNo' => '95216473', 
			'email' => 'a0@gmail.com', 
			'postalcode' => '{
                    "pickup": "730309",
                    "delivery": "540248"
                }', 
			'latlng' => $latlng, 
			'lat' => $lat, 
			'lng' => $lng, 
			'address' => $addr, 
			'zone' => $zone, 
			'floorNo' => $floor, 
			'unitNo' => $unit, 
			'pickupDate' => '09/08/2014', 
			'pickupTime' => '12:00:00', 
			'deliveryDate' => '10/08/2014', 
			'deliveryTime' => '12:00:00', 
			'type' => 'pickup',
			'date' => '09/08/2014'
		) 
	);
}



register_activation_hook( __FILE__, 'jsinsertdummydata' );



function jsdropcustomtable()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "jsct";
	$sql = "DROP TABLE IF EXISTS $table_name;";
	$wpdb->query($sql);
	delete_option("jsct_db_version");

}
//drop table upon deactivation
register_deactivation_hook( __FILE__, 'jsdropcustomtable' );




?>