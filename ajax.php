<?php

function random_string($length_of_string)
{
  
    // String of all alphanumeric character
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
  
    // Shuffle the $str_result and returns substring
    // of specified length
    return substr(str_shuffle($str_result), 
                       0, $length_of_string);
}

$dt = new DateTime();
$dt2 = date_modify($dt, '+2 month +1 day');

$Items = [
  'Item1'=> $dt->format('d-m-Y H:i:s'),
  'Item2'=> $dt2->format('d-m-Y H:i:s')  
];

$response =[
  'Caption' => random_string(10),
  'Items' => $Items
];


echo json_encode($response);
?>