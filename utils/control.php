<?php
  require_once("conexion.php");
  require("config.php");
require("checkFirebase.php");
  
  class control{
	   public $cable;
	   public $ordinales;
	   public $cardinales;
	   public $numeros;
	   public $romanos;
	      
	   function control()
	   {
		   $this->cable= new Cable();   
		   $this->lugares=array('1ero','2do','3ero','4to','5to','6to','7mo','8vo','9no','10mo','11mo','12mo','13mo','14mo','15mo','16mo','17mo','18mo','19mo','20mo','21ero','22do','23ero','24to','25to','26to','27mo','28vo','29no','30mo','31ero','32do');
		   
		     $this->romanos = array("I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII","XIII","XIV","XV","XVI","XVII","XVIII","XIX","XX","XXI","XXII","XXIII","XXIV","XXV","XXVI","XXVII","XXVIII","XXIX","XXX");
			$this->ordinales=array("1st","2nd","3rd" ,"4th" ,"5th"  ,"6th"  ,"7th" ,"8th" ,"9th" ,"10th" ,"11th" ,"12th","13th","14th","15th","16th","17th","18th","19th","20th","21th","22th","23th","24th","25th","26th","27th","28th","29th","30th");
			$this->cardinales=array("First","Second","Third","Fourth","Fifth","Sixth","Seventh","Eighth","Ninth","Tenth","Eleventh","Twelfth");

		   	$this->numeros= array_merge($this->ordinales,$this->romanos,$this->cardinales);
	   }  
	  
	  
	  function nuevaVenta($idVenta,$idUser,$cantCoins,$costo,$desc)
	  {
		  $query="INSERT INTO `venta`(`idVenta`, `idUser`, `coins`, `costo`, `descripcion`,fecha) VALUES ('".$idVenta."','".$idUser."','".$cantCoins."','".$costo."','".$desc."','".time()."')";  		  
		  $this->cable->execute_query($query);
		  
		  $query="delete from venta where idUser='".$idUser."' and fecha - ".time().">60*60 and complete='0' and process='0'";
		  $this->cable->execute_query($query);
	  }
	  
	  function procesarVenta($idVenta,$pago,$idOrden)
	  {
		 $query="select * from venta where idVenta='".$idVenta."' limit 1";
		 $datos=$this->cable->execute_query($query);
		 $datos= mysqli_fetch_array($datos);
		 $query="update venta set process=1, idOrden='".$idOrden."' limit 1";
		 $this->cable->execute_query($query);
		  
		 if($datos['costo']<=$pago)
		 {
			 $query="update venta set complete=1 where idVenta='".$idVenta."' limit 1";
			 $this->cable->execute_query($query);
			 
			 $this->creditar($datos['idUser'],$datos['coins'],$datos['descripcion']);
			 
			 return true;
		 }
		 
		 return false;
	  }
	  
	  function getBasic()
	  {
		  $query="select * from basicliga where 1";
		  $datos= $this->cable->execute_query($query);
		  
		  return $this->cable->HacerArray($datos);
	  }
	  function crearBasic($clase,$ciclo,$entry,$cantTeams,$tipo,$premio)
	  {
		  $query="INSERT INTO `basicliga`(`clase`, `ciclo`, `orden`, `entry`, `startCiclo`, `cantTeams`, `tipo`,`premio`) VALUES ('".$clase."','".$ciclo."','0','".$entry."','".time()."','".$cantTeams."','".$tipo."','".$premio."')";		  
		  $this->cable->execute_query($query);
	  }
	  
	  function borrarNews($usuario,$tipo)
	  {
		  $query="delete from news where idUser='".$usuario."' and tipo='".$tipo."'";
		  
		  if($tipo==0)
			$query="delete from news where idUser='".$usuario."'";  
		  
		  $this->cable->execute_query($query);
	  }
	  
	  function calcularMedia($orden)
	 {
		 $query= "select avg(".$orden.") as media from team";
		  
		 if($orden=="frec")
			$query= "select avg(segundo/golFavor) as media from team where golFavor>0" ;
		  
		 $datos= $this->cable->execute_query($query);
		 $datos= mysqli_fetch_array($datos);
		 
		 return number_format($datos['media'],2);
	 }
	
	  
	  function getCantTeams()
	  {
		  $query="select count(*) as cant from team";
		  $datos=$this->cable->execute_query($query);
		  $datos= mysqli_fetch_array($datos);
		  
		  return $datos['cant']; 
	  }
	  
	  function getTeamsBy($page,$orderBy,$dir,$cant)
	  {
		  $query= "select *,segundo/golFavor as frec from team order by ".$orderBy."  ".$dir." limit ".($page*$cant).",".$cant;	
		  
		  if($orderBy=="frec")
		     $query= "select *,segundo/golFavor as frec from team where golFavor>0 order by ".$orderBy."  ".$dir." limit ".($page*$cant).",".$cant;	
		     
		  if($orderBy=="segundo")
		     $query= "select *,segundo/golFavor as frec from team where segundo>0 order by ".$orderBy."  ".$dir." limit ".($page*$cant).",".$cant;	
		  
		  
		  $datos= $this->cable->execute_query($query);
		  
		  if(!empty($datos))
			  return $this->cable->HacerArray($datos);
		  
		  return false;
	  }
	  
	  function crearLigaSegunBasic($idBasic,$idOwner)
	  {
		  
		  $query="select * from basicliga where idBasicLiga='".$idBasic."' limit 1";
		  $datos= $this->cable->execute_query($query);
		  $datos= mysqli_fetch_array($datos);
		  $orden= $datos['orden'];
		   
		  $nombre= $this->numeros[$orden]." ".$datos['clase']." ".date('M Y');
		  
		  try{
		  	$idLiga=$this->crearLiga($nombre,$datos['tipo'],$datos['cantTeams'],$datos['entry']+rand(0,100),$datos['premio']+rand(0,500),$idOwner,1,-1,$idBasic);	  
		  }
		  catch(Exception $e)
		  {}  
		  
		  
		  $query="update basicliga set orden='".($orden+1)."' where idBasicLiga='".$idBasic."' limit 1";		  
		  $this->cable->execute_query($query);
		  
		  $cant= intval($datos['cantTeams']/4);
		 
		  
		  $query="select idTeam,team.elo as elo from team inner join gente where team.iduser=gente.idUser and gente.permisos='0' order by elo DESC";
		  $eqs= $this->cable->execute_query($query);
		  $eqs= $this->cable->HacerArray($eqs);
		  
		  $total= count($eqs);
		  
		  $clase= explode(' ',$datos['clase'])[0];
		  $range=["Clasic"=>0,"Super"=>1,"Master"=>2,"Champion"=>3,"Golden"=>4,"Elite"=>5,"Premium"=>6,"Platinum"=>7,"Mundial"=>8,"Prestige"=>9];
		  
		  $pos= $range[$clase];
		  
		  $min= (9-$pos)*intval($total/10);
		  $max= $min + intval($total/10)-1; 
		  $ids=array();	   		  
		  while(count($ids)<$cant)
		  {
			  $num= rand($min,$max);			  
			  if(!in_array($eqs[$num]['idTeam'],$ids))			  
				  array_push($ids,$eqs[$num]['idTeam']);		 
		  }	 
		  
		  foreach($ids as $id)	
		  {
			  try{
			  	$this->addTeamToLeague($idLiga,1,$id,0);
			  }
			  catch(Exception $e)
			  {
				  
			  }
		  }
	  }
	  
	  function checkToken($token)
	  {
		 $verified_array = verify_firebase_token($token);
		  		  
		 if(!empty($verified_array) && $verified_array['state']==1)
				   return 1;		  
		 return 0;
	  }
	  
	  
	  function checkName($name)
	  {
	      $query="select count(idTeam) as cant from team where nombre='".$name."'";
	      $datos= $this->cable->execute_query($query);
	      $datos= mysqli_fetch_array($datos);
	      
	      return $datos['cant']==0;
	  }
	  
	  
	  function checkSecurity()
	  {		  
		  if(isset($_SESSION['user']) && isset($_SESSION['token']))
			{					    
			   	if($this->checkToken($_SESSION['token'])==1)
					return true;
		    }
		  
		  return false;
	  }
	  
	  function getGameLiga($idGame)
	  {
		 $query="select idGame, idTeam1,idTeam2,fechaDone,gol1,gol2,winner,dirGame,idLiga from game where idGame='".$idGame."' limit 1";
		   
		  $rs= $this->cable->execute_query($query);
		  $rs= mysqli_fetch_array($rs);
		  
		  $dirGame= $rs['dirGame'];
		  
		  if(is_file("/home/futbol/public_html/games/".$dirGame))
		  {			   
			  $this->extraerArchivo("/home/futbol/public_html/games/".$dirGame,"/home/futbol/public_html/games/");
			  $dirGame="/home/futbol/public_html/games/games/".$idGame.".fer";			  
			  if(is_file($dirGame))
			  {				  
				  $handle= fopen($dirGame,'r');
				  $buffer= fread($handle,filesize($dirGame));  
				  fclose($handle);
				  $rs['game']=$buffer;
				  unlink($dirGame);
			  }
		  }		  
		  return $rs; 
	  } 
	  
	  function getNotifLastSeg($iduser)
	  {
		  $tiempo= time();
		  $query= "select * from news where idUser='".$iduser."' and (".$tiempo."-fecha)<=2 and leida='0'";		  
		  $datos= $this->cable->execute_query($query);
		  $datos= $this->cable->HacerArray($datos);
		  
		  for($i=0;$i<count($datos);$i++){
			  $datos[$i]['msg']=$this->procesarMensaje($datos[$i]['msg']);
			  $query="update news set leida=1 where idNews='".$datos[$i]['idNews']."' limit 1";
			  $this->cable->execute_query($query);
		  }
			  
		  return $datos;
	  }
	  function getTodosTeam()
	  {
		  $query="select idTeam from team";
		  $datos= $this->cable->execute_query($query);
		  $datos=$this->cable->HacerArray($datos);
		  
		  return $datos;
	  }
	  
	  function getTeamPera($elo,$idTeam)
	  {
		  $query="select idTeam,nombre,elo from team where elo>'".$elo."' and idTeam!='".$idTeam."' order by elo ASC limit 20";
		  $datos= $this->cable->execute_query($query);
		  $datos= $this->cable->HacerArray($datos);
		  
		  
		  if(count($datos)<20)
		  {
			 $query="select DISTINCT(idTeam),nombre,elo from team where elo<='".$elo."' and idTeam!='".$idTeam."' order by elo DESC limit ".(20-count($datos));
			   
			  $datos2= $this->cable->execute_query($query);
		 	  $datos2= $this->cable->HacerArray($datos2); 
			  $datos= array_merge($datos2,$datos);
		  }
		  
		  return $datos;		  
	  }
	  
	  function getTotalTeams()
	  {
		  $query="select count(*) as cant from team";
		  $datos= $this->cable->execute_query($query);
		  $datos= mysqli_fetch_array($datos);
		  
		  return $datos['cant'];
	  }
	  
	  function getChallenge()
	  {
		$query="select* from challenge";
		$datos= $this->cable->execute_query($query);
		$datos= $this->cable->HacerArray($datos);
		return $datos;  
	  }
	  
	  function getChalldone($idUser)
	  {
		$query="select * from challdone where idUser='".$idUser."' limit 1";		
		$datos= $this->cable->execute_query($query);
		  
		$datos= mysqli_fetch_array($datos);
		return $datos;  
	  }
	  function getResultFriend($idTeam,$tiempo)
	  {
		  
	  }
	  function getGameFriendly($idGame,$debug)
	  {
		  $query="select idFriendly, idTeam1,idTeam2,fecha,gol1,gol2,winner,dirGame,done,executed from friendly where idFriendly='".$idGame."' limit 1";		  
		  $rs= $this->cable->execute_query($query);
		  $rs= mysqli_fetch_array($rs);
		  
		  $dirGame= $rs['dirGame'];
		  $name=explode(".",$dirGame)[0];
		  
		  if(is_file("/home/futbol/public_html/friendly/".$dirGame))
		  {			   
			  $this->extraerArchivo("/home/futbol/public_html/friendly/".$dirGame,"/home/futbol/public_html/friendly/");
			  $dirGame="/home/futbol/public_html/friendly/friendly/".$name.".fer";			  
			  if(is_file($dirGame))
			  {				  
				  $handle= fopen($dirGame,'r');
				  $buffer= fread($handle,filesize($dirGame));  
				  fclose($handle);
				  $rs['game']=$buffer;
				  unlink($dirGame);
				  unlink("/home/futbol/public_html/friendly/friendly/".$name.".txt");
			  }
		  }
		  
		  if($debug==1)
		  {
		  $dirGame =$rs['dirGame'];
		  
		   if(is_file("/home/futbol/public_html/debug/".$dirGame))
		  {			   
			  $this->extraerArchivo("/home/futbol/public_html/debug/".$dirGame,"/home/futbol/public_html/debug/");
			  $dirGame="/home/futbol/public_html/debug/debug/".$name.".fer.debug";			  
			  if(is_file($dirGame))
			  {				  
				  $handle= fopen($dirGame,'r');
				  $buffer= fread($handle,filesize($dirGame));  
				  fclose($handle);
				  $rs['debug']=$buffer;
				  unlink($dirGame);
			  }
		  }
		  }  
		  return $rs;
	  }
	  
	  function checkNombreLiga($nombre)
	  {
		  $query="select count(*) as cant from liga where nombre='".$nombre."' limit 1";
		  $datos= $this->cable->execute_query($query);
		  $datos= mysqli_fetch_array($datos);
		  
		  if($datos['cant']==0)
			  return false;
		  
		  return true;
	  }
	  
	  function comparaCdn($cdn1,$cdn2)
	  {
		  if( abs(count($cdn1)-count(cdn2)>=2))
			 return false;
			 
		  for($i=0;$i<count($cdn1);$i++)
			 {
			  if(is_numeric($cdn1[$i]))
				  return true;
			  if($cdn1[$i]!=$cdn2[$i])
				  return false;
			 }
			 
		  return true;
	  }
	  
	  function cambiarTshirt($idTeam,$choice,$tshirt)
	  {
		  $team=new Team($idTeam);
		  $query="";
		  if($team->tshirt==$choice)
			  $query="update team set tshirt='".$tshirt."' where idTeam='".$idTeam."' limit 1";
		  else
			  $query="update team set tshirt2='".$tshirt."' where idTeam='".$idTeam."' limit 1";
		  
		  
		  $this->cable->execute_query($query);
	  }
	  
	  function setEscudo($idTeam,$escudo)
	  {
		  $query= "update team set escudo='".$escudo."' where idTeam='".$idTeam."' limit 1";
		  $this->cable->execute_query($query);
	  }
	  
	  function getListadoEscudos()
	  {
		    $directorio = opendir("../images/escudos/"); //ruta actual
		    $retorno=array();
			while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
			{
				if (!is_file($archivo))//verificamos si es o no un directorio				
				{
					$info = new SplFileInfo($archivo);
					$ext= $info->getExtension();
					if($ext=="jpg" || $ext=='JPG' || $ext=='png' ||$ext=='PNG' || $ext=='jpeg')
						array_push($retorno, $archivo);
				}
			} 
		  
		  return $retorno;
	  }
	  
	  function getListadoThemas()
	  {
		    $directorio = opendir("../css/theme/"); //ruta actual
		    $retorno=array();
			while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
			{
				if (!is_file($archivo))//verificamos si es o no un directorio				
				{
					$info = new SplFileInfo($archivo);
					$ext= $info->getExtension();
					if($ext=="css")
						array_push($retorno, $archivo);
				}
			} 
		  
		  return $retorno;
	  }
	  
	  function getListadoModes()
	  {
		    $directorio = opendir("../js/mode/"); //ruta actual
		    $retorno=array();
			while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
			{
				if (!is_file($archivo))//verificamos si es o no un directorio				
				{
					$info = new SplFileInfo($archivo);
					$ext= $info->getExtension();
					if($ext=="js")
						array_push($retorno, $archivo);
				}
			} 
		  
		  return $retorno;
	  }
	  
	  function getLigaPorNombre($nombre)
	  {		   
		  $query="select idTeam from team where LCASE(nombre) like '%".strtolower($nombre)."%' limit 50";		  
		  $datos= $this->cable->execute_query($query);
		  $datos= $this->cable->HacerArray($datos);

		   $retorno=array();

		   foreach($datos as $ids)
		   {
			   if($ids['idTeam']!=""){
			   $t= new Team($ids['idTeam']);			   
			   array_push($retorno,$t);
			   }
		   }

		   return $retorno; 
	  }
	  
	  function getListadoTshirt()
	  {
		    $directorio = opendir("../images/tshirt/"); //ruta actual
		    $retorno=array();
			while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
			{
				if (!is_file($archivo))//verificamos si es o no un directorio				
				{
					$info = new SplFileInfo($archivo);
					$ext= $info->getExtension();
					if($ext=='png' ||$ext=='PNG'){
					    $archivo=str_replace(['0','1','2','3','4','5','6','7','8','9','10','11'],'',$archivo);
					    if(!in_array($archivo,$retorno))
						    array_push($retorno, $archivo);
					}
				}
			} 
			
			 
		  
		  return $retorno;
	  }
	  
	  function getCuentasUser($user,$pag,$cant,$type)
	  {
		  $query="";
		  if($type==0)
		  $query="select* from voucher where idUser='".$user."' order by fecha DESC limit ".($pag*$cant).",".$cant;
		  
		  if($type==1)
		  $query="select* from voucher where idUser='".$user."' and credito!=0 and debito=0 order by fecha DESC limit ".($pag*$cant).",".$cant;
		  
		  if($type==2)
		  $query="select* from voucher where idUser='".$user."' and debito!=0 and credito=0 order by fecha DESC limit ".($pag*$cant).",".$cant;	  
		  $datos= $this->cable->execute_query($query);
		  $datos= $this->cable->HacerArray($datos);
		  return $datos;
	  }
	  
	  function getCantCuentasTipo($user,$tipo)
	  {
		  if($tipo==1)
			  $query="select count(*) as cant from voucher where idUser='".$user."' and credito!=0 and debito=0";
		  if($tipo==2)
			  $query="select count(*) as cant from voucher where idUser='".$user."' and debito!=0 and credito=0";
		  
		   $datos= $this->cable->execute_query($query);
		   $datos= mysqli_fetch_array($datos);
		  
		  return $datos['cant'];
	  }
	  
	  function getEstadoCuenta($user)
	  {
		  $query= "select sum(debito) as debito , sum(credito) as credito, (credito-debito) as total, count(*) as cant, count(debito!=0) as cantDebitos from voucher where idUser='".$user."'";
		   
		  $datos= $this->cable->execute_query($query);
		  $datos= mysqli_fetch_array($datos);
		  return $datos;
	  }
	  
	  function getFriendGame($idTeam,$cant)
	  {
		  
		  $query= "select DISTINCT idFriendly,idTeam1,idTeam2,gol1,gol2,distie,winner,executed from friendly where (idTeam1='".$idTeam."' or idTeam2='".$idTeam."') and done='1' order by executed DESC limit ".$cant;
		  $datos= $this->cable->execute_query($query);
		  $datos= $this->cable->HacerArray($datos);
		  
		  return $datos;
			  
	  }
	  
	  function getGamesdeLiga($idTeam,$cant)
	  {
		  
		  $query= "select DISTINCT fechaDone, idGame,idTeam1,idTeam2,gol1,gol2,distie,winner,idLiga from game where (idTeam1='".$idTeam."' or idTeam2='".$idTeam."') and done='1' order by fechaDone DESC limit ".$cant;  
		  $datos= $this->cable->execute_query($query);
		  $datos= $this->cable->HacerArray($datos);
		  
		  return $datos;
			  
	  }
	  function getRanking($inicio,$fin)
	  {	
		$retorno=array();  
		$query="select idTeam,nombre,elo,escudo,@rownum:=@rownum+1 AS ranking from (SELECT @rownum:=0) r, team order by elo DESC limit ".$inicio.",".($fin-$inicio);
		
		$datos= $this->cable->execute_query($query);
		
		$datos= $this->cable->HacerArray($datos);
		for($i=0;$i<count($datos);$i++)
		   $datos[$i]['ranking']=$datos[$i]['ranking']+$inicio;
		return $datos;
	  }
	  
	  function getLastFriend($idTeam)
	  {
		  $query="select * from friendly where idTeam1='".$idTeam."' order by fecha DESC limit 1";
		  $datos= $this->cable->execute_query($query);
		  $datos= mysqli_fetch_array($datos);
		  return $datos;
	  }
	  function getListaGames($idTeam1,$idTeam2,$type,$cant)
	  {
		  $query="select DISTINCT idFriendly as id,idTeam1,idTeam2, fecha, gol1,gol2, winner from friendly where idTeam1='".$idTeam1."' or idTeam2='".$idTeam1."' or idTeam1='".$idTeam2."' or idTeam2='".$idTeam2."' order by fecha DESC  limit ".$cant/2;
		  
		  $uno=$this->cable->execute_query($query);
		  $uno= $this->cable->HacerArray($uno);
		   
		  
		  for($i=0;$i<count($uno);$i++)
			  $uno[$i]["type"]="f";		  
		  $cant=intval($cant/2 + $cant/2-count($uno));
		  $query="select DISTINCT idGame as id,idTeam1,idTeam2, fechaDone as fecha, gol1,gol2, winner from game where idTeam1='".$idTeam1."' or idTeam2='".$idTeam1."' or idTeam1='".$idTeam2."' or idTeam2='".$idTeam2."' order by fechaDone DESC limit ".$cant;
		  $tres=$this->cable->execute_query($query);
		  $tres= $this->cable->HacerArray($tres);
		   
		  
		  for($i=0;$i<count($tres);$i++)
			  $tres[$i]["type"]="l";
		  		   	  
		  $rt= array_merge($uno,$tres);
		  				  
		  sort($rt);
		  
		  return $rt;
	  }
	  
	  function abrNombreTeam($nombre)
	  {
		  $espacios= explode(' ',$nombre);
	  }
	   function extraerArchivo($ruta,$destino)
	   {
			$zip = new ZipArchive();
			$zip->open($ruta, ZipArchive::CREATE);		    
			$zip->extractTo($destino);
			$zip->close();
	   }
	  
	  function completarReto($codigo,$idUser)
	  {
		  $challdone= $this->getChalldone($idUser);
		  
		  $query= "update challdone set ";
	  }
		  
	  function setTactica($id,$initial,$forces,$players)
	  {
		  $query="update tactic set initial='".$initial."',forces='".$forces."', players='".$players."' where idTeam='".$id."'";
		  		  
		  try{
			  $this->cable->execute_query($query);
			  
		  }
		  catch(Exception $e){
			  return false; 
		  }
		  
		  return true;		  
	  }
	  
	  function getMisLigas($idUser,$estado)
	  {
		  $query="select * from liga where idOwner='".$idUser."' and state='".$estado."'";
		  $datos= $this->cable->execute_query($query);
		  
		  return mysqli_fetch_array($datos); 
	  }
	  
	  function login($token)
	  {
		$verified_array = verify_firebase_token($token);
		  
		$usuario=-1;
		$idTeam="";
		 
		if(!empty($verified_array) && isset($verified_array['state']) && $verified_array['state']==1)
		{
		  $email= $verified_array['email'];
		  $nombre= $verified_array['name'];
		  $origen= $verified_array['provider'];	
		  $urlPhoto= $verified_array['picture'];		
			
		   if(!$this->checkMail($email))
		   {
			  $query="insert into gente (coins,email,born,nombre,origen) values(0,'".$email."','".time()."','".$nombre."','".$origen."')"; 
			  
			  $this->cable->execute_query($query);	  
			   
			  $usuario= $usuario=$this->getUserbyEmail($email)['idUser'];			  
			  
			  $query="insert into challdone (idUser) values('".$usuario."')"; 			  
			  $this->cable->execute_query($query);	

			  $query="select max(idTeam) from team";		  
			  $datos= $this->cable->HacerArray($this->cable->execute_query($query));
			  $idTeam= $datos[0][0] +1;
			  $nom= explode("@",$email)[0];
			  $this->addEquipo($usuario,$nom." FC");
			  $this->notificar($usuario,'Bienvenido a Futbolines. Has recibido 500 coins por registrarse. Que tenga buen juego. Suerte!!',1);	
			  $this->creditar($usuario,500,"Bienvenido a FutbolinCode.com"); 
			  
			  $equip=$this->getSystemTeams(50);
			  
			  $r= rand(0,count($equip)-1);
			  
			  $this->ejecutarAmistoso($this->getTeamsOfUser($usuario)[0],$equip[$r]['idTeam']);
			  
		   }
			
			 $user=$this->getUserbyEmail($email);
			  $usuario=$user['idUser'];
			  $this->nuevaConexion($usuario);
			  if($idTeam=="")
				  $idTeam=$this->getTeamsOfUser($usuario)[0]['idTeam'];
			  $_SESSION['user']=$usuario;
			  $_SESSION['idTeam']=$idTeam;		  
			  $_SESSION['nombre']=$nombre;
			  $_SESSION['token']=$token;
			  $_SESSION['urlPhoto']=$urlPhoto;
			  $_SESSION['lang']=$user['lang'];
		}	
		return $usuario;
	  }
	  
	  function getSystemTeams($cant)
	  {
	      $query="select * from team where iduser=1 order by elo DESC limit ".$cant;
	      $datos= $this->cable->execute_query($query);
	      $datos= $this->cable->HacerArray($datos);
	      
	      return $datos;
	  }
	 
	  
	  function registerUser($email,$nombre,$origen,$urlPhoto,$token)
	  {	  
		  $usuario= -1;
		  $idTeam="";
		  
		  if($this->checkToken($token))
		  {		  		
		  if(!$this->checkMail($email))
		  {			  		  
			  $query="insert into gente (coins,email,born,nombre,origen) values(0,'".$email."','".time()."','".$nombre."','".$origen."')"; 
			  
			  $this->cable->execute_query($query);	  
			   
			  $usuario= $usuario=$this->getUserbyEmail($email)['idUser'];			  
			  
			  $query="insert into challdone (idUser) values('".$usuario."')"; 			  
			  $this->cable->execute_query($query);	

			  $query="select max(idTeam) from team";		  
			  $datos= $this->cable->HacerArray($this->cable->execute_query($query));
			  $idTeam= $datos[0][0] +1;

			  $this->addEquipo($usuario,"");
			  
			  $this->notificar($usuario,'Bienvenido a Futbolines. Has recibido 500 coins por registrarse. Que tenga buen juego. Suerte!!',1);	
			  $this->creditar($usuario,500,"Bienvenido a FutbolinCode.com");
		  }
			  
		  $usuario=$this->getUserbyEmail($email)['idUser'];
		  $this->nuevaConexion($usuario);
		  if($idTeam=="")
			  $idTeam=$this->getTeamsOfUser($usuario)[0]['idTeam'];
		  $_SESSION['user']=$usuario;
		  $_SESSION['idTeam']=$idTeam;		   
		  $imagen = file_get_contents($urlPhoto); // guardamos la imagen en la variable
		  file_put_contents('../images/usersPhoto/'.$usuario.'.jpg',$imagen); // guardamos la imagen con nombre: imagen_copiada.jpg
		   
		  $_SESSION['nombre']=$nombre;
		  $_SESSION['token']=$token;
		  }

		  return $usuario;
	  }
	   
	  
	 

	  function getUserbyEmail($email)
	  {
		  $query="select idUser,email,coins,last,born,lang from gente where email='".$email."' limit 1";
		  $datos= $this->cable->execute_query($query);
		  
		  return mysqli_fetch_array($datos); 
	  }
	  function notificar($usuario,$mensaje,$tipo)
	  {		 
		 $msg= htmlentities(addslashes($mensaje)); 
		 $query="insert into news (msg,idUser,fecha,tipo) values('".$msg."','".$usuario."','".time()."','".$tipo."')";  
		 $this->cable->execute_query($query); 	 
	  }
	  
	   function nuevaConexion($usuario)
	   {	
		 
		  $query="update gente set last='".time()."' where iduser='".$usuario."' limit 1";
		  $this->cable->execute_query($query);
	   }    
	   
	  
	   function getRangoElo($min,$max)
	   {
		   $query="select idTeam from team where elo>'".$min."' and elo<'".$max."' limit 50";
		   $datos= $this->cable->execute_query($query);
		   $datos= $this->cable->HacerArray($datos);
		   
		   $retorno=array();
		   
		   foreach($datos as $ids)
		   {
			   if($ids['idTeam']!=""){
			   $t= new Team($ids['idTeam']);			   
			   array_push($retorno,$t);
			   }
		   }
		   
		   return $retorno;  
	   }
	   function getTeamsLiga($idLiga)
	   {
		   $liga= $this->getLiga($idLiga);
		   $retorno=array();
		   $teams= explode(";",$liga['teams']);
		   foreach($teams as $ids)
		   {
			   if($ids!=""){
			   $t= new Team($ids);			   
			   array_push($retorno,$t);
			   }
		   }
		   
		   return $retorno;
	   }
	   
	   function getTeam($idTeam) 
	   {
		   $query="select nombre, elo, win, lost, tie, golFavor, golContra,segundo,idTeam,escudo from team where idTeam='".$idTeam."' limit 1";  		   
		   $datos= $this->cable->execute_query($query);
		    
		   if($datos)
		      return mysqli_fetch_array($datos);
			  
		   return NULL;   
	    } 
	 
	  
	   function getTeamsOfUser($idUser)
	   {
		  $query="select idTeam,nombre,elo,win,lost from team where iduser='".$idUser."'";
		  $datos =$this->cable->execute_query($query);		  
		  $datos= $this->cable->HacerArray($datos);			 
		  return $datos;   
	   }
	  
	  function getConfigAleatoria()
	  {
		  $query="select * from config";
		  $datos= $this->cable->execute_query($query);
		  $datos= $this->cable->HacerArray($datos);
		  
		  $num=rand(0,count($datos)-1);
		  
		  return $this->getConfig($datos[$num]['idConfig']);
	  }
	  
	  function addEquipo($idUser,$nombre)
	  {
		 $config= $this->getConfigAleatoria();
		 $idTeam=0;
		 $query= "select max(idTeam) as idTeam from team";
		 $datos= $this->cable->execute_query($query);
		 $datos= mysqli_fetch_array($datos);
		 $idTeam = $datos['idTeam']+1;
		  
		 $query= "insert into team (idTeam,iduser,nombre,escudo,tshirt,tshirt2) values('".$idTeam."','".$idUser."','".$nombre."','".$config['escudo']."','".$config['tshirt']."','".$config['tshirt2']."')";
		  $this->cable->execute_query($query);
		  $query= "insert into tactic (idTeam, forces, initial, players, dirCode) values('".$idTeam."','".$config['forces']."','".$config['initial']."','".$config['players']."','".$idTeam.".fer')";		  
		  $this->cable->execute_query($query);
		  
		   $dirGame= $config['dir'];		  
		   $handle= fopen("../codes/".$idTeam.".fer",'w');
		   fwrite($handle,$config['code']);
		   fclose($handle);			 
		  }		  
	   	
	  
	  function asynejecutarAmistoso($id1,$id2)
	  {
		  $query = "SELECT MAX(idFriendly) AS id FROM friendly";
		  $id=0;
		  $owner = new Team($id1,false);
		  $rs=$this->cable->execute_query($query);
		 
		  if ($row = mysqli_fetch_array($rs))  
		    $id = $row['id'];			   
 
		  $id= $id+1;
		  $query="insert into friendly (idFriendly,idTeam1,idTeam2,fecha,done,gol1,gol2,distie,winner)
		  		  values ('".($id)."','".$id1."','".$id2."','".time()."','0','0','0','0','0')	
		  ";
		 
		  $this->cable->execute_query($query)	;	  
		  $cdn= $id+","+1;
		  
		  exec("python /home/futbol/public_html/core/prueba.py ".$id." > /dev/null 2>/dev/null &");
		  
		  
		  
		  $msg= "Organizar partido amistoso contra #".$id2."#,. Ver partido %".$id.",f%";
		  $this->debitar($owner->iduser,10,$msg);
		  
		  return $id;
	  }
	  
	  
	  function ejecutarAmistoso($id1,$id2)
	  {
		  $query = "SELECT MAX(idFriendly) AS id FROM friendly";
		  $id=0;
		  $rs=$this->cable->execute_query($query);
		 
		  if ($row = mysqli_fetch_array($rs))  
		    $id = $row['id'];			   
 
		  $id= $id+1;
		  $query="insert into friendly (idFriendly,idTeam1,idTeam2,fecha,done,gol1,gol2,distie,winner)
		  		  values ('".($id)."','".$id1."','".$id2."','".time()."','0','0','0','0','0')	
		  ";
		
		  $this->cable->execute_query($query);			  
		  $cdn= $id+","+1;
		  exec("python /home/futbol/public_html/core/execGameFriend.py ".$id." > /dev/null 2>/dev/null &");	  
		  $done=0;
		  $rs=null;	
		  $owner = new Team($id1,false);
		  
		  $msg= "Partido amistoso contra #".$id2."#,. Ver partido %".$rs['idFriendly'].",f%";	
		  $this->debitar($owner->iduser,10,$msg);
		  
		  do{
			  sleep(0.15);
			  $query="select idFriendly,idTeam1,idTeam2,fecha,done,gol1,gol2,distie,winner,executed from friendly where idFriendly='".$id."' limit 1";
			  $rs= $this->cable->execute_query($query);			  
			  $rs= mysqli_fetch_array($rs);		  
			  $done= $rs['done'];			  
		  }
		  while($done!=1);		  
		  			  
		  $t1= new Team($id1);
		  $t2= new Team($id2);
		  		  	  
		   
		   
		  if($rs['winner']==$id1)
		  {					   
			  $msg="Su equipo #".$t1->idTeam."'# , ganó un partido amistoso ".$rs['gol1']."-".$rs['gol2']." contra #".$id2."#. Ver partido %".$rs['idFriendly'].",f%";
			  $this->notificar($t1->iduser,$msg,2);			  
			  
			  $msg="Su equipo #".$t2->idTeam."# , perdió un partido amistoso ".$rs['gol2']."-".$rs['gol1']." contra #".$id1."#. Ver partido %".$rs['idFriendly'].",f%";
			   
			  $this->notificar($t2->iduser,$msg,3);
			   
			  $msg= "Ganador de partido amistoso contra #".$t2->idTeam."'#, resultado ( ".$rs['gol1']." - ".$rs['gol2']." ). Ver partido %".$rs['idFriendly'].",f%";
			  			   
			  $this->creditar($id1,15,$msg); 			  
		  }
		  
		  if($rs['winner']==$id2)
		  {			  
			  $msg="Su equipo #".$t2->idTeam."'# , ganó un partido amistoso ".$rs['gol2']."-".$rs['gol1']." contra #".$id1."#. Ver partido %".$rs['idFriendly'].",f%";
			   
			  $this->notificar($t2->iduser,$msg,2);			  
			  
			  $msg="Su equipo #".$t1->idTeam."'# , perdió un partido amistoso ".$rs['gol1']."-".$rs['gol2']." contra #".$id2."#. Ver partido %".$rs['idFriendly'].",f%";
			   
			  $this->notificar($t1->iduser,$msg,3);			  
		  }	 
		  
		  if($rs)
			  return $rs;
		  
		  return null;
	  }
	 
	   
	   function checkMail($email)
	   {
		  $query = "select count(email) as cant from gente where email='".$email."' limit 1";		  
		  $datos= $this->cable->HacerArray($this->cable->execute_query($query));		   
		  return ($datos[0]['cant']==1);
		       
	   }
	   
	    
	  
	   
	   function isMyTeam($idTeam,$usuario)
	    {
			 $query= "select idTeam from team where idTeam='".$idTeam."' and iduser='".$usuario."' limit 1";
			 $datos= $this->cable->execute_query($query);			 
			 $datos= mysqli_fetch_array($datos);		 
		   
			 if(!empty($datos) && $datos['idTeam']==$idTeam)
			    return true;
			    
			 return false;
		}
	  

		function getCantPagGames($idteam)
		{
		   $query="select count(idGame) from game where idTeam1='".$idteam."' or idTeam2='".$idteam."'";
		   $datos= $this->cable->execute_query($query);
		   if($datos){
		   	   $datos= mysqli_fetch_row($datos);
			   return $datos[0][0]/15 + 1;
		   }
		   return 0;
		}
		
		function listaLigas($idTeam)
		{
		     $query="select idLeagueTCT,nombre from leaguetct where teams like '%".$idTeam.",' or '".$idTeam.",%' or '%,".$idTeam."'";	 
			 $datos=$this->cable->execute_query($query);
			 if($datos)
			  return $this->cable->HacerArray($datos);
			 
			 return NULL;
		}	
	
		
		function getPlanLeague($idLiga)
		{
		    $query="select idTeam1,idTeam2,fecha from plan where idLiga='".$idLiga."' order by fecha ASC";
			$datos= $this->cable->execute_query($query);
			
			if($datos)
			  return $this->cable->HacerArray($datos);
			
			return NULL;	
		}
	  
	   function getCantLigasEspera()
	   {
		   $query= "select count(idLiga) as cant from liga where state='0'";
		   $datos= $this->cable->execute_query($query);
		   $datos= mysqli_fetch_array($datos);
		   
		   return $datos['cant'];
	   }
		function getLigasenEstado($estado,$idTeam)
		{
			$query="select idLiga,nombre, entry, prize, cantTeams, teams, tipo, ordenFinal,idOwner from liga where state='".$estado."' and (teams like '%;".$idTeam.";%' or teams like '".$idTeam.";%') order by fechaDone ASC limit 50";
			
			$datos= $this->cable->execute_query($query);
			
			if($datos)
				return $this->cable->HacerArray($datos);
			return NULL;
		}
	  
	   function getCantLigasHabiles($idTeam,$estado)
	   {
		   $query="select count(idLiga) as cant from liga where state='".$estado."' and not ( teams like '%;".$idTeam.";%' or teams like '".$idTeam.";%')";
		   $datos= $this->cable->execute_query($query);
		   $datos= mysqli_fetch_array($datos);
		   return $datos['cant'];		   
	   }
	   function getLigasHabiles($estado,$idTeam,$indice,$direc,$page)
		{		   
		    
			$query="select idLiga,nombre, entry, prize, cantTeams, teams, tipo, cantTeams - ROUND ( ( LENGTH(teams) - LENGTH( REPLACE ( teams, ';', '') ) ) / LENGTH(';') ) AS plazas
			from liga where state='".$estado."' and not (teams like '%;".$idTeam.";%' or teams like '".$idTeam.";%') order by ".$indice." ".$direc." limit ".($page*20).",20";
 
			$datos= $this->cable->execute_query($query);
			
			if($datos)
				return $this->cable->HacerArray($datos);
			return NULL;
		}
		
	   function getLiga($idLiga)
		{
					   
			$query= "select idLiga,tipo,nombre, state, entry, prize, teams, cantTeams, ordenFinal,fechaInicio,fechaDone,idOwner,idBasicLiga from liga where idLiga='".$idLiga."' limit 1";
			$datos= $this->cable->execute_query($query);
			
			return mysqli_fetch_array($datos);		 		  
		}
	  
	   function getLigaTCT($idLiga)
	   {
		  $query= "select idLiga,tablaPos from ligatct where idLiga='".$idLiga."' limit 1";
			$datos= $this->cable->execute_query($query);
			$datos= mysqli_fetch_array($datos); 
			
			return $datos; 
	   }
	  function getLigaGrupos($idLiga)
	   {
		  $query= "select idLiga,grupos,octavos,cuartos,semi,final,etapa from ligagroup where idLiga='".$idLiga."' limit 1";
		  
		  $datos= $this->cable->execute_query($query);
		  $datos= mysqli_fetch_array($datos);			
		  return $datos; 
	   } 
	  
	  function creditar($idUser,$cantidad,$asunto)
	   {		 
			  $usuario= $this->getUser($idUser);
			  $c=$usuario[1]+$cantidad;
			  $query= "update gente set coins='".$c."' where idUser='".$idUser."' limit 1";
			  $this->cable->execute_query($query);  			   
			  $as= htmlentities(addslashes($asunto));
		  
			  $query= "insert into voucher (idUser,debito,credito,fecha,asunto) values('".$idUser."','0','".$cantidad."','".time()."','".$as."')";			   
		      $this->cable->execute_query($query);
			   
			  $query= "select monedero from banco where nombre='BANFER'";
			  $dato= $this->cable->execute_query($query);
			  $dato= mysqli_fetch_row($dato);
		  	  
			  $c=$dato[0]-$cantidad;
			  $query= "update banco set monedero='".$c."' where nombre='BANFER'";
			  $this->cable->execute_query($query);
			  
			  $this->notificar($idUser,"Se han añadido ".$cantidad." coins a su monedero. Asunto: ".$asunto,5);			  
		 	 
	   }
	   
	   function debitar($idUser,$cantidad,$asunto)
	   {
		  if($this->checkCoins($idUser,$cantidad) && $cantidad>0)
		  {
			  $usuario= $this->getUser($idUser);			 
			  $c=$usuario[1]-$cantidad;
			  
			  $query= "update gente set coins='".$c."' where idUser='".$idUser."' limit 1";
			  $this->cable->execute_query($query);  		  		  
			   
			  $query= "insert into voucher (idUser,debito,credito,fecha,asunto) values('".$idUser."','".$cantidad."','0','".time()."','".$asunto."')";
			  $this->cable->execute_query($query);
			  
			  
			  $query= "select monedero from banco where nombre='BANFER'";
			  $dato= $this->cable->execute_query($query);
			  $dato= mysqli_fetch_row($dato);
			  $c=$dato[0]+$cantidad;
			  $query= "update banco set monedero='".$c."' where nombre='BANFER'";
			  $this->cable->execute_query($query);			  
			  
			  $this->notificar($idUser,"Se han extraído ".$cantidad." coins de su monedero. Asunto: ".$asunto,7);			  
		  }
		 
	   }
	  
	   function planificarLigaTCT($teams,$idLiga)
	   {
		  $eqs= explode(";",$teams);
		  unset($eqs[count($eqs)-1]);
		  $fecha= time();
		  $tablapos="";						 
			foreach($eqs as $tim)						
				$tablapos.=$tim.",0,0,0,0,0;";						  
			$query="update ligatct set tablaPos='".$tablapos."' where idLiga='".$idLiga."' limit 1";
			$this->cable->execute_query($query);  
			 
		    $cantEq=count($eqs);
			foreach($eqs as $i)
			 foreach($eqs as $m)					  
				if($i!=$m)
				  {
					 $id1=$i;
					 $id2= $m;  
					 $fe= rand($fecha,$fecha+($cantEq*$cantEq-$cantEq)*30);
					 $query ="insert into plan (idLiga,idTeam1,idTeam2,fecha, desicion) values('".$idLiga."','".$id1."','".$id2."',".$fe.",1)";
					 $this->cable->execute_query($query); 
				  }	  					   
			  
	   }
	  
	   function planificarLigaGrupo($teams,$idLiga)
	   {
		    
		 $teams= explode(";",$teams);
		   
		 $eqs=array();
		 $c=0;
		 foreach($teams as $tm)	
			 if($tm!='')
				 $eqs[$c++]= new Team($tm);

		 for($i=0;$i<count($eqs);$i++)
			 for($e=0;$e<count($eqs)-1;$e++)							  
				 if($eqs[$e]->elo<$eqs[$e+1]->elo)
				 {
					$temp= $eqs[$e+1];
					 $eqs[$e+1]= $eqs[$e];
					 $eqs[$e]=$temp;
				 }
		  
		  $grupos=array();
		  $cantG=count($eqs)/4; 
		  for($i=0;$i<$cantG;$i++)						  
			  $grupos[$i]=array();

		   $gr=0;	
		   $n=count($eqs);
		    
		   while($n>0)
		   {
			   $teq= $eqs[0];			   
			   array_splice($eqs,0,1);
			   array_push($grupos[$gr++],$teq);
			   if($gr==$cantG)
				   $gr=0;
			   $n--;
		   }	   
		   
		   $cdn="";
		   foreach($grupos as $g)
		   {	
			   foreach($g as $tm){
				   $cdn.=$tm->idTeam.',0,0,0,0,0;';			   	  
			   }
			   $cdn.=":";
			   str_replace(";:",":",$cdn);
		   }
		   
		   if($cantG>2)
		   for($i=1;$i<$cantG/2;$i++)
		   {
			   $temp= $grupos[$i];
			   $grupos[$i]=$grupos[$cantG-$i];
			   $grupos[$cantG-$i]=$temp;
		   }

		   $query="update ligagroup set grupos='".$cdn."', etapa='1' where idLiga='".$idLiga."' limit 1";   
		   $this->cable->execute_query($query);
		    
		   $fecha= time();
		   $cantEq= count($grupos)*4; 
		   foreach($grupos as $g)		   			   
			   for($i=0;$i<count($g);$i++)
				   for($e=$i;$e<count($g);$e++)
				   {
					   if($i!=$e){
						 $fe= rand($fecha,$fecha+($cantEq*$cantEq-$cantEq)*30);
						 $r= rand(0,100);
						 $uno= $r%2==0?$i:$e;
						 $dos= $r%2!=0?$i:$e;
						 $query ="insert into plan (idLiga,idTeam1,idTeam2,fecha, desicion,etapa) values('".$idLiga."','".$g[$uno]->idTeam."','".$g[$dos]->idTeam."',".$fe.",1,1)";						  
						 $this->cable->execute_query($query);
					   }
				   }				   
	   }
	  
		function addTeamToLeague($idLiga,$usuario,$idteam,$intencion)
		{					
			$liga=$this->getLiga($idLiga);			
			$team= new Team($idteam);
			$max= $liga['cantTeams'];			
			$eqs= explode(';',$liga['teams']);					 
			unset($eqs[count($eqs)-1]);
			$num= count($eqs);
			$teams="";	
			$user= $this->getUser($usuario);			
			$costo= intval($team->elo*0.04);
			
			if(in_array($idteam,$eqs)){
				throw new Exception("Equipo ".$team->nombre." ya esta en esta liga.");
				return;
			}
			elseif($costo>$user['coins'])
				{
					throw new Exception("Incluir el equipo ".$team->nombre." ,en esta liga, le cuesta ¢".$costo." coins, su saldo es insuficiente.");
					return;
				}			
				elseif($max>count($eqs))
			  {
				$teams= $liga['teams'].$idteam.";";
				$query="update liga set teams='".$teams."' where idLiga='".$idLiga."' limit 1";
				$this->cable->execute_query($query);
					
				if($intencion==0)					
				{
					$this->debitar($usuario,$costo,"Incluir equipo #".$team->idTeam."# [".$team->elo."]   en liga *".$liga['idLiga']."*.");

					$this->creditar($team->iduser,intval($costo*0.75),"Su equipo #".$team->idTeam."# se ha incluido en la liga *".$liga['idLiga']."*.");					
					$this->notificar($team->iduser,"Su equipo #".$team->idTeam."# fue incluido en la liga *".$liga['idLiga']."*.",6);
				}
			  }
			else
			{
			 throw new Exception("Esta liga esta llena.");
				return;	
			}
			
			$liga=$this->getLiga($idLiga);			
			$team= new Team($idteam);
			$max= $liga['cantTeams'];			
			$eqs= explode(';',$liga['teams']);					 
			unset($eqs[count($eqs)-1]);
			$num= count($eqs);
			$teams="";
			
			if($this->checkCoins($usuario,$liga['entry']))
			{			
				if($max==$num)
				{					 
					 $query="update liga set state=1,fechaInicio='".time()."' where idLiga='".$idLiga."' limit 1";					 
					 $this->cable->execute_query($query);
					 
					  if($liga['tipo']==2)
					    $this->planificarLigaTCT($liga['teams'],$idLiga);
					  if($liga["tipo"]==1)
					    $this->planificarLigaGrupo($liga['teams'],$idLiga);
					
					  $this->notificar($usuario,"Su liga *".$liga['idOwner']."* comenzó.",6);
									  
					  if($liga['idBasicLiga']!=-1)
					  {
						  $this->crearLigaSegunBasic($liga['idBasicLiga'],1);
					  }
						  
					 exec ("python /home/futbol/public_html/core/matador.py ".$idLiga." > /dev/null 2>/dev/null &"); 
					  
			  	} 
				 
				 $this->debitar($usuario,$liga[4],"Inscripción en la liga *".$liga['idLiga']."*.");
				
				 throw new Exception("Equipo ".$team->nombre." añadido a la liga '*".$liga['idLiga']."*' con éxito.");
				}			
				else{
					throw new Exception("Tienes insuficientes coins.");
				}
			}
			
	  	function getPlannedGame($idTeam)
		{
			$query= "select idTeam1,idTeam2,fecha,idLiga from plan where idTeam1='".$idTeam."' or idTeam2='".$idTeam."' order by fecha ASC";
			$datos= $this->cable->execute_query($query);
			
			if($datos)
				return $this->cable->HacerArray($datos);
			
			return NULL;
		}
	  
	  	function procesarMensaje($msg)
		{		 			
			$posi=strpos($msg,"#");
			while($posi){
			if($posi){
				$posf=strpos($msg,"#",$posi+1);
				if($posf)
				{
				  $idTeam= substr($msg,$posi+1,$posf-$posi-1);				  
				  $t= new Team($idTeam);
				  $cdn="<a href='team.php?i=".$idTeam."'><strong>".$t->nombre."</strong></a>";
				  $msg=str_replace("#".$idTeam."#",$cdn,$msg);	
				}
			}
				$posi=strpos($msg,"#");
			}
			
			$posi=strpos($msg,"*");
			if($posi){
				$posf=strpos($msg,"*",$posi+1);
				if($posf)
				{
				  $idLiga= substr($msg,$posi+1,$posf-$posi-1);
				  $lig= $this->getLiga($idLiga);
				  $cdn="<a href='league.php?i=".$idLiga."'><strong>".$lig['nombre']."</strong></a>";
				  $msg=str_replace("*".$idLiga."*",$cdn,$msg);	
				}
			}
			
			$posi=strpos($msg,"%");				 
			if($posi){
				$posf=strpos($msg,"%",$posi+1);				 
				if($posf)
				{
				  $st= substr($msg,$posi+1,$posf-$posi-1);				  
				  $st1= explode(",",$st);					  
				  $cdn="<a href='show.php?g=".$st1[0]."&type=".$st1[1]."'><img src='../images/campo.png' style='width:35px;'></a>";
				  $msg=str_replace("%".$st."%",$cdn,$msg);	
				}
			}
			
			
			return $msg;
		}
	  
		
		
		function programarPartido($idTeam1,$idTeam2,$idLeague,$desicion,$fecha)
		{
		   $query ="insert into plan (idTeam1,idTeam2,fecha,idLeague,desicion) values(".$idTeam1.",".$idTeam2.",".$fecha.",".$idLeague.",".$desicion.")";	   
		   $this->cable->execute_query($query);	
		} 
	  
	 function getConfig($id)
	 {
		 $query= "select initial,forces,players,code as dir,escudo,tshirt,tshirt2 from config where idConfig='".$id."' limit 1";
		 $datos= $this->cable->execute_query($query);
		 $datos= mysqli_fetch_array($datos);
		 
		 $dirGame= $datos['dir'];
		  
		  if(is_file("../codes/".$dirGame))
		  {
			  $handle= fopen("../codes/".$dirGame,'r');
			  $buffer= fread($handle,filesize("../codes/".$dirGame));  
			  fclose($handle);
			  $datos['code']=$buffer;
		  }
		 return $datos;	 
	 }
	  
	 function getPlayers()
	{
	  $query= "select id_player, nombre from player";
	  $datos= $this->conexion->execute_query($query);	   
	  return $this->conexion->HacerArray($datos);			
	}	
	function saveTactic($id, $nombres, $forces, $positions, $code)
	{	  	
	  $query="select count(*) from team where iduser='".$id."'";
	  $cuenta= $this->conexion->execute_query($query);
	  $cuenta= mysqli_fetch_row($cuenta);
	  	    
	  if($cuenta[0]==0){
	    $query= "insert into team (players, forces, initial,iduser) values('".$nombres."', '".$forces."','".$positions."','".$id."',code='".$code."')";
		 
	  }
	  if($cuenta[0]==1){
	    $query= "update team set players='".$nombres."', forces='".$forces."', initial='".$positions."', code='".$code."' where iduser='".$id."' limit 1";
		
	  }	  
		$datos= $this->conexion->execute_query($query);	
		
		if($datos)
			 return 1;
			
		return 0;		  	
	}
	
	function getTactic($id,$conCode)
	{
	  $query="select forces,players,initial,dirCode from tactic where idTeam='".$id."' limit 1 ";
	  $datos= $this->cable->execute_query($query);	
	  $datos= mysqli_fetch_array($datos);		
		
	  if($conCode==1 && $datos!=[]){	
	  $dirGame= $datos['dirCode'];
	  if(is_file("codes/".$dirGame))
	  {
		  $handle= fopen("codes/".$dirGame,'r');
		  $buffer= fread($handle,filesize("codes/".$dirGame));  
		  fclose($handle);
		  $datos['code']=$buffer;
	  }
	  }
	
	  return $datos;
	}
	
	function getListaTactica()
	{
	   $query="select iduser from team";
	   $datos= $this->cable->execute_query($query);
	   return $this->cable->HacerArray($datos);
	}	  

	   
	   function setName($name,$idTeam,$idUser)
	   {
	       if($this->isMyTeam($idTeam,$idUser)){
    		  $query = "update team set nombre='".$name."' where idTeam='".$idTeam."' and iduser='".$idUser."' limit 1";
    		  $this->cable->execute_query($query);	
	       }
	   }  
	   	   
	    function getNewsTipo($idUser,$tipo,$page,$cant){
	     $query= "select idNews,msg,fecha,leida,tipo from news where idUser='".$idUser."' and tipo='".$tipo."' order by fecha DESC limit ".($page*$cant).",".$cant;		   
		 $datos=$this->cable->HacerArray($this->cable->execute_query($query));		 
		 return $datos;
	   }
	   
	   function getNews($idUser,$page,$canti){
	     $query= "select idNews,msg,fecha,leida,tipo from news where idUser='".$idUser."' order by fecha DESC limit ".($page*$canti).",".$canti;
	    
		 $datos=$this->cable->HacerArray($this->cable->execute_query($query));		 
		 return $datos;
	   }
	  
	  function getCantNews($idUser){
	     $query= "select count(idNews) as cant from news where idUser='".$idUser."'";		   
		 $datos=$this->cable->HacerArray($this->cable->execute_query($query));		 
		 return $datos[0]['cant'];
	   }
	  
	 
	  
	   function getCantNewsTipo($idUser,$tipo)
	   {
		   $query= "select count(idNews) as cant from news where idUser='".$idUser."' and tipo='".$tipo."'";
		   
		   $datos=$this->cable->HacerArray($this->cable->execute_query($query));	
		   return $datos[0]['cant'];
	   }
	  

		function getLastGames($idTeam)
		{
			$query="select idGame, idTeam1,idTeam2,gol1,gol2,winner,fechaDone from game where (idTeam1='".$idTeam."' or idTeam2='".$idTeam."') and done=1 order by fechaDone ASC ";			
			
			$datos= $this->cable->execute_query($query);
		    		
			return $this->cable->HacerArray($datos);
		}		
		
		function getGamesLiga($idLiga)
		{
		   	$query="select idGame, idTeam1,idTeam2,gol1,gol2, winner,fechaDone,etapa from game where idLiga='".$idLiga."' and done=1 order by fechaDone DESC";
			$datos= $this->cable->execute_query($query);
			
			if($datos)
			  return $this->cable->HacerArray($datos);
			
			return NULL;
		}		
		
		function getUser($idUser)
		{
		  $query="select idUser,coins,email,nombre,permisos,theme,mode from gente where idUser='".$idUser."' limit 1";
		  $datos=$this->cable->execute_query($query);
		  return mysqli_fetch_array($datos);  
		}
		
	  
		function checkCoins($user, $coins)
		{
			$usuario= $this->getUser($user);
			
			if($usuario[1]-$coins>=0)
				 return true;
			
			return false;
		}
	  
	  function setThemeMode($idUser,$theme,$mode)
	  {
		  $query= "update gente set theme='".$theme."', mode='".$mode."' where idUser='".$idUser."' limit 1";
		  $this->cable->execute_query($query);
	  }
	
	   function rankingTeam($idTeam)
	  {
		 $query= "select elo from team where idTeam='".$idTeam."' limit 1";
		 
		 $result= $this->cable->execute_query($query);
		 $team= mysqli_fetch_array($result);
		 $elo= $team['elo'];

		 $query= "select count(idTeam) as cant from team where elo>'".$elo."'";
		 $result= $this->cable->execute_query($query);
		 $row= mysqli_fetch_array($result);
		 $cantMasElo= $row['cant'];

		 $query="select idTeam,win,lost,golFavor,golContra,elo from team where elo='".$elo."'";
		 $result= $this->cable->execute_query($query);

		 $mismoElo= $this->OrdenarElo($result);
		   
		 $cont=0;
		   foreach($mismoElo as $team)
			   if($team['idTeam']==$id_team)
				   break;
				else
				  $cont++;
		 return $cont + $cantMasElo;	 	 	 	 	   
	   }
	  
	   function OrdenarElo($result)
		 {   
		   $cambiar=false;
		   $retorno=$this->cable->HacerArray($result);
		   $cant= count($retorno); 
		   for($i=0;$i<count($retorno);$i++){
			   $cambiar=false;
			  for($e=0;$e<$cant-1;$e++)
				 {
				   if($retorno[$e]['elo']<$retorno[$e+1]['elo'])
				   {
					  $cambiar=true;   
					} 	 
					elseif($retorno[$e]['elo']==$retorno[$e+1]['elo'] && $retorno[$e]['win']< $retorno[$e+1]['win'])
					 {
						$cambiar=true; 
					 }	 
					 elseif($retorno[$e]['elo']==$retorno[$e+1]['elo'] && $retorno[$e]['won']== $retorno[$e+1]['win'] && $retorno[$e]['golFavor']<$retorno[$e+1]['golFavor'])
					 {
						 $cambiar=true;
						 }
					 elseif($retorno[$e]['elo']==$retorno[$e+1]['elo'] && $retorno[$e]['win']== $retorno[$e+1]['win'] && $retorno[$e]['golFavor']== $retorno[$e+1]['golFavor'] && $retorno[$e]['lost'] > $retorno[$e+1]['lost'])
					 {
						 $cambiar=true;
						 }
					elseif($retorno[$e]['elo']==$retorno[$e+1]['elo'] && $retorno[$e]['won']== $retorno[$e+1]['win'] && $retorno[$e]['golFavor']== $retorno[$e+1]['golFavor'] && $retorno[$e]['lost'] == $retorno[$e+1]['lost'] && $retorno[$e]['golContra'] > $retorno[$e+1]['golContra'])
					{
						$cambiar=true;
						}

						if($cambiar==true)
						{
						 $temp= $retorno[$e];				  
						 $retorno[$e]=$retorno[$e+1];
						 $retorno[$e+1]=$temp;  	
						}
				 }
			 }

			 return $retorno;
		 }      
	  
	  function deleteNew($idNew,$idUser)
	  {
		  $query="delete from news where idNews='".$idNew."' and idUser='".$idUser."' limit 1";	
		  
		  $this->cable->execute_query($query);
	  }
	  
	  function changeImagen($idTeam,$img)
	  {
		  $query="update team set escudo='".$img."' where idTeam='".$idTeam."' limit 1";
		  $this->cable->execute_query($query);
	  }
	  
	  
	  function getLigasofTeam($idTeam)
	  {
		  $query="select idLiga, nombre, state, tipo,ordenFinal from liga where teams like '".$idTeam.";%' or teams like '%;".$idTeam."%'";
		  $datos= $this->cable->execute_query($query);
		  
		  return $this->cable->HacerArray($datos);
	  }
	  
	  function crearLiga($nombre,$tipo,$cantEquipos,$entrada,$premio,$idOwner,$publica,$idTeam,$idBasicLiga)
	  {	  
		  $query="select count(idLiga) as cuenta from liga where nombre='".$nombre."' limit 1";
		  
		  $datos= $this->cable->execute_query($query);
		  $datos= mysqli_fetch_array($datos);
		  $user= $this->getUser($idOwner);
		  $costo=1500;
		  $cantGames=0;

		  if($tipo==2)
			$cantGames=$cantEquipos*$cantEquipos-$cantEquipos;

		  if($tipo==1)
			{
				$c=$cantEquipos/4;
				$cantGames=$cantEquipos/4*6-1;

				while($c>0)
					{
					  $cantGames+=$c;
					  $c/=2;				
					}	
			} 
		  $costo+=intval($cantGames)*20+intval($premio);
		  
		  if($datos['cuenta']==0)
		  {
			  if($costo<=$user['coins'] || $user['permisos']==0){
				  $query= "select max(idLiga) as idLiga from liga";
				  $datos=$this->cable->execute_query($query);
				  $datos= mysqli_fetch_array($datos);
				  $idLiga= $datos['idLiga']+1;
				  $query="insert into liga (idLiga,tipo,cantTeams,entry,prize,nombre,fechaInicio,idOwner,publica,teams,idBasicLiga) values('".$idLiga."','".$tipo."','".$cantEquipos."','".$entrada."','".$premio."','".$nombre."','".time()."','".$idOwner."','".$publica."','','".$idBasicLiga."')";	
				  
				  $this->cable->execute_query($query);
				  if($user['permisos']!=0)
				  	$this->debitar($idOwner,$costo,"Creación de la liga *".$idLiga."*");
				  $this->notificar($idOwner,"Su liga *".$idLiga."* se ha creado con éxito.",6);				  
				  if($tipo==2)
				  {
					$query="insert into ligatct (idLiga,tablaPos) values ('".$idLiga."','')";
					$this->cable->execute_query($query);					  
				  }
				  
				  if($tipo==1)
				  {
					$query="insert into ligagroup (idLiga) values ('".$idLiga."')";				 
					$this->cable->execute_query($query);
				  }	
				  if($idTeam!=-1){
					  try{					  
					  $this->addTeamToLeague($idLiga,$idOwner,$idTeam,1);	
					  }
					  catch(Exception $e)
					  {
						  throw new Exception($e->getMessage()); 
					  }
				  }
				  
				  return $idLiga;
			  }
			  else
				 throw new Exception("Saldo insuficiente para crear esta liga.<br>"); 
		  }
		  else
			  throw new Exception("Nombre de liga en uso.<br>");
		  
		  return -1;
	  }
	  
	  function checkCode($user,$idTeam,$code)
	{	
		$query="SELECT `AUTO_INCREMENT`
				FROM  INFORMATION_SCHEMA.TABLES
				WHERE TABLE_SCHEMA = 'futbol_futbolin'
				AND   TABLE_NAME   = 'chec'"  ;
		$dato= $this->cable->execute_query($query);
		$id= mysqli_fetch_row($dato)[0];
		  
		$file=fopen("/home/futbol/public_html/chec/".$id."chec.txt","w");
		fwrite($file,$code);
		fclose($file); 		  
		  
		$query="insert into chec (idChec, iduser,code,idTeam) values('".$id."','".$user."','".$code."','".$idTeam."')";		
		$this->cable->execute_query($query); 	
		  
		exec ("python /home/futbol/public_html/core/check.py ".$id." > /dev/null 2>/dev/null &"); 
		  
		return $id;
	}  
	  
	function getCheck($idChec)
	{
		$query="select * from chec where idChec='".$idChec."' limit 1";
		$datos= $this->cable->execute_query($query);
		return mysqli_fetch_array($datos);
	}
	  
	  function saveCode($idTeam,$user, $code)
	  {
		  $file=fopen("../codes/".$idTeam.".fer","w");
		  fwrite($file,$code);
		  fclose($file);			  
		  $query="update tactic set(lastUpdate='".date("Y-m-j h:i:s")."') where idTeam='".$idTeam."'";
		  $this->cable->execute_query($query);
		  
		  return $code;
	    
	  }
	  
	  function getCantLineas($text)
	  {
	      $cdn= explode("\n",$text);
	      return count($cdn);
	  }
	  
	  function getCantCharMax($text)
	  {
	      $cdn= explode("\n",$text);
	      $max=0;
	      
	      foreach($cdn as $c)
	      {
	          if(strlen($c)>$max)
	            $max=strlen($c);
	      }
	      
	      return $max;
	  }
  };
  

