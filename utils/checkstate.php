<?php
session_start();
	require("control.php");
	$control= new control();
	
	if($control->checkSecurity())
	{
	$usuario="";
	if(isset($_POST['idUser']))
		$usuario= $_POST['idUser'];
    $news= $control->getNotifLastSeg($usuario);
	echo json_encode($news);
	}
	else
	  echo "-1";
?>