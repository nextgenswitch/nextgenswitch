<?php
function sanitize_phone( $phone ) {

  $intl = false;
  if(strlen($phone) == 0)
     return $phone;
  $phone = trim($phone); 
  if(substr($phone, 0, 1) == '+'){
    $intl = true;
    $phone = substr($phone, 1);
  }

  $phone = preg_replace('/\D+/', '', $phone);

  if($intl) $phone = '+' . $phone;

  return $phone;

}

echo sanitize_phone('++3 674324(343)@$&');