<?php
include_once("cabecera.php");
if(isset($_POST['i']))
{
	$idTactic=$_POST['i'];
	$_GET['i']=$idTactic;
	if($control->isMyTeam($idTactic,$usuario))
	{
		$initial="";
		$forces="";
		$players="";
		
		for($i=1;$i<12;$i++)
		{	
			$v='posXP'.$i;
			$vv= 'posYP'.$i;
			$initial.=($_POST[$v].','.$_POST[$vv]);
			$forces.=$_POST['forceP'.$i];
			$players.=$_POST['nP'.$i];
			if($i!=11)
			{
				$forces.=",";
				$players.=",";
				$initial.=',';
			}
		}
		
		$control->setTactica($idTactic,$initial,$forces,$players);
	 
	}
	else{
	  header("location: tactica.php?i=".$idTactic);	
	}	
}

if(isset($_GET['i']))
{
   $idTactic=$_GET['i'];
   
   if($control->isMyTeam($idTactic,$usuario))
	{
    		
    	$tactic=$control->getTactic($idTactic,0);
	}
	else{
	echo "<script>window.history.back()</script>";
	die;
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
  <title>Editor Tactica <?php echo $team->nombre;?></title>

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
  <link rel="stylesheet" type="text/css" href="../css/editorTactica.css">
  <link rel="stylesheet" type="text/css" href="../lib/gritter/css/jquery.gritter.css" />
  
  <style type="text/css">

#p1{
  background:url(../images/tshirt/<?php echo $team->tshirt;?>1.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p2{
 background:url(../images/tshirt/<?php echo $team->tshirt;?>2.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p3{
 background:url(../images/tshirt/<?php echo $team->tshirt;?>3.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p4{
  background:url(../images/tshirt/<?php echo $team->tshirt;?>4.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p5{
  background:url(../images/tshirt/<?php echo $team->tshirt;?>5.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p6{
  background:url(../images/tshirt/<?php echo $team->tshirt;?>6.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p7{
 background:url(../images/tshirt/<?php echo $team->tshirt;?>7.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p8{
  background:url(../images/tshirt/<?php echo $team->tshirt;?>8.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p9{
  background:url(../images/tshirt/<?php echo $team->tshirt;?>9.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p10{
  background:url(../images/tshirt/<?php echo $team->tshirt;?>10.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p11{
  background:url(../images/tshirt/<?php echo $team->tshirt;?>11.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
   
</style>
  
  
</head>

<body>
<script src="https://www.gstatic.com/firebasejs/6.3.4/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.10.1/firebase-auth.js"></script>
<script src="../js/firebase.js"></script>
  <script src="https://kit.fontawesome.com/070081e2e4.js"></script>
	<script src="../js/jquery.js"></script>
<script src="../js/editorTactica.js"></script>

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
			 
			<div class="col-lg-12 col-sm-12 col-md-12"> 
			 <div class="col-lg-6 col-md-12 col-sm-12" > 
			 <div id='terreno' title='El punto exacto del jugador en el terreno esta marcado en el centro de los dos pies.'>
			  <img src="../images/<?php echo $team->escudo;?>" style="position: absolute; width: 60%; top:50%; left:20%; opacity: 0.05; vertical-align: middle;"> 
			 <div id='p1' class='players' onMouseOver="sobrePlayer(this);" onMouseDown="comienzoMovimiento(event, this.id);" onMouseOut="fueraPlayer(this);"></div>
			 <div id='p2'  class='players' onMouseOver="sobrePlayer(this);" onMouseDown="comienzoMovimiento(event, this.id);" onMouseOut="fueraPlayer(this);"></div>
			 <div id='p3'  class='players' onMouseOver="sobrePlayer(this);" onMouseDown="comienzoMovimiento(event, this.id);" onMouseOut="fueraPlayer(this);"></div>
			 <div id='p4'  class='players' onMouseOver="sobrePlayer(this);" onMouseDown="comienzoMovimiento(event, this.id);" onMouseOut="fueraPlayer(this);"></div>
			 <div id='p5'  class='players' onMouseOver="sobrePlayer(this);" onMouseDown="comienzoMovimiento(event, this.id);" onMouseOut="fueraPlayer(this);"></div>
			 <div id='p6'  class='players' onMouseOver="sobrePlayer(this);" onMouseDown="comienzoMovimiento(event, this.id);" onMouseOut="fueraPlayer(this);"></div>
			 <div id='p7'  class='players' onMouseOver="sobrePlayer(this);" onMouseDown="comienzoMovimiento(event, this.id);" onMouseOut="fueraPlayer(this);"></div>
			 <div id='p8'  class='players' onMouseOver="sobrePlayer(this);" onMouseDown="comienzoMovimiento(event, this.id);" onMouseOut="fueraPlayer(this);"></div>
			 <div id='p9'  class='players' onMouseOver="sobrePlayer(this);" onMouseDown="comienzoMovimiento(event, this.id);" onMouseOut="fueraPlayer(this);"></div> 
			 <div id='p10'  class='players' onMouseOver="sobrePlayer(this);" onMouseDown="comienzoMovimiento(event, this.id);" onMouseOut="fueraPlayer(this);"></div>
			 <div id='p11'  class='players' onMouseOver="sobrePlayer(this);" onMouseDown="comienzoMovimiento(event, this.id);" onMouseOut="fueraPlayer(this);"></div>
			</div>

			<?php
			$initial= explode(',',$tactic[2]);
			$forces= explode(',',$tactic[0]);
			$players= explode(',',$tactic[1]);
			?>
			 <div id="n1" class="names"><?php echo $players[0];?></div>
			 <div id="n2" class="names"><?php echo $players[1];?></div>
			  <div id="n3" class="names"><?php echo $players[2];?></div>
			   <div id="n4" class="names"><?php echo $players[3];?></div>
				<div id="n5" class="names"><?php echo $players[4];?></div>
				 <div id="n6" class="names"><?php echo $players[5];?></div>
				  <div id="n7" class="names"><?php echo $players[6];?></div>
				   <div id="n8" class="names"><?php echo $players[7];?></div>
					<div id="n9" class="names"><?php echo $players[8];?></div>
					 <div id="n10" class="names"><?php echo $players[9];?></div>
					  <div id="n11" class="names"><?php echo $players[10];?></div>
			</div> 

			<div class="col-lg-6 col-md-12 col-sm-12"> 
			<form action="tactica.php" method='post'>
			<input type="hidden" id="i" name="i" value="<?php echo $idTactic;?>">	

			<table class="table-sm table-responsive">
			<tr class="bg-info text-white" align="center">
			   <td>#</td>
			   <td  >NOMBRE</td>
			   <td  >FUERZA</td>    
			   <td  >POS X</td>
			   <td  >POS Y</td>
			</tr>
			<?php

			for($i=0;$i<11;$i++)
			  {	 
				?>
			<tr class="" id="linea<?php echo $i+1; ?>" onMouseOver="sobreLinea(this);" onMouseOut="fueraLinea(this);">
				<td><?php echo $i+1; ?></td>
			   <td><input class="form-control nombre" type="text" value="<?php echo $players[$i];?>" id="nP<?php echo $i+1;?>" name="nP<?php echo $i+1;?>" onKeyUp="cambiarNombre(this);"></td>
			   <td>
			   <input class="form-control rango" type="range" min=1 max=4 name='forceP<?php echo $i+1;?>' id='forceP<?php echo $i+1;?>' title="<?php echo $forces[$i];?>" onChange="updateRange(this);" value=<?php echo $forces[$i];?>>    
			   </td>      
			   <td><input min=0 max=480 class="form-control posi" type="number" id='posXP<?php echo $i+1;?>' name='posXP<?php echo $i+1;?>' value=<?php echo $initial[$i*2];?>  onChange="cambiarPLayer(this);"></td>
			   <td><input min=0 max=360 class="form-control posi" type="number" id='posYP<?php echo $i+1;?>' name='posYP<?php echo $i+1;?>' value=<?php echo $initial[$i*2+1];?> onChange="cambiarPLayer(this);"></td>
			</tr>

			<?php 
			 }
			?>

			</table>
				<div class="row" align="center">
				<div class="col"><input class='btn btn-primary' type="submit" value="SAVE" style="margin: 10px;"></div>
			</div>	
				</form>
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
    
    <!-- /MAIN CONTENT -->
    <!--main content end-->
    <!--footer start-->
    <footer class="site-footer">
      <div class="text-right">
        <p>
          &copy; Copyrights <strong>futbolinCode</strong>. All Rights Reserved
        </p>
       
        <a href="tactica.php#" class="go-top">
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
