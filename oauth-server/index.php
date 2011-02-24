<?
	include('config.php');

	include('head.txt');
?>

<h2>Step 1 - Redirect user to authorization endpoint</h2>
<?

	$args = array(
		'response_type'	=> 'code',
		'client_id'	=> $client_id,
		'redirect_uri'	=> $redir_url,
		'scope'		=> 'identity',
		'state'		=> 'hello-world',
	);

	$base_url = "http://api.alpha.glitch.com/oauth2/authorize";

	function build_url($base_url, $args, $more=array()){

		foreach ($more as $k => $v){
			$args[$k] = $v;
		}

		$pairs = array();
		foreach ($args as $k => $v){
			$pairs[] = urlencode($k).'='.urlencode($v);
		}
		return $base_url.'?'.implode('&', $pairs);
	}

?>

<p>For this test, we will redirect the user to <code><?=HtmlSpecialChars($base_url)?></code>, with the following parameters:</p>

<ul>
<? foreach ($args as $k => $v){ ?>
	<li><code><?=HtmlSpecialChars($k)?></code> = <code><?=HtmlSpecialChars($v)?></code></li>
<? } ?>
</ul>

<p><a href="<?=build_url($base_url, $args)?>">Start authorization</a></p>

<p>Failures that <b>should not</b> redirect back to here:</p>

<ul>
	<li><a href="<?=build_url($base_url, $args, array('client_id' => 'waffles'))?>">Start authorization with bad client_id</a></li>
	<li><a href="<?=build_url($base_url, $args, array('client_secret' => 'waffles'))?>">Start authorization with bad client_secret</a></li>
	<li><a href="<?=build_url($base_url, $args, array('redirect_uri' => 'waffles'))?>">Start authorization with bad redirect_uri</a></li>
</ul>

<p>Failures that <b>should</b> redirect back to here:</p>

<ul>
	<li><a href="<?=build_url($base_url, $args, array('scope' => 'waffles'))?>">Start authorization with bad scope</a></li>
	<li><a href="<?=build_url($base_url, $args, array('response_type' => 'waffles'))?>">Start authorization with bad response_type</a></li>
</ul>

<?
	include('foot.txt');
?>