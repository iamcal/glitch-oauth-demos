<?
	include('config.php');

	include('head.txt');
?>

<h2>Step 2 - Retrieve token from URL fragment</h2>

<noscript>
	<b style="color: red">This demo requires you to enabled JavaScript.</b>
</noscript>

<div id="noresponse-output" style="display: none">
	<p>No response fragment was returned - odd!</p>
</div>

<div id="response-output" style="display: none">
	<p>Response:</p>
	<ul id="response-list"></ul>
</div>

<div id="api-request" style="display: none">
	<p>We were given a token, so here's a request using it:</p>

	<iframe width="100%" height="200" src="" id="call-frame"></iframe>
</div>

<script>

window.onload = function(){

	var bits = window.location.href.split('#');
	var fragment = bits[1];

	if (!fragment){
		document.getElementById('noresponse-output').style.display = 'block';
		return;
	}

	// parse fragment into k=v pairs
	var obj = {};
	var pairs = fragment.split('&');
	for (var i=0; i<pairs.length; i++){
		var pair = pairs[i].split('=');
		var k = decodeURIComponent(pair[0]);
		var v = decodeURIComponent(pair[1]);
		obj[k] = v;
	}

	// output repsonse

	var args_html = '';
	for (var k in obj){
		args_html += "<li> <code>"+escapeXML(k)+"</code> = <code>"+escapeXML(obj[k])+"</code> </li>\n";
	}

	document.getElementById('response-list').innerHTML = args_html;
	document.getElementById('response-output').style.display = 'block';

	// if we got an access token, make a request

	if (obj.access_token){
		document.getElementById('call-frame').src = "<?=$api_base?>/simple/auth.check?oauth_token="+obj.access_token+"&simple=1&pretty=1";
		document.getElementById('api-request').style.display = 'block';
	}
};

function escapeXML(s){
	s = ""+s;
	return s.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;");
}

</script>

<?
	include('foot.txt');
?>