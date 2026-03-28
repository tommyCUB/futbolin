<?php
include_once("cabecera.php");

if(isset($_POST['escudo']))
{
	$escudo= $_POST['escudo'];
	$control->setEscudo($idTeam,"escudos/".$escudo);
	$team= new Team(intval($idTeam),false);
}
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
  <link href="../css/escudo.css" rel="stylesheet">	 
	<link rel="stylesheet" type="text/css" href="../lib/gritter/css/jquery.gritter.css" />	
</head>
<body>
<script src="https://www.gstatic.com/firebasejs/6.3.4/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.10.1/firebase-auth.js"></script>
<script src="../js/firebase.js"></script>
<script src="https://kit.fontawesome.com/070081e2e4.js"></script>
 
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
          <div class="col-lg-9" >	
			   <form action="escudo.php" method="post">
			  <div class="col-lg-12 centered" style="">				  	
			  		<img src="../images/<?php echo $team->escudo;?>" alt="" style="width: 15rem; height: 15rem;border: 1px solid #10AD13;">
				  <input class="btn btn-round btn-primary" type="submit" value="Cambiar Escudo">
				   <hr>	
			  </div>		    
			  	<?php
			  		$listado= $control->getListadoEscudos();	
			        foreach($listado as $escudo)
					{
					  echo "<label class='d-inline-Flex' style='margin:5px;'>
						  		<input type='radio' name='escudo' value='".$escudo."'>
						  		<img src='../images/escudos/".$escudo."' style='width:50px;height:50px; '>
							</label>";	
					}
			    ?>
			  <br>
			  	
			   
			  </form>
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
          &copy; Copyrights <strong>futbolinCode.com</strong>. All Rights Reserved
        </p>
        <div class="credits">
         
        </div>
        <a href="escudo.php#" class="go-top">
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
