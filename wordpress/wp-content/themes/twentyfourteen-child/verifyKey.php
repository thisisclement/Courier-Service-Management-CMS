<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$key = $_POST['key'];
	$verifyKey = $_POST['verifyKey'];

	if (empty($_POST['key']) || empty($_POST['verifyKey'])) {
		header("HTTP/1.1 400 Bad Request");
		exit;
	}

	if ($key === $verifyKey){
		header("HTTP/1.1 200 OK");
		echo "Mobile No. successfully verified!";	
		exit;	
	}

	else {
		header("HTTP/1.1 400 Bad Request");
        echo "Verification key does not tally. Please fill in or resend verification key again.".$key;
        exit;
	}
}


?>