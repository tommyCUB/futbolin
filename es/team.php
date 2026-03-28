<?php
include_once("cabecera.php");


if($idTeam!="" && isset($_POST['setName']) &&isset($_POST['inName']))
{
    $name= $_POST['inName'];
    
    if(strlen($name)>=5){
	$control->setName($name, $idTeam,$usuario);
	$_SESSION['idTeam']=$idTeam;
	$teamActual= new Team($idTeam);
    }
}

if($idTeam!="" && isset($_POST['checkName']) &&isset($_POST['inName']))
{
    $nom= $_POST['inName'];
    if($control->checkName($nom) && !strlen($nom)<5)
	  echo "1";
	else
	  echo "0";  
	die();
}
$verTactica=false;
$verCode=false;

$chall= $control->getChalldone($usuario);

if(strpos($chall['checkit'],"CH075;"))
   $verTactica=true;

if(strpos($chall['checkit'],"CH076;"))
   $verCode=true;  

?> 

<!DOCTYPE html>
<html lang="es">

<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
  
 
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
  <link rel="stylesheet" href="../css/team.css">	
  <link rel="stylesheet" type="text/css" href="../lib/gritter/css/jquery.gritter.css" />
  <link rel="stylesheet" href="../css/codemirror.css">
  <link rel="stylesheet" href="../css/theme/midnigth.css">
</head>

