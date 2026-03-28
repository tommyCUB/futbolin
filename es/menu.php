<aside>
      <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
          <p class="centered"><a href="user.php"><img id="photoUser" src="<?php echo $_SESSION['urlPhoto'];?>" class="img-circle" width="80"></a></p>
		  <h6 class="centered text-white" id="" style="color:gray;">
          <?php 
            if($user['permisos']==0)
             echo "Administrador";
            if($user['permisos']==1)
             echo "Manager"; 
            if($user['permisos']==2)
             echo "Productor";    
             if($user['permisos']==3)
             echo "Gerente"; 
             if($user['permisos']==4)
             echo "Master";   
             if($user['permisos']==5)
             echo "Presidente";     
          ?>          
          </h6>
          <h5 class="centered text-white" id="nameUser2" style="color:gray;"><?php echo $_SESSION['nombre'];?></h5>
		   <h6 class="text-white centered" id="coins" style="color:gold;"><sup>¢</sup><?php echo $user['coins'];?></h6>			          
          <li class="sub-menu">
            <a href="javascript:;">
              <i class="fa fa-cogs"></i>
              <span>Administración Equipo</span>
              </a>
            <ul class="sub">
	 
			<li><a href="team.php?i=<?php echo $team->idTeam;?>"><img alt="avatar" src="../images/<?php echo $team->escudo;?>" style="width: 25px;">  
					<?php echo $team->nombre;?></a></li>
              <li><a href="tactica.php?i=<?php echo $team->idTeam;?>"><img alt="camp" src="../images/campo3.png" style='width:15px;'>  Posición Equipo</a></li>
              <li><a href="code.php"><i class='fa fa-code'></i>  Editor Código</a></li>
              <li><a href="tshirt.php?i=<?php echo $team->idTeam;?>"><img alt="camp" src="../images/tshirt/<?php echo $team->tshirt;?>9.png" style='width:10px;'>  Elección de camisetas</a></li>
              <li><a href="escudo.php?i=<?php echo $team->idTeam;?>"><img alt="avatar" src="../images/<?php echo $team->escudo;?>" style="width: 25px; -webkit-filter: grayscale(100%); filter: grayscale(100%);">  Eleccion del escudo</a></li>              
            </ul>
          </li>
		  <li class="sub-menu">
				<a href="javascript:;">
				  <i class="fa fa-check-square"></i>
					<?php 
						$cantCheck= $challdone['cantCheck'];					    
					?>
					<span>Challenge </span>
				  </a>
				<ul class="sub">   
				  <li><a href="challenge.php"><i class=""></i>Retos
					  <span class="badge bg-success" title="# Retos cumplidos"><?php echo $cantCheck;?></span>
						<span class="badge bg-warning" title='# Retos sin cumplir'><?php echo (count($challenge)-$cantCheck);?></span>
					  </a></li> 			  				
				</ul>				
				
         	 </li> 	
		  <li class="sub-menu">
            <a href="javascript:;">
              <i class="fa fa-money"></i>
              <span>Compras</span>
              </a>
            <ul class="sub">                   
                <li>
					<form action="pagar.php" method="post" >
						<input type="hidden" name="inCosto" id= "inCosto" value="0.49">
						<input type="hidden" name="inCant" value="1000">
						<input type="hidden" name="inDesc" id="inDesc" value="Recibes 1000¢ de futbolinCode.com">					
						<button  class="a btn-link li" href="this.form.submit" onClick="this.form.submit" style='padding:.6rem; color:gray;'>1000 <sup>¢</sup>  <strong>$0.49</strong></button> 
					</form>
				 </li>				 
				 <li>
					<form action="pagar.php" method="post">
						<input type="hidden" name="inCosto" value="0.79">
						<input type="hidden" name="inCant" value="3000">
						<input type="hidden" name="inDesc" value="Recibes 3000¢ de futbolinCode.com">
						<button class="a btn-link" href="this.form.submit" onClick="this.form.submit" style='padding:.6rem; color:gray;'>3000 <sup>¢</sup>   <strong>$0.79</strong></button>
					</form>
				 </li>
				 
				 <li>
					<form action="pagar.php" method="post">
						<input type="hidden" name="inCosto" value="0.99">
						<input type="hidden" name="inCant" value="5000">
						<input type="hidden" name="inDesc" value="Recibes 5000¢ de futbolinCode.com">
						<button class="a btn-link" href="this.form.submit" onClick="this.form.submit" style='padding:.6rem; color:gray;'>5000 <sup>¢</sup>   <strong>$0.99</strong></button>
					</form>
				 </li>
				 <li>
					<form action="pagar.php" method="post">
						<input type="hidden" name="inCosto" value="1.29">
						<input type="hidden" name="inCant" value="15000">
						<input type="hidden" name="inDesc" value="Recibes 15 000¢ de futbolinCode.com">
						
						<button class="a btn-link" href="this.form.submit" onClick="this.form.submit" style='padding:.6rem; color:gray;'>15000 <sup>¢</sup>   <strong>$1.29</strong></button>
					</form>
				 </li>				 
				 <li>
					<form action="pagar.php" method="post">
						<input type="hidden" name="inCosto" value="1.89">
						<input type="hidden" name="inCant" value="50000">
						<input type="hidden" name="inDesc" value="Recibes 50 000¢ de futbolinCode.com">
						<button class="a btn-link" href="this.form.submit" onClick="this.form.submit" style='padding:0.6rem; color:gray;'>50 000 <sup>¢</sup>  <strong> $1.89</strong></button>
					</form>
				 </li>          
            </ul>
          </li>			
		   
		  <li class="sub-menu">
				<a href="javascript:;">
				  <i class="fa fa-trophy"></i>
				  <span>Control de ligas</span>
				  </a>
				<ul class="sub">   
				  <li><a href="newliga.php"><i class="fa fa-plus-circle"></i>Nueva Liga</a></li> 	
				  <li>
					  <a href="market.php"><i class="fa fa-store"></i>Mercado de ligas<span class="badge bg-theme"><?php  echo $cantHabil;?></span>
					  </a>
				  </li> 				
				</ul>				
				
         	 </li> 
			<li class="sub-menu">
            <a href="javascript:;">
              <i class="fa fa-gamepad"></i>
              <span>Último Partido Amistoso</span>
              </a>
            <ul class="sub">               
              <li> 
				  <?php
				    $teamLast= new Team($lastFriend['idTeam2']);
				  ?>
				  <a href="show.php?g=<?php echo $lastFriend['idFriendly'];?>&type=f">					  
				  	<i class='fa fa-eye'></i>
					  
					  <?php echo $lastFriend['gol1'];?> :
					  <?php echo $lastFriend['gol2'];?> 					  
					 <img src="../images/<?php echo $teamLast->escudo;?>" style="width:15px; height:15px;">
					 <?php echo $teamLast->nombre;?> 
				  </a>
			  </li>  
				
				<li>
				   <a href="debug.php?i=<?php echo $lastFriend['idFriendly'];?>"><i class='fa fa-thermometer'></i>Analizar Partido</a>
				</li>
            </ul>			    
          </li>
		
		 <li class="sub-menu">
				<a href="javascript:;">
				  <i class="fa fa-dollar"></i>
				  <span>Economía</span>
				  </a>
				<ul class="sub">   
				  <li><a href="cuentas.php"><i class="fa fa-book"></i>Cuentas</a></li> 	
				  				
				</ul>				
				
         	 </li>	
			
			<li class="sub-menu">
				<a href="javascript:;">
				  <i class="fa fa-list"></i>
				  <span>Ranking</span>
				  </a>
				<ul class="sub">   
				  <li><a href="ranking.php"><i class="fa fa-list-ol"></i>Ranking Mundial</a></li> 	
				  				
				</ul>				
				
         	 </li>
			
</aside>