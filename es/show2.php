<?php

require_once("../utils/control.php");
$control= new control();
$idGame="";
if(isset($_GET['g']))
	$idGame=$_GET['g'];

if(isset($_GET['type']))
	  $type=$_GET['type']; 

  $team1="";
  $team2="";
  $fecha="";  
  $coord="";
  $liga="";

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

 }
else{
	echo "<script>window.history.back()</script>";
	die;
}

?>  
<link rel="stylesheet" type="text/css" href="../css/show2.css">
<body>
	
 <script src="../js/show2.js"></script>		   
				<div class="centered" id="divTerreno" onClick="tooglePlay()">	
					<div class="col-lg-12 col-md-12 col-sm-12" id="tbpizarra">
						<div class="col-lg-4 col-md-4 col-sm-4" style="text-align: right;">
							<a href='team.php?i=<?php echo $team1->idTeam;?>' style="color: darkred;"><?php echo strtoupper($team1->nombre);?></a>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4" style="">
							<div class="col-lg-4 col-md-4 col-sm-4">
								<img src="../images/<?php echo $team1->escudo?>" style="width:30px; height:30px;margin:0px;">
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4" >
								<div class="row" style="background: #1E1E1E;">
									<div class="col-lg-6 col-md-6 col-sm-6">
										<div id="golTeam1">0</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6">
										<div id="golTeam2">0</div>
									</div>
								</div>
								<div class="row">
									<b><div class="bg-theme text-center text-white" id="divReloj" style="text-align: center; position: relative; margin: 0px; width: 25px; padding-left:0.1rem; padding-rigth: 0.1rem; margin: auto; font-size: 10px; z-index: 10000;">90</div></b>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4">
								<img src="../images/<?php echo $team2->escudo?>" style="width:30px; height:30px;margin:0px;">
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4" style="text-align: left; ">
							<a href='team.php?i=<?php echo $team2->idTeam;?>'style="color: darkblue;"><?php echo strtoupper($team2->nombre);?></a>
						</div>
					  </div>	
					 	
					 
										
					<div class="col-lg-12 col-md-12 centered" id="msgGol" style="height: 30%; top:20%; display: none;">
						<div style="width: 720px; height: 40%; top:40%; left:0px; background: #229DA3; opacity: 0.7;position: absolute;">
						</div>
						<img  id="imgGol" src="../images/gol.png" alt="" style="position: absolute; right: -130px; top:0px;">
						<div id="imgTeam2Gol" style="position: absolute;left:40%; top: 20%;">
							<img class="img-circle" src="../images/<?php echo $team2->escudo?>" style="width:150px; height:150px;">
							<img   src="../images/goll.png" alt="" style="position: absolute; top:-80px; left:-100px;">
						</div>	
						<div id="imgTeam1Gol" style="position: absolute;left:40%; top: 20%;">
							<img class="img-circle" src="../images/<?php echo $team1->escudo?>" style="width:150px; height:150px;">
							<img  src="../images/goll.png" alt="" style="position: absolute; top:-80px; left:-100px;">
						</div>
					</div>
					
					<div class="col-lg-12" id="divFulltime" style="display: none; top:20%; position: absolute; z-indez:20000;">
						<span class="h2" style="width:120px; height:120px;position: absolute;left:53%;top:77px; z-index: 100000;color: beige;"><?php echo $game['gol2'];?></span>
						<span class="h2"  style="width:120px; height:120px;position: absolute;left:27%;top:77px; z-index: 100000; color: beige;"><?php echo $game['gol1'];?></span>
						
						<span class="h6" id="fullNombre1" style="width:120px; height:120px;position: absolute;left:57%;top:135px; z-index: 100000;color: beige; text-align: right"><?php echo $team2->nombre?></span>
						
						<span class="h6" style="width:85px;position: absolute;left:27%;top:135px; z-index: 100000; color: beige; text-align: left"><?php echo $team1->nombre?></span>
						
						<?php
							$left="5";
							if($game['winner']==$game['idTeam2'] && $game['gol1']<$game['gol2'])
								$left="85";								
						?>
						<img class="img-circle" src="../images/winner.png" style="width:70px; height:70px;position: absolute;left:<?php echo $left;?>%; top:30px; z-index: 200000;">						
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
					
					<div class="col-lg-12 col-md-12" style="position: absolute; z-index=9999; float: left; bottom: 15px;">		
						<div class="progress leftOne col-lg-6 col-md-6" style="width: 50%; height: 4px; margin: 0px; padding: 0px;">
						  <div id="progresLeft" class=" progress-bar progress-bar-striped progress-bar-animated bg-warning " role="progressbar" style="width: 0%; background-color: red;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<div class="" id="txtDistie" style="font-size: 9px; position: absolute; z-index: 10000; left:48.5%; top: -15px; color:red;"></div>
						<div class="progress  col-lg-6 col-md-6" style="width: 50%; height: 4px; margin: 0px; padding: 0px;">
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
			        
          </div> 	
			<!-- **********************************************************************************************************************************************************
              RIGHT SIDEBAR CONTENT
              *********************************************************************************************************************************************************** -->
          
	 

</body>

</html>
