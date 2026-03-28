<?php
require_once("cabecera.php");

$page=0;
$orderBy='elo';
$direccion='DESC';
$cant=50;
$media=0;

if(isset($_GET['pg']))
{
	$page=$_GET['pg'];	
}

if(isset($_GET['or']))
{
	$orderBy=$_GET['or'];	
}

if(isset($_GET['dir']))
{
	$direccion=$_GET['dir'];	
}

$media= $control->calcularMedia($orderBy);

$teames= $control->getTeamsBy($page,$orderBy,$direccion,$cant);
$cantTeams= $control->getCantTeams();

if($orderBy=='frec')
{
	for($i=0;$i<count($teames);$i++)
	{
		if($teames[$i]['frec']=='' || $teames[$i]['frec']=='0')
		{
			array_push($teames,$teames[$i]);
			unset($teames[$i]);
		}
		else
		  break;
	}
}
?>
 
<!DOCTYPE html>
<html lang="es">

<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Futbolin">
  <meta name="author" content="Tommy Rod ">
  <meta name="keyword" content="Futbolin, Programación, Futbol, Código, Inteligencia, Programador, Informatic, Code, Artificial Intelligence">
  <title>Ranking Mundial FutbolinCode</title>

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
   <link href="../css/ranking.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../lib/gritter/css/jquery.gritter.css" />	

</head>

<body>
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
        <div class="row">
          <div class="col-lg-9 col-md-9">
			  
			    <div class="col-lg-7 col-md-7 menuRanking" style="position: fixed; background: #FFFFFF; z-index: 1000; padding-bottom: 0px; margin-bottom: 0px; padding: 0px; margin: 0px;">	
					<span class="centered info text-info"><strong>RANKING MUNDIAL</strong></span>
			  		<form action="ranking.php" method="get">
						<div class="form-row">
							<div class="form-group col-lg-6 col-md-6">
								<label for="or">Ordenar por:</label>
								<select name="or" id="or" class="form-control  form-inline" onChange="this.form.submit()">
									<option value="elo" <?php echo $orderBy=='elo'?'selected':''?> >Ranking</option>
									<option value="win" <?php echo $orderBy=='win'?'selected':''?>>Partidos Ganados</option>
									<option value="lost" <?php echo $orderBy=='lost'?'selected':''?>>Partidos Perdidos</option>
									<option value="golFavor" <?php echo $orderBy=='golFavor'?'selected':''?>>Goles Anotados</option>
									<option value="golContra" <?php echo $orderBy=='golContra'?'selected':''?>>Goles Permitidos</option>
									<option value="segundo" <?php echo $orderBy=='segundo'?'selected':''?>>Tiempo</option>
									<option value="frec" <?php echo $orderBy=='frec'?'selected':''?>>Frecuencia Goles</option>
								</select></div>

							<div class="form-group col-lg-6 col-md-6">
								<label for="dir"></label>
								<select name="dir" id="dir" class="form-control form-inline" onChange="this.form.submit()">
									<option value="DESC" <?php echo $direccion=='DESC'?'selected':''?>>Mayor a menor</option>
									<option value="ASC" <?php echo $direccion=='ASC'?'selected':''?>>Menor a Mayor</option>
								</select>
							   </div>	
						</div>	
					</form>	
					<span class="text-warning fl">Media:  <strong><?php echo $media;?></strong></span>
					<div class="col-lg-12 col-md-12 col-sm-12 centered" style="padding-top: .5rem; padding-bottom: .5rem; overflow-y: scroll; max-height: 100px;"> 
					  <p>				
						<?php 				    
							for($i=1;$i<=$cantTeams/50+1;$i++)
							{
								$btn="btn-default";
								if($i-1==$page)
									$btn="btn-primary";

							 echo "<a href='ranking.php?pg=".($i-1)."&or=".$orderBy."&dir=".$direccion."' class='btn ".$btn." btn-xs inline-block'>".$i."</a>";
							}
						?>					  
					  </div>
					<div class="col-lg-12 col-md-12" style="padding-left: 0px; padding-right: 0px; margin-left: 0px; margin-right: 0px;">
						<table class="table table-sm" style="padding-bottom: 0px;margin-bottom: 0px;">
						<tr class="text-center" align="center">
							<th class='text-center'><strong>Pos</strong></th>
							<th class='text-center'><strong>Rk</strong></th>	
							<th class='text-center'><strong>Equipo</strong></th>							
							<th class='text-center'><strong>Elo</strong></th>	
							<th class='text-center'><strong>G</strong></th>	
							<th class='text-center'><strong>P</strong></th>	
							<th class='text-center'><strong>GF</strong></th>
							<th class='text-center'><strong>GC</strong></th>
							<th class='text-center'><strong>Seg</strong></th>
							<th class='text-center'><strong>Frec </strong></th>	
						</tr>
							
						<tr class="centered">
							<td>    </td>
							<td ><?php echo $team->ranking;?></td>	
							<td class="tdNombre"><img src="../images/<?php echo $team->escudo;?>" alt="" style="width: 25px;">  <?php echo $team->nombre;?></td>							
							<td><?php echo intval($team->elo);?></td>	
							<td><?php echo $team->win;?></td>	
							<td><?php echo $team->lost;?></td>	
							<td><?php echo $team->golFavor;?></td>
							<td><?php echo $team->golContra;?></td>
							<td><?php echo $team->segundo;?></td>
							<td><?php echo $team->golFavor!=0?number_format($team->segundo/$team->golFavor,2):0;?> s.</td>		
						</tr>
							</table>
					</div>
			  	</div>
			  

			  
  			<div class="col-lg-12 col-md-12" style="position: relative; top:180px;padding-top: 0px;">
				
				<div class="col-lg-12 col-md-12">
			  	<table class="table table-sm table-light" style="padding-top: 0px; margin-top: 0px;">
				 
						<?php 
							$cont=0;
					       foreach($teames as $tim)
						   {
							  $cont++;   
						     $eq= new Team($tim['idTeam']);
					     ?>				
						<tr class="<?php echo $eq->idTeam==$idTeam?"trMio":""; ?>">			<td><?php echo $page*$cant + $cont;?></td>				
							<td><?php echo $eq->ranking;?></td>	
							<td class="tdNombre"><a href="team.php?i=<?php echo $eq->idTeam;?>"><img src="../images/<?php echo $eq->escudo;?>" alt="" style="width: 30px;">  <?php echo $eq->nombre;?></a></td>							
							<td class='<?php echo $orderBy=='elo'?"tdOrden":""; ?>'><?php echo intval($eq->elo);?></td>	
							<td class='<?php echo $orderBy=='win'?"tdOrden":""; ?>'><?php echo $eq->win;?></td>	
							<td class='<?php echo $orderBy=='lost'?"tdOrden":""; ?>'><?php echo $eq->lost;?></td>	
							<td class='<?php echo $orderBy=='golFavor'?"tdOrden":""; ?>'><?php echo $eq->golFavor;?></td>
							<td class='<?php echo $orderBy=='golContra'?"tdOrden":""; ?>'><?php echo $eq->golContra;?></td>
							<td class='<?php echo $orderBy=='segundo'?"tdOrden":""; ?>'><?php echo $eq->segundo;?></td>
							<td class='<?php echo $orderBy=='frec'?"tdOrden":""; ?>'><?php echo $eq->golFavor!=0?number_format($eq->segundo/$eq->golFavor,2):0;?> s.</td>		
						</tr>
					<?php }?>
							</table>
					
					</div>
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
