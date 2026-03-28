<?php
include_once("cabecera.php");


$page=0;
$tip='mp';
$msj="";

if(isset($_GET['pg']))
	$page=$_GET['pg'];

if(isset($_GET['tip']))
	$tip=$_GET['tip'];

if(isset($_GET['type']) && $_GET['type']=="add")
{									
	$idLiga=$_GET["l"];
	try{
		$control->addTeamToLeague($idLiga,$usuario,$idTeam,1);
		$ligas= $control->getLigasHabiles(0,$idTeam,'prize','DESC',0);
	}
	catch (Exception $e)
	{
		$msj= "<span class='text-info' style='margin:auto;width:100%;'>".$e->getMessage()."</span>";
	}				
}

$ligas= $control->getLigasHabiles(0,$idTeam,'prize','DESC',0);

if($tip=='mp')
{
	$ligas= $control->getLigasHabiles(0,$idTeam,'prize','DESC',$page);
}

if($tip=='np')
{
	$ligas= $control->getLigasHabiles(0,$idTeam,'prize','ASC',$page);
}

if($tip=='me')
{
	$ligas= $control->getLigasHabiles(0,$idTeam,'entry','DESC',$page);
}

if($tip=='ne')
{
	$ligas= $control->getLigasHabiles(0,$idTeam,'entry','ASC',$page);
}

if($tip=='mz')
{
	$ligas= $control->getLigasHabiles(0,$idTeam,'plazas','ASC',$page);
}


?> 
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
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
  <link rel="stylesheet" href="../css/market.css">  
	<link rel="stylesheet" type="text/css" href="../lib/gritter/css/jquery.gritter.css" />	
