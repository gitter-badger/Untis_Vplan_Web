<?php
$usr = $_POST[usr]; 
$pw = $_POST[pw];
$xtra = $_POST[xtra];
$data = '{"id":"ID","method":"authenticate","params":{"user":"' . $usr . '", "password":"' . $pw . '", "client":"CLIENT"},"jsonrpc":"2.0"}';                                                                   
//$data_string = json_encode($data);
unset($_COOKIE['jsessionid']);                                                                                

 
 
$ch = curl_init("https://stundenplan.hamburg.de/WebUntis/jsonrpc.do?school=hh5888");                                                                      
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Content-Length: " . strlen($data_string))));
//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

curl_setopt($ch, CURLOPT_POST, 1);                                                                    
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                               
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    "Content-Type: application/json",
	"Content-Length: " . strlen($data))	
);                                                                                                                   

$result = curl_exec($ch);
//print($result);
$json = json_decode($result); //flag true
/*echo $json->result->sessionId;*/
$sessid = $json->result->sessionId;
$pertype = $json->result->personType;
//setcookie("jsessionid", $sessid);
//echo $_COOKIE["jsessionid"];
//setcookie('jsessionid', $sessid);
//$_COOKIE['jsessionid'] = $sessid;

if ($xtra == "admin") {
	//setcookie('persontype', "2");
	//$_COOKIE['persontype'] = "2";
}
else {
	//setcookie('persontype', $pertype);
	//$_COOKIE['persontype'] = $pertype;
}
$datum = date('l jS \of F Y h:i:s A');
if ($sessid != "") {
	error_log("\n" . $datum . ": User Erfolgreich authentifiziert! Jsessionid: " . $sessid . "\n", 3, "whathappened.log");
	//print "Erfolgreich eingeloggt!";
	echo $sessid . ";" . $pertype;
}
else {
	print "Something went wrong. Entweder die Kombination aus Passwort und Benutzername ist nicht richtig, oder es liegt am Server. :-)";
}


?>