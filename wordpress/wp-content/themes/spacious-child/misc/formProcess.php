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
        /* Save to JSON */
        //$folderpath = '/home/sce2014/public_html/scet.sg/courier/clem/wordpress/wp-content/themes/spacious-child/data';
        $folderpath = get_stylesheet_directory_uri()."/data";
        $folderdir = get_stylesheet_directory()."/data";
        $districtfile = '/district.json';
        $datafile = '/data1.json';
        $jobsfile = '/jobsProcess.json';

        $district = jsonfilereader($folderpath.$districtfile, true);
        $twodigits = floor($postalCode/10000); //first two digits of 6 digits
        if($twodigits < 10)
            $twodigits = sprintf("%02d", $twodigits);//for single digits, add a zero in front
        $intStr = "$twodigits"; //convert int to string
        $delTwoDigits = floor($delPostalCode/10000);
        if($delTwoDigits < 10)
            $delTwoDigits = sprintf("%02d", $delTwoDigits);//for single digits, add a zero in front
        $delIntStr = "$delTwoDigits"; //convert int to string
        //writing postal details to district file
        for ($a = 0; $a < count($district["data"]); $a++){ //pickup postal
            if ($district["data"][$a]["postalcode"] == $intStr){
                if (count($district["data"][$a]["available"]) != 0){
                    $districtAvailCount = count($district["data"][$a]["available"]);
                    for ($b = 0; $b < $districtAvailCount; $b++){
                        $filePostalCode = $district["data"][$a]["available"][$b]["postalcode"];
                        echo ("file: ".$filePostalCode."\n");
                        echo ("strcmp: ".strcmp($filePostalCode, $postalCode)."\n");
                        if (strcmp($filePostalCode, $postalCode) == 0){
                            echo "inside";
                            break 2;
                        }
                        if ($b == ($districtAvailCount-1)){ 
                            if (strcmp($filePostalCode, $postalCode) != 0) {
                                $tid = $district["data"][$a]["available"][$districtAvailCount-1]["tid"];
                                $tidPieces = explode("T", $tid);
                                $district["data"][$a]["available"][$districtAvailCount]["tid"] = "T".(string)((int)$tidPieces[1] + 1);
                                $district["data"][$a]["available"][$districtAvailCount]["postalcode"] = $postalCode;
                                $district["data"][$a]["available"][$districtAvailCount]["latlng"] = array("lat"=>$lat, "lng"=>$lng);
                                $district["data"][$a]["available"][$districtAvailCount]["lat"] = $lat;
                                $district["data"][$a]["available"][$districtAvailCount]["lng"] = $lng;
                                $district["data"][$a]["available"][$districtAvailCount]["address"] = $postalAdd;
                                file_put_contents($folderdir.$districtfile, str_replace('\/', '/', json_encode($district, 
                                    JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
                            }
                        }

                    }//inner for loop                              
                }

                else {
                    $tid = $district["data"][$a]["available"][0]["tid"];
                    $tidPieces = explode("T", $tid);
                    $district["data"][$a]["available"][0]["tid"] = "T".(string)((int)$tidPieces[1] + 1);
                    $district["data"][$a]["available"][0]["postalcode"] = $postalCode;
                    $district["data"][$a]["available"][0]["latlng"] = array("lat"=>$lat, "lng"=>$lng);
                    $district["data"][$a]["available"][0]["lat"] = $lat;
                    $district["data"][$a]["available"][0]["lng"] = $lng;
                    $district["data"][$a]["available"][0]["address"] = $postalAdd;
                    file_put_contents($folderdir.$districtfile, str_replace('\/', '/', json_encode($district, 
                        JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
                }
            }
        }
        for ($a = 0; $a < count($district["data"]); $a++){ //delivery postal
            if ($district["data"][$a]["postalcode"] == $delIntStr){
                if (count($district["data"][$a]["available"]) != 0){
                    $districtAvailCount = count($district["data"][$a]["available"]);
                    for ($b = 0; $b < $districtAvailCount; $b++){
                        $filePostalCode = $district["data"][$a]["available"][$b]["postalcode"];
                        echo ("file: ".$filePostalCode."\n");
                        echo ("strcmp: ".strcmp($filePostalCode, $delPostalCode)."\n");
                        if (strcmp($filePostalCode, $delPostalCode) == 0){
                            echo "inside";
                            break 2;
                        }
                        if ($b == ($districtAvailCount-1)){ 
                            if (strcmp($filePostalCode, $delPostalCode) != 0) {
                                $tid = $district["data"][$a]["available"][$districtAvailCount-1]["tid"];
                                $tidPieces = explode("T", $tid);
                                $district["data"][$a]["available"][$districtAvailCount]["tid"] = "T".(string)((int)$tidPieces[1] + 1);
                                $district["data"][$a]["available"][$districtAvailCount]["postalcode"] = $delPostalCode;
                                $district["data"][$a]["available"][$districtAvailCount]["latlng"] = array("lat"=>$lat, "lng"=>$lng);
                                $district["data"][$a]["available"][$districtAvailCount]["lat"] = $delLat;
                                $district["data"][$a]["available"][$districtAvailCount]["lng"] = $delLng;
                                $district["data"][$a]["available"][$districtAvailCount]["address"] = $delPostalAdd;
                                file_put_contents($folderdir.$districtfile, str_replace('\/', '/', json_encode($district, 
                                    JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
                            }
                        }

                    }//inner for loop                              
                }

                else {
                    $tid = $district["data"][$a]["available"][0]["tid"];
                    $tidPieces = explode("T", $tid);
                    $district["data"][$a]["available"][0]["tid"] = "T".(string)((int)$tidPieces[1] + 1);
                    $district["data"][$a]["available"][0]["postalcode"] = $delPostalCode;
                    $district["data"][$a]["available"][0]["latlng"] = array("lat"=>$delLat, "lng"=>$delLng);
                    $district["data"][$a]["available"][0]["lat"] = $delLat;
                    $district["data"][$a]["available"][0]["lng"] = $delLng;
                    $district["data"][$a]["available"][0]["address"] = $delPostalAdd;
                    file_put_contents($folderdir.$districtfile, str_replace('\/', '/', json_encode($district, 
                        JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
                }
            }
        }
        // echo "<pre>";echo $intStr;echo "</pre>";
        //filereader function
        /*function jsonfilereader($url)
        {
            
            $contents = file_get_contents($url); //if no file_get_contents, use curl
            //$results = json_decode($contents, true); //without true it is stdClass
            $results = json_decode($contents, true);
            
            return $results;
        }*/
        
        /* Write job timeslot data */
        $jobs = jsonfilereader($folderpath.$jobsfile, true);
        foreach($jobs as $key=>$value){
            if ($pickupDate == $deliveryDate){
                $pickupJob = $jobs[$pickupDate][$pickupTime];
                $deliveryJob = $jobs[$pickupDate][$deliveryTime];
                if($key == $pickupDate){
                    $jobs[$pickupDate][$pickupTime] = (int)$pickupJob + 1;
                    $jobs[$pickupDate][$deliveryTime] = (int)$deliveryJob + 1; 
                }
                else { //pickupDate not found
                    echo "\nIm inside key != pickupDate\n\n";
                    if ($jobs[$pickupDate] == ""){ 
                        //create new entry
                        $jobs[$pickupDate] = array("12:00:00"=>0, "17:00:00"=>0, "19:00:00"=>0);
                        $jobs[$pickupDate][$pickupTime] = 1;
                        $jobs[$pickupDate][$deliveryTime] = 1;                      
                    }               
                }
            } //

            else { //pickupDate != deliveryDate
                $pickupJob = $jobs[$pickupDate][$pickupTime];
                $deliveryJob = $jobs[$deliveryDate][$deliveryTime];
                if ($key == $pickupDate){ //pickupDate                    
                    $jobs[$pickupDate][$pickupTime] = (int)$pickupJob + 1;
                }
                else{ //pickupDate not found
                    if ($jobs[$pickupDate] == ""){ 
                        //create new entry
                        $jobs[$pickupDate] = array("12:00:00"=>0, "17:00:00"=>0, "19:00:00"=>0);
                        $jobs[$pickupDate][$pickupTime] = 1;                            
                    }               
                }

                if ($key == $deliveryDate){ //deliveryDate
                    $jobs[$deliveryDate][$deliveryTime] = (int)$deliveryJob + 1;
                }
                else{ //deliveryDate not found
                    if ($jobs[$deliveryDate] == ""){ 
                        //create new entry
                        $jobs[$deliveryDate] = array("12:00:00"=>0, "17:00:00"=>0, "19:00:00"=>0);
                        $jobs[$deliveryDate][$deliveryTime] = 1;                            
                    }               
                }

            }
        }
        //write to jobsfile
        file_put_contents($folderdir.$jobsfile, str_replace('\/', '/', json_encode($jobs, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));

        /* write form data */
        // echo "<pre>";var_dump($data["data"][0]["postalcode"]); echo "</pre>";
        $count = count($district["data"]);
        $zone = "";
        $delZone = "";

        for ( $i = 0;$i < $count; $i++){
            if ($district["data"][$i]["postalcode"] == $intStr){
                $zone = $district["data"][$i]["zone"];
            }
             
             if($district["data"][$i]["postalcode"] == $delIntStr){
                $delZone = $district["data"][$i]["zone"];
            }
        }
        
        $data = jsonfilereader($folderpath.$datafile, true);
        if ($pickupDate == $deliveryDate){
            if ($data["date"][$pickupDate] == ""){
            // echo "Date not found";
            $data["date"][$pickupDate][0] = array("tid"=>"T1", "name"=>$name, "tel"=>$telNo, "pickupContactName"=>$pickupContactName, "pickupContactNo"=>$pickupContactNo,
                "deliveryContactName"=>$delContactName, "deliveryContactNo"=>$delContactNo, "email"=>$email, 
                "postalcode"=>array("pickup"=>$postalCode, "delivery"=>$delPostalCode), 
                "latlng"=>array("pickup" => $latlng, "delivery" => $delLatlng), "lat"=>array("pickup"=>$lat, "delivery"=>$delLat), 
                "lng"=>array("pickup"=>$lng, "delivery"=>$delLng), "address"=>array("pickup"=>$postalAdd, "delivery"=>$delPostalAdd),
                "zone"=>array("pickup"=>$zone, "delivery"=>$delZone), "floorNo"=>array("pickup"=>$floorNo, "delivery"=>$delFloorNo), 
                "unitNo"=>array("pickup"=>$unitNo, "delivery"=>$delUnitNo), "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime, 
                "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime, "type"=> "pickup");

            $data["date"][$pickupDate][0] = array("tid"=>"T1", "name"=>$name, "tel"=>$telNo, "pickupContactName"=>$pickupContactName, "pickupContactNo"=>$pickupContactNo,
                "deliveryContactName"=>$delContactName, "delContactNo"=>$delContactNo, "email"=>$email, 
                "postalcode"=>array("pickup"=>$postalCode, "delivery"=>$delPostalCode), 
                "latlng"=>array("pickup" => $latlng, "delivery" => $delLatlng), "lat"=>array("pickup"=>$lat, "delivery"=>$delLat), 
                "lng"=>array("pickup"=>$lng, "delivery"=>$delLng), "address"=>array("pickup"=>$postalAdd, "delivery"=>$delPostalAdd),
                "zone"=>array("pickup"=>$zone, "delivery"=>$delZone), "floorNo"=>array("pickup"=>$floorNo, "delivery"=>$delFloorNo), 
                "unitNo"=>array("pickup"=>$unitNo, "delivery"=>$delUnitNo), "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime, 
                "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime, "type"=> "delivery");
            // echo "Writing to file";
            file_put_contents($folderdir.$datafile, str_replace('\/', '/', json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
            }                    
            else{
                $count = count($data["date"][$pickupDate]);
                //echo $count;
                $data["date"][$pickupDate][$count] = array("tid"=>"T".(string)($count+1), "name"=>$name, "tel"=>$telNo, "pickupContactName"=>$pickupContactName, "pickupContactNo"=>$pickupContactNo,
                    "deliveryContactName"=>$delContactName, "deliveryContactNo"=>$delContactNo, "email"=>$email, 
                    "postalcode"=>array("pickup"=>$postalCode, "delivery"=>$delPostalCode), 
                    "latlng"=>array("pickup" => $latlng, "delivery" => $delLatlng), "lat"=>array("pickup"=>$lat, "delivery"=>$delLat), 
                    "lng"=>array("pickup"=>$lng, "delivery"=>$delLng), "address"=>array("pickup"=>$postalAdd, "delivery"=>$delPostalAdd),
                    "zone"=>array("pickup"=>$zone, "delivery"=>$delZone), "floorNo"=>array("pickup"=>$floorNo, "delivery"=>$delFloorNo), 
                    "unitNo"=>array("pickup"=>$unitNo, "delivery"=>$delUnitNo), "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime, 
                    "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime, "type"=> "pickup");

                $data["date"][$pickupDate][$count] = array("tid"=>"T".(string)($count+1), "name"=>$name, "tel"=>$tel, "pickupContactName"=>$pickupContactName, "pickupContactNo"=>$pickupContactNo,
                    "deliveryContactName"=>$deliveryContactName, "deliveryContactNo"=>$delContactNo, "email"=>$email, 
                    "postalcode"=>array("pickup"=>$postalCode, "delivery"=>$delPostalCode), 
                    "latlng"=>array("pickup" => $latlng, "delivery" => $delLatlng), "lat"=>array("pickup"=>$lat, "delivery"=>$delLat), 
                    "lng"=>array("pickup"=>$lng, "delivery"=>$delLng), "address"=>array("pickup"=>$pickupAddress, "delivery"=>$deliveryAddress),
                    "zone"=>array("pickup"=>$zone, "delivery"=>$delZone), "floorNo"=>array("pickup"=>$floorNo, "delivery"=>$delFloorNo), 
                    "unitNo"=>array("pickup"=>$unitNo, "delivery"=>$delUnitNo), "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime, 
                    "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime, "type"=> "delivery");
                // echo "Writing to file";
                file_put_contents($folderdir.$datafile, str_replace('\/', '/', json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
            }
        }
        else {//pickupDate != deliveryDate
            if ($data["date"][$pickupDate] == "" && $data["date"][$deliveryDate] == ""){
            // echo "Date not found";
            $data["date"][$pickupDate][0] = array("tid"=>"T1", "name"=>$name, "tel"=>$telNo, "pickupContactName"=>$pickupContactName, "pickupContactNo"=>$pickupContactNo,
                "deliveryContactName"=>$delContactName, "deliveryContactNo"=>$delContactNo, "email"=>$email, 
                "postalcode"=>array("pickup"=>$postalCode, "delivery"=>$delPostalCode), 
                "latlng"=>array("pickup" => $latlng, "delivery" => $delLatlng), "lat"=>array("pickup"=>$lat, "delivery"=>$delLat), 
                "lng"=>array("pickup"=>$lng, "delivery"=>$delLng), "address"=>array("pickup"=>$postalAdd, "delivery"=>$delPostalAdd),
                "zone"=>array("pickup"=>$zone, "delivery"=>$delZone), "floorNo"=>array("pickup"=>$floorNo, "delivery"=>$delFloorNo), 
                "unitNo"=>array("pickup"=>$unitNo, "delivery"=>$delUnitNo), "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime, 
                "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime, "type"=> "pickup");

            $data["date"][$deliveryDate][0] = array("tid"=>"T1", "name"=>$name, "tel"=>$telNo, "pickupContactName"=>$pickupContactName, "pickupContactNo"=>$pickupContactNo,
                "deliveryContactName"=>$delContactName, "delContactNo"=>$delContactNo, "email"=>$email, 
                "postalcode"=>array("pickup"=>$postalCode, "delivery"=>$delPostalCode), 
                "latlng"=>array("pickup" => $latlng, "delivery" => $delLatlng), "lat"=>array("pickup"=>$lat, "delivery"=>$delLat), 
                "lng"=>array("pickup"=>$lng, "delivery"=>$delLng), "address"=>array("pickup"=>$postalAdd, "delivery"=>$delPostalAdd),
                "zone"=>array("pickup"=>$zone, "delivery"=>$delZone), "floorNo"=>array("pickup"=>$floorNo, "delivery"=>$delFloorNo), 
                "unitNo"=>array("pickup"=>$unitNo, "delivery"=>$delUnitNo), "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime, 
                "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime, "type"=> "delivery");
            // echo "Writing to file";
            file_put_contents($folderdir.$datafile, str_replace('\/', '/', json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
            }

            else if ($data["date"][$pickupDate] == "" && $data["date"][$deliveryDate] != "") {
                $data["date"][$pickupDate][0] = array("tid"=>"T1", "name"=>$name, "tel"=>$telNo, "pickupContactName"=>$pickupContactName, "pickupContactNo"=>$pickupContactNo,
                "deliveryContactName"=>$delContactName, "deliveryContactNo"=>$delContactNo, "email"=>$email, 
                "postalcode"=>array("pickup"=>$postalCode, "delivery"=>$delPostalCode), 
                "latlng"=>array("pickup" => $latlng, "delivery" => $delLatlng), "lat"=>array("pickup"=>$lat, "delivery"=>$delLat), 
                "lng"=>array("pickup"=>$lng, "delivery"=>$delLng), "address"=>array("pickup"=>$postalAdd, "delivery"=>$delPostalAdd),
                "zone"=>array("pickup"=>$zone, "delivery"=>$delZone), "floorNo"=>array("pickup"=>$floorNo, "delivery"=>$delFloorNo), 
                "unitNo"=>array("pickup"=>$unitNo, "delivery"=>$delUnitNo), "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime, 
                "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime, "type"=> "pickup");

                $count = count($data["date"][$deliveryDate]);
                $data["date"][$deliveryDate][$count] = array("tid"=>"T".(string)($count+1), "name"=>$name, "tel"=>$telNo, "pickupContactName"=>$pickupContactName, "pickupContactNo"=>$pickupContactNo,
                "deliveryContactName"=>$delContactName, "delContactNo"=>$delContactNo, "email"=>$email, 
                "postalcode"=>array("pickup"=>$postalCode, "delivery"=>$delPostalCode), 
                "latlng"=>array("pickup" => $latlng, "delivery" => $delLatlng), "lat"=>array("pickup"=>$lat, "delivery"=>$delLat), 
                "lng"=>array("pickup"=>$lng, "delivery"=>$delLng), "address"=>array("pickup"=>$postalAdd, "delivery"=>$delPostalAdd),
                "zone"=>array("pickup"=>$zone, "delivery"=>$delZone), "floorNo"=>array("pickup"=>$floorNo, "delivery"=>$delFloorNo), 
                "unitNo"=>array("pickup"=>$unitNo, "delivery"=>$delUnitNo), "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime, 
                "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime, "type"=> "delivery");
                // echo "Writing to file";
                file_put_contents($folderdir.$datafile, str_replace('\/', '/', json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
            }    

            else if ($data["date"][$pickupDate] != "" && $data["date"][$deliveryDate] == "") {
                $count = count($data["date"][$pickupDate]);
                $data["date"][$pickupDate][$count] = array("tid"=>"T".(string)($count+1), "name"=>$name, "tel"=>$telNo, "pickupContactName"=>$pickupContactName, "pickupContactNo"=>$pickupContactNo,
                "deliveryContactName"=>$delContactName, "deliveryContactNo"=>$delContactNo, "email"=>$email, 
                "postalcode"=>array("pickup"=>$postalCode, "delivery"=>$delPostalCode), 
                "latlng"=>array("pickup" => $latlng, "delivery" => $delLatlng), "lat"=>array("pickup"=>$lat, "delivery"=>$delLat), 
                "lng"=>array("pickup"=>$lng, "delivery"=>$delLng), "address"=>array("pickup"=>$postalAdd, "delivery"=>$delPostalAdd),
                "zone"=>array("pickup"=>$zone, "delivery"=>$delZone), "floorNo"=>array("pickup"=>$floorNo, "delivery"=>$delFloorNo), 
                "unitNo"=>array("pickup"=>$unitNo, "delivery"=>$delUnitNo), "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime, 
                "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime, "type"=> "pickup");
                
                $data["date"][$deliveryDate][0] = array("tid"=>"T1", "name"=>$name, "tel"=>$telNo, "pickupContactName"=>$pickupContactName, "pickupContactNo"=>$pickupContactNo,
                "deliveryContactName"=>$delContactName, "delContactNo"=>$delContactNo, "email"=>$email, 
                "postalcode"=>array("pickup"=>$postalCode, "delivery"=>$delPostalCode), 
                "latlng"=>array("pickup" => $latlng, "delivery" => $delLatlng), "lat"=>array("pickup"=>$lat, "delivery"=>$delLat), 
                "lng"=>array("pickup"=>$lng, "delivery"=>$delLng), "address"=>array("pickup"=>$postalAdd, "delivery"=>$delPostalAdd),
                "zone"=>array("pickup"=>$zone, "delivery"=>$delZone), "floorNo"=>array("pickup"=>$floorNo, "delivery"=>$delFloorNo), 
                "unitNo"=>array("pickup"=>$unitNo, "delivery"=>$delUnitNo), "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime, 
                "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime, "type"=> "delivery");
                // echo "Writing to file";
                file_put_contents($folderdir.$datafile, str_replace('\/', '/', json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
            }    

            else {
                $count = count($data["date"][$pickupDate]);
                $data["date"][$pickupDate][$count] = array("tid"=>"T".(string)($count+1), "name"=>$name, "tel"=>$telNo, "pickupContactName"=>$pickupContactName, "pickupContactNo"=>$pickupContactNo,
                "deliveryContactName"=>$delContactName, "deliveryContactNo"=>$delContactNo, "email"=>$email, 
                "postalcode"=>array("pickup"=>$postalCode, "delivery"=>$delPostalCode), 
                "latlng"=>array("pickup" => $latlng, "delivery" => $delLatlng), "lat"=>array("pickup"=>$lat, "delivery"=>$delLat), 
                "lng"=>array("pickup"=>$lng, "delivery"=>$delLng), "address"=>array("pickup"=>$postalAdd, "delivery"=>$delPostalAdd),
                "zone"=>array("pickup"=>$zone, "delivery"=>$delZone), "floorNo"=>array("pickup"=>$floorNo, "delivery"=>$delFloorNo), 
                "unitNo"=>array("pickup"=>$unitNo, "delivery"=>$delUnitNo), "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime, 
                "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime, "type"=> "pickup");
                
                $delcount = count($data["date"][$deliveryDate]);
                $data["date"][$deliveryDate][$delcount] = array("tid"=>"T".(string)($count+1), "name"=>$name, "tel"=>$telNo, "pickupContactName"=>$pickupContactName, "pickupContactNo"=>$pickupContactNo,
                "deliveryContactName"=>$delContactName, "delContactNo"=>$delContactNo, "email"=>$email, 
                "postalcode"=>array("pickup"=>$postalCode, "delivery"=>$delPostalCode), 
                "latlng"=>array("pickup" => $latlng, "delivery" => $delLatlng), "lat"=>array("pickup"=>$lat, "delivery"=>$delLat), 
                "lng"=>array("pickup"=>$lng, "delivery"=>$delLng), "address"=>array("pickup"=>$postalAdd, "delivery"=>$delPostalAdd),
                "zone"=>array("pickup"=>$zone, "delivery"=>$delZone), "floorNo"=>array("pickup"=>$floorNo, "delivery"=>$delFloorNo), 
                "unitNo"=>array("pickup"=>$unitNo, "delivery"=>$delUnitNo), "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime, 
                "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime, "type"=> "delivery");
                // echo "Writing to file";
                file_put_contents($folderdir.$datafile, str_replace('\/', '/', json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
            }          
        }

        // echo "<pre>"; var_dump($data); echo "</pre>";

        /* SEND EMAIL */
        //set readable client pickup and delivery times
        if($pickupTime == "12:00:00"){
            $clientPickupTime = "Before 12PM";
        }
        if($deliveryTime == "12:00:00"){
            $clientDeliveryTime = "Before 12PM";
        }
        if($pickupTime == "17:00:00"){
            $clientPickupTime = "12PM to 5PM";
        }
        if($deliveryTime == "17:00:00"){
            $clientDeliveryTime = "12PM to 5PM";
        }
        if($pickupTime == "19:00:00"){
            $clientPickupTime = "5PM to 7PM";
        }
        if($deliveryTime == "19:00:00"){
            $clientDeliveryTime = "5PM to 7PM";
        }
        $email_headers = "From: clem0007@e.ntu.edu.sg\r\n";
        $email_headers .= "Reply-To: clem0007@e.ntu.edu.sg\r\n";
        $email_headers .= "MIME-Version: 1.0\r\n";
        $email_headers = "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $email_headers .= "Content-Transfer-Encoding: 7bit";
        $email_headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-\""; 
        /*--PHP-alt- 
        Content-Type: text/plain; charset="iso-8859-1" 
        Content-Transfer-Encoding: 7bit*/

        // Set the email subject.
        $email_subject = "TAQBIN Pickup/Delivery Request Confirmation";

        $email_message = "<html><body>";
        $email_message .= "<div align='center'><h2 style='text-align:center'>Pickup/Delivery Request Confirmation</h2>";
        $email_message .= "<table width='100%' rules='all' style='border:1px solid #3A5896;' cellpadding='10'>";
        $email_message .= "<tr><td colspan=2><h3>Personal details</h3></td></tr>\r\n";
        $email_message .= "<tr><td>First Name:</td> <td>$firstName</td></tr>\r\n";
        $email_message .= "<tr><td>Last Name:</td> <td>$lastName</td></tr>\r\n";
        $email_message .= "<tr><td>Mobile No:</td> <td>$telNo</td></tr>\r\n";
        $email_message .= "<tr><td colspan=2><h3>Pickup details</h3></td></tr>\r\n";
        if ($pickupContactCheck === "No"){
            $email_message .= "<tr><td>Pickup contact is myself?</td> <td>$pickupContactCheck</td></tr>\r\n";
            $email_message .= "<tr><td>Pickup Contact Name:</td> <td>$pickupContactName</td></tr>\r\n";
            $email_message .= "<tr><td>Pickup Contact No:</td> <td>$pickupContactNo</td></tr>\r\n";
        }
        $email_message .= "<tr><td>Postal Code:</td> <td>$postalCode</td></tr>\r\n";
        $email_message .= "<tr><td>Address:</td> <td>$postalAdd</td></tr>\r\n";
        if ($floorNo !== "" && $unitNo !== ""){
            $email_message .= "<tr><td>Floor No:</td> <td>$floorNo</td></tr>\r\n";
            $email_message .= "<tr><td>Unit No:</td> <td>$unitNo</td></tr>\r\n";
        }        
        $email_message .= "<tr><td>Pickup Date:</td> <td>$pickupDate</td></tr>\r\n";
        $email_message .= "<tr><td>Pickup Time:</td> <td>$clientPickupTime</td></tr>\r\n";
        $email_message .= "<tr><td colspan=2><h3>Delivery details</h3></td></tr>\r\n";
        $email_message .= "<tr><td>Delivery Contact Name:</td> <td>$delContactName</td></tr>\r\n";
        $email_message .= "<tr><td>Delivery Contact No:</td> <td>$delContactNo</td></tr>\r\n";
        $email_message .= "<tr><td>Delivery Postal Code:</td> <td>$delPostalCode</td></tr>\r\n";
        $email_message .= "<tr><td>Delivery Address:</td> <td>$delPostalAdd</td></tr>\r\n";
        if ($delFloorNo !== "" && $delUnitNo !== ""){
            $email_message .= "<tr><td>Delivery Floor No:</td> <td>$delFloorNo</td></tr>\r\n";
            $email_message .= "<tr><td>Delivery Unit No:</td> <td>$delUnitNo</td></tr>\r\n";
        }
        $email_message .= "<tr><td>Delivery Date:</td> <td>$deliveryDate</td></tr>\r\n";
        $email_message .= "<tr><td>Delivery Time:</td> <td>$clientDeliveryTime</td></tr></table>\r\n";
        $email_message .= "<table align=center><tr><td align='center' colspan=2><h4>Thank you for choosing TAQBIN.</h4></td></tr>\r\n"; 
        $email_message .= "<tr><td align='center'>Regards,</td></tr>\r\n";
        $email_message .= "<tr><td align='center'>TAQBIN HQ</td></tr><tr><td align='center'><i>This is a system-generated email. Please do not reply.</i></td></tr>\r\n";
        $email_message .= "</table></div></body></html>";
        date_default_timezone_set('Asia/Singapore');
        $timestamp = date('H:i:s d/m/Y');
        $email_message .= "[$timestamp]";
        

        // Check that data was sent to the mailer.
        if (empty($firstName) OR empty($lastName) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            header("HTTP/1.1 400 Bad Request");
            // status_header("HTTP/1.1 400 Bad Request");
            echo "Oops! There was a problem with your submission. Please complete the form and try again.";
            exit;
        }

        // Send the email.
        if (wp_mail($email, $email_subject, $email_message, $email_headers)) {
        // if (true) {
            // Set a 200 (okay) response code.
            //header("HTTP/1.1 200 OK");
             status_header("HTTP/1.1 200 OK");
            echo "Thank You <b> $firstName </b>! Your request has been saved. A confirmation email has been sent to your mailbox.";
        } else {
            // Set a 500 (internal server error) response code.
            //header("HTTP/1.1 500 Internal Server Error");
             status_header("HTTP/1.1 500 Internal Server Error");
            echo "Oops! Something went wrong and we couldn't send your message. Please try again later.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        //header("HTTP/1.1 403 Forbidden");
         status_header("HTTP/1.1 403 Forbidden");
        echo "There was a problem with your submission, please try again.";
    }

?>
