<?php
session_start();
include ("../utils/control.php");
$usuario="";
$control= new control();

$teams="";
$idTeam="";

if($control->checkSecurity()){
	$usuario= $_SESSION['user'];
}
else	   
{
	echo "<script>window.location.replace('../utils/cerrar.php')</script>";
	die;
}

$idTeamActual="";
$archivo_actual = basename($_SERVER['PHP_SELF']);

if(isset($_GET['i']))
	$idTeamActual=$_GET['i'];
 

if(isset($_SESSION['idTeam']))
   $idTeam= $_SESSION['idTeam'];	

if($idTeam==""){
	$idTeam= $control->getTeamsOfUser($usuario)[0]['idTeam'];	
 	$_SESSION['idTeam']=$idTeam;
}

if($idTeamActual=="")
	$idTeamActual=$idTeam;

if($idTeam!=$idTeamActual && $control->isMyTeam($idTeamActual,$usuario)){	
	$_SESSION['idTeam']=$idTeamActual;	
	$idTeam=$idTeamActual;
}

$teams= $control->getTeamsOfUser($usuario);	
$user= $control->getUser($usuario);
$news= $control->getNews($usuario,0,50);
$team="";

$team= new Team(intval($idTeam),false);


$teamActual= new Team($idTeamActual);

$cant= $control->getCantLigasEspera();
$cantHabil=$control->getCantLigasHabiles($idTeam,0);
$ligasFinish= $control->getLigasenEstado(2,$idTeam);
$ligasExec= $control->getLigasenEstado(1,$idTeam);
$ligasEspera= $control->getLigasenEstado(0,$idTeam);
$lastFriend= $control->getLastFriend($idTeam);
$challenge= $control->getChallenge();
$challdone= $control->getChalldone($usuario);
?>



 