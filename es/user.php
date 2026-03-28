<?php
  include_once("cabecera.php");
  $page=0;
  $cant=0;
  $type=0;

$id="";

if(isset($_GET['i']))
{
	$id=$_GET['i']; 
   $control->deleteNew($id,$usuario);
}

if(isset($_GET['pg']))
  {
	  $page=$_GET['pg'];
  }

  if(isset($_GET['t']))
  {
	  $type=$_GET['t'];
	  if(isset($_GET['act']))
		{
			$control->borrarNews($usuario,$type);
		}
	  
	  if($type==0)
	  {
		  $news= $control->getNews($usuario,$page,50);
		  $cant=$control->getCantNews($usuario);
	  }
	  else
	  {
	  	$news= $control->getNewsTipo($usuario,$type,$page,50);
		$cant=$control->getCantNewsTipo($usuario,$type);
	  }
  }
$cantNewsG= $control->getCantNews($usuario); 

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="tommyCUB">
  <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
  <title><?php echo $user['nombre'];?></title>

  <!-- Favicons -->
  <link href="../images/favicon.png" rel="icon">
  <link href="images/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Bootstrap core CSS -->
  <link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--external css-->
  <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <!-- Custom styles for this template -->
  <link href="../css/style.css" rel="stylesheet">
  <link href="../css/style-responsive.css" rel="stylesheet">
  <link href="../css/user.css" rel="stylesheet">

</head>

