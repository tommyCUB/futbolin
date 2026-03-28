// JavaScript Document

$(document).ready(function(e) {
		
	cargarPosInicial();	  
}); 

function cargarPosInicial()
{
 	
  posAbs= $("#terreno").offset();
  
  for(i=1;i<12;i++){	
  x= parseInt($("#posXP"+i).val())/1.3;
  y= parseInt($("#posYP"+i).val())/1.3;	  
  $("#p"+i).offset({left: posAbs.left+y - 15, top: (posAbs.top+369)- x - 25 });  
  $("#n"+i).offset({left: posAbs.left+y + 5, top: (posAbs.top+369)- x -15}); 	
}
}



