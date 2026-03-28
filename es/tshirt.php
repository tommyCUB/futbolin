<?php
require_once("cabecera.php");
$error="";
if(isset($_POST['switch']))
{
	$choice=$_POST['switch'];	
	
	if(isset($_POST['rdTshirt']))
	{
		$camis=$_POST['rdTshirt'];
		
		if($team->tshirt==$choice && $team->tshirt2==$camis)
			$error="No deben ser iguales las camisetas de Home Club y de visitador.";
		elseif($team->tshirt2==$choice && $team->tshirt==$camis)
			$error="No deben ser iguales las camisetas de Home Club y de visitador.";
		
		if($error=="")
		{
			$control->cambiarTshirt($idTeam,$choice,$camis);
			$team= new Team(intval($idTeam),false);
		}
	}
	
}

$tshirt= $control->getListadoTshirt();

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
  <link href="../css/tshirt.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../lib/gritter/css/jquery.gritter.css" />	

</head>

<body>
<script src="https://www.gstatic.com/firebasejs/6.3.4/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.10.1/firebase-auth.js"></script>
<script src="../js/firebase.js"></script>
  <script src="https://kit.fontawesome.com/070081e2e4.js"></script>
	<script src="../js/jquery.js"></script>
<script src="../js/tshirt.js"></script>

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
          <div class="col-lg-9">			
			    <div class="col-lg-12 col-md-12">
			  		<form action="tshirt.php" method="post">
						<span class="text-danger"><?php echo $error;?></span>
						<br>
						<div class="col-lg-12 col-md-12 centered">	
						   <label class="h4" for="switch"  class='d-inline-Flex' style='margin:5px;  border:2px solid red; padding:10px;' id="lbHome" onclick="choice('lbHome')">
							<input type="radio" name="switch" value="<?php echo $team->tshirt; ?>" checked id="swHome">
							<?php
							        for($i=1;$i<12;$i++)
									echo "<img src='../images/tshirt/".$team->tshirt.$i.".png' style='width:40px;height:80px;' class='ts'>";
							?>	
							HOME CLUB
							</label>
						    <label class="h4" for="switch"  class='d-inline-Flex' style='margin:5px;  padding:10px;' id="lbVisit" onclick="choice('lbVisit')">
							<input type="radio" name="switch" value="<?php echo $team->tshirt2; ?>" id="swVisit">
							<?php
							        for($i=1;$i<12;$i++)
									echo "<img src='../images/tshirt/".$team->tshirt2.$i.".png' style='width:40px;height:80px;' class='ts'>";
							?>	
							VISITADOR
							</label>
							<p>
							<input class="btn btn-primary centered" type="submit" value="Cambiar Camiseta" style=" z-index: 9999; posicion:fixed;">
							<hr>
						</div>
						
						<div class="col-lg-12 centered">
            			  	<?php
            			  		$cont=1;	
            			        for($i=0;$i<count($tshirt);$i++)
            					{
            					  $ts=  $tshirt[$i];
            					  $ts2= str_replace(".",$cont.".",$ts);
            					  $n= explode(".",$ts)[0];
            					  echo "<label class='d-inline-Flex' style='margin:5px;'>
            						  		<input type='radio' name='rdTshirt' value='".$n."'>
            						  		<img src='../images/tshirt/".$ts2."' style='width:70px;height:140px;'>
            							</label>";	
            					  $cont++;
            					  if($cont>11)
            					    $cont=1;
            					}
            			    ?>
            			  <br>
            			  	
            			 </div>  
            			 </form>
						
						
						
						
					</form>
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
