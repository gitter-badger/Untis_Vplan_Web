var langs = ['en', 'it', 'de'];
var langCode = '';
var langJS = null;


var translate = function (jsdata)
{	
	$("[tkey]").each (function (index)
	{
		var strTr = jsdata [$(this).attr ('tkey')];
	    $(this).html (strTr);
	});
}


langCode = navigator.language.substr (0, 2);
//alert(langCode);

//if (langCode in langs)
	//$.getJSON('lang/'+langCode+'.json', translate);
if (langCode == "en")
	$.getJSON('lang/en.json', translate);
if (langCode == "it")
	$.getJSON('lang/it.json', translate);
if (langCode == "de")
	$.getJSON('lang/de.json', translate);



