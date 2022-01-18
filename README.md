$config = array(
		    "digest_alg" => "sha512",
		    "private_key_bits" => 4096,
		    "private_key_type" => OPENSSL_KEYTYPE_RSA,
		);


		// Create the private and public key
		$res = openssl_pkey_new($config);

		// Extract the private key from $res to $privKey
		openssl_pkey_export($res, $privKey);

		echo $privKey; 


		// Extract the public key from $res to $pubKey
		$pubKey = openssl_pkey_get_details($res);
		$pubKey = $pubKey["key"];

		echo $pubKey; 


		$data = 'ZERIN';

		// Encrypt the data to $encrypted using the public key
		openssl_public_encrypt($data, $encrypted, $pubKey);

		echo '<br/>Encrypted data: '.$encrypted; 


		// Decrypt the data using the private key and store the results in $decrypted
		openssl_private_decrypt($encrypted, $decrypted, $privKey);

		echo '<br/>Decrypted data: '.$decrypted; 

		die();