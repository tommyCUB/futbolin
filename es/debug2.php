<?php
session_start(); 
$usuario="";
$usuario= $_SESSION['user'];
require_once("../utils/control.php");
$control= new control();

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
  header("location: ../index.php");
	die;
}
$tactica1= $control->getTactic($team1->idTeam,1);	
$forces= $tactica1['forces'];
$forces=explode(',',$forces);
?> 

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="../css/debug2.css">
<body>
<script src="../js/debug2.js"></script> 
        
          <div class="col-lg-8 col-md-8 col-sm-8">								
			   <div class="col-lg-12" >							
					<div id="divTerreno" style="text-align: center;">
					  <div class="col-lg-12 col-md-12 col-sm-12" id="tbpizarra">
							<div class="col-lg-4 col-md-4 col-sm-4" style="text-align: right;">
								<a href='team.php?i=<?php echo $team1->idTeam;?>'><?php echo $team1->nombre;?></a>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4" style="">
								<div class="col-lg-4 col-md-4 col-sm-4">
									<img src="../images/<?php echo $team1->escudo?>" style="width:30px; height:30px;margin:0px;">
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 textGoles" >
									<div class="row textGoles" style="background: #1E1E1E; padding-left: 0px; padding-right: 0px;">
										<div class="col-lg-6 col-md-6 col-sm-6 txtGoles" style="padding-right: 0px;padding-left: 0px;">
											<div id="golTeam1">0</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 txtGoles" style="padding-right: 0px;padding-left: 0px;">
											<div id="golTeam2">0</div>
										</div>
									</div>
									<div class="row">
										<b><div class="bg-theme text-center text-white" id="divReloj" style="text-align: center; position: relative; margin: 0px; width: 25px; padding-left:0.1rem; padding-rigth: 0.1rem; margin: auto; font-size: 10px;"></div></b>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4">
									<img src="../images/<?php echo $team2->escudo?>" style="width:30px; height:30px;margin:0px;">
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4" style="text-align: left;">
								<a href='team.php?i=<?php echo $team2->idTeam;?>'><?php echo $team2->nombre;?></a>
							</div>
					  </div>	

				  <div id="ball"></div>
				  <?php 
						for($i=1;$i<=11;$i++)
						{
				  ?>
				   <div id='e1p<?php echo $i;?>' class='player d-inline-block' style="background:url(../images/tshirt/<?php echo $team1->tshirt;echo $i;?>.png) no-repeat; background-size:100% 100%; text-align-center;">
					<b id="fze1p<?php echo $i;?>" style="text-align: center; position: absolute; top: -10px; float: left; font-size: 9px; color: white; width: 45px; left:-15px;"><?php echo $forces[$i-1];?></b>  
					<b id="pose1p<?php echo $i;?>" style="text-align: center; position: absolute; bottom: -10px; float: left; font-size: 9px; color: darkblue; width: 45px; left:-15px;"><?php echo $i;?></b>
					</div>
				   <div id='e2p<?php echo $i;?>' class='player d-inline-block' style="background:url(../images/tshirt/<?php echo $team2->selectTshirt($team1->tshirt);echo $i;?>.png)  no-repeat; background-size:100% 100%;"><b id="pose2p<?php echo $i;?>" style="text-align: center; position: absolute; top: -10px; float: left; font-size: 9px; color: darkred; width: 45px; left:-15px;"><?php echo $i;?></b></div>
				   <?php
						}
				   ?>
					<div class="col-lg-12 col-md-12" style="position: absolute; z-index=9999; float: left; bottom: 45px;">		
				<div class="progress leftOne col-lg-6 col-md-6" style="width: 50%; height: 4px; margin: 0px; padding: 0px;">
				  <div id="progresLeft" class=" progress-bar progress-bar-striped bg-warning progress-bar-animated" role="progressbar" style="width: 0%; background-color: red;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="" id="txtDistie" style="font-size: 9px; position: absolute; z-index: 10000; left:48.5%; top: -15px; color:red;"></div>		
				<div class="progress  col-lg-6 col-md-6" style="width: 50%; height: 4px; margin: 0px; padding: 0px;">
				  <div id="progresRight" class=" progress-bar progress-bar-striped bg-warning progress-bar-animated" role="progressbar" style="width: 0%; background-color: blue;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				</div>	

					<div id="divControls"  class="row d-flex justify-content-between" style="margin: auto; width: 100%; position: absolute; bottom: 0px; background: #E0E0E0; opacity: 0.45; z-index: 10001" >
					<div class="d-flex">
						<a href=# class="btn btn-outline-primary" onClick="setTurno(-10);"><sup>1s</sup><i class="fa fa-step-backward"></i></a>
						<a href=# class="btn btn-outline-primary" onClick="setTurno(-5);"><sup>0.5s</sup><i class="fa fa-step-backward"></i></a>				
						<a href=# class="btn btn-outline-primary" onClick="setTurno(-1);"><sup>0.1s</sup><i class="fa fa-step-backward"></i></a>
						<a href=# class="btn btn-outline-primary" onClick="setTurno(0,1);">GOTO <small>seg</small><input type="text" name="inpSeg" id="inmSeg" class="" value="" style="text-align: center; width: 35px; display: inline;"></a>
						<a href=# class="btn btn-outline-primary" onClick="switchPlayPause()"><i class="fa fa-play"></i>/<i class="fa fa-pause"></i></a>
						<a href=# class="btn btn-outline-primary" onClick="setTurno(1);"><sup>0.1s</sup><i class="fa fa-step-forward"></i></a>
						<a href=# class="btn btn-outline-primary" onClick="setTurno(5);"><sup>0.5s</sup><i class="fa fa-step-forward"></i></a>
						<a href=# class="btn btn-outline-primary" onClick="setTurno(10);"><sup>1s</sup><i class="fa fa-step-forward"></i></a>
					</div>
				</div>
					</div>
				</div>					
		  </div> 		
			<div class="col-lg-4 col-md-4 col-sm-4 info">
				<div class="col-lg-12 col-md-12">				
					 <div class="row content-panel w-100">
					   <div class="panel-heading">
						<ul class="nav nav-tabs nav-justified">
						  <li class='active'>
							<a  data-toggle="tab" href="#tic" title="Info">
								<i class="fa fa-clock-o"></i>								
							  </a>
						  </li>
						  <li>
							<a data-toggle="tab" href="#tabsym" title="Tabla de Simbolos"><i class="fa fa-table"></i></a>
						  </li>

						  <li>
							<a data-toggle="tab" href="#comandos" title="Comandos"><i class="fa fa-terminal"></i></a>
						  </li>									
						  <li>
							<a data-toggle="tab" href="#comandos2" title="Comandos"><i class="fa fa-bug"></i></a>
						  </li>	
						</ul>
					  </div>
					  <!-- /panel-heading -->
				   <div class="panel-body">							
					<div class="tab-content">

						<!-- /tab-pane -->
						 <div class='active tab-pane' role="tabpanel" id="tic">
						<div class="row">
						  <div class="col-lg-11 col-md-10  detailed">	
						   <h4></h4>	
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
						 <div class="tab-pane fade" id="deliga">
							<div class="row">
							  <div class="col-lg-10 col-lg-offset-1 detailed">				   	
							   <div class="fontawesome-list row">

								</div>	
							  <!-- /col-md-6 -->
							 </div>
							</div>	
							<!-- /row -->
						  </div>
						<!-- /tab-pane -->				
						<div class="tab-pane fade" id="tabsym">
							<div class="row">
							 <div class="col-lg-10 col-lg-offset-1 detailed">				   	
							   <div class="fontawesome-list row">
									<div id="divTablaSym" class="d-flex" style="overflow: visible;"></div> 
								</div>	
							  <!-- /col-md-6 -->
							 </div>
							</div>	
							<!-- /row -->
							</div>

						<div class="tab-pane fade" id="comandos">
							<div class="row">
							  <div class="col-lg-10 col-lg-offset-1 detailed">				   	
							   <div class="fontawesome-list row">
									<div id="divComandos"></div>  
								</div>	
							  <!-- /col-md-6 -->
							 </div>
							</div>	
							<!-- /row -->
						  </div>					 

						<div class="tab-pane fade" id="comandos2">
							<div class="row">
							  <div class="col-lg-10 col-lg-offset-1 detailed">				   	
							   <div class="fontawesome-list row">
									<div id="divErrores"></div>  
								</div>	
							  <!-- /col-md-6 -->
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
            <!-- /col-lg-12 -->
          				</div>	
			</div>
		
			
		 <!-- **********************************************************************************************************************************************************
              RIGHT SIDEBAR CONTENT
              *********************************************************************************************************************************************************** -->
          
			
		
      
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

</body>

</html>
