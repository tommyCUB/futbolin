<?php
include_once("cabecera.php");

if(isset($_GET['g']))
	  $idGame=$_GET['g'];
  if(isset($_GET['type']))
	  $type=$_GET['type']; 

  $team1="";
  $team2="";
  $fecha="";  
  $coord="";
  $liga="";
  $lista="";
  $game="";
  
 if($idGame!="" && $type!="")
  {
	if($type=='f')
	{
	  $game= $control->getGameFriendly($idGame,0); 
	  $team1= new Team($game['idTeam1']);	
	  $team2= new Team($game['idTeam2']);
	  $fecha=date("d m Y h:i:s",$game['fecha']);
	  $coord= $game['game'];
	}
	if($type=='l')
	{   
	  $game= $control->getGameLiga($idGame);  	
	  $team1= new Team($game['idTeam1']);	
	  $team2= new Team($game['idTeam2']);
	  $fecha=date("d m Y h:i:s",$game['fechaDone']);
	  $coord= $game['game'];
	  $liga= $control->getLiga($game['idLiga']);
	}  
$lista= $control->getListaGames($team1->idTeam,$team2->idTeam,$type,16);
 }
else{
	echo "<script>window.history.back()</script>";
	die;
}

?>

<!DOCTYPE html>
<html lang="es">

<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Futbolin">
  <meta name="author" content="Tommy Rod ">
  <meta name="keyword" content="Futbolin, Programación, Futbol, Código, Inteligencia, Programador, Informatic, Code, Artificial Intelligence">
  <title><?php echo $team1->nombre."  ".$game['gol1']."vs".$game['gol2']."  ".$team2->nombre;?></title>

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
  <link rel="stylesheet" type="text/css" href="../css/show.css">
  <link rel="stylesheet" type="text/css" href="../lib/gritter/css/jquery.gritter.css" />	
</head>

