<?php
include("cabecera.php");  
$error="";

if(isset($_POST['type']))
 {
	 if($_POST['type']=="ae")
	 {
		 $nom= $_POST['inName'];
		 $control->addEquipo(1,$nom);
	 }
	 
	 if($_POST['type']=="nl")
	 {
		$nom= $_POST['inName']; 
		$tipo= $_POST['slTipo'];
		$cant= $tipo==1?$_POST['slCantG']:$_POST['slCantT']; 
		$premio= $_POST['inPremio'];
		$entrada= isset($_POST['inEntrada'])?$_POST['inEntrada']:0;
		$publica=0;
		 if(isset($_POST['ckPublica']))
			 $publica=  $_POST['ckPublica']=='on'?1:0;	  
		 
		 try
		  {
			 $control->crearLiga($nom,$tipo,$cant,$entrada,$premio,$usuario,$publica,$idTeam,-1); 
		  }
		  catch(Exception $e)
		  {
			  $error.= "<span class='small li'>".$e->getMessage()."</span>,<br>";
		  } 
		 $ligasEspera= $control->getLigasenEstado(0,$idTeam);
	 }
	 
	 if($_POST['type']=="el")
	 {
	   $idLiga= $_POST['slLiga'];
	   $teams=$_POST['slTeams'];  
		 
	  foreach($teams as $t)
	  {
		  try
		  {
			$control->addTeamToLeague($idLiga,$usuario,$t,0);
		  }
		  catch(Exception $e)
		  {
			  $error.="<span class='text-warning li'>".$e->getMessage()."</span>, <br>  ";
		  }
	  }	  
	 }
	
	if($_POST['type']=="cn")
	 {
		$nombre= $_POST['n'];
		
		if($control->checkNombreLiga($nombre))
			echo "yes";
		else
			echo "no";
		
		die;
	 }
	
	if($_POST['type']=="be")
	 {
		$rk=$team->ranking;
		$minrk=$rk-25>=0?$rk-25:0;
		$maxrk= $rk+25;
		 
		$ranking= $control->getRanking($minrk,$maxrk);
		 
		echo json_encode($ranking);
	    die;
	  }
	
	if($_POST['type']=="tl")
	 {
		$idLiga=$_POST['i'];		 
		$eqs=$control->getTeamsLiga($idLiga);		
		echo json_encode($eqs);
		die;
	  }
	
	if($_POST['type']=="lo")
	 {
		$min= $_POST['min'];	
		$max= $_POST['max'];
		$eqs=$control->getRangoElo($min,$max);		
		echo json_encode($eqs);
		die;
	  }
	
	if($_POST['type']=="na")
	 {			
		$nombre= $_POST['name'];
		$eqs=$control->getLigaPorNombre($nombre);		
		echo json_encode($eqs);
		die;
	  }
 }
