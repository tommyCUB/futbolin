<?php
include_once("cabecera.php"); 
$team= new Team(intval($idTeam),true);
$datos=null;
$codigo= $team->code;
$themas= $control->getListadoThemas();
$modes= $control->getListadoModes();
$teamPera=$control->getTeamPera($team->elo,$idTeam);

if(isset($_POST['slTheme']))
{
	$theme=$_POST['slTheme'];
	$mode="futbolinCode";
	$control->setThemeMode($usuario,$theme,$mode);
	$user= $control->getUser($usuario);
}

if(!$control->isMyTeam($idTeamActual,$usuario)){
   
   die();
}
?>

<!DOCTYPE html>
<html lang="es">
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Futbolin">
  <meta name="author" content="Tommy Rod ">
  <meta name="keyword" content="Futbolin, Programación, Futbol, Código, Inteligencia, Programador, Informatic, Code, Artificial Intelligence">
  <title>Editor Código <?php echo $team->nombre;?></title>

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
	<link rel="stylesheet" href="../css/codemirror.css">
	<link rel="stylesheet" href="../css/code.css">   
	<link rel="stylesheet" href="../js/addon/hint/show-hint.css">     
	<link rel="stylesheet" type="text/css" href="../lib/gritter/css/jquery.gritter.css" />	
	<link rel="stylesheet" href="../css/theme/<?php echo $user['theme'];?>.css">
</head>

<body class="black-bg">
	
