<?php
require_once("cabecera.php");
$costo=0;
$desc="";
$cant=0;

if(isset($_POST['inCosto']))
	$costo=$_POST['inCosto'];
if(isset($_POST['inDesc']))
	$desc=$_POST['inDesc'];
if(isset($_POST['inCant']))
	$cant=$_POST['inCant'];


$desc="Compra en www.futbolincode.com por valor de ".$costo." USD.".$desc;
$sid= session_id();
$idVenta=md5($usuario.time().$costo.$desc.myCdn.$sid);

$control->nuevaVenta($idVenta,$usuario,$cant,$costo,$desc);
?>
 
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Add meta tags for mobile and IE -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="Futbolin">
  <meta name="author" content="Tommy Rod ">
  <meta name="keyword" content="Futbolin, Programación, Futbol, Código, Inteligencia, Programador, Informatic, Code, Artificial Intelligence">
  <title>Pago</title>

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
        <div class="row mt">
          <div class="col-lg-9 col-md-9">			
			    <div class="jumbotron centered">
				  <h1 class="display-4">¡Pago!</h1>
					<hr class="my-4">
				  <p class="lead">Estas a punto de pagar con Paypal 
					  <h3><strong>$ <?php echo $costo;?> USD.</strong></h3>
					</p>				  
				  <p><?php echo $desc;?></p>
			  
				  <small>Las coins se recibirán una vez se procese el pago.</small>
			  
				  <p class="lead">
					<head>
						
					</head>
					<body>
						<!-- Set up a container element for the button -->
						<div id="paypal-button-container"></div>

						<!-- Include the PayPal JavaScript SDK -->
						<script src="https://www.paypal.com/sdk/js?client-id=AYAMUOvpLK1LJK_ucegW6iLUowSf9eYHdsQypm1QDrATDYfDUnypRXnM67X7IcEvKMhyH1yqMZJXh1SG&currency=USD"></script>

						<script>
							// Render the PayPal button into #paypal-button-container
							paypal.Buttons({
								// Set up the transaction
								createOrder: function(data, actions) {
									return actions.order.create({
										purchase_units: [{
											amount: {
												value: '<?php echo $costo;?>'
											},
											description: '<?php echo $desc;?>',
											custom_id: '<?php echo $idVenta;?>' 		
										}]
									});
								
								},

								// Finalize the transaction
								onApprove: function(data, actions) {							 
									return actions.order.capture().then(function(details) {
										// Show a success message to the buyer
										if(details.status=='COMPLETED'){					
											window.location="processPays.php?id="+details.id;
										}
										else
											alert("El pago no se realizó.");
										
										alert('Pago efectuado por ' + details.payer.name.given_name + '! En este momento se procesa su pago y recibe sus coins. Felicidades!!!');															
											
									});
								}


							}).render('#paypal-button-container');
						</script>
					</body>

				  </p>
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
        <a href="pagar.php" class="go-top">
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