$misligas=[];
$ligasEspera= $control->getLigasenEstado(0,$idTeam);
foreach($ligasEspera as $lig)
{
	if($lig['idOwner']==$usuario)
		array_push($misligas,$lig);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Futbolin">
  <meta name="author" content="Tommy Rod ">
  <meta name="keyword" content="Futbolin, Programación, Futbol, Código, Inteligencia, Programador, Informatic, Code, Artificial Intelligence">
  <title><?php echo $user['nombre'];?></title>

  <!-- Favicons -->
  <link href="../images/favicon.png" rel="icon">
  <link href="../images/apple-touch-icon.png" rel="apple-touch-icon">
  <!-- Bootstrap core CSS -->
  <link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--external css-->
  <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="../css/style.css" rel="stylesheet">
  <link href="../css/style-responsive.css" rel="stylesheet">
 	<link rel="stylesheet" type="text/css" href="../css/admin.css">
	<link rel="stylesheet" type="text/css" href="../lib/gritter/css/jquery.gritter.css" />	

  <!-- =======================================================
    Template Name: Dashio
    Template URL: https://templatemag.com/dashio-bootstrap-admin-template/
    Author: TemplateMag.com
    License: https://templatemag.com/license/
  ======================================================= -->
</head>

<body>
<script>
	var usuario= <?php echo $usuario;?>;
</script>	
<script src="https://www.gstatic.com/firebasejs/6.3.4/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.10.1/firebase-auth.js"></script>
<script src="../js/firebase.js"></script>
<script src="https://kit.fontawesome.com/070081e2e4.js"></script>
<script src="../js/jquery.js"></script>
<script src="../js/admin.js"></script>	

  <section id="container" style="">
    <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
    <!--header start-->
    <?php include_once("menu2.php");?>
    <!--header end-->
    <!-- **********************************************************************************************************************************************************
        MAIN SIDEBAR MENU
        *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
	<?php include_once("menu.php");?>
    <!--sidebar end-->
    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
      <section class="wrapper site-min-height">        
        <div class="row mt">
          <div class="col-lg-9 col-md-9">
			  
			  <div class="row content-panel">
				  <div class="panel-heading">
					<ul class="nav nav-tabs nav-justified">
					  <li class="<?php echo count($misligas)==0?"active":''?>">
						<a data-toggle="tab" href="#liga">Nueva Liga</a>
					  </li>
					  <li class="<?php echo count($misligas)>0?"active":''?>">
						<a data-toggle="tab" href="#addTeams" class="contact-map">Añadir Equipos a la Liga</a>
					  </li>
					</ul>
				  	</div>
              <!-- /panel-heading -->
          		    <div class="panel-body">
						<div class="tab-content">					
						  <div id="liga" class="tab-pane <?php echo count($misligas)==0?"active":''?>">
							<div class="row">
							  <div class="col-lg col-lg detailed">					      		 
								  <form class="form-group-lg" action="newliga.php" method="post">
										<div class="form-group" id="grName">					
										  <div class="input-group-prepend">
											<span class="input-group-text">Nombre Liga</span>
										  </div>
										  <input type="text" aria-label="First name" class="form-control" name="inName" id="inName" onKeyUp="calcularLiga(<?php echo $user['coins'];?>)">
											<p class="help-block" id="helpName"></p>
										</div> 
										  <div class="input-group">	
										  <div class="input-group-prepend">
											  <span class="input-group-text">Tipo</span>	  
											  <select class="form-control" name="slTipo" id="slTipo" onChange="calcularLiga(<?php echo $user['coins'];?>)">
												  <option value="1">Grupos</option>
												  <option value="2">Todos Contra Todos</option>
											  </select>
											</div>									   
											<span class="input-group-text">Cant. Equipos</span>
											  <select class="form-control" name="slCantG" id="slCantG" onChange="calcularLiga(<?php echo $user['coins'];?>)">
												  <option value="8">8</option>
												  <option value="16">16</option>
												  <option value="32">32</option>
											  </select> 
											  <select class="form-control" name="slCantT" id="slCantT" style="display:none;" onChange="calcularLiga(<?php echo $user['coins'];?>)">
												  <option value="4">4</option>
												  <option value="6">6</option>
												  <option value="10">10</option>
											  </select> 					  
											 	
										   </div>
									 <?php 
										  if($user['permisos']==0)
										  {
									  ?>
										  <div class="input-group">	
										  <div class="input-group-prepend">
											<span class="input-group-text">Entrada</span><sup>¢</sup>
										  </div>
										  <input type="text" value=0 aria-label="First name" class="form-control" name="inEntrada" id="inEntrada">	
											  </div>
									  <?php
										  }
											  ?>
										  <div class="input-group">	
										  <div class="input-group-prepend">
											<span class="input-group-text">Premio</span><sup>¢</sup>
										  </div>
										  <input type="text" value=0 aria-label="First name" class="form-control" name="inPremio" id="inPremio" onChange="calcularLiga(<?php echo $user['coins'];?>)" onKeyUp="calcularLiga(<?php echo $user['coins'];?>)">	
										  </div>
										  <input type="hidden" value="nl" name="type" id="type">
									   <p>
									    <div class="input-group">							   
											<span class="input-group-text" title="Una liga pública es visible desde el mercado de ligas y cuarquier equipo puede incluirse. Si no es pública, solo el dueño de la liga puede añadir equipos.">Liga pública</span>
										   
											<input class="" type="checkbox" name="ckPublica" checked title="Una liga pública es visible desde el mercado de ligas y cuarquier equipo puede incluirse. Si no es pública, solo el dueño de la liga puede añadir equipos.">								
										</div>
										<p>
										<div class="input-group">
											 <span class="h3 centered bg-success text-success" id="cantGames"></span><small> juegos.</small>
											 
											 <span class="h3 centered text-success" id="costo" title="1500¢ por crear la liga, más 20¢ por cada partido, más el premio. Precio que se cobrará al crear la liga."></span><sup>¢</sup>		
										</div>
										<p>
									   <div class="input-group">
										 <input  class="btn btn-block btn-primary" type="submit" value="Crear Liga" id="btnNewLiga">   
									   </div>									  
								  </form>  
								  </div>
							  </div>						
							<!-- /OVERVIEW -->
							</div>
							
						  <!-- /tab-pane -->
						  <div id="addTeams" class="tab-pane <?php echo count($misligas)>0?"active":''?>">
							<div class="row">
							  <div class="col-lg-12 detailed">					   
								<?php
								  if(count($misligas)>0)
								  {
								  ?>
								<div class="col-lg-12 col-md-12">							  	
									<span class="alert-info"><input type="radio" value="barrio" name="modo" checked onClick="setOpcion(this)"> Barrio Elo</span>
									<span class="alert-info"><input type="radio" value="elo" name="modo" onClick="setOpcion(this)"> Elo</span>
									<span class="alert-info"><input type="radio" value="name" name="modo" onClick="setOpcion(this)"> Nombre</span>

									<div id="pnElo" class="form-group">
										<label for="elomin">Elo min</label>
										<input class="form-control" type="number" id="elomin" value='<?php echo intval($team->elo);?>'onKeyUp="setOpcion(document.getElementsByName('modo')[1]);" onChange="setOpcion(document.getElementsByName('modo')[1]);">
										<p>
										<label for="elomax">Elo max</label>
										<input class="form-control" type="number" id="elomax" value='<?php echo intval($team->elo)+200;?>' onKeyUp="setOpcion(document.getElementsByName('modo')[1]);" onChange="setOpcion(document.getElementsByName('modo')[1]);">
									</div>

									<div id="pnName" class="form-group">
										<label for="name">Nombre equipo</label>
										<input class="form-control" type="text" id="name" value='' onKeyUp="setOpcion(document.getElementsByName('modo')[2]);" onChange="setOpcion(document.getElementsByName('modo')[2]);">							
									</div>
									<hr>
									<span class="badge bg-success" id="txtCantidad"></span>  
									  <select class="form-control" name="slTeam" id="slTeam">					
									  </select>
									<p>
									  <input class="btn btn-clear-g text-success" type="button" value="Add Team" onClick="addToWait();">

										 <form action="newliga.php" method="post" onSubmit="probar()">
										  <div class="input-group">
											<select class="form-control" name="slLiga" id="slLiga" onChange="cambiarLiga()">
												<?php
												  foreach($misligas as $l)
												  {		
													  $faltan= $l['cantTeams']-(count(explode(';',$l['teams']))-1);

													  echo "<option value='".$l['idLiga']."'>".$l['nombre']." ,Faltan: ".$faltan.", cantEquipos:".$l['cantTeams']."</option>";
													  print_r($l);
												  }
												  ?>
											 </select>
											<div class="text-muted" style="font-size: 11px;" id="divTeamsLiga"></div>
											 </div>
											<div class="input-group">
											  <div class="input-group-prepend">
												<span class="badge" id="txtCantAdd"></span>   
												<select multiple name="slTeams[]" id="slTeams" size="10" style="min-width: 250px;" class="form-control centered"></select>
											  </div>

											  <input type="hidden" value="el" name="type" id="type">	 
											</div>
											 
										   <div class="input-group">
											   <input class="btn btn-danger inline-block" type="button" value="Erase" onClick="erase()" style="display: inline">
											 <input class="btn btn-primary" type="submit" value="Incluir Todos"  style="display: inline">  
											 
										   </div>
										   										 
									  </form>			  
								<div class="col-lg-12">
									<?php echo $control->procesarMensaje($error);?>		  
							  </div>	
							</div>	
								  <?php }?>
							 
							 </div>
							</div>	
							<!-- /row -->
						  </div>
						  <!-- /tab-pane -->                  
						</div>
						<!-- /tab-content -->
					  </div>
					  <!-- /panel-body -->
            </div>			  
			  
          </div> 	
			<!-- **********************************************************************************************************************************************************
              RIGHT SIDEBAR CONTENT
              *********************************************************************************************************************************************************** -->
          <div class="col-lg-3 ds">
            
          </div>
		</div>		 
        </div>
      </section>
      <!-- /wrapper -->
    </section>
    <!-- /MAIN CONTENT -->
    <!--main content end-->
    <!--footer start-->
    <footer class="site-footer">
      <div class="text-right">
        <p>
          &copy; Copyrights <strong>FutbolinCode</strong>. All Rights Reserved
        </p>
        <div class="credits">  
           
        </div>
        <a href="newLiga.php#" class="go-top">
          <i class="fa fa-angle-up"></i>
          </a>
      </div>
    </footer>
    <!--footer end-->
  </section>
  <!-- js placed at the end of the document so the pages load faster -->
  <script src="../lib/jquery/jquery.min.js"></script>
  <script src="../lib/bootstrap/js/bootstrap.min.js"></script>
  <script src="../lib/jquery-ui-1.9.2.custom.min.js"></script>
  <script src="../lib/jquery.ui.touch-punch.min.js"></script>
  <script class="include" type="text/javascript" src="../lib/jquery.dcjqaccordion.2.7.js"></script>
  <script src="../lib/jquery.scrollTo.min.js"></script>
  <script src="../lib/jquery.nicescroll.js" type="text/javascript"></script>
  <!--common script for all pages-->
  <script src="../lib/common-scripts.js"></script>
  <!--script for this page-->
  <script type="text/javascript" src="../lib/gritter/js/jquery.gritter.js"></script>
  <script type="text/javascript" src="../lib/gritter-conf.js"></script>	
  <script src="../js/general.js"></script>	
  <script>
	var usuario= <?php echo $usuario;?>;
  </script>	
</body>

</html>