<body>
<script src="https://www.gstatic.com/firebasejs/6.3.4/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.10.1/firebase-auth.js"></script>
<script src="../js/firebase.js"></script>
<script src="https://kit.fontawesome.com/070081e2e4.js"></script>
<script src="../js/jquery.js"></script>	
<script src="../js/show.js"></script>

	
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
      <section class="wrapper site-min-height" style="padding-left: 0px;">        
        <div class="row">
          <div class="col-lg-9 col-md-9 col-sm-12">			  
			<div class="col-lg-12 centered" id="masterTerreno" style="">			   
				<div class="centered" id="divTerreno" onClick="tooglePlay()">	
					<div class="col-lg-12 col-md-12 col-sm-12" id="tbpizarra">
						<div class="col-lg-4 col-md-4 col-sm-4" style="text-align: right; padding-right: 0px;">
							<a href='team.php?i=<?php echo $team1->idTeam;?>'style="color: darkred;"><?php echo strtoupper($team1->nombre);?></a>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4" style="padding-left: 0px; padding-right: 0px;">
							<div class="col-lg-4 col-md-4 col-sm-4" style="text-align: right; padding-right: 0px; padding-left: 0px;">
								<img src="../images/<?php echo $team1->escudo?>" style="width:30px; height:30px;margin:0px;">
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4">
								<div class="row" style="background: #1E1E1E;">
									<div class="col-lg-6 col-md-6 col-sm-6" style="text-align: right;">
										<div id="golTeam1">0</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6" style="text-align: left;">
										<div id="golTeam2">0</div>
									</div>
								</div>
								<div class="row">
									<b><div class="text-center" id="divReloj" style="background:#1E1E1E; text-align: center; position: relative; margin: 0px; width: 25px; padding-left:0.1rem; padding-rigth: 0.1rem; border-top: 1px solid beige; margin: auto; font-size: 10px; color: beige;">90</div></b>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 bg-white" style="text-align: left; padding-left: 0px; padding-right: 0px;width: 30px;">
								<img src="../images/<?php echo $team2->escudo?>" style="width:30px; height:30px;margin:0px;">
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4" style="text-align: left; vertical-align: bottom; padding-left: 0px; margin-left: 0px;">
							<a href='team.php?i=<?php echo $team2->idTeam;?>' style="color: darkblue;"><?php echo strtoupper($team2->nombre);?></a>
						</div>
					  </div>	
					
					<button class="btn btn-navbar" onClick="cambiarLista()" style="left: 15; top:15;position: absolute;"><i class="fa fa-list-ul" ></i></button> 
					
					<div id="cajalista"  class="col-lg-5 col-sm-5 col-md-5 text-center">
						<hr>
						<span class="text-center h4" style="margin-bottom: 20px;">PARTIDOS</span>	
						<hr>
					  <?php 
							if($lista!=""){
								for($i=0;$i<count($lista);$i++)
								{
									$g= $lista[$i];
									$eq1= new Team($g['idTeam1']);
									$eq2= new Team($g['idTeam2']);

									echo "<div class='col-lg-12'><a class='text-center fill-flex' style='margin:auto;position:relative;' href='show.php?g=".$g['id']."&type=".$g['type']."' title='".$eq1->nombre." [Rk:".$eq1->ranking."] vs [Rk:".$eq2->ranking."]".$eq2->nombre." '>
									<span style='font-size:9px;'>".$eq1->nombre."</span>
									<img src='../images/".$eq1->escudo."' style='width:25px; height:25px;'>
									 ".$g['gol1']." 
									-
									 ".$g['gol2']." 
									<img src='../images/".$eq2->escudo."' style='width:25px; height:25px;'>
									<span style='font-size:9px;'>".$eq2->nombre."</span>									
									</a></div>";
								}
							}
					  ?>
											
				</div> 					
					<div class="col-lg-12 col-md-12 centered" id="msgGol" style="height: 30%; top:20%; display: none; z-index:10000;">
						<div style="width: 720px; height: 40%; top:40%; left:0px; background: #229DA3; opacity: 0.7;position: absolute;">
						</div>
						<img  id="imgGol" src="../images/gol.png" alt="" style="position: absolute; right: -130px; top:0px;">
						<div id="imgTeam2Gol" style="position: absolute;left:40%; top: 20%;">
							<img class="img-circle" src="../images/<?php echo $team2->escudo?>" style="width:150px; height:150px;">
							<img   src="../images/goll.png" alt="" style="position: absolute; top:-80px; left:-80px;">
							<h1 class="" style="position: absolute; top: 130px; width: 100%;" ><?php echo strtoupper($team2->nombre);?></h1>
						</div>	
						<div id="imgTeam1Gol" style="position: absolute;left:40%; top: 20%;">
							<img class="img-circle" src="../images/<?php echo $team1->escudo?>" style="width:150px; height:150px;">
							 
							<h1 class="" style="position: absolute; top: 130px; width: 150%;"><?php echo strtoupper($team1->nombre);?></h1>
						</div>
						
					</div>
					
					<div class="col-lg-12" id="divFulltime" style="display: none; top:20%; position: absolute; z-indez:20000;">
						<span class="h2" style="width:120px; height:120px;position: absolute;left:53%;top:77px; z-index: 100000;color: beige;"><?php echo $game['gol2'];?></span>
						<span class="h2"  style="width:120px; height:120px;position: absolute;left:27%;top:77px; z-index: 100000; color: beige;"><?php echo $game['gol1'];?></span>
						
						<span class="h6" id="fullNombre1" style="width:120px; height:120px;position: absolute;left:57%;top:135px; z-index: 100000;color: beige; text-align: right"><?php echo $team2->nombre?></span>
						
						<span class="h6" style="width:85px;position: absolute;left:27%;top:135px; z-index: 100000; color: beige; text-align: left;"><?php echo $team1->nombre?></span>
						
						<?php
							$left="5";
							if($game['winner']===$game['idTeam2'] && $game['gol1']<$game['gol2'])
								$left="85";								
						?>
						<img class="img-circle" src="../images/winner.png" style="width:70px; height:70px;position: absolute;left:<?php echo $left;?>%; top:30; z-index: 200000;">						
						<img class="img-circle" src="../images/<?php echo $team1->escudo?>" style="width:120px; height:120px;position: absolute;left:10%;top:40px; z-index: 100000;">
						<img class="img-circle" src="../images/<?php echo $team2->escudo?>" style="width:120px; height:120px;position: absolute;left:73.5%;top:40px; z-index: 100000;">
						
						<img id="maqueta" src="../images/fulltime.png" alt="" style="left:0px;top:50%; position: absolute;z-index: 20000; width: 100%;">
					</div>
					
					
					
				  <div id="ball"></div>
				  <?php
					
					for($i=1;$i<=11;$i++)
						{
				  ?>
				   <div id='e1p<?php echo $i;?>' class='player d-inline-block' style="background:url(../images/tshirt/<?php echo $team1->tshirt;echo $i;?>.png) no-repeat; background-size:100% 100%;"><b style="position: absolute; text-align: center; bottom:-10px; font-size: 9px; color: black; width: 5px;"><?php echo $i;?></b>
					</div>
				   <div id='e2p<?php echo $i;?>' class='player d-inline-block' style="background:url(../images/tshirt/<?php echo $team2->selectTshirt($team1->tshirt);echo $i;?>.png)  no-repeat; background-size:100% 100%;"><b class="centered" style="position: absolute; bottom:-10px; font-size: 9px; color: white;"><?php echo $i;?></b></div>
				   <?php
						}
				   ?>         	  

					 <div class="col-12 centered" style="position:absolute; bottom: 25px;">   	
							  
							<div class="col"><a href="#" onClick="stop()"><i class="fas fa-stop-circle"></i></a></div>
							<div class="col"><a href="#" onClick="play()"><i class="fas fa-play-circle"></i></a></div>
							<div class="col"><a href="#" onClick="pause()"><i class="fas fa-pause-circle"></i></a></div>
							<div class="col"><a href='#' id="video-fullscreen" title="fullscreen"><i class="fa fa-window-maximize"></i></a></div>
							 
					  </div>
					
					<div class="col-lg-12 col-md-12" style="position: absolute; z-index=9999; float: left; bottom: 10px;">		
						<div class="progress leftOne col-lg-6 col-md-6" style="width: 50%; height: 4px; margin: 0px; padding: 0px;background-color: transparent;">
						  <div id="progresLeft" class=" progress-bar progress-bar-striped progress-bar-animated bg-warning " role="progressbar" style="width: 0%; background-color: red;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<div class="" id="txtDistie" style="font-size: 9px; position: absolute; z-index: 10000; left:49%; top: -15px; color:red;"></div>
						<div class="progress  col-lg-6 col-md-6" style="width: 50%; height: 4px; margin: 0px; padding: 0px;background-color: transparent;">
						  <div id="progresRight" class=" progress-bar progress-bar-striped progress-bar-animated bg-warning " role="progressbar" style="width: 0%;background-color: blue;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>	
				</div> 	  	   
			</div>				
			
			 <div id="divMemory" style="display:none">
				<?php 
				   echo $coord;		   
				?>
			 </div>  
			  <div id="linea"></div>
			 <div id="divInterval" style="display:none">100</div>
			 <div id="idInterval" style="display:none"></div>
			 <div id="divTurno" style="display:none">0</div>  
			 <div id="divTiempo" style="display:none">0</div>         
          </div> 	
			<!-- **********************************************************************************************************************************************************
              RIGHT SIDEBAR CONTENT
              *********************************************************************************************************************************************************** -->
          <div class="col-lg-3 ">
            <!--COMPLETED ACTIONS DONUTS CHART-->
            
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
          &copy; Copyrights <strong>Dashio</strong>. All Rights Reserved
        </p>
        <div class="credits">
          <!--
            You are NOT allowed to delete the credit link to TemplateMag with free version.
            You can delete the credit link only if you bought the pro version.
            Buy the pro version with working PHP/AJAX contact form: https://templatemag.com/dashio-bootstrap-admin-template/
            Licensing information: https://templatemag.com/license/
          -->
          Created with Dashio template by <a href="https://templatemag.com/">TemplateMag</a>
        </div>
        <a href="blank.html#" class="go-top">
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
