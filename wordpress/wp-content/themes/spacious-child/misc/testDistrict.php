<?php

    if ( !defined('ABSPATH') ) {
        require_once "../../../../wp-load.php";
        require_once("../../../../wp-blog-header.php");

    }

    //require_once($_SERVER['DOCUMENT_ROOT'].'/courier/clem/wordpress/wp-blog-header.php');
    // My modifications to mailer script from:
    // http://blog.teamtreehouse.com/create-ajax-contact-form
    // Added input sanitizing to prevent injection

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $firstName = strip_tags(trim($_POST["firstName"]));
                                $firstName = str_replace(array("\r","\n"),array(" "," "),$firstName);
        $lastName = strip_tags(trim($_POST["lastName"]));
                $lastName = str_replace(array("\r","\n"),array(" "," "),$lastName);
        $name = $firstName." ".$lastName;
        $telNo = strip_tags(trim($_POST["telNo"]));
                $telNo = str_replace(array("\r","\n"),array(" "," "),$telNo);
        $pickupContactCheck = $_POST["pickupContactCheck"];
        $pickupContactName = strip_tags(trim($_POST["pickupContactName"]));
                $pickupContactName = str_replace(array("\r","\n"),array(" "," "),$pickupContactName);
        $pickupContactNo = strip_tags(trim($_POST["pickupContactNo"]));
                $pickupContactNo = str_replace(array("\r","\n"),array(" "," "),$pickupContactNo);
        $email = filter_var(trim($_POST["emailAdd"]), FILTER_SANITIZE_EMAIL);
        $postalCode = strip_tags(trim($_POST["postalCode"]));
                $postalCode = str_replace(array("\r","\n"),array(" "," "),$postalCode);
        $latlng = strip_tags(trim($_POST["latlng"]));
        $latlngPieces = explode(',', $latlng);
        $lat = $latlngPieces[0];
        $lng = $latlngPieces[1];
        // $lat = strip_tags(trim($_POST["lat"]));
        // $lng = strip_tags(trim($_POST["lng"]));
        $postalAdd = strip_tags(trim($_POST["postalAdd"]));
                $postalAdd = str_replace(array("\r","\n"),array(" "," "),$postalAdd);
        $floorNo = strip_tags(trim($_POST["floorNo"]));
                $floorNo = str_replace(array("\r","\n"),array(" "," "),$floorNo);
        $unitNo = strip_tags(trim($_POST["unitNo"]));
                $unitNo = str_replace(array("\r","\n"),array(" "," "),$unitNo);
        $pickupDate = $_POST["pickupDate"];
        $pickupTime = $_POST["pickupTime"];

        $delContactName = $_POST["delContactName"];
        $delContactNo = $_POST["delContactNo"];
        $delPostalCode = $_POST["delPostalCode"];
        $delPostalAdd = $_POST["delPostalAdd"];
        $delLatlng = $_POST["delLatlng"];
        $delLatlngPieces = explode(',', $delLatlng);
        $delLat = $delLatlngPieces[0];
        $delLng = $delLatlngPieces[1];
        // $delLat = $_POST["delLat"];
        // $delLng = $_POST["delLng"];
        $delFloorNo = $_POST["delFloorNo"];
        $delUnitNo = $_POST["delUnitNo"];
        $deliveryDate = $_POST["deliveryDate"];
        $deliveryTime = $_POST["deliveryTime"];   
$folderpath = get_stylesheet_directory_uri()."/data";
$folderdir = get_stylesheet_directory()."/data";
$districtfile = '/district.json';
$datafile = '/data1.json';
$jobsfile = '/jobsProcess.json';

$twodigits = floor($postalCode/10000); //first two digits of 6 digits
if($twodigits < 10)
    $twodigits = sprintf("%02d", $twodigits);//for single digits, add a zero in front
$intStr = "$twodigits"; //convert int to string
$delTwoDigits = floor($delPostalCode/10000);
if($delTwoDigits < 10)
    $delTwoDigits = sprintf("%02d", $delTwoDigits);//for single digits, add a zero in front
$delIntStr = "$delTwoDigits"; //convert int to string
for ($a = 0; $a < count($district["data"]); $a++){ //pickup postal
            if ($district["data"][$a]["postalcode"] == $intStr){
                if (count($district["data"][$a]["available"]) != 0){
                    $districtAvailCount = count($district["data"][$a]["available"];
                    for ($b = 0; $b < $districtAvailCount); $b++){
                        if ($district["data"][$a]["available"][$b]["postalcode"] == $postalCode){
                            break;
                        }
                        else {
                            $tid = $district["data"][$a]["available"][$districtAvailCount-1]["tid"];
                            $tidPieces = explode("T", $tid);
                            $district["data"][$a]["available"][$districtAvailCount-1]["tid"] = "T".(string)((int)$tidPieces[1] + 1);
                            $district["data"][$a]["available"][$districtAvailCount-1]["postalcode"] = $postalCode;
                            $district["data"][$a]["available"][$districtAvailCount-1]["latlng"] = array("lat"=>$lat, "lng"=>$lng);
                            $district["data"][$a]["available"][$districtAvailCount-1]["lat"] = $lat;
                            $district["data"][$a]["available"][$districtAvailCount-1]["lng"] = $lng;
                            $district["data"][$a]["available"][$districtAvailCount-1]["address"] = $postalAdd;
                            file_put_contents($folderdir.$districtfile, str_replace('\/', '/', json_encode($district, 
                                JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
                        }
                    }//inner for loop              
                }
            }
}

?>