<?php 
class Cable
 {
   private  $host;
   private  $user;
   private  $pass;
   private  $db;

   function Cable()
   {
     $this->host="162.241.72.152";
     $this->user="futbol_rooter";
     $this->pass= "PlanetaEcil**2020";
     $this->db="futbol_futbolin";
   }

   function execute_query($query)
   { 
     $link= mysqli_connect($this->host,$this->user,$this->pass);
	   
     mysqli_select_db($link,$this->db);
     $result= mysqli_query($link,$query);
	   
     mysqli_close($link);	   
     return $result;
   }
   
   function HacerArray($result)
    {
	 $retorno= array();
	 $cant=0;
	 while($row= mysqli_fetch_array($result))  
	     $retorno[$cant++]=$row;     
		 	 
	 return $retorno;	  	 
	}
	
	function HacerArraySimple($result)
    {
	 $retorno= array();
	 $cant=0;
	 while($row= mysqli_fetch_array($result,MYSQL_NUM))  
	     $retorno[$cant++]=$row;     
		 	 
	 return $retorno;	  	 
	}
	
	
 }
 
 ?>