</head>
<body>
<script src="https://www.gstatic.com/firebasejs/6.3.4/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.10.1/firebase-auth.js"></script>
<script src="../js/firebase.js"></script>
  <script src="https://kit.fontawesome.com/070081e2e4.js"></script>
  <script src="../js/jquery.js"></script>	
  <script src="../js/market.js"></script>
 
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
		<div class="col-lg-9 col-md-9 col-sm-12 centered">
          <div class="col-lg-12 col-md-12 col-sm-12">
		  <?php		         
				echo $control->procesarMensaje($msj);
	       ?>
		   </div>	
			<div class="col-lg-12 col-md-12 col-sm-12 centered" style="margin-top: .5rem;">
				<a class="btn btn-xs" href="market.php?i=<?php echo $idTeam;?>&tip=mp"><i class='fa fa-arrow-circle-up'></i> mayor Premio</a>
				<a class="btn btn-xs" href="market.php?i=<?php echo $idTeam;?>&tip=np"><i class='fa fa-arrow-circle-down text-danger'></i> menor Premio</a>
				<a class="btn btn-xs" href="market.php?i=<?php echo $idTeam;?>&tip=me"><i class='fa fa-arrow-circle-up'></i> mayor Entrada</a>
				<a class="btn btn-xs" href="market.php?i=<?php echo $idTeam;?>&tip=ne"> <i class='fa fa-arrow-circle-down'></i> menor Entrada</a>
				<a class="btn btn-xs" href="market.php?i=<?php echo $idTeam;?>&tip=mz"><i class='fa fa-arrow-circle-down'></i> menor Plazas</a>			
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 centered" style="padding-top: .5rem; padding-bottom: .5rem;">							
				<?php 				    
					for($i=1;$i<=$cantHabil/20+1;$i++)
					{
						$btn="btn-default";
						if($i-1==$page)
							$btn="btn-primary";
							
					 echo "<a href='market.php?i=".$idTeam."&tip=".$tip."&pg=".($i-1)."' class='btn ".$btn." btn-xs inline-block'>".$i."</a>,";
					}		
				?>
				<p>
				<span class="text-muted centered" style="padding: .5rem;">					
					<?php 
					$orden=["mp"=>"mayor premio","np"=>"menor premio","me"=>"mayor etrada","ne"=>"menor entrada","mz"=>"menor plazas"];
					echo "<strong>".$orden[$tip]."</strong>, <small>pág:</small> <strong>".($page+1)."</strong>";
					?>
				</span>
				 
			</div>
			
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="pricing-wrap">
					<?php
							for($i=0;$i<count($ligas);$i++)
							{		
								$ids=  explode(";",$ligas[$i][5]);
								$ids=array_filter($ids);
								$c= count($ids);
								sort($ids);
								?>						
						   
							<div class="pricing-table">
								<div class="pricing-table-cont">
								<div class="pricing-table-month">
									<div class="pricing-table-head">
										<h6><a href="league.php?i=<?php echo $ligas[$i][0];?>" style="overflow: visible; line-height: normal;"> <?php echo strtoupper($ligas[$i][1]); ?></a></h6>
										<div class="profile-pic">
										<p>
											<span class="badge bg-warning" style="font-size: 25px; margin: 1rem;"><?php echo $ligas[$i][3];?><sup>¢</sup> </span>								
										</p>
										</div>
									</div>
									<ul class="pricing-table-list">
										<li><small>Entrada:</small><span class="text-danger h4"> <?php echo $ligas[$i][2]; ?> <sup>¢</sup>  </li>
										<li><small>Tipo:</small> <?php echo $ligas[$i][6]==1?"Grupos":"TodosXtodos"; ?>  </li>
										<li><small>Equipos:</small> <?php echo $ligas[$i][4]; ?> </li>							
										<li><small>Plazas:</small> <span class="badge bg-danger text-white">
											<?php echo $ligas[$i][4] - $c;?> 
											</span>
										</li>
										<?php 
									$a="";

									for($e=0;$e<$c;$e++)
									{
										$t= $control->getTeam($ids[$e]);						
										$a.= trim($t['nombre'])." (".intval($t['elo'])."), ";
									}					    
								?>
										<li title="<?php echo $a;?>">								
											<i class="fas fa-info-circle" title="<?php echo $a;?>"></i>
										</li>							
									</ul>
									<form action="market.php" method="get">
										<input type="hidden" name="l" value="<?php echo $ligas[$i][0];?>">
										<input type="hidden" name="type" value="add">
										<input type="hidden" name="i" value="<?php echo $idTeam;?>">
										<input type="hidden" name="tip" value="<?php echo $tip;?>">
										<input type="hidden" name="pg" value="<?php echo $page;?>">
										<button type="submit" class="pricing-table-button btn btn-primary">JOIN</button>
									</form>						

							</div>		    
						   </div>
						</div>
								
						<?php
							}
						?>     
			         </div>
				</div>
			   <?php if(($cantHabil)>10)
				{
			    ?>
				<div class="col-lg-12 col-md-12 col-sm-12 centered" style="padding-top: 1.5rem; padding-bottom: 1.5rem;">		
					 
					  <span class="text-muted centered" style="padding: .5rem;">
							<?php 							
							echo " <strong>".$orden[$tip]."</strong>, <small>pág:</small> <strong>".($page+1)."</strong>";
							?>
						</span>	
					<p>
						<?php 
							for($i=1;$i<=$cantHabil/20+1;$i++)
							{
								$btn="btn-default";
								if($i-1==$page)
									$btn="btn-primary";

							 echo "<a href='market.php?i=".$idTeam."&tip=".$tip."&pg=".($i-1)."' class='btn ".$btn." btn-xs inline-block'>".$i."</a>,";
							}		
						?>
										 			
			    </div>	
			  <?php }?>
			  </div>
			
		 <!-- **********************************************************************************************************************************************************
              RIGHT SIDEBAR CONTENT
              *********************************************************************************************************************************************************** -->
          <div class="col-lg-3 ds">
                      
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
         
        </div>
        <a href="market.php#" class="go-top">
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