class Team{
	public $idTeam;
	public $players;
	public $forces;
	public $initial;
	public $iduser;
	public $lasUpdate;
	public $code;
	public $nombre;
	public $elo;
	public $golFavor;
	public $golContra;
	public $segundo;
	public $win;
	public $lost;
	public $tie;
	public $tieWin;
	public $ranking;
	public $escudo;
	public $tshirt;
	public $tshirt2;
	
	
	
	function Team($idTeam,$code=false){
		
		$cable= new Cable();
		
		$query= "select * from team where idTeam='".$idTeam."' limit 1";
		 
		$result= $cable->execute_query($query);
		$result = $cable->HacerArray($result);
					
		if(count($result)>0){	
		$result= $result[0];
		$this->idTeam= $idTeam;		
		$this->iduser= $result['iduser'];
		$this->lastUpdate= $result['lastUpdate'];		
		$this->nombre= $result['nombre'];
		$this->elo= $result['elo'];
		$this->golFavor= $result['golFavor'];
		$this->golContra= $result['golContra'];
		$this->segundo= $result['segundo'];
		$this->win= $result['win'];
		$this->lost= $result['lost'];
		$this->tie= $result['tie'];
		$this->tshirt2= $result['tshirt2'];
		$this->escudo= $result['escudo'];
		$this->tshirt= $result['tshirt'];
		$this->ranking= $this->rankingTeam($idTeam,$cable);			
		
		if($code==true)
		{	
			if(is_file('../codes/'.$idTeam.'.fer'))
			{
				$this->code=implode('',file('../codes/'.$idTeam.'.fer'));
			}
			else
			{
				$id= rand(1,5);
				$query= "select code from config where idConfig='".$id."'";
				$datos= $cable->execute_query($query);
				$datos= mysqli_fetch_row($datos);
				$archivo= fopen('../codes/'.$idTeam.'.fer',"w");
				fwrite($archivo,$datos[0][0]);
				fclose($archivo);
			}
		}
					
		}				
	}
	
	 
	function selectTshirt($contrario)
	{
		if($contrario==$this->tshirt)
			return $this->tshirt2;
		
		return $this->tshirt;
	}
	
	
	
