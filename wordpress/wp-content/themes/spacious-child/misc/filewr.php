<?php
/* Save to JSON */
        $folderpath = get_stylesheet_directory().'/data';
        $postalCode = "098762";
        //filereader function
        function jsonfilereader($url)
        {
            
            $contents = file_get_contents($url); //if no file_get_contents, use curl
            //$results = json_decode($contents, true); //without true it is stdClass
            $results = json_decode($contents, true);
            
            return $results;
        }
        $district = jsonfilereader($folderpath.'/district.json');

        // echo "<pre>";var_dump($data["data"][0]["postalcode"]); echo "</pre>";
        $count = count($district["data"]);
        echo $count;
        $zone = "";
        $i = 0;
        $twodigits = floor($postalCode/10000); //first two digits of 6 digits
        if($twodigits < 10)
            $twodigits = sprintf("%02d", $twodigits);//for single digits, add a zero in front
        $intStr = "$twodigits"; //convert int to string
        echo "<pre>";echo $intStr;echo "</pre>";
        //echo '<pre>';echo $i;echo '</pre>';
        echo $district["data"][1]["postalcode"];
        for ( $i = 0;$i < $count; $i++){
            //echo '<pre>';echo $i;echo '</pre>';
            if ($district["data"][$i]["postalcode"] == $intStr){
                $zone = $district["data"][$i]["zone"];
            }
        }
        echo '<pre>';echo $zone;echo '</pre>';
        $data = jsonfilereader($folderpath.'/data1.json');
        // if ($data["date"][$pickupDate] == ""){
        //     echo "Date not found";
        //     $data["date"][$pickupDate][0] = array("tid"=>"T1", "name"=>$fullName, "tel"=>$telNo, "pickupContactName"=>$pickupContactName, "email"=>$email, 
        //         "postalcode"=>$postalCode, "address"=>$postalAdd, "zone"=>$zone, "floorNo"=>$floorNo, "unitNo"=>$unitNo, "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime,
        //         "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime);
        //     echo "Writing to file";
        //     file_put_contents($folderpath.'/data1.json', str_replace('\/', '/', json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
        // }

        // else{
        //     $count = count($data["date"][$pickupDate]);
        //     //echo $count;
        //     $data["date"][$pickupDate][$count] = array("tid"=>"T".(string)($count+1), "name"=>$fullName, "tel"=>$telNo, "pickupContactName"=>$pickupContactName, "email"=>$email, 
        //         "postalcode"=>$postalCode, "zone"=>$zone, "address"=>$postalAdd, "floorNo"=>$floorNo, "unitNo"=>$unitNo, "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime,
        //         "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime);
        //     echo "Writing to file";
        //     file_put_contents($folderpath.'/data1.json', str_replace('\/', '/', json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
        // }
        // echo "<pre>"; var_dump($data); echo "</pre>";

        ?>