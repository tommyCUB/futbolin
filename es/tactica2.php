<?php
$tactic=""; 
$tactic=$control->getTactic($idTeam,0);
$teamA= new Team($idTeamActual,false);
?>
<link rel="stylesheet" type="text/css" href="../css/editorTactica2.css">
<style type="text/css">

#p1{
  background:url(../images/tshirt/<?php echo $teamA->tshirt;?>1.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p2{
 background:url(../images/tshirt/<?php echo $teamA->tshirt;?>2.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p3{
 background:url(../images/tshirt/<?php echo $teamA->tshirt;?>3.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p4{
  background:url(../images/tshirt/<?php echo $teamA->tshirt;?>4.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p5{
  background:url(../images/tshirt/<?php echo $teamA->tshirt;?>5.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p6{
  background:url(../images/tshirt/<?php echo $teamA->tshirt;?>6.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p7{
 background:url(../images/tshirt/<?php echo $teamA->tshirt;?>7.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p8{
  background:url(../images/tshirt/<?php echo $teamA->tshirt;?>8.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p9{
  background:url(../images/tshirt/<?php echo $teamA->tshirt;?>9.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p10{
  background:url(../images/tshirt/<?php echo $teamA->tshirt;?>10.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
#p11{
  background:url(../images/tshirt/<?php echo $teamA->tshirt;?>11.png);
  background-size: 100% 100%;
  left:auto;
  top:auto;	
}
   
</style>

<body>
<script src="../js/jquery.js"></script>
<script src="../js/editorTactica2.js"></script>

  <section id="container" style="">
    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <!--main content start-->
           
        <div class="row">
          <div class="col-lg-12 col-md-12">
			 
			<div class="col-lg-12 col-sm-12 col-md-12"> 
			 <div class="col-lg-12 col-md-12 col-sm-12" > 
			 <div id='terreno'>
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
			for($i=0;$i<11;$i++)
			{
				echo "<input type='hidden' value='".$initial[$i*2]."' id='posXP".($i+1)."'>";
				echo "<input type='hidden' value='".$initial[$i*2+1]."' id='posYP".($i+1)."'>";
			}
			?>
				 
				 
			 <div id="n1" class="names"><?php echo "<span class='numeros  '>1 (".$forces[0].")</span>";?></div>
			 <div id="n2" class="names"><?php echo "<span class='numeros  '>2 (".$forces[1].")</span>";?></div>
			  <div id="n3" class="names"><?php echo "<span class='numeros  '>3 (".$forces[2].")</span>";?></div>
			   <div id="n4" class="names"><?php echo "<span class='numeros  '>4 (".$forces[3].")</span>";?></div>
				<div id="n5" class="names"><?php echo "<span class='numeros  '>5 (".$forces[4].")</span>";?></div>
				 <div id="n6" class="names"><?php echo "<span class='numeros  '>6 (".$forces[5].")</span>";?></div>
				  <div id="n7" class="names"><?php echo "<span class='numeros  '>7 (".$forces[6].")</span>";?></div>
				   <div id="n8" class="names"><?php echo "<span class='numeros  '>8 (".$forces[7].")</span>";?></div>
					<div id="n9" class="names"><?php echo "<span class='numeros  '>9 (".$forces[8].")</span>";?></div>
					 <div id="n10" class="names"><?php echo "<span class='numeros  '>10 (".$forces[9].")</span>";?></div>
					  <div id="n11" class="names"><?php echo "<span class='numeros  '>11 (".$forces[10].")</span>";?></div>
			</div>
			         
          </div> 	
	       
		</div>		 
        </div>
      

</body>

</html>
