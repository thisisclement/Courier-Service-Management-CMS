<?php

    /*
     * Telerivet API - Sending SMS
     * --------------------------------------------------------------------
     */
    /* GMAIL ACC */
    // $api_key = 'FXGDQFPFQPZNRULLRZ3K99E94FG3Q3U9';
    // $project_id = 'PJ05162c3be81104c6'; //Test project
    // $phone_id = 'PN5a9e55124fba51db';  //Test project 
    // $project_id = 'PJ164c9fda7240e8e7'; //Untitled project
    // $phone_id = 'PNcaba206b0f4e263d';  //Untitled project  

    /* NTU ACC */
    $api_key = 'R8KfRsI8MK4yZmqUKrsQCtdm4kp74piV'; 
    $project_id = 'PJ2e6d99b351c587f3'; //Untitled project
    $phone_id = 'PNea4a4be820c43c33';  //Untitled project
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if(!empty($_POST['telNo'])){ 
            $to_number = "65".$_POST['telNo'];
        }
        else {
            header("HTTP/1.1 400 Bad Request");
            echo "Oops! Your mobile number cannot be empty!";
            exit;
        }
        $content = 'This is your key: '.$_POST['key'];
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 
            "https://api.telerivet.com/v1/projects/$project_id/messages/outgoing");
        curl_setopt($curl, CURLOPT_USERPWD, "{$api_key}:");  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'content' => $content,
            'phone_id' => $phone_id,
            'to_number' => $to_number,
        ), '', '&'));        
        
        // if you get SSL errors, download SSL certs from https://telerivet.com/_media/cacert.pem .
        curl_setopt($curl, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");    
        
        $json = curl_exec($curl);    
        $network_error = curl_error($curl);
        curl_close($curl);    
        
        if ($network_error) 
        { 
            $message_html = htmlspecialchars($network_error);
        }    
        else
        {
            $res = json_decode($json, true);        
        
            if (isset($res['error']))
            {
                $message_html = htmlspecialchars($res['error']['message']);
            }
            else
            {
                $message_html = "Message sent! (status: ". htmlspecialchars($res['status']). ")";
                $content = '';
            }
        }
    }
    else
    {
        $message_html = '';   
        $to_number = '';
        $content = '';
    }
    echo $message_html;
    // header("HTTP/1.1 200 OK");
    // echo "Message sent";
?>