<html>
<head>
<title>Vertretungsplan</title>
<link type="text/css" rel="stylesheet" href="../../selfmail/css/materialize.css"  media="screen,projection"/>
<link href="jquery.w8n.min.css" rel="stylesheet" type="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<style>
.hundert {
	width: 70%;
	margin-left: 15%;
	margin-right: 15%;
}
.ninja {
	visibility: hidden;
}
</style>
</head>
<body>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../../selfmail/js/materialize.js"></script>
<script src="jquery.w8n.min.js"></script>
<script>

function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

function logout(){
                //var data = $('form#'+id).serialize();
				var sessid = readCookie("jsessionid");
				//console.log(sessid);
				var data = "jsessionid=" + sessid;
				//alert(data);
                //$('form#'+id).unbind('submit');                
                $.ajax({
                    url: "logout.php",
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                    },
                    success: function(data, textStatus, xhr) {
						setTimeout(function(){
							$.w8n('Ausloggen erfolgreich', 'Sie wurden automatisch ausgeloggt.', {timeout: 2500});
						}, 4000);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });
                return false;
            }
			
			

function getsubs(id){
                var data = $('form#'+id).serialize();
				//alert(data);
                $('form#'+id).unbind('submit');                
                $.ajax({
                    url: "allinoneapi.php",
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                    },
                    success: function(data, textStatus, xhr) {
						 //console.log(data);
						 $('#scnbtn').hide();
						 document.getElementById("maindiv").innerHTML+= data;
						 logout();
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });
                return false;
            }
			

function sendPushNotification(id){
                var data = $('form#'+id).serialize();
				//alert(data);
                $('form#'+id).unbind('submit');                
                $.ajax({
                    url: "auth.php",
                    type: 'POST',
                    data: data,
                    beforeSend: function() {
                    },
                    success: function(data, textStatus, xhr) {
						 var sessid = data.substr(0,data.indexOf(";"));
						 var o_pertype = data.substr(data.indexOf(";"), data.length);
						 pertype = o_pertype.substr(o_pertype.length - 1)
						 createCookie('jsessionid',sessid,7);
						 createCookie('pertype', pertype, 7)
						 document.getElementById('sessid').value = sessid;
						 document.getElementById('perstype').value = pertype;
						 //getsubs();
						 //Materialize.toast('Erfolgreich eingeloggt.', 4000)
						 document.title = document.title + " - Eingeloggt";//HTMLEncode("&bull;Eingeloggt");
						 $("#trigger").hide();
						 document.getElementById("scnbtn").style.visibility='visible';
						 $.w8n('Einloggen erfolgreich', 'Sie koennen jetzt die Vertretungen abfragen.', {timeout: 2500});
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });
                return false;
            }

$(document).ready(function(){
    // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
	  $('#trigger').leanModal({
      dismissible: false, // Modal can be dismissed by clicking outside of the modal
      opacity: .5, // Opacity of modal background
      in_duration: 300, // Transition in duration
      out_duration: 200, // Transition out duration
      complete: function() { $('#aform').submit(); }, // Callback for Modal open
      //complete: function() { alert('Closed'); } // Callback for Modal close
    }
  );
	$('#scnbtn').leanModal({
      dismissible: false, // Modal can be dismissed by clicking outside of the modal
      opacity: .5, // Opacity of modal background
      in_duration: 300, // Transition in duration
      out_duration: 200, // Transition out duration
      complete: function() { $('#bform').submit();document.getElementById('sessid').value = sessid;document.getElementById('perstype').value = pertype; }, // Callback for Modal open
      //complete: function() { alert('Closed'); } // Callback for Modal close
    }
  );
  document.getElementById("scnbtn").style.visibility='hidden';
  });
</script>
<h2 align="center">Heilwig Gymnasium Vertretungsplan</h2>

<br><br>

<noscript>Um diese Seite nutzen zu können, müssen Sie Javascript aktivieren.</noscript>

<!-- Modal Trigger -->
<form id="aform" method="post" onsubmit="return sendPushNotification('aform')">
	<a class="waves-effect waves-light btn modal-trigger hundert" id="trigger" type="submit" href="#modal1">Einloggen</a>
</form>

<form id="bform" method="post" onsubmit="return getsubs('bform')">
<a class="waves-effect waves-light btn modal-trigger hundert" id="scnbtn" type="submit" href="#modal2">Vertretungen abfragen</a>
</form>
  <!-- Modal Structure -->
  <div id="modal1" class="modal">
    <div class="modal-content">
      <h4>Bitte einloggen</h4>
	  <form id="aform" method="post" onsubmit="return sendPushNotification('aform')">
	  <div class="row">
		<div class="input-field col s12">
			<input id="username" name="usr" type="text" class="validate" autocomplete="off">
			<label for="username">Benutzername</label>
		</div>
		<div class="input-field col s12">
			<input id="password" name="pw" type="password" class="validate" autocomplete="off">
			<label for="password">Passwort</label>
		</div>
	   </div>
	   </form>
	
    </div>
    <div class="modal-footer">
      <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Einloggen</a>
    </div>
  </div>
<!-- MODAL END -->
<div id="modal2" class="modal">
    <div class="modal-content">
      <h4>Datum ausw&auml;hlen</h4>
	  <form id="bform" method="post" onsubmit="return getsubs('bform');logout('bform')">
	  <input type="date" class="datepicker">
	  <input type="hidden" name="jsessionid" id="sessid" value=""></input>
	  <input type="hidden" name="pertype" id="perstype" value=""></input>
	   </form>
	
    </div>
    <div class="modal-footer">
      <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Los!</a>
    </div>
  </div>
<!-- MODAL END -->
<div id="maindiv">

</div>

<footer>
<p align="center" class="footer">Copyright &copy; 2015 <a href="http://littleminimalist.de/">Jossi Wolf</a> &bull; Erstellt von <a href="http://littleminimalist.de/">Jossi Wolf</a><br>Vertretungsplan Version 1.8<br>We &hearts; <a href="http://materializecss.com/">Materializecss</a></p>
</footer>
</body>
</html>
