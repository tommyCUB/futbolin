<?php
 include ("control.php");
 $control= new control();
 $idTeam1="";
 $idTeam2="";
 $type="ex";
 $resp="";	
 $idGame="";
 
  if(isset($_POST['type']))
	 {
		$type= $_POST['type']; 
	 }
  if(isset($_POST['idTeam1']))
	 {
		$idTeam1= $_POST['idTeam1']; 
	 }
	 
   if(isset($_POST['idTeam2']))
	 {
		$idTeam2= $_POST['idTeam2'];
	 } 
	
   if(isset($_POST['idGame']))
	 {
		$idGame= $_POST['idGame'];
	 }
    
	if($type=="ex")
		$resp= $control->asynejecutarAmistoso($idTeam1,$idTeam2);
		
	if($type=="que")
		$resp= $control->getGameFriendly($idGame,0);
   
	echo json_encode($resp);
?>