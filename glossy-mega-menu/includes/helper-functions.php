<?php
/*
 * Functions For Glossymm
 * 
 */



function glossymm_get_view($path, $data = null){
   include  GLOSSYMM_PATH .'views/'. $path . ".php";
}