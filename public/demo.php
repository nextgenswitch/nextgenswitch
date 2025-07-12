<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, X-Authorization, X-Authorization-Secret');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
    exit;
}


$name = $_POST['name'] ?? '';
$sub_domain = $_POST['sub_domain'] ?? '';
$contact_no = $_POST['contact_no'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$address = $_POST['address'] ?? '';


$errors = [];
if (empty($name)) $errors['name'] = 'The name field is required.';
if (empty($sub_domain)) $errors['domain'] = 'The domain field is required.';
if (empty($email)) $errors['email'] = 'The email field is required.';
if (empty($password)) $errors['password'] = 'The password field is required.';


if (count($errors)) {
    http_response_code(400);
    echo json_encode([
        'status' => false,
        'message' => 'Validation failed.',
        'errors' => $errors
    ]);
    exit;
}

$apiData = [
    'name' => $name,
    'domain' => sprintf("%s.nextgenswitch.com", $sub_domain),
    'contact_no' => $contact_no,
    'email' => $email,
    'password' => $password,
    'address' => $address,
    'expire_date' => date('Y-m-d', strtotime('+1 month')),
    'max_extension' => 10,
    'call_limit' => 10,
];


$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'http://sg.nextgenswitch.com/api/v1/tenant/create',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => http_build_query($apiData),
    CURLOPT_HTTPHEADER => [
        'X-Authorization: NlgtUjh8tuc2fuYWbcRa47Tdtkm1k2wYUhqSkl5gX1vgJMpU30GkisYkn7wXb4ll',
        'X-Authorization-Secret: zTMcHC6vKolSUY9jsmzRO0qc1xFE7g4Pw7pBHksz8S1adUAaEfPLgHBAFU6oEprp',
        'Content-Type: application/x-www-form-urlencoded'
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);


if ($err) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'cURL Error: ' . $err
    ]);
} else {
    echo $response;
}
?>
