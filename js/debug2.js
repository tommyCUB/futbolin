// JavaScript Document
var playpause=false;
$(document).ready(function(e) {
	$("#divTurno").html(0);
	mostrarTurno(0);
	setTurno(0,1);	
	
});

$("#terreno").mouseenter(function(e){ 	
	 e.preventDefault()
      this.css({
         'visibility': "visible"
      });
});

function actDisTie(desempate)
{ 
	 
	$("#txtDistie").html(Math.abs(desempate)) ;
	if(desempate<0)
	{		 
		porC= parseInt(desempate*100/48000*-1);			 
		//alert($("#progresLeft").width());
	    $("#progresRight").width(porC+"%");	
		$("#txtDistie").css("color","blue");
	}	
	else
	{
		porC= parseInt(desempate*100/48000);	
		//alert($("#progresRight").width());
	
		$("#progresLeft").width(porC+"%");
		$("#txtDistie").css("color","red");
	}
}

$("#terreno").mouseleave(function(e){ 	
	 e.preventDefault()
      this.css({
         'visibility': "hidden"
      });
});

function procesarDebug(turno)
{
  lineas= $("#divDebug").html();
    
  lineas= lineas.split("%%");
	
   if(turno<lineas.length){		   
	actual= lineas[turno].split(';');
	
	comandos= actual[0].split(':');	
	tablaSym= actual[1].split('#');	 
	errores= actual[2];	   
	estado= actual[3]; 
	   
	cmd="";		   
	   
	for(i=0;i<comandos.length;i++)
		if(comandos[i]!='')
		cmd+="<a href='#' id='cmd"+i+"' style='padding:0.1rem;'>"+comandos[i]+"</a>,<br> ";	 
	$("#divComandos").html(cmd);  
	cmd="";
	for(i=0;i<tablaSym.length;i++)
		if(tablaSym[i]!='')
		cmd+="<a href='#' style='padding:0.1rem;'>"+tablaSym[i]+"</a>,<br>";	 
	$("#divTablaSym").html(cmd);
	
	errores= errores.split(','); 
	cmd="";   
	for(i=0;i<errores.length;i++)
		cmd+="<a href='#' style='padding:0.1rem;'>"+errores[i]+"</a>,<br>";	
	$("#divErrores").html(cmd);	   
	   
	estado= estado.split(',');
	   
	if(parseInt(estado[7])<0)
		$("#tdFactor").html("<span class='text-danger'>"+Math.abs(estado[7])+"</span>");
	if(parseInt(estado[7])>=0)
		$("#tdFactor").html("<span class='text-success'>"+Math.abs(estado[7])+"</span>");	
	 actDisTie(parseInt(estado[7]));
	 
    $("#aTics").html(turno+" de "+estado[1]);	   
	$("#tdStatusBalon").html(estado[2]+" , "+estado[3]);
	   
	if(estado[4]=='False')
		$("#tdStatusEstoy").html("<span class='text-danger'>NO</span>");
	else
		$("#tdStatusEstoy").html("<span class='text-success'>SI</span>");
	 
	 $("#tdStatusMi").html(estado[5]);
	 $("#tdStatusSu").html(estado[6]);
   }	   	
}

function mostrarTurno(turno)
{
   moverPlayers(turno);	
   procesarDebug(turno);
}

function setTurno(pasos,seg=0)
{
	num=0;
    if(seg==1){
		pasos=$("#inmSeg").val();
		if(pasos=="")
			return;
		num= parseInt(pasos)*1000/100;
		$("#inmSeg").val("");
	}else{
	num=parseInt($("#divTurno").html());
	num+=pasos;
	}
	if(num<0)	 
		num=0;		
	if(num>400){
		num=400;		
	}
	
	last=$("#divTurno").html();	
	t= parseInt($("#divTiempo").html());
	t+= (num-last)*100;
	$("#divTiempo").html(t);
	$("#divReloj").html(t/1000);
	$("#divTurno").html(num);
	mostrarTurno(num);
	analizarGoles(last,num);
}

function analizarGoles(desde, hasta)
{
   lineas= $("#divMemory").html();	 
   lineas= lineas.split(';');
	
  if(desde<hasta)	
   for(i=desde;i<=hasta;i++)
	   {
		 cdn= lineas[i];
		  
		 if(cdn.indexOf("GOL")!=-1)
		  {
			
			goles=cdn.slice(cdn.indexOf('(')+1,cdn.indexOf(')'));			
			goles= goles.split(',');
			console.log(cdn);  
			
		    $("#golTeam1").html(goles[1]);
		    $("#golTeam2").html(goles[2]);
			
		  }
	   }
	if(desde>hasta){
		gol1=0;
		gol2=0;
	   for(i=desde;i>=hasta;i--)
		   {
			 cdn= lineas[i];
			 if(cdn.indexOf("GOL")!=-1)
			  {
				goles=cdn.slice(cdn.indexOf('(')+1,cdn.indexOf(')'));
				goles= goles.split(',');
				gol1= goles[1];
				gol2= goles[2];
				  
				if(goles[0]==0)
					 gol1--;
				 else
					 gol2--;
			  }
		   }
		$("#golTeam1").html(gol1);
		$("#golTeam2").html(gol2);  
	}
}

