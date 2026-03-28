<?php
session_start();
require_once("control.php");
$usuario= $_SESSION['user'];
$idTeam= $_SESSION['idTeam'];

$control= new control();

if(isset($_POST['saveBtn']))
	 {
	   $code=$_POST['myTextArea'];   
	   $control->saveCode($idTeam,$usuario,$code);	  
       echo "done";
	 }

if(isset($_POST['checkBtn'])  && isset($_POST['myTextArea']))
  {
	 $code=$_POST['myTextArea']; 	  
	 $datos= $control->checkCode($usuario,$idTeam,$code);	
	 echo $datos;
	 die();
  }  

 if(isset($_POST['type']))
 {	
	$idCheck= $_POST['idChec'];		
	$chec= $control->getCheck($idCheck);
	
	 echo json_encode($chec);
	 die();
 }
?>