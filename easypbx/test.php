<?php
/* error_log("opening stdin\n", 3, "storage/test.log");
$handle = fopen('php://stdin','r');
error_log("getting from stdin\n", 3, "storage/test.log");
$input = fgets($handle);
fclose($handle);
error_log("writing stdout \n", 3, "storage/test.log");

//$handle = fopen('php://stdout','r');
//fwrite($handle, "hello {$input}\n"); 
echo "hello {$input}"; */

$msg = "Easypbx AGI 1.0\r\n".
"Length: 4\r\n".
"path: /sip_user_validate\r\n\r\n".
"{hjhj}\n";

//$data = explode( "\r\n\r\n",$msg);
$headers = strstr($msg,"\r\n\r\n",true);

echo $headers; 
$body = trim(strstr($msg,"\r\n\r\n"));
echo $body;

$line = strtok($headers, "\r\n");
$status_code = trim($line);
$response_headers = [];
// Parse the string, saving it into an array instead
while (($line = strtok("\r\n")) !== false) {
    if(false !== ($matches = explode(':', $line, 2))) {
      $response_headers["{$matches[0]}"] = trim($matches[1]);
    }  
}

print_r($response_headers);

?>