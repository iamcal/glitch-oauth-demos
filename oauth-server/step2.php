<?
	include('config.php');
	include('curl.php');

	if ($_GET['error']){

		include('head.txt');
?>

<h2>Step 2 Error: <?=HtmlSpecialChars($_GET['error'])?></h2>

<p><b>Error description:</b> <?=HtmlSpecialChars($_GET['error_description'])?></p>
<p><b>State:</b> <code><?=HtmlSpecialChars($_GET['state'])?></code></p>

<?
		include('foot.txt');
		exit;
	}


	if (!$_GET['code']){

		include('head.txt');
?>

<h2>Step 2 Error: No code</h2>

<p>Odd - we didn't get an authorization code passed back to us. I wonder why?</p>

<?
		include('foot.txt');
		exit;
	}


	$args = array(
		'grant_type'	=> 'authorization_code',
		'code'		=> $_GET['code'],
		'client_id'	=> $client_id,
		'client_secret'	=> $client_secret,
		'redirect_uri'	=> $redir_url,
	);

	if ($_GET['exchange']){

		$ret = curl_http_post("http://api.alpha.glitch.com/oauth2/token", $args);


		#
		# check for bad status
		#

		if ($ret['status'] != 200 && $ret['status'] != 400){

			include('head.txt');
?>
	<h2>Step 3 Error - Unexpected HTTP status code</h2>

	<p>The POST to the token endpoint unexpectedly returned status code <?=HtmlSpecialChars($ret['status'])?>. This might be a temporary failure.</p>
	<p>The body of the request follows:</p>
	<pre><?=HtmlSpecialChars($ret['body'])?></pre>
<?
			include('foot.txt');
			exit;
		}


		#
		# can we decode the JSON?
		#

		$obj = @json_decode($ret['body'], true);
		if (!is_array($obj) || !count($obj)){

			include('head.txt');
?>
	<h2>Step 3 Error - Unable to parse JSON response</h2>

	<p>The JSON body returned by the API request could not be parsed.</p>
	<p>The body of the request follows:</p>
	<pre><?=HtmlSpecialChars($ret['body'])?></pre>
<?
			include('foot.txt');
			exit;

		}


		#
		# was there an error?
		#

		if (strlen($obj['error'])){

			include('head.txt');
?>
	<h2>Step 3 Error: <?=HtmlSpecialChars($obj['error'])?></h2>

	<p><b>Error description:</b> <?=HtmlSpecialChars($obj['error_description'])?></p>

	<p>The body of the request follows:</p>
	<pre><?=HtmlSpecialChars($ret['body'])?></pre>
<?
			include('foot.txt');
			exit;

		}


		#
		# looks like we're good to go...
		#

		include('head.txt');
?>
	<h2>Step 3 - Use access token</h2>

	<p>The token endpoint has exchanged our authorization code for a usable access token:</p>

	<ul>
<? foreach ($obj as $k => $v){ ?>
		<li><code><?=HtmlSpecialChars($k)?></code> = <code><?=HtmlSpecialChars($v)?></code></li>
<? } ?>
	</ul>

	<p>We will call an API method using this token, in the iframe below:</p>

	<iframe width="100%" height="200" src="http://api.alpha.glitch.com/simple/auth.check?oauth_token=<?=HtmlSpecialChars($obj['access_token'])?>&simple=1&pretty=1"></iframe>

	<p>That concludes the demo. In your application, you would then store the <code>access_token</code> somewhere on the server and use it for subsequent requests.</p>
<?
		include('foot.txt');
		exit;
	}


	include('head.txt');
?>

<h2>Step 2 - Exchange code for access token</h2>

<p>The user has authorized our request and we have been returned the code <code><?=HtmlSpecialChars($_GET['code'])?></code>.</p>

<p>We now need to exchange this code for an access token, by calling the token endpoint <code>http://api.alpha.glitch.com/oauth2/token</code> with the following parameters:</p>

<ul>
<? foreach ($args as $k => $v){ ?>
	<li><code><?=HtmlSpecialChars($k)?></code> = <code><?=HtmlSpecialChars($v)?></code></li>
<? } ?>
</ul>

<p>This step must be done using an HTTP POST from the server.</p>

<p><a href="step2.php?code=<?=HtmlSpecialChars($_GET['code'])?>&exchange=1">Exchange code for access token</a></p>

<?
	include('foot.txt');
?>