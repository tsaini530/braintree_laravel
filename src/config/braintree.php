<?php

return [
	'environment' => env('BRAINTREE_MODE', 'sandbox'),
	'merchantId' => env('BRAINTREE_MERCHANTID', '') ,
	'publicKey'  => env('BRAINTREE_PUBLICKEY', '') ,
	'privateKey' => env('BRAINTREE_PRIVATEKEY', '') , 
];
?>