<script src="https://www.gstatic.com/firebasejs/6.3.4/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.10.1/firebase-auth.js"></script>	
<script src="../js/firebase.js"></script>
<script src="../lib/jquery/jquery.min.js"></script>
<script src="../js/code.js"></script>	  
<script src="../js/codemirror.js"></script>	
<script src="https://kit.fontawesome.com/070081e2e4.js"></script>
<script src="../js/mode/<?php echo $user['mode'];?>.js"></script>		
<input type="hidden" value='<?php echo $user['theme'];?>' id="inTheme">	
  <section id="container" style="">
    <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
    <!--header start-->
      <?php 
        include_once("menu2.php");
      ?>
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
       <div class='col-lg-9 col-md-9 col-sm-12'>
			<div class="col-lg-7 col-md-7 col-sm-9 black-bg" style="position: fixed;z-index: 100; display: block;" id="divInfo">
			   <span	class='text-danger centered' id='msjError'></span>
			   <button class="btn btn-primary" id="checkBtn" style="visibility:visible;" onClick="check()"><i class="fa fa-check"></i> Check</button>					
				<button  class="btn btn-success" style="visibility:hidden;" id="saveBtn" onClick="save()"> <i class="fa fa-save"></i> Save</button>
				
				<div class="col-lg-12 col-md-12 " id="errors">					 
				</div> 	
				
				<div class="inline-block centered" style="border-left: 1px solid white;border-right: 1px solid white; padding-left: 5px; padding-right: 5px;" id="divTest">
					
					<select class="inline-block" id="slPera" name="slPera" style="clear: both">
						<?php
							foreach($teamPera as $tim)
							{
								echo "<option value='".$tim['idTeam']."'>".$tim['nombre']." [elo: ".intval($tim['elo'])."]</option>";
							}
						?>						
					</select>
					<input class="btn btn-warning inline-block" type="button" value="Test" style="clear: both; " onClick="jugarAmistoso(<?php echo $idTeam;?>);" id="btnTest">
					
					<img src="../images/loading.gif" alt="" id="loading" style="width: 20px; display: none;">
					<span id="spResultado" class="inline-block text-info" style="padding-left: 5px;"></span>				
				</div>	
				
				
			    <a class="" href="#myModal2" data-toggle='modal' data-target='#myModal2' style="position: relative; z-index: 10000;"><span style="font-size: 20px; padding-left: 4px;"><i class="fa fa-question-circle"></i></span></a>
				<img src="../images/campo3.png" style="width: 20px; cursor: pointer; border-left: 1px solid white; margin-left:10px;" alt="" title="Posicion Equipo" onClick="toggleTactica()">
			
				<div style="width: auto; height: auto; position: absolute; bottom: -20; right: 0px; float:right;" id="divTactica">
				    
				    <canvas id="cuadro" style="position:absolute;left:30px;top:0px;z-index:10000; width:277px;height:369px; cursor:url(../images/pincel.cur),pointer;"></canvas>
				   <?php require("tactica2.php");?>
				   <div class="col-lg-12 col-md-12 centered">
    				    <button class='btn-link' onclick="activarLapiz()"><i class='fa fa-pencil display-3'></i></button>  
    			        <button class='btn-link' onclick="activarGoma()"><i class='fa fa-eraser display-3'></i></button>  
    			        <button class='btn-link' onclick="limpiarCanvas();"><i class='fa fa-file-o display-3'></i></button> 
				    </div>
				</div>
				
			    
			</div>	                
         	<div class="col-lg-12 col-md-12" style="" id="divContCode">
				   
					<input type="hidden" value="<?php echo $idTeam; ?>" name="idTeam">  
					<textarea id="myTextArea" style="width: 100%; min-height: 900px;"><?php echo $codigo;?></textarea>	  
            </div> 
			<div class="col-lg-9" id="divTheme" style="position: absolute;">				
				<form action="code.php" method="post">
				<h4>Tema:</h4>
				<select name="slTheme" id="slTheme">
					<?php 
						foreach($themas as $th)
						{
							$selected="";							
							$nombre= explode(".",$th);
							
							if($nombre[0]==$user['theme'])
								$selected=" selected ";
							
							echo "<option ".$selected." value='".$nombre[0]."'>".$nombre[0]."</option>" ;
						}
					?>				
					
				</select>				
				<input type="submit" value="Cambiar">
				</form>
			</div>
		 	
				
			<!-- **********************************************************************************************************************************************************
              RIGHT SIDEBAR CONTENT
              *********************************************************************************************************************************************************** -->
          <div class="col-lg-3">
           
          </div>
		
		</div>  
			 <div id="linea"></div>
			 <div id="divInterval" style="display:none">100</div>
			 <div id="idInterval" style="display:none"></div>
			 <div id="divTurno" style="display:none">0</div>  
			 <div id="divTiempo" style="display:none">0</div> 
		  
		  
			 <!-- Modal -->
			 <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="left:-250px;">
			  <div class="modal-dialog">				  
				<div class="modal-content" style="width: auto; margin:0px; padding: 0px; background-color: transparent;">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="z-index: 10000;">
					  <span aria-hidden="true">&times;</span>
				    </button>
					<div class="modal-body" style="width: auto; margin:0px; padding: 0px;">
						<div id="contentTerreno">
						</div>							 
					</div>
				</div>
			  </div>
			</div>
		  
		  <!-- Modal -->
			 <div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="left:-300;">
			  <div class="modal-dialog">				  
				<div class="modal-content" style="width: 950px; margin:0px; padding: 0px;   background: #FFFFFF;">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="z-index: 999; font-size: 25px;">
					  <span aria-hidden="true">&times;</span>
				    </button>
					<div class="modal-body" style="width: 950px; margin:0px; padding: 0px; ">
						 <h1 class="centered">Ayuda</h1>
						  <?php require("helpCode.html");?>						
					</div>
				</div>
			  </div>
			</div>
		  
		  		        
      </section>
      <!-- /wrapper -->
    </section>
    <!-- /MAIN CONTENT -->
  
  </section>
	

<script src="../js/mode/futbolinCode.js"></script>
<script src="../js/addon/hint/show-hint.js"></script>
<script src="../js/addon/hint/futbolinCode-hint.js"></script>

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

  <!--script for this page-->
	
   
</body>

</html>