<body>
<script src="https://www.gstatic.com/firebasejs/6.3.4/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.10.1/firebase-auth.js"></script>
<script src="../js/firebase.js"></script>
<script src="../js/jquery.js"></script>

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
        <div class="row">
            
		 <div class="col-lg-9 col-md-9">
			<p>				
			<?php 
				if($cantNewsG==0)
					echo "<span class='h2 text-info centered'>No hay Noticias</span>";
				else{
			?>	
		  <div class="col-lg-12 col-md-12 centered">
			  <?php 
			  	
			    $newsInfo=$control->getCantNewsTipo($usuario,1);
			  	$newsWin=$control->getCantNewsTipo($usuario,2);
			   	$newsLost=$control->getCantNewsTipo($usuario,3);
			  	$newsReto=$control->getCantNewsTipo($usuario,4);
			  	$newsIngreso=$control->getCantNewsTipo($usuario,5);
			  	$newsGasto=$control->getCantNewsTipo($usuario,7);
			  	$newsLiga=$control->getCantNewsTipo($usuario,6);
			  $color=array("brown","darkslategrey","green","red","blue","darkolivegreen","purple","darkcyan");
			  
			  	if($cantNewsG>=1)			   
					echo "<a href='user.php?t=0' class='boton active' style='color:".$color[0]."; clear:both; padding:1.5rem; font-size:10px;'>ALL (".$cantNewsG.")</a>";
			  	if($newsInfo>=1)			   
					echo "<a href='user.php?t=1' class='boton' style='color:".$color[1]."; clear:both; padding:1.5rem; font-size:10px;'>INFO (".$newsInfo.")</a>";
			    if($newsWin>=1)			   
					echo "<a href='user.php?t=2'class='boton' style='color:".$color[2]."; clear:both;padding:1.5rem; font-size:10px;'>VICTORIAS (".$newsWin.")</a>";
			    if($newsLost>=1)			   
					echo "<a href='user.php?t=3' class='boton' style='color:".$color[3]."; clear:both;padding:1.5rem; font-size:10px;'>DERROTAS (".$newsLost.")</a>";
			  if($newsReto>=1)			   
					echo "<a href='user.php?t=4' class='boton' style='color:".$color[4]."; clear:both;padding:1.5rem; font-size:10px;'>RETOS (".$newsReto.")</a>";
			  if($newsIngreso>=1)			   
					echo "<a href='user.php?t=5' class='boton' style='color:".$color[5]."; clear:both;padding:1.5rem; font-size:10px;'>INGRESOS (".$newsIngreso.")</a>";
			  if($newsGasto>=1)			   
					echo "<a href='user.php?t=7' class='boton' style='color:".$color[7]."; clear:both;padding:1.5rem; font-size:10px;'>GASTOS (".$newsGasto.")</a>";
			  if($newsLiga>=1)			   
					echo "<a href='user.php?t=6' class='boton' style='color:".$color[6]."; clear:both;padding:1.5rem; font-size:10px;'>LIGAS (".$newsLiga.")</a>";	  	
			   ?> 		
			  <p>
		  </div>
		  <div class="col-lg-12 col-md-12 col-sm-12 centered" style="padding-top: .5rem; padding-bottom: .5rem; overflow-y: scroll; max-height: 100px;"> 
			  <p>				
				<?php 	
				  $cantTipo=0;				    
				    switch($type)
					{
						case 0: $cantTipo=$cantNewsG; break;
						case 1: $cantTipo=$newsInfo; break;
						case 2: $cantTipo=$newsWin; break;
						case 3: $cantTipo=$newsLost; break;
						case 4: $cantTipo=$newsReto; break;
						case 5: $cantTipo=$newsIngreso; break;
						case 6: $cantTipo=$newsLiga; break;
						case 7: $cantTipo=$newsGasto; break;						
					}
					for($i=1;$i<=$cantTipo/50+1;$i++)
					{
						$btn="btn-default";
						if($i-1==$page)
							$btn="btn-primary";
							
					 echo "<a href='user.php?pg=".($i-1)."&t=".$type."' class='btn ".$btn." btn-xs inline-block'>".$i."</a> ";
					}
				  	$tipos=["All","Info","Victorias","Derrotas","Retos","Ingresos","Ligas","Gastos"];
				  
				?>			 
			  </div>
		 <p>
		 <div class="col-lg-12 col-md-12">
			 <a href="user.php?t=<?php echo $type;?>&pag=0&act=e" style="padding-right: 5rem">
			 <span class='boton'><i class="fa fa-trash"></i>Limpiar <?php echo $tipos[$type];?></span>
			</a>
			 
			<span style='color:<?php echo $color[$type];?>'><strong><?php echo $tipos[$type];?></strong>,pág: <?php echo $page+1;?></span> 	 
		</div>	 
		 
			
          <div class="col-lg-12 col-md-12" style="color: darkolivegreen">			
				<table class="table table-inbox table-hover table-striped table-responsive table-sm">
					<tbody>
				<?php
				 $num=0;

				   foreach($news as $n){
					$num++;	
					$fecha=date("jMY H:i",$n['fecha']);  

				?>							
			  <tr class="" style="font-size: 12px; color:<?php echo $color[$n['tipo']];?>; padding:1px; margin: 1px;">                        
				<td class="inbox-small-cells" style="padding:1px; margin: 1px;">
					<a href="user.php?i=<?php echo $n['idNews'];?>&pg=<?php echo $page;?>&t=<?php echo $type;?>" type="button" class="close" aria-label="Close" style="float: left;" title="Eliminar notificacion.">
					<span style="color:<?php echo $color[$n['tipo']];?>;"><i class="fa fa-trash"></i></span>						 
						</a>
				  </td> 
				  <td>
				  <?php echo $page*50+$num;?>
				  </td>
				<td class="" style="font-size: 12px; padding:1px; margin: 1px;">
					<?php echo $control->procesarMensaje($n['msg']);?>
				 </td>                        
				<td class="view-message  text-right" style="padding:1px; margin: 1px;"><small><?php echo $fecha;?></small></td>
			  </tr>
						<?php
				   }
					   ?>
				</tbody>
			  </table>						
			</div>
			 
			 <?php 
			   if($cantTipo>20){
			 ?>
			 <div class="col-lg-12 col-md-12 col-sm-12 centered" style="padding-top: .5rem; padding-bottom: .5rem; overflow-y: scroll; max-height: 100px;"> 
				  
		 		<span><strong><?php echo $tipos[$type];?></strong>,pág: <?php echo $page+1;?></span>
			  <p>				
				<?php 	
				  
				   for($i=1;$i<=$cantTipo/50+1;$i++)
					{
						$btn="btn-default";
						if($i-1==$page)
							$btn="btn-primary";
							
					 echo "<a href='user.php?pg=".($i-1)."&t=".$type."' class='btn ".$btn." btn-xs inline-block'>".$i."</a> ";
					}
				  	$tipos=["All","Info","Victorias","Derrotas","Retos","Ingresos","Ligas","Gastos"];
				  
				?>			 
			  </div>
		 	<?php
			   }
				}
			 ?>
			 
		 </div>	
			
		  <div class="col-lg-3">
            
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
          &copy; Copyrights <strong>FutbolinCode</strong>. All Rights Reserved
        </p>
        <div class="credits">
          <a href="https://templatemag.com/"></a>
        </div>
        <a href="user.php#" class="go-top">
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
  
  <script class="include" type="text/javascript" src="../lib/jquery.dcjqaccordion.2.7.js"></script>
  <script src="../lib/jquery.scrollTo.min.js"></script>
  <script src="../lib/jquery.nicescroll.js" type="text/javascript"></script>
  <!--common script for all pages-->
  <script src="../lib/common-scripts.js"></script>
  <!--script for this page-->
  <script src="../js/user.js"></script>	

</body>

</html>
