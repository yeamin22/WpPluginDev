<?php 
function random_id($length){   
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';  
    return substr(str_shuffle($str_result), 0, $length);
}