	 function rankingTeam($idTeam,$cable)
	  {
		 $query= "select elo from team where idTeam='".$idTeam."' limit 1";
		 
		 $result= $cable->execute_query($query);
		 $team= mysqli_fetch_array($result);
		 $elo= $team['elo'];

		 $query= "select count(idTeam) as cant from team where elo>'".$elo."' and idTeam!='".$idTeam."'";
		 $result= $cable->execute_query($query);
		 $row= mysqli_fetch_array($result);
		 $cantMasElo= $row['cant'];
		 
		/* $query="select idTeam,win,lost,golFavor,golContra,elo from team where elo='".$elo."' and idTeam!='".$idTeam."'";
		 $result= $this->cable->execute_query($query);

		 $mismoElo= $this->OrdenarElo($result);
		   
		 $cont=0;
		   foreach($mismoElo as $team)
			   if($team['idTeam']==$idTeam)
			   {
				   $cont++;
				   break;
			   }
				else
				  $cont++;
		 return $cont + $cantMasElo+1;	 */
		 
		 return $cantMasElo+1;
	   }
	  
	   function OrdenarElo($result)
		 {   
		   $cambiar=false;
		   $retorno=$this->cable->HacerArray($result);
		   $cant= count($retorno); 
		   for($i=0;$i<count($retorno);$i++){
			   $cambiar=false;
			  for($e=0;$e<$cant-1;$e++)
				 {
				   if($retorno[$e]['elo']<$retorno[$e+1]['elo'])
				   {
					  $cambiar=true;   
					} 	 
					elseif($retorno[$e]['elo']==$retorno[$e+1]['elo'] && $retorno[$e]['win']< $retorno[$e+1]['win'])
					 {
						$cambiar=true; 
					 }	 
					 elseif($retorno[$e]['elo']==$retorno[$e+1]['elo'] && $retorno[$e]['win']== $retorno[$e+1]['win'] && $retorno[$e]['golFavor']<$retorno[$e+1]['golFavor'])
					 {
						 $cambiar=true;
						 }
					 elseif($retorno[$e]['elo']==$retorno[$e+1]['elo'] && $retorno[$e]['win']== $retorno[$e+1]['win'] && $retorno[$e]['golFavor']== $retorno[$e+1]['golFavor'] && $retorno[$e]['lost'] > $retorno[$e+1]['lost'])
					 {
						 $cambiar=true;
						 }
					elseif($retorno[$e]['elo']==$retorno[$e+1]['elo'] && $retorno[$e]['win']== $retorno[$e+1]['win'] && $retorno[$e]['golFavor']== $retorno[$e+1]['golFavor'] && $retorno[$e]['lost'] == $retorno[$e+1]['lost'] && $retorno[$e]['golContra'] > $retorno[$e+1]['golContra'])
					{
						$cambiar=true;
						}

						if($cambiar==true)
						{
						 $temp= $retorno[$e];				  
						 $retorno[$e]=$retorno[$e+1];
						 $retorno[$e+1]=$temp;  	
						}
				 }
			 }

			 return $retorno;
		 }      
    
};

