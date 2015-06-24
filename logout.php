<?php
$data = '{"id":"ID","method":"logout","params":{},"jsonrpc":"2.0"}';                                                                   
$sessid = $_POST["jsessionid"];
echo $sessid;                                                                                 
 
$ch = curl_init("https://stundenplan.hamburg.de/WebUntis/jsonrpc.do?school=hh5888");

curl_setopt($ch, CURLOPT_POST, 1);                                                                    
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                               
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    "Content-Type: application/json",
	"Content-Length: " . strlen($data),
	"Cookie: JSESSIONID=" . $sessid)	
);
curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);                                                                                                                   
$authresult = $json->result;
$result = curl_exec($ch);
print($result);
unset($_COOKIE['jsessionid']);
$datum = date('l jS \of F Y h:i:s A');
if ($authresult == "null") {
	error_log("\n" . $datum . ": User erfolgreich abgemeldet! Jsessionid: " . $sessid . "\n", 3, "whathappened.log");
	print "Erfolgreich ausgeloggt!";
}
elseif ($json->error->message == "not authenticated") {
	print "Nicht eingeloggt, ausloggen nicht möglich";
}


//error_log("\n" . $datum . ": User  Jsessionid: " . $sessid . "\n", 3, "whathappened.log");
?>