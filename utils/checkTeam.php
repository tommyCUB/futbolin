<?php 
 session_start(); 
  include ("control.php"); 
  
  $usuario="";
  $usuario= $_SESSION['user'];
  $cable= new control();
  $name="";
  $check="";
  
  if(isset($_POST['c']))
    {
	   $check=$_POST['c'];
	   $datos= $cable->checkName($check);  
	   
	   if($datos)
	    echo "is";
	   else
	     echo "no"; 	
	}
	else	
	if(isset($_POST['n']))
    {  
	   $idTeam="";
	   if(isset($_POST['i']))
	     $idTeam=$_POST['i'];
	   $name=$_POST['n'];
	   
	   $datos= $cable->setName($name,$idTeam,$usuario);  
	   echo $datos;
	   if($datos)
	    echo "si";
	   else
	     echo "no"; 	
	}
   
?>