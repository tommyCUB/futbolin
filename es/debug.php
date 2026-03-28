<?php
include_once("cabecera.php");
$team= new Team(intval($idTeam),true);
if(isset($_GET['i']))
{
	$idFriendly= $_GET['i'];
	$game= $control->getGameFriendly($idFriendly,1);
	
	$team1= new Team($game['idTeam1']);
	$team2= new Team($game['idTeam2']);	
		
	
	
	if($usuario!=$team1->iduser)
	{
		echo "<span class='alert alert-danger display-4'>No puede acceder a esta página. Este no es su equipo.</span>";
		die();
	}
}
else
{
  header("location: index.php");
	die;
}
$tactica1= $control->getTactic($team1->idTeam,1);	
$forces= $tactica1['forces'];
$forces=explode(',',$forces);

?>
 

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Futbolin">
  <meta name="author" content="Tommy Rod ">
  <meta name="keyword" content="Futbolin, Programación, Futbol, Código, Inteligencia, Programador, Informatic, Code, Artificial Intelligence">
  <title>Debugging...</title>

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
  <link rel="stylesheet" href="../css/debug.css">
  <link rel="stylesheet" href="../css/codemirror.css">
  <link rel="stylesheet" href="../css/midnight.css">	
  <link rel="stylesheet" type="text/css" href="../lib/gritter/css/jquery.gritter.css" />

  <!-- =======================================================
    Template Name: Dashio
    Template URL: https://templatemag.com/dashio-bootstrap-admin-template/
    Author: TemplateMag.com
    License: https://templatemag.com/license/
  ======================================================= -->
</head>

<body>
<script src="https://www.gstatic.com/firebasejs/6.3.4/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.10.1/firebase-auth.js"></script>
<script src="../js/firebase.js"></script>
  <script src="https://kit.fontawesome.com/070081e2e4.js"></script>
<script src="../js/jquery.js"></script>
	<script src="../js/codemirror.js"></script>

