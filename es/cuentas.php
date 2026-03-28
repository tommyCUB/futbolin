<?php
include_once("cabecera.php");

$estado=$control->getEstadoCuenta($usuario);
$cantTipo=$estado['cant'];

$page=0;
$type=0;  // 0 todos, 1 creditos, 2 debitos

if(isset($_GET['pg']))
{
	$page=$_GET['pg'];
}

if(isset($_GET['type']))
{
	$type= $_GET['type'];
}

if($type<0 || $type>2)
	$type=0;

$cantDebito=$control->getCantCuentasTipo($usuario,2);
$cantCredito=$estado['cant']-$cantDebito;

if($type==1)
	$cantTipo=$cantCredito;
if($type==2)
	$cantTipo=$cantDebito;

$cuentas= $control->getCuentasUser($usuario,$page,50,$type);
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
	<link href="../css/cuentas.css" rel="stylesheet">
 <link rel="stylesheet" type="text/css" href="../lib/gritter/css/jquery.gritter.css" />

  <!-- =======================================================
    Template Name: Dashio
    Template URL: https://templatemag.com/dashio-bootstrap-admin-template/
    Author: TemplateMag.com
    License: https://templatemag.com/license/
  ======================================================= -->
</head>

<body>

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
          <div class="col-lg-9">			 
			    <div class="col-lg-12">
			  		<div class="col-md-12 col-sm-12 mb">
						<div class="grey-panel centered donut-chart">
						  <div class="grey-header">
							<h4 class="text-info"><?php  echo strtoupper($user['nombre']);?></h4>
						  </div>						 
							 
							<div class="col-sm-4 col-xs-4">
							  <h2 style='color:<?php echo ($estado['credito']-$estado['debito'])>0?  'seagreen': 'red'?>; font-size: 40px;'>
								  <?php  
								  echo $estado['credito']-$estado['debito'];
								  ?><sup>¢</sup></h2>
								<p>Total</p>
							</div>				   
						     
							<div class="col-sm-4 col-xs-4 text-success">
								 <h2 style='color:forestgreen; font-size: 20px;'><?php  echo $estado['credito'];?><sup>¢</sup></h2>
								  <p>Crédito</p>
							</div>							
							 
							<div class="col-sm-4 col-xs-4">
								<h2 style='color:red; font-size: 20px;'><?php  echo $estado['debito'];?><sup>¢</sup></h2>
								<p>Débito</p>
							</div>	
							
						</div>
						<!-- /grey-panel -->
					  </div>
			  	</div>
			  <div class="col-lg-12 col-md-12 col-sm-12">
			  	<form action="cuentas.php">
					<select name="type" id="type" class="form-control" onChange="this.form.submit()">
						<option value="0" <?php echo $type==0?'selected':'';?></optio>Todo(<?php echo $estado['cant'];?>)</option>
						<option value="1" <?php echo $type==1?'selected':'';?>>Créditos (<?php echo $cantCredito;?>)</option>
						<option value="2" <?php echo $type==2?'selected':'';?>>Débitos (<?php echo $cantDebito;?>)</option>
					</select>
						<input type="hidden" value="<?php echo $page;?>" name="pg">
				</form>
			  </div>
			    
			  <div class="col-lg-12 col-md-12 col-sm-12 centered" style="padding-top: .5rem; padding-bottom: .5rem; overflow-y: scroll; max-height: 100px;"> 
			  <p>				
				<?php 				    
					for($i=1;$i<=$cantTipo/50+1;$i++)
					{
						$btn="btn-default";
						if($i-1==$page)
							$btn="btn-primary";
							
					 echo "<a href='cuentas.php?pg=".($i-1)."&type=".$type."' class='btn ".$btn." btn-xs inline-block'>".$i."</a> ";
					}
				  $cdn="Todo";
				  if($type==1)
					  $cdn='Créditos';
				  if($type==2)
					  $cdn="Débitos";
					  
				?>					  
			  </div>
			  <p>
				<span class='centered'>pág:<strong><?php echo $page+1;?></strong>,<?php echo $cdn;?></span>
			   
			  
			  <div class="col-lg-12">
				   <table class="table table-stripped table-lg table-responsive centered">
				  		<thead class="centered">
							<th class="centered">No</th>
					   		<th class="centered">Fecha</th>
							<th class="centered">Monto</th>
							<th class="centered">Asunto</th>
					   </thead>	
					   <tbody>
					   		<?php 
						        $cont=1;
						   		foreach($cuentas as $c)
								{
									if($type==0 || ($c['debito']!=0 && $type==2)
									   || ($c['credito']!=0 && $type==1))
									{
									if($c['credito']-$c['debito']<0)
										echo "<tr style='background-color: lightsalmon; padding:0px;margin:0px;'>";
									else
										echo "<tr style='background-color: lightgreen;padding:0px;margin:0px;'>";
									    echo "<td>".($page*50+$cont)."</td>";
										echo "<td><small style='font-size:10px;'>".date("d/m/Y h:i",$c['fecha'])."</small></td>";					     
										echo "<td><strong>".($c['credito']-$c['debito'])."<sup>¢</sup></strong></td>
										      <td>".$control->procesarMensaje($c['asunto'])."</td>
									</tr>";
										$cont++;
									}
								}
						   
						    ?>
					   </tbody>
				   </table>
			  </div>
			  
			  <?php 
			  if($cantTipo>20){
				?>
			  <div class="col-lg-12 col-md-12 col-sm-12 centered" style="padding-top: .5rem; padding-bottom: .5rem; overflow-y: scroll; max-height: 100px; color:">
			  <p>
			  <span>pág:<strong><?php echo $page+1;?></strong>,<?php echo $cdn;?></span>
			  <p>				
				<?php 				    
					for($i=1;$i<=$cantTipo/50+1;$i++)
					{
						$btn="btn-default";
						if($i-1==$page)
							$btn="btn-primary";
							
					 echo "<a href='cuentas.php?pg=".($i-1)."' class='btn ".$btn." btn-xs inline-block'>".$i."</a> ";
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
          &copy; Copyrights <strong>FutbolinCode</strong>. All Rights Reserved
        </p>
        <div class="credits">
         
        </div>
        <a href="cuentas.php#" class="go-top">
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
