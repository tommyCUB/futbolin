<?php
include_once("cabecera.php");
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
  <link href="../css/challenge.css" rel="stylesheet">
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
    <?php  include_once("menu2.php");?>
    <!--header end-->
    <!-- **********************************************************************************************************************************************************
        MAIN SIDEBAR MENU
        *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
    <?php  include_once("menu.php");?>
    <!--sidebar end-->
    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
      <section class="wrapper site-min-height">        
        <div class="row mt">
          <div class="col-lg-9">	
			  	<div class="col-lg-12">
					<span class="text-info h5 centered">
			  		<?php
					  echo $challdone['cantCheck']." / ".count($challenge)." <small> retos</small>";
					?>
					</span>
			  	</div>
			  <hr>
			    <div class="col-lg-12">
			  		<table class="table table-striped">
						<?php
							$checked= explode(';',$challdone['checkit']);
							$cont=1;
							foreach($challenge as $chall)
							{
								$check=false;
								if(in_array($chall['codigo'],$checked))
									$check=true;
								
								if($check==true)
								{
									echo "<tr style='color:green'>";
									echo "<td class='h6 centered'><strong>".$cont."</strong></td>";
								     $cont++;
									echo "<td><img src='../images/check.png' alt='done' style='width:25px;'></td>";	
								}
								else	{	
									echo "<tr style='color:gray'>";	
									echo "<td class='h6 centered'><strong>".$cont."</strong></td>";
								     $cont++;
									echo "<td style=''></td>";
								}
								
								$min=0;
								
								if($chall['depend']){									 
									$min= $challdone[$chall['depend']];
								}
								elseif($check==true)
									$min=1;
								
								
								echo "<td class='rotar1'><span class='rotar1 h4'><sup>¢</sup>".$chall['premio']."</span></td>";	
								echo "<td class='centered'><small>".$min."/".$chall['meta']."</small></td>";
								echo "<td style='padding-left:1rem'>".$chall['asunto']."</td>";	
								echo "</tr>";									
							}
						?>
					</table>
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
          &copy; Copyrights <strong>futbolinCode</strong>. All Rights Reserved
        </p>
        <div class="credits">
             
        </div>
        <a href="challenge.php#" class="go-top">
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
