// JavaScript Document

$(document).ready(function(e) {
	cargar(); 	
	cargarPosInicial();	  
}); 


function cambiarPLayer(elem)
{	 
     ids= elem.id;
	 numero='';
	 
	 if(ids.indexOf('X')>0)	 
	   numero= ids.split('posXP')[1];
	 if(ids.indexOf('Y')>0)	 
	   numero= ids.split('posYP')[1];	
	 
	 x= parseInt($("#posXP"+numero).val());
	 y= parseInt($("#posYP"+numero).val());
	  	 
	 posAbs= $("#terreno").offset();	  
	
	 if(y>=0 && y<=360 && x>=0 && x<=480){			   
		$("#p"+numero).offset({left: posAbs.left+y - 17, top: (posAbs.top+480)- x - 65 });	
		$("#n"+numero).offset({left: posAbs.left+y - 15, top: (posAbs.top+480)- x  });		 
	}    
}


function cargar()
{
   posicion=0;	
  // IE
    if(navigator.userAgent.indexOf("MSIE")>=0) navegador=0;
    // Otros
    else navegador=1;	
} 
function getAbsoluteElementPosition(element) {
	
  if (typeof element == "string")
   element = document.getElementById(element);  
    
  if (!element)	  
	   return { top:0,left:0 };
    
  var y = 0;
  var x = 0;
  while (element.offsetParent) {
    x += element.offsetLeft;
    y += element.offsetTop;
    element = element.offsetParent;
  }
  
  return {top:y,left:x};
} 

function sobrePlayer(elem)
{
 id= elem.id; 
 pos=  parseInt(id.split('p')[1]);  
 linea= document.getElementById("linea"+pos);
 linea.style.backgroundColor="#d8dfd8";
 
 }
function fueraPlayer(elem)
{
 id= elem.id;
 pos= parseInt(id.split('p')[1]);
 linea= document.getElementById("linea"+pos);  
 linea.style.backgroundColor='#FFFFFF';	
}

function sobreLinea(elem)
{
 id= elem.id; 
 pos=  parseInt(id.split('linea')[1]);  
 player= document.getElementById("p"+pos);
 player.style.border="0.5px solid #f0f0f5";
 
 }
function fueraLinea(elem)
{
 id= elem.id;
 pos= parseInt(id.split('linea')[1]);
 player= document.getElementById("p"+pos);  
 player.style.border="";
}

function cambiarNombre(elem)
{  	 
  id= elem.id;
  idn= "n" + id.split('nP')[1];   
  document.getElementById(idn).innerHTML= elem.value;  	
}

function cargarPosInicial()
{
 	
  posAbs= $("#terreno").offset();
  
  for(i=1;i<12;i++){	
      	cambiarPLayer(document.getElementById("posXP"+i));
      	/*
  x= parseInt($("#posXP"+i).val());
  y= parseInt($("#posYP"+i).val());	  
  $("#p"+i).offset({left: posAbs.left+y - 17, top: (posAbs.top+480)- x - 70 });  
  $("#n"+i).offset({left: posAbs.left+y - 17, top: (posAbs.top+480)- x + 70 }); */
}
}

function evitaEventos(event)
{
    // Funcion que evita que se ejecuten eventos adicionales
    if(navegador==0)
    {
        window.event.cancelBubble=true;
        window.event.returnValue=false;
    }
    if(navegador==1) event.preventDefault();
}
 
function comienzoMovimiento(event, id)
{	
    elMovimiento=document.getElementById(id);
    
     // Obtengo la posicion del cursor
    if(navegador==0)
     {
        cursorComienzoX=window.event.clientX+document.documentElement.scrollLeft+document.body.scrollLeft;
        cursorComienzoY=window.event.clientY+document.documentElement.scrollTop+document.body.scrollTop;
 
        document.attachEvent("onmousemove", enMovimiento);
        document.attachEvent("onmouseup", finMovimiento);
    }
    if(navegador==1)
    {   
        
        cursorComienzoX=event.clientX+window.scrollX;
        cursorComienzoY=event.clientY+window.scrollY;
       
        document.addEventListener("mousemove", enMovimiento, true);
        document.addEventListener("mouseup", finMovimiento, true);
    }
    
	 
    elComienzoX=parseInt(elMovimiento.style.left);
    elComienzoY=parseInt(elMovimiento.style.top);
    
    posEl= $("#"+id).offset();
    
    elComienzoX=parseInt(posEl.left-17);
    elComienzoY=parseInt(posEl.top-170);
    
    // Actualizo el posicion del elemento
    elMovimiento.style.zIndex=++posicion;   
    evitaEventos(event);
}
 
function enMovimiento(event)
{ 
    var xActual, yActual;	 
	 
    if(navegador==0)
    {   
        xActual=window.event.clientX+document.documentElement.scrollLeft+document.body.scrollLeft-17;
        yActual=window.event.clientY+document.documentElement.scrollTop+document.body.scrollTop+35;
    } 
    if(navegador==1)
    {
        xActual=event.clientX+window.scrollX-17;
        yActual=event.clientY+window.scrollY+35;
    }
   
    posAbs= getAbsoluteElementPosition('terreno');
	id= elMovimiento.id;
	poss= id.split('p')[1];
			 
	x= (parseInt(xActual)-parseInt(posAbs.left));
	y= (parseInt(yActual)- parseInt(posAbs.top));
	
	if(x>=0 && x<=360 && y>=0 && y<=480){	
	document.getElementById("posXP"+poss).value= parseInt(480-y);
	document.getElementById("posYP"+poss).value= parseInt(x)+15;
	cambiarPLayer(document.getElementById("posXP"+poss));
	}
 
    evitaEventos(event);
}
 
function updateRange(elem)
{
	elem.title= elem.value;	 
	 
} 
function finMovimiento(event)
{
    if(navegador==0)
    {   
        document.detachEvent("onmousemove", enMovimiento);
        document.detachEvent("onmouseup", finMovimiento);
    }
    if(navegador==1)
    {
        document.removeEventListener("mousemove", enMovimiento, true);
        document.removeEventListener("mouseup", finMovimiento, true);
    }
}
