<?
	##################################################################

	#
	# perform a 'simple' HTTP POST.
	#

	function curl_http_post($url, $post_args){

		$curl_handler = curl_init();

		curl_setopt($curl_handler, CURLOPT_URL, $url);
		curl_setopt($curl_handler, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_handler, CURLOPT_TIMEOUT, 5);
		curl_setopt($curl_handler, CURLOPT_FAILONERROR, FALSE);

		#
		# ignore invalid HTTPS certs. you probably want to comment out
		# these lines...		
		#

		curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, FALSE);


		#
		# it's a post
		#

		curl_setopt($curl_handler, CURLOPT_POST, 1);
		curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $post_args);


		#
		# send the request
		#

		$body = @curl_exec($curl_handler);
		$info = @curl_getinfo($curl_handler);


		#
		# close the connection
		#

		curl_close($curl_handler);


		#
		# return
		#

		return array(
			'status'	=> $info['http_code'],
			'body'		=> $body,
			'info'		=> $info,
		);
	}

	##################################################################
?>