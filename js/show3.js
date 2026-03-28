// JavaScript Document

var played=true;
var desempate=0;

$(document).ready(function(e) {	
	stop();
	$("#divTurno").html(1);
	$("#divTiempo").html(0);
	/*
	moverPlayers(parseInt($("#divTurno").html()));
	setTimeout(function(){			
		empezarJuego();		
		},1000);*/
	
	desempate=0;	 
});

function showFull()
{
    
}

function fullscreen(elem)
{
  //Si el navegador es Mozilla Firefox
  if(elem.mozRequestFullScreen) {
    elem.mozRequestFullScreen();
  }
  //Si el navegador es Google Chrome
  else if(elem.webkitRequestFullScreen) {
    elem.webkitRequestFullScreen();
  }
  //Si el navegador es otro
  else if(elem.requestFullScreen) { 
    elem.requestFullScreen(); 
  }
    
}

function pantallaNormal() {
  //Mozilla Firefox
  if(document.mozCancelFullScreen) {
    document.mozCancelFullScreen();
  }
  //Google Chrome
  else if(document.webkitCancelFullScreen) {
    document.webkitCancelFullScreen();
  }
  //Otro
  else if(document.cancelFullScreen) { 
    document.cancelFullScreen(); 
  }
}

function tooglePlay()
{
	if(played===true){
		played=false;
		pause();
	}
	else{
		played=true;
		play();
	}
}

function empezarJuego()
{
   idInterval= setInterval(function(){
		  moverPlayers(parseInt($("#divTurno").html()))
		},parseInt($("#divInterval").html()));
		
    $("#idInterval").html(idInterval);	
}

function animarGol(cantGol,equipo)
{	 
	pause();
	
	$("#msgGol").css("display","block");
	
	if(equipo===0)	{ 
		$("#imgGol").removeClass("imgVolteada");
		$("#imgGol").css("left","");
		$("#imgGol").css("rigth","-130px");
		$("#imgTeam1Gol").css("display","block");
		$("#imgTeam2Gol").css("display","none");
		
	}
	
	if(equipo==1)
	{		
		$("#imgGol").addClass("imgVolteada");
		$("#imgGol").css("rigth","");
		$("#imgGol").css("left","-130px");
		$("#imgTeam1Gol").css("display","none");
		$("#imgTeam2Gol").css("display","block");	 
	}	
	
	
	volverDeGol();
 }
var lock=false;
function getDistance(p1,p2)
{
	return Math.sqrt(Math.pow(p2.top-p1.top,2)+Math.pow(p2.left-p1.left,2));
}

function volverDeGol()
{
	$("#msgGol").css("display","none");	
	play();
	$("#divTurno").html(parseInt($("#divTurno").html())+1);	
	
}
 
function actDisTie()
{ 
	$("#txtDistie").html(Math.abs(desempate)) ;
	if(desempate<0)
	{		 
		porC= parseInt(desempate*100/48000*-1);			 
		 
	    $("#progresRight").width(porC+"%");	
		
		$("#txtDistie").css("color","blue");
	}	
	else
	{
		porC= parseInt(desempate*100/48000);	
		$("#progresLeft").width(porC+"%");
		
		$("#txtDistie").css("color","red");
	}
}
	
function numeroAleatorio(min, max) {
      return Math.round(Math.random() * (max - min) + min);
 }

function finJuego()
{
	$("#divFulltime").css("display","block");	 
	$("#pizarra").css("display","none");
	
	desempate=0;
}

function moverPlayers(turno)
{
   lineas= $("#divMemory").html();
   lineas= lineas.split(';');
   
   if(turno>=lineas.length){
     finJuego();
	 stop();
	 clearInterval(parseInt($("#idInterval").html()));
   }
   else{
	   actual = lineas[turno];
    if(actual.indexOf("GOL")!=-1){		
		    
		    pos1=actual.indexOf('(')+1;
			pos2=actual.indexOf(')');
			cdn= actual.substr(pos1,pos2-pos1);
			 
		    goles= cdn.split(',');
			gol1=goles[1];
			gol2= goles[2];
				
			$("#golTeam1").html(gol1);
			$("#golTeam2").html(gol2);
		     
			animarGol(0,parseInt(goles[0])+1);
		}
	   else{	   
		         	
		   actual = actual.split(',');   
		   posTerreno= $("#divTerreno").offset();   
		   desempate+= (parseInt(actual[0])-240);
		   
		   $("#ball").offset({left: posTerreno.left+ actual[0]*1.5 - 5, top: posTerreno.top+ actual[1]*1.5 - 5}); 
		   jug=1;
		   for(i=2;i<=22;i+=2)
		   {
			 x= parseInt(actual[i])*1.5;
			 y= parseInt(actual[i+1])*1.5;

			 $("#e1p"+(jug++)).offset({left: posTerreno.left+x -5 ,top: posTerreno.top+ y -43});	 
		   }
		   jug=1;
			for(i=24;i<=46;i+=2)
		   {
			x= parseInt(actual[i])*1.5;
			 y= parseInt(actual[i+1])*1.5;

			 $("#e2p"+(jug++)).offset({left: posTerreno.left+x -5 ,top: posTerreno.top+ y -43 });  
		   }

		   $("#divTurno").html(parseInt($("#divTurno").html())+1);
		   tiempo= parseInt($("#divTiempo").html())+100;   
		   $("#divTiempo").html(tiempo);
		   $("#divReloj").html(parseInt(tiempo/1000));  
		   
		   actDisTie();
		   } 
   }
}

function play()
{
	clearInterval(parseInt($("#idInterval").html()));   
   idInterval= setInterval(function(){
		  moverPlayers(parseInt($("#divTurno").html()))
		},parseInt($("#divInterval").html()));
		
   $("#idInterval").html(idInterval);
   $("#divFulltime").css("display","none");
   $("#pizarra").css("display","block");
	played=true;
}

function pause()
{
   clearInterval(parseInt($("#idInterval").html())); 
   played=false;
}

function stop()
{
	clearInterval(parseInt($("#idInterval").html())); 
	$("#divTurno").html(1);	
	moverPlayers(parseInt($("#divTurno").html()));
	$("#divTiempo").html(0);
	played=false;
	desempate=0;
}