<body>
<script src="https://www.gstatic.com/firebasejs/6.3.4/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.10.1/firebase-auth.js"></script>
<script src="../js/firebase.js"></script>
<script src="../js/jquery.min.js"></script>
<script src="../js/team.js"></script>	
 
	
  <section id="container" style="">
    <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
    <!--header start-->
    <?php  include_once("menu2.php");?>
     
    <!--header end-->
    <!-- **********************************************************************************************************************************************************
        MAIN SIDEBAR MENU
        *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
    <?php  include_once("menu.php");?>
    <!--sidebar end-->
    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
      <section class="wrapper site-min-height">        
        <div class="row mt">
		  <div class="col-lg-9">
		  <div class="col-lg-12">
            <div class="row content-panel">
              <div class="col-md-4 profile-text mt mb centered" style="height: 350px;">
                <div class="right-divider hidden-sm hidden-xs" style="height: 350px;">
                  <h4><strong><?php echo intval($teamActual->elo); ?></strong></h4>
                  <h6>ELO</h6>
                  <h4><?php echo intval($teamActual->win); ?></h4>
                  <h6>GANADOS</h6>
                  <h4><?php echo intval($teamActual->lost); ?></h4>
                  <h6>PERDIDOS</h6>
				  <h4><?php echo intval($teamActual->golFavor); ?></h4>
                  <h6>GOLES ANOTADOS</h6>
				  <h4><?php echo intval($teamActual->golContra); ?></h4>
                  <h6>GOLES PERMITIDOS</h6>	
				  <h4><?php echo intval($teamActual->segundo); ?> seg.</h4>
                  <h6>TIEMPO EN JUEGO</h6>
				  <h4><?php echo intval($teamActual->golFavor)==0?0:"1 gol cada: ".number_format((intval($teamActual->segundo)/intval($teamActual->golFavor)),2);?> seg.</h4>
                  <h6>FRECUENCIA DE GOLES</h6>	
                </div>
              </div>
              <!-- /col-md-4 -->
              <div class="col-md-4 centered profile-text">
                <h3><?php echo strtoupper($teamActual->nombre);?>  
                  <?php
                    if($control->isMyTeam($idTeamActual,$usuario))
                    {
                  ?>
                    <small><a href="#myModal" data-toggle='modal' data-target='#myModal'><i class='fa fa-edit'></i></a></small>
                    <?php 
                    }
                    ?>
                </h3>
				  <br>
				  <?php
                    if($control->isMyTeam($idTeamActual,$usuario))
                    {
                  ?>
				  <!-- Modal -->
        			 <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
        			  <div class="modal-dialog">				  
        				<div class="modal-content" style="">
        					<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="">
        					  <span aria-hidden="true">&times;</span>
        				    </button>
        					<div class="modal-body" style="">
        						 <h3>Cambiar Nombre Equipo</h3>
        						 <form class='form' method='post' action="team.php">
        						     <div class='form-group' id='formGroup'>
        						         <input class='form-control' type="text" name='inName' id='inName' value="<?php echo $team->nombre;?>" onKeyUp="checkName()">
        						         <input type="hidden" value="set" name='setName'>
        						         <span class="text-info" id="infoError"></span><p>
        						         <input class='btn btn-primary' type="submit" value="Cambiar" id="btnSubmit">
        						     </div>
        						 </form>
        					</div>
        				</div>
        			  </div>
        			</div>
        			 <?php 
                    }
                    ?>
			
                <p>	
					<div class="col-lg-6 col-md-6 col-sm-6 centered">
						<div class="profile-text">						  
						  <img src="../images/tshirt/<?php echo $teamActual->tshirt."1.png";?>" style="width: 50px;height: 120px;" class="img">
						  <h5 class="mt badge badge-success">HOME CLUB</h5>	  						  
						</div>
					  </div>
				    <div class="col-lg-6 col-md-6 col-sm-6 centered">
						<div class="profile-text">						  
						  <img src="../images/tshirt/<?php echo $teamActual->tshirt2."10.png";?>" style="width: 50px;height: 120px;" class="">
						  <h5 class="mt badge badge-warning">VISITADOR</h5>	  						  
						</div>
					  </div>					 
				</p>                 				
                <p>	
				
				<?php
					if($idTeam!=$idTeamActual){	
				?>
				<button class="btn btn-theme" onClick='jugarAmistoso(<?php echo $idTeam;?>,<?php echo $idTeamActual;?>);' id="btnJugar"><i class="fa fa-gamepad"></i> Jugar Amistoso</button>
				<?php }?>	
				<div class="alert alert-info" style="display:none;" id="pnInfo">
					<div class="" id="carga" style=" position: relative; float: right;"></div>
					<b>Ejecutando partido...</b>
					<p> 
						<small><?php echo $team->nombre;?></small> <img src="../images/<?php echo $team->escudo;?>" class="img-circle" style="width: 30px;height: 30px;"> - <img src="../images/<?php echo $teamActual->escudo;?>" class="img-circle" style="width: 30px;height: 30px;"><small><?php echo $teamActual->nombre;?></small>
					</p>					
				</div>	
				
				<div class="" style="display:none;" id="pnResult">					
					<b>Score</b>
					<p> 
						<table class="table table-condensed table-sm">
						<tr>
							<td id="gol1" style="font-size: 2rem; text-align: right; border-right: 1px solid #252525">1</td>
							<td id="gol2" style="font-size: 2rem; text-align: left">0</td>
						</tr>
						<tr>
							<td id="name1" class="text-info" style="font-size: 1rem; text-align: right; border-right: 1px solid #252525;"><?php echo strtoupper($team->nombre);?></td>
							<td id="name2" class="text-info" style="font-size: 1rem; text-align: left;"><?php echo strtoupper($teamActual->nombre);?></td>
						</tr>
						<tr>
							<td colspan="2" id="divVerGame" style="font-size: 1.5rem;" class="alert alert-success centered">Ver Partido</td>							 
						</tr>							
						</table>						
					</p>					
				</div>
				
				</p>
              </div>
              <!-- /col-md-4 -->
              <div class="col-md-4 centered">
                <div class="profile-pic">
                  <p><img src="../images/<?php echo $teamActual->escudo;?>" class="img-circle"></p>
                  <p>
                    <span class="badge bg-theme" style="font-size: 25px"><?php echo $teamActual->ranking;?></span>
                  </p>
                </div>
              </div>
              <!-- /col-md-4 -->
            </div>
            <!-- /row -->
          </div>
			<!-- /row -->
          
          <!-- /col-md-12 -->
		  <div class="col-lg-12 mt">
            <div class="row content-panel">
              <div class="panel-heading">
                <ul class="nav nav-tabs nav-justified">
                  <li class="active">
                    <a data-toggle="tab" href="#ligas">Ligas</a>
                  </li>
                  <li>
                    <a data-toggle="tab" href="#amistosos" class="contact-map">Ultimos partidos amistosos</a>
                  </li>
                  <li>
                    <a data-toggle="tab" href="#deliga">Ultimos partidos de liga</a>
                  </li>
                  <?php if($verTactica==true){?>
                  <li>
                    <a data-toggle="tab" href="#tactica"><i class='fa fa-gift'  style="font-size:20px;"></i>  Posicion y Fuerza</a>
                  </li>
                  
                  <?php if($verCode==true){?>
                  <li>
                    <a data-toggle="tab" href="#code"><i class='fa fa-gift' style="font-size:20px;"></i>  Codigo</a>
                  </li>
                  <?php }
                  }?>
                </ul>
              </div>
              <!-- /panel-heading -->
              <div class="panel-body">
                <div class="tab-content">					
                  <div id="ligas" class="tab-pane active">
                    <div class="row">
					  <div class="col-lg col-lg detailed">
					   <h4><i class="fa fa-trophy"></i> Ligas en Espera</h4>	
                       <div class="fontawesome-list row">
						   <?php 
						   $ligasEspera=$control->getLigasenEstado(0,$idTeamActual);
						   	foreach($ligasEspera as $liga)
							{	
							
						   ?>    
						  		<div class="fa-hover col-md-3 col-sm-4" style="overflow: hidden">
									<a href="league.php?i=<?php echo $liga['idLiga'];?>">
										<i class="fa fa-trophy"></i>
										<?php echo $liga['nombre'];?> [<?php echo $liga['tipo']==1?"Grupos":"TCT";?>, <?php echo $liga['cantTeams']." equipos.";?>]
									</a>
						        </div>	
						   <?php 
							
						   }
						   ?>
						</div>	
					  </div>
						<div class="col-lg detailed">
						<h4><i class="fa fa-trophy"></i> Ligas en Ejecución</h4>	
                       <div class="fontawesome-list row">
						   <?php 	
						   $ligasExec=$control->getLigasenEstado(1,$idTeamActual);
						   	foreach($ligasExec as $liga)
							{
							 
						   ?>    
						  		<div class="fa-hover col-md-3 col-sm-4" style="overflow: hidden">
									<a href="league.php?i=<?php echo $liga['idLiga'];?>">
										<i class="fa fa-trophy"></i>
										<?php echo $liga['nombre'];?> [<?php echo $liga['tipo']==1?"Grupos":"TCT";?>, <?php echo $liga['cantTeams']." equipos.";?>]
									</a>
						        </div>	
						   <?php 
							 	
						   }
						   ?>
						</div>	
					  </div>
						<div class="col-lg detailed">
						<h4><i class="fa fa-trophy"></i> Ligas Terminadas</h4>	
                       <div class="fontawesome-list row">
						   <?php 
						   $ligasFinish=$control->getLigasenEstado(2,$idTeamActual);
						  $lugares=array('1ero','2do','3ero','4to','5to','6to','7mo','8vo','9no','10mo','11mo','12mo','13mo','14mo','15mo','16mo','17mo','18mo','19mo','20mo','21ero','22do','23ero','24to','25to','26to','27mo','28vo','29no','30mo','31ero','32do');
						   
						   	foreach($ligasFinish as $liga)
							{	
								
								  $fin= explode(",",$liga['ordenFinal']);
								  $pos= array_search($idTeamActual,$fin);
								  	
						   ?>    
						  		<div class="fa-hover col-md-4 col-sm-4" style="overflow: visible;">
									<a href="league.php?i=<?php echo $liga['idLiga'];?>" style="overflow: hidden;">
										<?php 
											if($pos<3)						   
							   					{
							   						if($pos==0)
							   							echo "<img src='../images/gold.png' alt='GOLD' style='width:15px;'>";
							   						if($pos==1)
							   						echo "<img src='../images/silver.png' alt='silver' style='width:15px;'>";
							   						if($pos==2)
							   						echo "<img src='../images/bronz.png' alt='bronce' style='width:15px;'>";
							   						
												}
											else
												echo "<i class='fa fa-trophy'></i>";
										?>
										
										<?php 
											echo "<small class='text-warning'>(".$lugares[$pos].")</small><small style='font-size:10px;overflow:hidden'>".$liga['nombre']."</small> ";
										?>
									</a>
						        </div>	
						   <?php 
								
						   }
						   ?>
						</div>	
						</div>	
                    <!-- /OVERVIEW -->
                  </div>
					</div>
                  <!-- /tab-pane -->
                  <div id="amistosos" class="tab-pane">
                    <div class="row">
					  <div class="col-lg-12 detailed">	
					   <h4></i></h4>	
                       <div class="fontawesome-list row">
						   <?php 
						   $amistosos=$control->getFriendGame($idTeamActual,50);
						   
						   	foreach($amistosos as $game)
							{							  
							   $team2= new Team($game['idTeam2']);	
							   $win=" alert-success text-success";						   	
							   $gol1=$game['gol1'];
							   $gol2= $game['gol2'];
							   if($game['idTeam2']==$idTeamActual){							    	
								 $team2=new Team($game['idTeam1']);		
								 $gol1=$game['gol2'];
							   	 $gol2= $game['gol1'];
							   }
							    
								if($game['winner']!=$idTeamActual)
									$win="alert-danger text-danger";
								
								$cadena="<a href='show.php?g=".$game['idFriendly']."&type=f' class='".$win."'><i class='fa fa-gamepad ".$win."'></i>".$gol1." - ".$gol2." </a><img alt='avatar' src='../images/".$team2->escudo."' style='width: 15px;'>"."  <a href='team.php?i=".$team2->idTeam."' class='".$win."'>".$team2->nombre."</a>";
								
								
						   ?>  
						  		<div class="col-md-4 col-sm-4 col-lg-4 boton <?php echo $win;?>"></i>
									<?php echo $cadena;?>									
						        </div>	
						   <?php 							  
						   }
						   ?>
						</div>	
                      <!-- /col-md-6 -->
                     </div>
					</div>	
                    <!-- /row -->
                  </div>
                  <!-- /tab-pane -->
                  <div id="deliga" class="tab-pane">
                    <div class="row">
					  <div class="col-lg-12 col-md-12">						   	
                       <div class="fontawesome-list row">
						   <?php 
						   $deliga=$control->getGamesdeLiga($idTeamActual,50);
						   
						   	foreach($deliga as $game)
							{							  
							   $team2= new Team($game['idTeam2']);	
							   $win=" alert-success text-success";						   	
							   $gol1=$game['gol1'];
							   $gol2= $game['gol2'];
							   if($game['idTeam2']==$idTeamActual){							    	
								 $team2=new Team($game['idTeam1']);		
								 $gol1=$game['gol2'];
							   	 $gol2= $game['gol1'];
							   }
							    	
								if($game['winner']!=$idTeamActual)
									$win=" alert-danger text-danger";
								
								$cadena="<a href='show.php?g=".$game['idGame']."&type=l'><i class='fa fa-gamepad".$win."'></i>".$gol1." - ".$gol2." </a><img alt='avatar' src='../images/".$team2->escudo."' style='width: 15px;'>"."  <a href='team.php?i=".$team2->idTeam."'>".$team2->nombre."</a>
								        <a href='league.php?i=".$game['idLiga']."><i class='fa fa-trophy'></i></a>";
								
								
						   ?>  
						  		<div class="col-md-4 col-sm-4 boton <?php echo $win;?>"><i class="fa fa donut-chart"></i>
									<?php echo $cadena;?>									
						        </div>	
						   <?php 							  
						   }
						   ?>
						</div>	
                      <!-- /col-md-6 -->
                     </div>
					</div>	
                    <!-- /row -->
                  </div>
                  <!-- /tab-pane -->
                  <?php if($verTactica==true){?>
                  <!-- /tab-pane -->
                  <div id="tactica" class="tab-pane">
                    <div class="row">
					  <div class="col-lg-12 col-md-12 centered">						   	
                        <?php 
                          require("tactica2.php");
                        ?>	
                      <!-- /col-md-6 -->
                     </div>
					</div>	
                    <!-- /row -->
                  </div>
                  <!-- /tab-pane -->
                  
                  <?php if($verCode==true){?>
                  <!-- /tab-pane -->
                  <div id="code" class="tab-pane">
                    <div class="row">
					  <div class="col-lg-12 col-md-12">	
					    
                        <?php
                           $t= new Team($idTeamActual,true);
                           echo $t->code;
                        ?>
                         
                      <!-- /col-md-6 -->
                     </div>
					</div>	
                    <!-- /row -->
                  </div>
                  <!-- /tab-pane -->
                 <?php }}?> 
                </div>
                <!-- /tab-content -->
              </div>
              <!-- /panel-body -->
            </div>
            <!-- /col-lg-12 -->
          </div>
	  	  </div>
	
		  <!-- **********************************************************************************************************************************************************
              RIGHT SIDEBAR CONTENT
              *********************************************************************************************************************************************************** -->
          <div class="col-lg-3 ds">
            
          </div>	
        </div>       
      </section>
      <!-- /wrapper -->
    </section>
    <!-- /MAIN CONTENT -->
    <!--main content end-->
    <!--footer start-->
    <footer class="site-footer">
      <div class="text-center">
        <p>
          &copy; Copyrights <strong>futbolinCode</strong>. All Rights Reserved
        </p>
        <div class="credits">
          
        </div>
        <a href="team.php#" class="go-top">
          <i class="fa fa-angle-up"></i>
          </a>
      </div>
    </footer>
    <!--footer end-->
  </section>
  <!-- js placed at the end of the document so the pages load faster -->
 
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
  <script src="../js/codemirror.js"></script>	
  <script src="../js/general.js"></script>	
  <script>
	var usuario= <?php echo $usuario;?>;
  </script>	
</body>

</html>
