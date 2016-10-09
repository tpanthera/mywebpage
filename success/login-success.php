<?php

function customError($errno, $errstr) {
  echo "There has been some error. Please clear browser cache and relogin.<br/>$errstr";
}

//set error handler
set_error_handler("customError");

echo "The request token received is : ".$_GET["request_token"];

$api_key = "your api_key";
$secret_key = "your secret key";

// Incoming from the redirect.
    $request_token = $_GET["request_token"];

    $checksum = hash("sha256", $api_key . $request_token . $secret_key);
echo "<br/>".$checksum;

$url = 'https://api.kite.trade/session/token';
$data = array('api_key' => $api_key, 'request_token' => $request_token, 'checksum' => $checksum);

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);

$result = file_get_contents($url, false, $context);
if ($result == FALSE) { 
echo "<h1 style='color:red;'>Error while logging in.</h1>";
}else{
echo "<h1>You are logged in succesfully.</h1>";
}

//var_dump($result);
$out = json_decode($result, true);
echo $out['data']['user_name'];
echo $out['data']['access_token'];

?>
<body>
<input type="hidden" id="zerodha_token" value="<?php echo $out['data']['access_token']; ?>">
<input type="hidden" id="zerodha_user" value="<?php echo $out['data']['user_name']; ?>">
</body>