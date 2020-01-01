<?php


if(isset($_POST['request'])) {

    $folderpath = '../../data';
    $filename = $folderpath.'/couriersupdatetest.json';

    //need to write to file
    file_put_contents($filename, json_encode($_POST['request'], JSON_PRETTY_PRINT));

    //header('Refresh: 3; URL=http://localhost:8080/fyp/');//redirect 3s

    //send back to client as a response
    $res = array("response"=>"successful");
    echo json_encode($res);

}

?>