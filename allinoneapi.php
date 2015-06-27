<?php

$date = $_POST[date];

if ($date == "") {
$date = date("Ymd");
}
else {
	$date = $_POST[date];
}

//$date = 20150626;


$logout = '{"id":"ID","method":"logout","params":{},"jsonrpc":"2.0"}';
$getteachers = '{"id":"ID","method":"getTeachers","params":{},"jsonrpc":"2.0"}';
$getklassen = '{"id":"ID","method":"getKlassen","params":{},"jsonrpc":"2.0"}';
$getsubjects = '{"id":"ID","method":"getSubjects","params":{},"jsonrpc":"2.0"}';
$getrooms = '{"id":"ID","method":"getRooms","params":{},"jsonrpc":"2.0"}';
$getdepartments = '{"id":"ID","method":"getDepartments","params":{},"jsonrpc":"2.0"}';
$getholidays = '{"id":"ID","method":"getHolidays","params":{},"jsonrpc":"2.0"}';
$gettimegridunits = '{"id":"ID","method":"getTimegridUnits","params":{},"jsonrpc":"2.0"}';
$getstatusdata = '{"id":"ID","method":"getStatusData","params":{},"jsonrpc":"2.0"}';
$getcurrentschoolyear = '{"id":"ID","method":"getCurrentSchoolyear","params":{},"jsonrpc":"2.0"}';
$gettimetablek = '{"id":"ID","method":"getTimetable","params":{"id":71,"type":1},"jsonrpc":"2.0"}'; //klasse
$gettimetablet = '{"id":"ID","method":"getTimetable","params":{"id":71,"type":2},"jsonrpc":"2.0"}';	//lehrer
$gettimetablef = '{"id":"ID","method":"getTimetable","params":{"id":71,"type":3},"jsonrpc":"2.0"}'; //fach
$gettimetabler = '{"id":"ID","method":"getTimetable","params":{"id":71,"type":4},"jsonrpc":"2.0"}'; //raum
$gettimetables = '{"id":"ID","method":"getTimetable","params":{"id":71,"type":5},"jsonrpc":"2.0"}'; //schueler
$getchange = '{"id":"ID","method":"getLatestImportTime","params":{},"jsonrpc":"2.0"}'; //letzte änderung
$getbyidt = '{"id":"req-002","method":"getPersonId","params":{"sn":" ' . $vorname . ' ","dob":0,"type":2,"fn":" ' . $nachname . '"},"jsonrpc":"2.0"}';
$getsubstitutions = '{"id":"req-002","method":"getSubstitutions","params":{"startDate":' . $date . ',"endDate":' . $date . ',"departmentId":0},"jsonrpc":"2.0"}'; //vertretungen abfragen
$getexams = '{ "id":"1", "method":"getExams", "params":{ "examTypeId":"1","startDate":"' . $estartdate . '", "endDate": "' . $eenddate . '" }, "jsonrpc":"2.0" };'; //letzte änderung




$inp = $_POST[befehl]; 

if ($inp == "Vertretungen abfragen") {
	$inp = $getsubstitutions;
}

$inp = $getsubstitutions;

//require("auth.php");

//$data = '{"id":"ID","method":"logout","params":{},"jsonrpc":"2.0"}'; 
$data = $inp;                                                                  
//$data_string = json_encode($data);
$sessid = $_POST["jsessionid"];
$pertype = $_POST["pertype"];
//echo $pertype;
//echo $sessid;                                                                                 
 
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

$result = curl_exec($ch);

$json= $result;
$data = json_decode($json, true);


$index = 0;



function findstunde($startTime, $endTime)
{
    /*
    #################################
    PROCESSING START AND END TIMES
    #################################
    */
    //$startTime = $value['startTime'];
    //$endTime = $value['endTime'];
    $startTime = str_pad($startTime, 4, '0', STR_PAD_LEFT);
    $endTime   = str_pad($endTime, 4, '0', STR_PAD_LEFT);
    $chunks    = str_split($startTime, 2);
    $chunks2   = str_split($endTime, 2);
    $startTime = implode(':', $chunks);
    $endTime   = implode(':', $chunks2);
    
    switch ($startTime) {
        case $startTime == "08:00" && $endTime == "08:45":
            $stunde = "1.";
            break;
        case $startTime == "08:50" && $endTime == "09:35":
            $stunde = "2.";
            break;
        case $startTime == "09:55" && $endTime == "10:40":
            $stunde = "3.";
            break;
        case $startTime == "10:45" && $endTime == "11:30":
            $stunde = "4.";
            break;
        case $startTime == "11:50" && $endTime == "12:35":
            $stunde = "5.";
            break;
        case $startTime == "12:40" && $endTime == "13:25":
            $stunde = "6.";
            break;
        case $startTime == "13:30" && $endTime == "14:15":
            $stunde = "7.";
            break;
        case $startTime == "14:20" && $endTime == "15:05":
            $stunde = "8.";
            break;
        case $startTime == "15:10" && $endTime == "15:55":
            $stunde = "9.";
            break;
		case $startTime == "16:00" && $endTime == "16:45":
			$stunde = "10.";
			break;
    }
    
    return $stunde;
}