<script src="../js/python.js"></script>
 <script src="../js/debug.js"></script>
	 
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
      <section class="wrapper site-min-height" style="padding-left: 0px; padding-right: 0px; margin-left: 0px; margin-right: 0px; ">       
        <div class="row">			
          <div class="col-lg-9 col-md-9">			   
					<div class="col-lg-8 col-md-9">						
					   <div id="divTerreno" style="text-align: left;float: left;" onMouseOver="controles(1)" onMouseOut="controles(0)">
						  
						   <div class="col-lg-12 col-md-12 col-sm-12" id="tbpizarra">
								<div class="col-lg-4 col-md-4 col-sm-4" style="text-align: right; padding-right: 0px;">
									<a href='team.php?i=<?php echo $team1->idTeam;?>'style="color: darkred;" id='aNombre1'><?php echo strtoupper($team1->nombre);?></a>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4" style="padding-left: 0px; padding-right: 0px;">
									<div class="col-lg-3 col-md-3 col-sm-3" style="text-align: right; padding-right: 0px; padding-left: 0px;">
										<img src="../images/<?php echo $team1->escudo?>" style="width:20px; height:20px;margin:0px;">
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6">
										<div class="row" style="background: #1E1E1E;">
											<div class="col-lg-6 col-md-6 col-sm-6" style="">
												<div id="golTeam1">0</div>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6" style="">
												<div id="golTeam2">0</div>
											</div>
										</div>
										<div class="row">
											<b><div class="text-center" id="divReloj" style="background:#1E1E1E; text-align: center; position: relative; margin: 0px; width: 25px; padding-left:0.1rem; padding-rigth: 0.1rem; border-top: 1px solid beige; margin: auto; font-size: 10px; color: beige;">90</div></b>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 bg-white" style="text-align: left; padding-left: 0px; padding-right: 0px;width: 20px;">
										<img src="../images/<?php echo $team2->escudo?>" style="width:20px; height:20px;margin:0px;">
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4" style="text-align: left; vertical-align: bottom; padding-left: 0px; margin-left: 0px;">
									<a href='team.php?i=<?php echo $team2->idTeam;?>' style="color: darkblue;" id='aNombre2'><?php echo strtoupper($team2->nombre);?></a>
								</div>
							  </div> 				   
						  
						  <div id="ball"></div>
						  <?php 
								for($i=1;$i<=11;$i++)
								{
						  ?>
						   <div id='e1p<?php echo $i;?>' class='player d-inline-block' style="background:url(../images/tshirt/<?php echo $team1->tshirt;echo $i;?>.png) no-repeat; background-size:100% 100%; text-align-center;">
							<b id="fze1p<?php echo $i;?>" style="text-align: center; position: absolute; top: -10px; float: left; font-size: 9px; color: white; width: 45px; left:-15px;"><?php echo $forces[$i-1];?></b>
							   <b id="pose1p<?php echo $i;?>" style="display: none;"></b>
							</div>
						   <div id='e2p<?php echo $i;?>' class='player d-inline-block' style="background:url(../images/tshirt/<?php echo $team2->tshirt;echo $i;?>.png)  no-repeat; background-size:100% 100%;">
						   <b id="pose2p<?php echo $i;?>" style="display: none;"></b>
						   </div>
						   <?php
								}
						   ?>
							<div id="divControls"  class="row d-flex justify-content-between" style="margin: auto; width: 100%; position: absolute; bottom: 4px; background: #E0E0E0; opacity: 0.45; z-index: 9999" >
							<div class="d-flex">
								<a href=# class="btn btn-outline-primary" onClick="setTurno(-10);"><sup>1s</sup><i class="fas fa-step-backward"></i></a>
								<a href=# class="btn btn-outline-primary" onClick="setTurno(-5);"><sup>0.5s</sup><i class="fas fa-step-backward"></i></a>				
								<a href=# class="btn btn-outline-primary" onClick="setTurno(-1);"><sup>0.1s</sup><i class="fas fa-step-backward"></i></a>
								<a href=# class="btn btn-outline-primary" onClick="setTurno(0,1);">GOTO <small>seg</small><input type="text" name="inpSeg" id="inmSeg" class="" value="" style="text-align: center; width: 35px; display: inline;"></a>
								<a href=# class="btn btn-outline-primary" onClick="switchPlayPause()"><i class="fas fa-play"></i>/<i class="fas fa-pause"></i></a>
								<a href=# class="btn btn-outline-primary" onClick="setTurno(1);"><sup>0.1s</sup><i class="fas fa-step-forward"></i></a>
								<a href=# class="btn btn-outline-primary" onClick="setTurno(5);"><sup>0.5s</sup><i class="fas fa-step-forward"></i></a>
								<a href=# class="btn btn-outline-primary" onClick="setTurno(10);"><sup>1s</sup><i class="fas fa-step-forward"></i></a>
							</div>
						</div>						   
					   		<div class="col-lg-12 col-md-12" style="z-index=10000;position: absolute; bottom: 0px;">		
								<div class="progress leftOne col-lg-6 col-md-6" style="width: 50%; height: 4px; margin: 0px; padding: 0px; background-color: transparent;">
								  <div id="progresLeft" class=" progress-bar progress-bar-striped progress-bar-animated bg-warning " role="progressbar" style="width: 0%; background-color: red;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<div class="" id="txtDistie" style="font-size: 9px; position: absolute; z-index: 10000; left:49%; top: -15px; color:red;"></div>
								<div class="progress  col-lg-6 col-md-6" style="width: 50%; height: 4px; margin: 0px; padding: 0px; background-color: transparent;">
								  <div id="progresRight" class=" progress-bar progress-bar-striped progress-bar-animated bg-warning " role="progressbar" style="width: 0%;background-color: blue;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
							</div>
						</div>				     	
					</div>			        
			  
					<div class="col-lg-4 col-md-9">
           					 <div class="row content-panel">
           					   <div class="panel-heading">
								<ul class="nav nav-tabs nav-justified">
								 
								  <li class='active'>
									<a data-toggle="tab" href="#tic">
										<i class="fa fa-clock-o"></i>								
									  </a>
								  </li>
								  <li>
									<a data-toggle="tab" href="#tabsym" title="Tabla de Simbolos"><i class="fa fa-memory"></i></a>
								  </li>
								  	
							      <li>
									<a data-toggle="tab" href="#comandos" title="Comandos"><i class="fa fa-terminal"></i></a>
								  </li>	
								   <li class="">
									<a  data-toggle="tab" href="#errors" title="Errores">
										<i class="fa fa-bug"></i>
									  </a>
								  </li>		
								</ul>
							  </div>
							  <!-- /panel-heading -->
						   <div class="panel-body">							
							<div class="tab-content">
															
								<!-- /tab-pane -->
							 	 <div id="tic" class="tab-pane active">
								<div class="row">
								  <div class="col-lg-11 col-lg-offset-1 detailed">	    	
								   <div class="col-md-12">
									   <span id="aTics"> 300:400 </span><small> tics</small>
										<li>									 
											<small><b>Balón: </b></small>
											<span id="tdStatusBalon" style="width: auto;"></span>					 
										</li>
										<li>
											<small><b>Factor de desempate: </b></small>
											<span id="tdFactor"></span>
										</li>
										<li>
											<td><small><b>Estoy más cerca balón?: </b></small></td>
											<span id="tdStatusEstoy"></span>
										</li>
										<li>
											<td><small><b>Mi jugador más cerca balón: </b></small></td>
											<span id="tdStatusMi"></span>
										</li>
										<li>
											<td><small><b>Su jugador más cerca balón: </b></small></td>
											<span id="tdStatusSu"></span>
										</li>										 
								</div>	
								  <!-- /col-md-6 -->
								 </div>
								</div>	
								<!-- /row -->
							  </div>
                  <!-- /tab-pane -->
                 				 <div id="deliga" class="tab-pane ">
									<div class="row">
									  <div class="col-lg-11 col-lg-offset-1 detailed">				   	
									   <div class="fontawesome-list row">

										</div>	
									  <!-- /col-md-6 -->
									 </div>
									</div>	
									<!-- /row -->
								  </div>
					<!-- /tab-pane -->				
								<div id="tabsym" class="tab-pane">
									<div class="row">
									  <div class="col-lg-11 col-lg-offset-1 detailed">				   	
									   <div class="fontawesome-list row">
											<div id="divTablaSym" class="d-flex" style="overflow: visible;"></div> 
										</div>	
									  <!-- /col-md-6 -->
									 </div>
									</div>	
									<!-- /row -->
									</div>
					
								<div id="comandos" class="tab-pane">
									<div class="row">
									  <div class="col-lg-8 col-lg-offset-2 detailed">				   	
									   <div class="fontawesome-list row">
											<div id="divComandos"></div>  
										</div>	
									  <!-- /col-md-6 -->
									 </div>
									</div>	
									<!-- /row -->
								  </div>
							<!-- /tab-pane -->				
								<div class="tab-pane" id="errors" class="tab-pane">
									<div class="row">
									  <div class="col-lg-11 col-lg-offset-1 detailed">		   	
									   <div class="fontawesome-list row">
											<div id="divErrores"></div> 
										</div>	
									  <!-- /col-md-6 -->
									 </div>
									</div>	
									<!-- /row -->
								  </div>
								<!-- /tab-pane -->
                  <!-- /tab-pane -->
                </div>
                <!-- /tab-content -->
              </div>
              <!-- /panel-body -->
            </div>
            <!-- /col-lg-12 -->
          				</div>  
			  
			  
			  <div class="col-lg-12" style="position: relative;" id="divContCode">		   			 
					<input type="hidden" value="<?php echo $idTeam; ?>" name="idTeam">	  
					<textarea id="myTextArea" style="width: 100%; min-height: 900px; overflow: scroll; top: auto;"><?php echo $team->code;?></textarea>	  
            </div> 
		  </div>  
			
		 <!-- **********************************************************************************************************************************************************
              RIGHT SIDEBAR CONTENT
              *********************************************************************************************************************************************************** -->
          <div class="col-lg-3 col-md-3">
           
       
          </div>
			</div>  
		  </div>
		</div>
      </section>
      <!-- /wrapper -->
    </section>
    <!-- /MAIN CONTENT -->
    <!--main content end-->
    <!--footer start-->
   
	<div id="divMemory" style="visibility: hidden;height: 0px;">
		<?php 
		echo $game['game'];
		?>
	</div>
	<div id="divDebug" style="visibility: hidden;height: 0px;">
		<?php 
			echo $game['debug'];
		?>
	</div>

	<div id="divInterval" style="display:none">100</div>
	 <div id="idInterval" style="display:none"></div>
	 <div id="divTurno" style="display:none">0</div>  
	 <div id="divTiempo" style="display:none">0</div> 

    <footer class="site-footer">
      <div class="text-center">
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
<script src="../js/html2canvas.min.js"></script>
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
