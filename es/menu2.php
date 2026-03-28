<?php
$cant= $control->getCantLigasEspera();
$cantHabil=$control->getCantLigasHabiles($idTeam,0);
$ligasFinish= $control->getLigasenEstado(2,$idTeam);
$ligasExec= $control->getLigasenEstado(1,$idTeam);
$ligasEspera= $control->getLigasenEstado(0,$idTeam);
?>

<header class="header black-bg">
      <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
      </div>
      <!--logo start-->
      <a href="index.html" class="logo"><b>FUTBOLIN<span>code</span></b></a>
      <!--logo end-->
      <div class="nav notify-row" id="top_menu">
        <!--  notification start -->		  
        <ul class="nav top-menu">          
          <!-- inbox dropdown start-->			
		 <li class="dropdown" id="nameUser">
			  
			 <a href="team.php" style="clear: b;">
				 <img alt="avatar" src="../images/<?php echo $team->escudo;?>" style="width: 20px;">  
				 <?php 
				 echo "<span class='badge bg-theme' style='font-size: 10px'>".($team->ranking)."</span>";
				 echo "<small>".$team->nombre."</small>";
				 echo "<small> (".intval($team->elo).")</small>";
				 ?>
			  </a>
		  </li>			
           <!-- Icono notificaciones -->     
		  <li id="header_notification_bar" class="dropdown" title="Notificaciones">
            <a class="" href="user.php">
              <i class="fa fa-bell-o"></i>
              <span class="badge bg-warning"><?php echo count($news);?></span>
              </a>            
          </li>	
			
			<!-- Icono Ligas en espera -->
			
		  <li class="dropdown" title="Ligas en Espera">			  
            <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">
              <i class="fa fa-trophy"></i>
              <span class="badge bg-theme"><?php  echo count($ligasEspera);?></span>
              </a>
            <ul class="dropdown-menu extended tasks-bar">
              <div class="notify-arrow notify-arrow-green"></div>
              <?php 	
				$cont=0;
				foreach($ligasEspera as $l){ 
					$tipo= $l['tipo'];					
					if($tipo==1)
						 $tipo='Grp';
					else
						$tipo='TCT';					
				?>
              <li style="padding: .1px; margin:.2px; padding-bottom: 0px; margin-bottom: 0px;">
                <a href="league.php?i=<?php echo $l['idLiga'];?>">
                  <i class="fa fa-trophy text-info"></i>
                  <?php echo $l['nombre'];?>
                  
                  </a>
              </li>
				<?php
					$cont++;
					if($cont>10)
						break;
					}
				?>
            </ul>
          </li>	
			
		  <!-- Icono Ligas en ejecucion -->
			
		   <li class="dropdown" title="Ligas en proceso">			  
            <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">				
			  <span style="color: red;">
              		<i class="fa fa-trophy"></i>
			  </span>  
              <span class="badge bg-theme"><?php  echo count($ligasExec);?></span>
              </a>
            <ul class="dropdown-menu extended tasks-bar">
              <div class="notify-arrow notify-arrow-green"></div>
              <?php 								
				foreach($ligasExec as $l){ 
					$tipo= $l['tipo'];
					
					if($tipo==1)
						 $tipo='Grp';
					else
						$tipo='TCT';
					
				?>
              <li>
                <a href="league.php?i=<?php echo $l['idLiga'];?>">
                   <i class="fa fa-trophy text-danger"></i> 
                  <?php echo $l['nombre'];?>
                   
                  </a>
              </li>
				<?php
					}
				?>
            </ul>
          </li>
			
			<!-- Icono Ligas terminadas -->
			
			<li class="dropdown" title="Ligas Terminadas">
			 
            <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">				
			  <span style="color: green;">
              		<i class="fa fa-trophy"></i>
			  </span>  
              <span class="badge bg-theme"><?php  echo count($ligasFinish);?></span>
              </a>
            <ul class="dropdown-menu extended tasks-bar">
              <div class="notify-arrow notify-arrow-green"></div>
              <?php 
				$cont=0;
				foreach($ligasFinish as $l){ 
					$tipo= $l['tipo'];
					
					if($tipo==1)
						 $tipo='Grupos';
					else
						$tipo='TCT';					
				?>
              <li>
                <a href="league.php?i=<?php echo $l['idLiga'];?>">
                   <i class="fa fa-trophy text-success"></i> 
                  <?php echo $l['nombre'];?>
                   
                  </a>
              </li>
				<?php
					$cont++;
					if($cont>10)
						break;
					}
				?>
            </ul>
          </li>			
			<!--  Market -->
			<li class="dropdown" title="Mercado" onClick="irMarket(<?php  echo $idTeam;?>);">
			  
             <a data-toggle="dropdown" class="dropdown-toggle" href="market.php?i=<?php  echo $idTeam;?>"> 
              		<i class="fa fa-shopping-cart"></i>	     
              <span class="badge bg-theme"><?php  echo $cantHabil;?></span>
              </a>           
          </li>	
         
        </ul>
        <!--  notification end -->
      </div>
      <div class="top-menu">
        <ul class="nav pull-right top-menu">
          <li><a class="logout" href="../utils/cerrar.php">Cerrar</a></li>
        </ul>
      </div>
    </header>