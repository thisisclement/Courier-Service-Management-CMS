<?php
	if ( !defined('ABSPATH') ) {
        require_once "../../../../wp-load.php";
        // require_once("../../../../wp-blog-status_header.php");
		// require_once "../../../../wp-includes/functions.php";
        
    }


 // Only process POST reqeusts.
	$JOBS_LIMIT = 100;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    	$pickupDate = $_POST["pickupDate"];
        $pickupTime = $_POST["pickupTime"];
        $deliveryDate = $_POST["deliveryDate"];
        $deliveryTime = $_POST["deliveryTime"];  

		$folderpath = get_stylesheet_directory_uri()."/data";
        $folderdir = get_stylesheet_directory()."/data";
		$jobsfile = "/jobsProcess.json";

        $jobs = jsonfilereader($folderpath.$jobsfile, true);
        $start = microtime(true);
		
		foreach($jobs as $key => &$value){
			$pickupJob = $value[$pickupTime];
			$deliveryJob = $value[$deliveryTime];
			echo "\n\npickupJob=>".$pickupJob."\n\n";
			echo "\n\ndeliveryjob=>".$deliveryJob."\n\n";
			if ($pickupDate == $deliveryDate){
				if ($key == $pickupDate){
					$pickupJobNotExceed = $pickupJob < $JOBS_LIMIT;
					$deliveryJobNotExceed = $deliveryJob < $JOBS_LIMIT;

					if ($pickupJobNotExceed == 1 && $deliveryJobNotExceed == 1){//ensure within limit
						(int)$pickupJob + 1;
						(int)$deliveryJob + 1;  			
					}

					elseif ($pickupJobNotExceed == 1) {
						(int)$pickupJob + 1;
						
						
					}

					elseif ($deliveryJobNotExceed == 1){
						(int)$deliveryJob + 1; 
						
						
					}

					 //exceed jobs limit
					//differentiate which job exceeded
					if ($pickupJobNotExceed != 1){ 
						file_put_contents($folderdir.$jobsfile, str_replace('\/', '/', json_encode($jobs, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
						status_header(400);
						echo "Pickup";
						exit;
					}

					if ($deliveryJobNotExceed != 1){
						file_put_contents($folderdir.$jobsfile, str_replace('\/', '/', json_encode($jobs, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
						status_header(400);
						echo "Delivery";
						exit;
					}
					
				}

				else{ //pickupDate not found
					if ($jobs[$pickupDate] == ""){ 
						//create new entry
						$jobs[$pickupDate] = array("12:00:00"=>0, "17:00:00"=>0, "19:00:00"=>0);
						$jobs[$pickupDate][$pickupTime] = 1;
						$jobs[$pickupDate][$deliveryTime] = 1;						
						
					}				
				}
			}

			else {//pickupDate != deliveryDate 
				if ($key == $pickupDate){
					if ($pickupJob < $JOBS_LIMIT){//ensure within limit
						$jobs[$pickupDate][$pickupTime] = (int)$pickupJob + 1;
											
					}
					else{
						file_put_contents($folderdir.$jobsfile, str_replace('\/', '/', json_encode($jobs, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
						// $deliveryJob = $jobs[$deliveryDate][$deliveryTime];
						// echo "\n\nno of delivery jobs=>".$deliveryJob."\n\n";
						if ($deliveryJob >= $JOBS_LIMIT){
							status_header(400);
							echo "Pickup&Delivery,".$pickupTime.",".$deliveryTime;
							exit;
						}
						status_header(400);
						echo "Pickup,".$pickupTime;
						exit;
					}
				}

				else{ //pickupDate not found
					if ($jobs[$pickupDate] == ""){ 
						//create new entry
						$jobs[$pickupDate] = array("12:00:00"=>0, "17:00:00"=>0, "19:00:00"=>0);
						$jobs[$pickupDate][$pickupTime] = 1;
							
					}				
				}

				if($key == $deliveryDate){
					// echo "\n";
					// echo "in key == deldate\n\n";

					$deliveryJob1 = $value[$deliveryTime];
					// echo "\n\nno of delivery jobs=>".$deliveryJob."\n\n";
					if ($deliveryJob1 < $JOBS_LIMIT){//ensure within limit
						$jobs[$deliveryDate][$deliveryTime] = (int)$deliveryJob1 + 1; 
						// echo $jobs[$deliveryDate][$deliveryTime];
										
					}
					else{
						file_put_contents($folderdir.$jobsfile, str_replace('\/', '/', json_encode($jobs, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
						$pickupJob = $value[$pickupTime];
						if ($pickupJob >= $JOBS_LIMIT){
							status_header(400);
							echo "Pickup&Delivery,".$pickupTime.",".$deliveryTime;
							exit;
						}	
						status_header(400);
						echo "Delivery".$deliveryTime;
						exit;
					}
					
				}

				if ($key != $deliveryDate){ //deliveryDate not found
					// echo "\n";
					// echo "in key != deldate\n\n";
					if ($jobs[$deliveryDate] == ""){ 
						//create new entry
						$jobs[$deliveryDate] = array("12:00:00"=>0, "17:00:00"=>0, "19:00:00"=>0);
						$jobs[$deliveryDate][$deliveryTime] = 1;
						
					}
				}

			}

		} //foreach
		file_put_contents($folderdir.$jobsfile, str_replace('\/', '/', json_encode($jobs, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
		// echo "\n\nat EOF!!\n\n";
		status_header(200);
		echo "Status OK";

		
	}// if POST
 	

?>