<?php
session_start();

require("control.php");
$control= new control();

$token="";

if(isset($_POST['token']))
	 $token= $_POST['token'];


echo $control->login($token);

 	
 