foreach ($data['result'] as $key => $row) {
        $klassen[$key] = $row['kl'][0]['name'];
        $stunden[$key] = findstunde($row['startTime'], $row['endTime']);
    }
if ($klassen) {
array_multisort($klassen, SORT_ASC, $stunden, SORT_ASC, $data['result']);
}


if ($klassen) {

echo '<table class="hoverable">';
echo "<thead>";
echo "<tr>";
echo "<th>Typ</th>";
echo "<th>Stunde</th>";
echo "<th>Klasse</th>";
if ($pertype == 2) {
	echo "<th>Lehrer</th>";
}
echo "<th>Fach</th>";
echo "<th>Raum</th>";
echo "<th>Text</th>";
echo "</tr>";
echo "</thead>";


echo "<tbody>";
$s_null = "0";



foreach($data['result'] as $key => $value) {
if (!$value['kl']['0']['name'] == "") {
  $aval = $value['txt'];
  //$teststr="ä ö ü Ä Ö Ü ß";
 // $aval = htmlentities($teststr);
  //sonderzeichen($aval);
  
  //$aval = strtr($value['txt'] ,$ers);
 // echo "<td>" . $value['type'] . "</td>";
  switch ($value['type']) {
	case 'subst':$color  = "#000066";break;
	case 'cancel':$color  = "#FF0000";$striket =  "<strike>";$striket2 = "</strike>";break;
	case 'add':$color  = "#000066";break;
	case 'shift':$color  = "#000066";break;
	case 'rmchg':$color  = "#008000";break;
	default:break;
		
  }
  echo "<tr style=\"color: $color\">";
  switch ($value['type']) {
	case 'subst':echo "<td style=\"color: $color\">Vertretung</td>";;break;
	case 'cancel':echo "<td style=\"color: $color\">Ausfall</td>";;break;
	case 'add':echo "<td>Zusatzstunde</td>";break;
	case 'shift':echo "<td>Verschoben</td>";break;
	case 'rmchg':echo "<td style=\"color: $color\">Raum&auml;nderung</td>";break;
	default:echo "<td>None</td>";
		
  }
  $startTime = $value['startTime'];
  $endTime = $value['endTime'];
  
  
  $hilfsint = 0;
  
  echo "<td>" . findstunde($startTime, $endTime) . "</td>";
  //echo "<td>" . $startTime . " - " . $endTime . "</td>";
  if ($value['kl']['0']['name'] == "") {
	  $s_work = "None";
	  echo "<td>" . $s_work . "</td>";
  } 
  else {
	  foreach ($value['kl'] as $somekey => $svalue) {
	  $s_work = $svalue['name'];
	  $s_work = ltrim($s_work, '0');
	  if ($hilfsint = 0) {
		$somestring = $s_work;
	  } else {
		$somestring = $somestring . "," . $s_work;
	  }
	  $hilfsint = $hilfsint + 1;
	  }
	 $somestring = trim($somestring, ',');
	 echo "<td>" . $somestring . "</td>";
	  $somestring = "";
	 $hilfsarray = array();
	 }
	  

  
  //echo "<td>" . $s_work . "</td>";
  if ($pertype == 2) {
	echo "<td>" . $value['te']['0']['name'] . "</td>";
  }
  else {
	//echo "<th>X</th>";
  }
  echo "<td>" . $value['su']['0']['name'] . "</td>";
  echo "<td>" . $value['ro']['0']['name'] . "</td>";
  echo "<td>" . $aval . "</td>";
  echo "</tr>" . $striket2; 
 }
 else {
 }
}


echo "</table><br><br>";

} else {
	echo "nosubs";
}

//echo "Logout: " . require('logout.php');

//unset($_COOKIE['jsessionid'])

unset($_COOKIE['jsessionid']);
unset($_COOKIE['persontype']);
?>