function moverPlayers(turno)
{
   lineas= $("#divMemory").html();
   lineas= lineas.split(';');
   
   if(turno<lineas.length && lineas[turno].indexOf("GOL")==-1){    
   actual = lineas[turno];      	
   actual = actual.split(',');   
   posTerreno= $("#divTerreno").offset();    
	   
    
   $("#ball").offset({left: parseInt(posTerreno.left)+ parseInt(actual[0]*1.31) - 4 ,top: parseInt(posTerreno.top) + parseInt(actual[1]*1.31)- 4});   
	   
   jug=1;
   for(i=2;i<=22;i+=2)
   {
	 x= parseInt(actual[i]*1.31);
	 y= parseInt(actual[i+1]*1.31);
	   
	 //$("#pose1p"+(jug)).html(x+" , "+ y); 
	 $("#pose1p"+(jug)).html(jug); 
	 $("#e1p"+(jug)).attr("title","("+(jug)+") Fz:"+$("#fze1p"+(jug)).html()+" ("+x+" , "+ y+")");
	 $("#e1p"+(jug++)).offset({left: posTerreno.left+x -10 ,top: posTerreno.top+ y -42});	 
   }
   jug=1;
    for(i=24;i<=46;i+=2)
   {
    x= parseInt(actual[i]*1.31);
	 y= parseInt(actual[i+1]*1.31);
	 //$("#pose2p"+(jug)).html(x+" , "+ y);
	 $("#pose2p"+(jug)).html(jug);
	 $("#e2p"+(jug)).attr("title","("+(jug)+")  "+x+" , "+ y);
	 $("#e2p"+(jug++)).offset({left: posTerreno.left+x -10 ,top: posTerreno.top+ y -42 });  
   }    
   }  
}

function switchPlayPause()
{	
	if(playpause==true){
		pause();
		playpause=false;
	}
	else
		{
		
		 play();
		 playpause=true;
		}
}

function interpretarComando(cmd)
{
  cdn= cmd.id;
  limpiarCuadro();
  cdn = $("#"+cmd.id).html();
  
  if(cdn.indexOf("GOTO")!=-1)
   {	 	   
	 pos= cdn.substring(cdn.indexOf('(')+1,parseInt(cdn.indexOf(')'))); 
	 pos= pos.split(',');
	 
	 inicio=$("#pose1p"+parseInt(pos[0])).html().split(',');
	  
	   alert(inicio[0]+"  "+inicio[1]);
	 $('#cuadro').drawArc({
		  fillStyle: 'black',
		  x: parseInt(inicio[0]), y: parseInt(inicio[1]),
		  radius: 10
		});
	   
	 circuloTransp(parseInt(inicio[0]),parseInt(inicio[1]),40,"#DB0003");	   
	 circuloRelleno(pos[1]-5,pos[2]-5,10,"#DB0003");   
	   
	 dist=getDistance(parseInt(inicio[0]),parseInt(inicio[1]),parseInt(pos[1]),parseInt(pos[2]));   
	 sen= (parseInt(pos[2]) - parseInt(inicio[1]))/dist;
	 cos= (parseInt(pos[1]) - parseInt(inicio[0]))/dist;
	    
	 x= cos*20+parseInt(inicio[0]);
	 y= sen*20+parseInt(inicio[1]);
	 
	 linea(x,y,pos[1],pos[2]);
   }
}

function getDistance(xo,yo,xf,yf)
{
	return Math.sqrt(Math.pow(xo-xf,2)+Math.pow(yo-yf,2));	
}

function play()
{  	
   if($("#divTurno").html()>=400){
	   $("#divTurno").html(0);
	   mostrarTurno(0);
   }
   clearInterval(parseInt($("#idInterval").html()));   
   idInterval= setInterval(function(){
		  moverPlayers(parseInt($("#divTurno").html()));
	      setTurno(1);
	      
		},parseInt($("#divInterval").html()));
		
   $("#idInterval").html(idInterval);	 	
}

function pause()
{
   clearInterval(parseInt($("#idInterval").html())); 
}

function circuloTransp(xo,yo,radio,color)
{
	var canvas = document.getElementById('cuadro');
  if (canvas.getContext){
    var ctx = canvas.getContext('2d');
  ctx.beginPath();
  
  ctx.arc(xo,yo,radio,0,Math.PI*2);
  ctx.stroke();
}
}

function circuloRelleno(xo,yo,radio,color)
{  
  var canvas = document.getElementById('cuadro');
	
  if (canvas.getContext){
    var ctx = canvas.getContext('2d');
	 
  ctx.beginPath();   
  ctx.arc(xo,yo,radio,0,Math.PI*2);
  ctx.fill();
}
}

function linea(xo,yo,xf,yf)
{
	var canvas = document.getElementById('cuadro');
  if (canvas.getContext){
    var ctx = canvas.getContext('2d');
  ctx.beginPath();
  
  ctx.moveTo(xo,yo);
  ctx.lineTo(xf,yf);
  ctx.stroke();
}
}

function limpiarCuadro()
{
	var canvas = document.getElementById('cuadro');
  if (canvas.getContext){
    var ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}
}




