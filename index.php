<?php
session_start();
include ("utils/control.php");
$usuario="";
$control = new control();
$page=0;

if($control->checkSecurity())
{
	echo "<script>window.location.replace('utils/cerrar.php')</script>";
	die;
}

?>
<!doctype html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Futbolin">
  <meta name="author" content="Tommy Rod ">
  <meta name="keyword" content="Futbolin, Programación, Futbol, Código, Inteligencia, Programador, Informatic, Code, Artificial Intelligence">
  <title>Welcome Futbolin</title>

  <!-- Favicons -->
  <link href="images/favicon.png" rel="icon">
  <link href="images/apple-touch-icon.png" rel="apple-touch-icon">
  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!--external css-->
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
  <link href="css/inicio.css" rel="stylesheet">   
</head>
<body>
 <script src="https://www.gstatic.com/firebasejs/6.3.4/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.10.1/firebase-auth.js"></script>
<script src="js/jquery.js"></script>
<script src="js/firebase.js"></script>
<script src="js/inicio.js"></script>
	
	<section id="main-content">
	 <section class="wrapper" style="margin-top: 0px;"> 		 
        <div class="row mt">
            <div class='col-lg-9 col-md-9'>
		        <div class="" style="float: right;">	 
						<img class="imgLogin" src="images/signgoogle.png" width="150px" onClick="signGoogle()">	 
						<img class="imgLogin" src="images/signfacebook.png" width="150px" onClick="signFacebook()">
				</div>
		    </div>
		<div class="col-lg-9 col-md-9 cuerpo">
			<div class="col-lg-12 col-md-12">		
				<div class="col-lg-12 col-md-12 centered" style="z-index:10000;">
				    <!--logo start-->
                      <a href="index.php" class="logo h1"><b><text style="color:darkcyan;">futbolin</text><span>code</span></b><img src='images/campo.png' style='padding:5px; width:50px; top:-10px;'></a>
                     <!--logo end-->
				</div>				
				
				<div id="demo" class="carousel slide" data-ride="carousel">
				  <!-- Indicators -->
				  <ul class="carousel-indicators" id="contentPlecas">
				  <li data-target="#demo" data-slide-to="0" class="active" style="background: #3B3B3B;"></li>
				  <?php	
					  $cant= $control->getTotalTeams();
					  for($i=0;$i<$cant/30-1;$i++)
						  echo "<li data-target='#demo' data-slide-to='".($i+1)."' style='background: #3B3B3B;'></li>";
				  ?>					 
				  </ul>
				  <!-- The slideshow -->
				  <div class="carousel-inner" id="contentCarusel">					 
					<?php 					   
					   	$ranking= $control->getRanking(0,$cant);
					     					  
						for($e=0;$e<intval($cant/30+1) ;$e++)  
						{						 	
					?>					  
					<div class="carousel-item <?php echo $e==0?"active":"";?>">
					  	<table class="table table-sm tabla1" style="" align="left">				
							<tbody>					
							<?php
								 
								for($i=$e*30;$i<$e*30+10 && $i<$cant;$i++)
								{
									$escudo=$ranking[$i]['escudo'];
									echo "<tr>
									<td>
										<small>".($i+1)."</small>
									</td>
									<td style='font-size:12px;'>
									   <img src='images/".$escudo."' class='img-circle' style='width:25px;'>
										".$ranking[$i]['nombre']." <small><b> Elo:</b>".intval($ranking[$i]['elo'])."</small>
									</td>
									</tr>";
								}
							?>
								</tbody>
						</table> 
						
						<table class="table table-sm" style="" align="center">				
							<tbody>					
							<?php
								 
								for($i=$e*30+10;$i<$e*30+20 && $i<$cant;$i++)
								{
									$escudo=$ranking[$i]['escudo'];
									echo "<tr>
									<td>
										<small>".($i+1)."</small>
									</td>
									<td style='font-size:12px;'>
									   <img src='images/".$escudo."' class='img-circle' style='width:25px;'>
										".$ranking[$i]['nombre']." <small><b>Elo: </b>".intval($ranking[$i]['elo'])."</small>
									</td>
									</tr>";
								}
							?>
								</tbody>
						</table>
						
						<table class="table table-sm" style="" align="right">				
							<tbody>					
							<?php
								 
								for($i=$e*30+20;$i<$e*30+30 && $i<$cant;$i++)
								{
									$escudo=$ranking[$i]['escudo'];
									echo "<tr>
									<td>
										<small>".($i+1)."</small>
									</td>
									<td style='font-size:12px;'>
									   <img src='images/".$escudo."' class='img-circle' style='width:25px;'>
										<h>".$ranking[$i]['nombre']."</h> <small><b>Elo: </b>".intval($ranking[$i]['elo'])."</small>
									</td>
									</tr>";
								}
							?>
								</tbody>
						</table>
					</div>
					<?php }?>
				  </div>

				  <!-- Left and right controls -->
				  <a class="carousel-control-prev" href="#demo" data-slide="prev">
					<span class="carousel-control-prev-icon"></span>
				  </a>
				  <a class="carousel-control-next" href="#demo" data-slide="next">
					<span class="carousel-control-next-icon"></span>
				  </a>
				</div>
				
				 
		    </div>	
		</div>
			
			<div class="col-lg-3" style="">
				
			</div>
		</div>	
		 </section>
</section>
	
<footer class="footer">
	
</footer>
<script src="js/bootstrap.min.js"></script>
  <script src="lib/jquery-ui-1.9.2.custom.min.js"></script>
  <script src="lib/jquery.ui.touch-punch.min.js"></script>
  <script class="include" type="text/javascript" src="lib/jquery.dcjqaccordion.2.7.js"></script>
  <script src="lib/jquery.scrollTo.min.js"></script>
  <script src="lib/jquery.nicescroll.js" type="text/javascript"></script>
  <!--common script for all pages-->
  <script src="lib/common-scripts.js"></script>
  <!--script for this page-->
</body>
</html>