class TextToImage {
    private $img;
    function createImage($text, $fontSize = 10, $imgWidth = 400, $imgHeight = 80){

        //text font path
        $font = '../font/code.ttf';
        
        //create the image
        $this->img = imagecreatetruecolor($imgWidth, $imgHeight);
        
        //create some colors
        $white = imagecolorallocate($this->img, 255, 255, 255);
        $grey = imagecolorallocate($this->img, 128, 128, 128);
        $black = imagecolorallocate($this->img, 0, 0, 0);
        imagefilledrectangle($this->img, 0, 0, $imgWidth - 1, $imgHeight - 1, $white);
        
        //break lines
        $splitText = explode ( "n" , $text );
        $lines = count($splitText);
        
        foreach($splitText as $txt){
            $textBox = imagettfbbox($fontSize,$angle,$font,$txt);
            $textWidth = abs(max($textBox[2], $textBox[4]));
            $textHeight = abs(max($textBox[5], $textBox[7]));
            $x = (imagesx($this->img) - $textWidth)/2;
            $y = ((imagesy($this->img) + $textHeight)/2)-($lines-2)*$textHeight;
            $lines = $lines-1;
        
            //add some shadow to the text
            imagettftext($this->img, $fontSize, $angle, $x, $y, $grey, $font, $txt);
            
            //add the text
            imagettftext($this->img, $fontSize, $angle, $x, $y, $black, $font, $txt);
        }
        return true;
    }
    
    /**
     * Display image
     */
    function showImage(){
        header('Content-Type: image/png');
        return imagepng($this->img);
    }
    
    /**
     * Save image as png format
     * @param string file name to save
     * @param string location to save image file
     */
    function saveAsPng($fileName = 'text-image', $location = ''){
        $fileName = $fileName.".png";
        $fileName = !empty($location)?$location.$fileName:$fileName;
        return imagepng($this->img, $fileName);
    }
    
    /**
     * Save image as jpg format
     * @param string file name to save
     * @param string location to save image file
     */
    function saveAsJpg($fileName = 'text-image', $location = ''){
        $fileName = $fileName.".jpg";
        $fileName = !empty($location)?$location.$fileName:$fileName;
        return imagejpeg($this->img, $fileName);
    }
}
?>