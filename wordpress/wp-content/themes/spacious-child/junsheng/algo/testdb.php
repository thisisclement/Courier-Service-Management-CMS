<?php

$data = getresults('09/08/2014');


echo "<pre>";
print_r( $data[0] );
echo "</pre>";

$d = json_decode(json_encode((object)$data));

echo "<pre>";
print_r( $d->{0} );
echo "</pre>";

//for database:
//1. the data returns as array, but my actual code from json file is stdClass:
//		can either change my actual file using json_decode(data,TRUE) to convert to array
//		OR
//		convert after retrieving from database e.g. $d = json_decode(json_encode((object)$data));
//2. storing to database:
//		either use nested fields:
//			have to traverse the data returned and change the nested fields' data into stdClass object
//			using json_encode because it is string by default
//		OR
//		not using nested fields:
//			do not have many complications (just that there are alot of columns in the database)




function getresults( $date )
{
    global $wpdb;

    //reason for doing this is to exclude primary key and date field, if want to include all just but *
    $exuniquek = 'tid, name, tel, pickupContactName, pickupContactNo, deliveryContactName, deliveryContactNo, 
    email, postalcode, latlng, lat, lng, address, zone, floorNo, unitNo, pickupDate, pickupTime, deliveryDate, deliveryTime, type';

    $table_name = $wpdb->prefix . 'jsct';

    $results = $wpdb->get_results( $wpdb->prepare('SELECT ' .$exuniquek.' FROM '.$table_name.' WHERE date = %s', $date));

    return $results;
}

?>