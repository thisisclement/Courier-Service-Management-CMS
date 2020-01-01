<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/courier/clem/wordpress/wp-blog-header.php');
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
        $fullName = $firstName." ".$lastName;
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
        $postalAdd = strip_tags(trim($_POST["postalAdd"]));
                $postalAdd = str_replace(array("\r","\n"),array(" "," "),$postalAdd);
        $floorNo = strip_tags(trim($_POST["floorNo"]));
                $floorNo = str_replace(array("\r","\n"),array(" "," "),$floorNo);
        $unitNo = strip_tags(trim($_POST["unitNo"]));
                $unitNo = str_replace(array("\r","\n"),array(" "," "),$unitNo);
        $pickupDate = $_POST["pickupDate"];
        $pickupTime = $_POST["pickupTime"];
        $deliveryDate = $_POST["deliveryDate"];
        $deliveryTime = $_POST["deliveryTime"];   
        /* Save to JSON */
        $folderpath = './data';
        //filereader function
        function jsonfilereader($url)
        {
            
            $contents = file_get_contents($url); //if no file_get_contents, use curl
            //$results = json_decode($contents, true); //without true it is stdClass
            $results = json_decode($contents, true);
            
            return $results;
        }
        // $data = jsonfilereader($folderpath.'/data1.json');
        // if ($data["date"][$pickupDate] == ""){
        //     echo "Date not found";
        //     $data["date"][$pickupDate][0] = array("tid"=>"T1", "name"=>$fullName, "tel"=>$telNo, "pickupContactName"=>$pickupContactName, "email"=>$email, 
        //         "postalcode"=>$postalCode, "address"=>$postalAdd, "floorNo"=>$floorNo, "unitNo"=>$unitNo, "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime,
        //         "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime);
        //     echo "Writing to file";
        //     file_put_contents($folderpath.'/data1.json', str_replace('\/', '/', json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
        // }

        // else{
        //     $count = count($data["date"][$pickupDate]);
        //     //echo $count;
        //     $data["date"][$pickupDate][$count] = array("tid"=>"T".(string)($count+1), "name"=>$fullName, "tel"=>$telNo, "pickupContactName"=>$pickupContactName, "email"=>$email, 
        //         "postalcode"=>$postalCode, "address"=>$postalAdd, "floorNo"=>$floorNo, "unitNo"=>$unitNo, "pickupDate"=>$pickupDate, "pickupTime"=>$pickupTime,
        //         "deliveryDate"=>$deliveryDate, "deliveryTime"=>$deliveryTime);
        //     echo "Writing to file";
        //     file_put_contents($folderpath.'/data1.json', str_replace('\/', '/', json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)));
        // }
        //echo "<pre>"; var_dump($data); echo "</pre>";

        /* SEND EMAIL */
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
        $email_subject = "TAQBIN Pickup Request Confirmation";

        $email_message = "<html><body>";
        $email_message .= "<div align='center'><h2 style='text-align:center'>Pickup Request Confirmation</h2>";
        $email_message .= "<table width='100%' rules='all' style='border:1px solid #3A5896;' cellpadding='10'>";
        $email_message .= "<tr><td colspan=2><h3>Personal details</h3></td></tr>\r\n";
        $email_message .= "<tr><td>First Name:</td> <td>$firstName</td></tr>\r\n";
        $email_message .= "<tr><td>Last Name:</td> <td>$lastName</td></tr>\r\n";
        $email_message .= "<tr><td>Mobile No:</td> $telNo</td></tr>\r\n";
        $email_message .= "<tr><td colspan=2><h3>Pickup details</h3></td></tr>\r\n";
        if ($pickupContactCheck === "No"){
            $email_message .= "<tr><td>Pickup contact is myself?</td> <td>$pickupContactCheck</td></tr>\r\n";
            $email_message .= "<tr><td>Pickup Contact Name:</td> <td>$pickupContactName</td></tr>\r\n";
            $email_message .= "<tr><td>Pickup Contact No:</td> <td>$pickupContactNo</td></tr>\r\n";
        }
        $email_message .= "<tr><td>Address:</td> <td>$postalAdd</td></tr>\r\n";
        if ($floorNo !== "" && $unitNo !== ""){
            $email_message .= "<tr><td>Floor No:</td> <td>$floorNo</td></tr>\r\n";
            $email_message .= "<tr><td>Unit No:</td> <td>$unitNo</td></tr>\r\n";
        }
        $email_message .= "<tr><td>Postal Code:</td> <td>$postalCode</td></tr>\r\n";
        $email_message .= "<tr><td>Pickup Date:</td> <td>$pickupDate</td></tr>\r\n";
        $email_message .= "<tr><td>Pickup Time:</td> <td>$pickupTime</td></tr>\r\n";
        $email_message .= "<tr><td>Delivery Date:</td> <td>$deliveryDate</td></tr>\r\n";
        $email_message .= "<tr><td>Delivery Time:</td> <td>$deliveryTime</td></tr></table>\r\n";
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
            echo "Oops! There was a problem with your submission. Please complete the form and try again.";
            exit;
        }

        // Send the email.
        if (wp_mail($email, $email_subject, $email_message, $email_headers)) {
        // if (true) {
            // Set a 200 (okay) response code.
            header("HTTP/1.1 200 OK");
            echo "Thank You <b> $firstName </b>! Your request has been saved. A confirmation email has been sent to your mailbox.";
        } else {
            // Set a 500 (internal server error) response code.
            header("HTTP/1.1 500 Internal Server Error");
            echo "Oops! Something went wrong and we couldn't send your message. Please try again later.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        header("HTTP/1.1 403 Forbidden");
        echo "There was a problem with your submission, please try again.";
    }

?>
