<?php

include_once("cabecera.php");

if(isset($_GET['i']))
{
   $idLiga=$_GET['i'];   	  	
   	
}
else{
	echo "<script>window.history.back()</script>";
	die;
}
$liga= $control->getLiga($idLiga); 
$ligagrupo="";
$ligaTCT="";

if($liga['tipo']==1)
	$ligagrupo= $control->getLigaGrupos($idLiga);

?>
 

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Futbolin">
  <meta name="author" content="Tommy Rod ">
  <meta name="keyword" content="Futbolin, Programación, Futbol, Código, Inteligencia, Programador, Informatic, Code, Artificial Intelligence">
  <title>Liga <?php echo $liga['nombre'];?></title>

  <!-- Favicons -->
  <link href="../images/favicon.png" rel="icon">
  <link href="../images/apple-touch-icon.png" rel="apple-touch-icon">
  <!-- Bootstrap core CSS -->
  <link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <!--external css-->
  <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="../css/style.css" rel="stylesheet">
  <link href="../css/style-responsive.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/liga.css">
   <link rel="stylesheet" type="text/css" href="../lib/gritter/css/jquery.gritter.css" />

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
          <div class="col-lg-9 col-md-9 col-sm-12">			
			 	<div class="col-lg-12 col-md-12">
			  		<div class="col-lg-6 col-md-6">
						<div class="chat-room-head centered">
							  <h3> <?php echo $liga['nombre'];?></h3>							  
						</div>
						<div class="col-lg-12 col-md-12 centered">
							
							<table class="table table-striped table-responsive table-condensed">
								<tr>
									<td><small>Premio:</small></td>
									<td class="h2"><?php echo $liga['prize'];?><sup>¢</sup></td>
								</tr>
								<tr class="centered">
									<td colspan="2"><?php if($liga['tipo']==1)echo "Grupos";else echo "TCT";?>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="centered">
										<?php echo $liga['cantTeams']." equipos";?>
									</td>
								</tr>
								<?php 
								$teams="";
								if($liga['state']==0){
									$teams=explode(';',$liga['teams']);							
								?>
								<tr>
									<td colspan="2" class="centered h2">
										<?php echo ($liga['cantTeams']-(count($teams)-1))."<small> plazas</small>";?>
									</td>
								</tr>
								<?php }?>
								<tr>
									<td><small>Entrada:</small></td>
									<td class="h4"><?php echo $liga['entry'];?><sup>¢</sup></td>
								</tr>								
								<tr>
									<td><small>Desde:</small></td>
									<td><?php echo date('d/m/Y',$liga['fechaInicio']);?></td>
								</tr>
								<tr>
									<td><small>Estado:</small></td>
									<td>
										<?php 
											if($liga['state']==0)
												echo "<span class='alert-info'>Espera...</span>";
										    if($liga['state']==1)
												echo "<span class='alert-danger'>En ejecución...</span>";
											if($liga['state']==2)
												echo "<span class='alert-success'>Terminada</span>";
										?>
									</td>
								</tr>
								<tr>
									<td class="centered">
										<?php if($liga['state']==2)echo intval(($liga['fechaDone']-$liga['fechaInicio'])/60)+1;?>
									</td>
								</tr>
								<?php 							 
								if($liga['state']==0 && strpos($liga['teams'],$idTeam.";")===false){		
								?>
								<tr>
									<td colspan="2" class="">
										<form action="market.php" class="form" method="get">
											<input type="hidden" name="l" value="<?php echo $idLiga;?>">
											<input type="hidden" name="type" value="add">
											<input type="hidden" name="i" value="<?php echo $idTeam;?>">									
											<input class="form-control btn btn-sm btn-send" type="submit" value="Entrar a Liga..: <?php echo $liga['entry'];?>¢">								
										</form>
									</td>
								</tr>
								<?php }?>
							</table>
							
						</div>													
					</div>
					<div class="col-lg-6 col-md-6" style="height: 350px; overflow: scroll;">
						<?php 
							if($liga['state']==0 || $liga['state']==1)
							{
							 $teams=explode(";",$liga['teams']);	
						?>
							<table class="table table-stripped">
								<?php
									for($i=0;$i<$liga['cantTeams'];$i++)
									{									   
									   if($i<count($teams) && $teams[$i]!="")
									   {
										$eq=new Team($teams[$i]);										    
									?>
										<tr>
											<td>
												<a href="team.php?i=<?php echo $eq->idTeam;?>">
												<?php echo ($i+1)."- ";?>	
												<img src="../images/<?php echo $eq->escudo;?>" alt="" style="width: 25px;">
												<?php echo $eq->nombre."  ";?>[
												 <small>Elo:</small> <strong><?php echo intval($eq->elo);?></strong>
												 <small>Rk:</small> <strong><?php echo intval($eq->ranking);?></strong>]</a>
											</td>
										</tr>
								  <?php
										}
										else
											echo "<tr><td>".($i+1)."- </td></tr>";
									}
								?>
							</table>
						<?php 
							}						
							if($liga['state']==2)
							{
								?>
						<table class="table table-sm table-stripped table-condensed">
							<h4 class="centered">TABLA FINAL</h4>
							<?php
								 $final= explode(',',$liga['ordenFinal']); 
								  $i=0; 
								  unset($final[count($final)-1]);  
								  foreach($final as $idT)
									{									   
									   $team= new Team($idT);					    
									   echo "<tr>
									   <td>".($i+1)."</td>";
									   echo "<td>";
										if($i+1==1)
											echo "<img src='../images/gold.png' style='width:20px;height:30px'>";
									   if($i+1==2)
											echo "<img src='../images/silver.png' style='width:20px;height:30px'>";
									   if($i+1==3)
											echo "<img src='../images/bronz.png' style='width:20px;height:30px'>";
									   echo "</td>
									   <td><a href='team.php?i=".$team->idTeam."' title='Elo: ".intval($team->elo)." Rk: ".$team->ranking."'><img src='../images/".$team->escudo."' alt='' style='width:30px;height:30px;'>".strtoupper($team->nombre)."</a></td>
									   <td>";
									   	  if($i==0)
											  echo "<span class='h2'>".intval($liga['prize']*0.5)."<sup>¢</sup></span>";
									  	  if($i==1)
											  echo "<span class='h3'>".intval($liga['prize']*0.25)."<sup>¢</sup></span>";
									      if($i==2)
											  echo "<span class='h4'>".intval($liga['prize']*0.1)."<sup>¢</sup></span>";
									      if($i>2)
											  echo "<span class='h6'>".intval($liga['prize']*0.15/($liga['cantTeams']-3))."<sup>¢</sup></span>";
											  
									   echo "</td>
									   </tr>";
									  $i++;
									}	
							}
						?>
							</table>
					</div>
			  	</div>
			  	<div class="col-lg-12 col-md-12">					
					<?php
						if($liga['tipo']==1 && $liga['state']!=0){
			   			$ligagrupo= $control->getLigaGrupos($idLiga);
			   			$grupos= $ligagrupo["grupos"];
			   			$grupos= explode(":",$grupos);
			   			
			   			$letras=array('A','B','C','D','E','F','G','H');
			   			for($i=0;$i<count($grupos);$i++)
						{
							if($grupos[$i]!="")
							{
								$g=explode(";",$grupos[$i]);							
							   echo "<div class='card' style=''>
							   <div class='card-body'>
								<h3 class='card-title centered'>Grupo ".$letras[$i]."</h3>					
								<p class='card-text'>";
								echo "<table class='table table-responsive table-sm table-striped'>
								<thead>
									<tr>
										<td><b>Team</b></td>
										<td>JJ</td>
										<td>JG</td>
										<td>JP</td>
										<td>GF</td>
										<td>GC</td>
										<td>Tie</td>
									</tr>
								</thead>
								<tbody>";

								for($e=0;$e<count($g);$e++)
								{
									if($g[$e]!="")
									{
									$l= explode(',',$g[$e]);
									$t= new Team($l[0]);
									echo "<tr><td><a href='team.php?i=".$t->idTeam."'><img src='../images/".$t->escudo."' alt='' style='width:30px;height:30px;'> ".strtoupper($t->nombre)."</a> <small><small>[<strong>Elo:</strong> </small>".intval($t->elo)."<small><strong> Rk:</strong></small> ".$t->ranking."]</small></td>
										<td>".($l[1]+$l[2])."</td>
										<td>".$l[1]."</td>
										<td>".$l[2]."</td>
										<td>".$l[3]."</td>
										<td>".$l[4]."</td>
										<td>".$l[5]."</td></tr>";
									}
								}								
						   
							echo "	
							</tbody>
						</table></p>						
						  </div>
						</div>";
					}
						} 	
						}
					?>
						
	  			
			    </div>	
			  	<div class="col-lg-12 col-md-12">
			  		<?php 
						if($liga[1]==2 && $liga['state']!=0){
					?>
					<table class="table table-sm table-responsive table-striped">
					   <thead>
					   <tr>							 
							<td><strong>Pos</strong></td>
							<td><strong>Equipo</strong></td> 
							<td><strong>PJ</strong></td>
							<td><strong>PG</strong></td>
							<td><strong>PP</strong></td>                     
							<td><strong>GF</strong></td>
							<td><strong>GC</strong></td>
							<td><strong>Desmp</strong></td> 
						                 
					   </tr>
					   </thead> 
					   <tbody>
					   <?php 
						  $ligatct= $control->getLigaTCT($idLiga);	
						  $tablaPos= explode(';',$ligatct['tablaPos']);
						  unset($tablaPos[count($tablaPos)-1]);  
						  for($i=0;$i<count($tablaPos);$i++)
							{
							   $teams= explode(',',$tablaPos[$i]);
							   $win=$teams['1'];
							   $lost=$teams['2'];
							   $golF=$teams['3'];
							   $golC=$teams['4'];
							   $tie=$teams['5'];

							   $team= new Team($teams[0]);					    
							   echo "<tr>
							   <td>".($i+1);								
							   echo "</td>
							   <td><a href='team.php?i=".$team->idTeam."' title='Elo: ".intval($team->elo)." Rk: ".$team->ranking."'><img src='../images/".$team->escudo."' alt='' style='width:30px;height:30px;'>".strtoupper($team->nombre)."</a></td>
							   <td>".($win+$lost)."</td>
							   <td>".$win."</td>
							   <td>".$lost."</td>
							   <td>".$golF."</td>
							   <td>".$golC."</td>
							   <td>".$tie."</td>
							   </tr>";	
							}
						}

					   ?>  
				   </tbody>
            </table> 
			  	</div>
			  	<div class="col-lg-12 col-md-12">
					<?php 
					  $plan =$control->getPlanLeague($idLiga);
					if(count($plan)>0)
					{
					?>					
					<div class="fontawesome-list row">
					<h4 class="centered">PARTIDOS PLANEADOS</h4>
			  		<?php
					}
						foreach($plan as $game)
						{				
							$team1= new Team($game['idTeam1']);
							$team2= new Team($game['idTeam2']);
					?>					
						<div class="col-md-4 col-sm-5 partidoJ" style="overflow: hidden">
							<a href='team.php?i=<?php echo $team1->idTeam;?>'>
								<?php echo $team1->nombre;?>
								<img alt='avatar' src='../images/<?php echo $team1->escudo;?>' style='width: 15px;'>
							</a>
							<small>vs</small>
							<a href='team.php?i=<?php echo $team2->idTeam;?>'>
								<img alt='avatar' src='../images/<?php echo $team2->escudo;?>' style='width: 15px;'>
								<?php echo $team2->nombre;?>								
							</a> 
						</div>
						
						<?php 
						}				
							?>
					</div>				
			  	</div>
			    <div class="col-lg-12 col-md-12 col-sm-12">
					<?php
					  if($liga['tipo']==1 && $liga['state']!=0){
					  $etapa="";				
					   if($ligagrupo['etapa']==1)
						 $etapa="grupos";
						   if($ligagrupo['etapa']==2)
							   $etapa="octavos";
						   if($ligagrupo['etapa']==3)
							   $etapa="cuartos";
						   if($ligagrupo['etapa']==4)
							   $etapa="semifinales";
						   if($ligagrupo['etapa']==5)
							   $etapa="final";
					   
					?>
					
			  			   	
			  <?php 			   
				 $octavos = $ligagrupo["octavos"];
				 if($octavos!="")
					 $octavos= explode(";",$octavos);
				 $cuartos = $ligagrupo["cuartos"];
				 if($cuartos!="")
					 $cuartos= explode(";",$cuartos);
				 $semis = $ligagrupo["semi"];
				  if($semis!="")
					 $semis= explode(";",$semis);
				 $final =$ligagrupo["final"];	
				 if($final!=""){					 
					 $final= explode(',',$final);
				 }
				  $cantTeams= $liga['cantTeams'];

				  $arTodos=array([],[],[],[],[]);
				  $partidos=array([],[],[],[],[]);
				  $goles= array([],[],[],[],[]);
				  $cont=0;
				  if($octavos!=[])
				  {
						foreach($octavos as $games)				 
							if($games!="")
							{
								$games=explode(',',$games);
								array_push($arTodos[$cont],$games[0]);
								array_push($arTodos[$cont],$games[1]);
								array_push($arTodos[$cont+1],$games[5]);
								array_push($partidos[$cont+1],$games[6]);
								array_push($goles[$cont+1],$games[2].','.$games[3]);
							}
					  $cont++;			 
				  }
				  if($cuartos!=[])
				  {
						foreach($cuartos as $games)				 
							if($games!="")
							{
								$games=explode(',',$games);
								if($octavos==''){
								array_push($arTodos[$cont],$games[0]);
								array_push($arTodos[$cont],$games[1]);						
								}
								array_push($arTodos[$cont+1],$games[5]);
								try{
								array_push($partidos[$cont+1],$games[6]);
								array_push($goles[$cont+1],$games[2].','.$games[3]);
								}
								catch(Exception $e)
								{
									
								}
							}
					  $cont++;			 
				  }

				  if($semis!=[])
				  {  
						foreach($semis as $games)				 
							if($games!="")
							{
								$games=explode(',',$games);
								if($cuartos==''){
								array_push($arTodos[$cont],$games[0]);
								array_push($arTodos[$cont],$games[1]);
								
								}
								array_push($arTodos[$cont+1],$games[5]);						
								try{
								array_push($partidos[$cont+1],$games[6]);
								array_push($goles[$cont+1],$games[2].','.$games[3]);
								}
								catch(Exception $e)
								{
									
								}
							}
					  $cont++;			 
				  }

				  if($final!=[])
				  {  			
					array_push($arTodos[$cont+1],$final[5]);
					try  
					{array_push($partidos[$cont+1],$final[6]);
					array_push($goles[$cont+1],$final[2].','.$final[3]);}
					  catch(Exception $e)
					  {
						  
					  }
				  }
			
				  ?>
				  <div class="col-lg-9 col-md-9">
				   <h4><?php 					
				   if($etapa!="grupos"){
						echo strtoupper($etapa);
						?> 
					</h4>	
				   <table class="orga" align="center">
					<tbody>
					<?php
						$inicial=$liga['cantTeams']/2;

						$quedan=$inicial;

						$ronda=0;
						$enColum=array();

						for($i=0;$i<$inicial+1;$i++)
							 array_push($enColum,0);

						for($i=0;$i<$inicial*2;$i++){					

							$paso=2;
							$factor=0;
							for($e=0;$e<sqrt($inicial)*2+1;$e++)
							  {																
								if($e%2==0 && $i==($enColum[$e]*$paso+$ronda+$factor)) 
								 {						 
									 $c= $enColum[$e];	
									$cdnPart="";
									
									 if(count($arTodos[$ronda])>$c)
									 {
										 $t= new Team($arTodos[$ronda][$c]);
										
										 if($ronda>=1)
										 {
											 $part= $partidos[$ronda][$c];
											 $cdnPart="<a href='show.php?g=".$part."&type=l'><img src='../images/campo.png' style='width:25px;' hint='".$goles[$ronda][$c]."'></a>" ;	
											
										 }
										 
										 echo "<td class='celdaSelected'>".$cdnPart." "."<a href='team.php?i=".$t->idTeam."'><img src='../images/".$t->escudo."' alt='' style='width:20px;height:20px;'> ".strtoupper($t->nombre)."</a></small>	
										 </td>";
										 
									 }
									
									 $enColum[$e]++;					
								 }
								else
								{
									if($enColum[$e]%2!=0 && $e<=sqrt($inicial)+2)
									{
										if($i+1==($enColum[$e]*$paso+$ronda))
										{
											echo "<td class='celdaDer'></td>";
										}
										else											
											echo "<td class='celdaDer'></td>"; 
									}
									else
									 	{
										 
											echo "<td class='celdaVacia'></td>";
									    }
								}

								if($e%2==0)
								{
									$paso=$paso*2;	
									$ronda++;
									if($ronda==2)
										$factor=1;
									if($ronda==3)
										$factor+=3;
									if($ronda==4)
										$factor+=7;
								}						
							}
							 $ronda=0;
							 echo "</tr>"; 			 										 			  
						}
					?>
					</tbody>
				</table>
				  
					<?php   
						}
					 }
					?>
					</div>
			    </div>			  	
			  	<div class="col-lg-9 col-md-9 col-sm-9">					 
					
			  		<?php
					   $games =$control->getGamesLiga($idLiga);
						$c=6;
					   if(count($games)>0)
						   echo "<h4 class='centered'>PARTIDOS JUGADOS</h4>";
						$etapas=['Grupos','Octavos','Cuartos','SemiFinal','Final'];
						 
						foreach($games as $game)
						{		
							if($c!=intval($game['etapa'])){								
								$c=$game['etapa'];
								if($c<5)
									echo "</div>";
							    echo "<h5>".$etapas[$c-1]."</h5>";	
								echo "<div class='fontawesome-list row''>";
							}
							
							
							$team1= new Team($game['idTeam1']);
							$team2= new Team($game['idTeam2']);
							
							$winner= $game['winner'];
							$color1="";
							$color2="line-through";
							
							if($winner==$team2->idTeam)
							{
							   $color1="line-through";
							   $color2="";	
							}
							
							
					?>					
						<div class="col-md-6 col-sm-6 partidoJ" style="overflow: hidden">
							<a href='team.php?i=<?php echo $team1->idTeam;?>' style="text-decoration: <?php echo $color1;?> ;">
								<?php echo strtoupper($team1->nombre);?>
								<img alt='avatar' src='../images/<?php echo $team1->escudo;?>' style='width: 15px;'>
								
							</a>
							<a href="show.php?g=<?php echo $game['idGame'];?>&type=l" title="Ver Partido">
							   <strong><?php echo $game['gol1'];?> - 
							<?php echo $game['gol2'];?></strong>
							</a>
							<a href='team.php?i=<?php echo $team2->idTeam;?>' style="text-decoration: <?php echo $color2;?> ;">
								<img alt='avatar' src='../images/<?php echo $team2->escudo;?>' style='width: 15px;'>
								<?php echo strtoupper($team2->nombre);?>
								
							</a> 
						</div>
						<?php 
						}
							?>
					</div>
		  </div>
                  
          
		 <!-- **********************************************************************************************************************************************************
              RIGHT SIDEBAR CONTENT
             *********************************************************************************************************************************************************** -->
          <div class="col-lg-3" style="background: #A62224;">
            <!--COMPLETED ACTIONS DONUTS CHART-->
           
           
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
          &copy; Copyrights <strong>FutboliCode.com</strong>. All Rights Reserved
        </p>
        <div class="credits">       
          
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
  <script src="../js/liga.js"></script>	
  <script src="../lib/jquery.nicescroll.js" type="text/javascript"></script>
  <!--common script for all pages-->
  <script src="../lib/common-scripts.js"></script>
  <script type="text/javascript" src="../lib/gritter/js/jquery.gritter.js"></script>
  <script type="text/javascript" src="../lib/gritter-conf.js"></script>	
  <script src="../js/general.js"></script>	
  <script>
	var usuario= <?php echo $usuario;?>;
  </script>		
  <!--script for this page-->
	

</body>

</html>
