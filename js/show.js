// JavaScript Document

var played=true;
var desempate=0;
var enGol=false;
var factorMul=1.5;

$(document).ready(function(e) {
	$("#divTurno").html(1);
	moverPlayers(parseInt($("#divTurno").html()));
	setTimeout(function(){			
		empezarJuego();		
		},1000);
	$("#cajalista").toggle("slow");
	desempate=0;	
	actDisTie();
	 
	
	
	(function () {
    var viewFullScreen = document.getElementById("view-fullscreen");
    if (viewFullScreen) {
        viewFullScreen.addEventListener("click", function () {
            var docElm = document.documentElement;
            if (docElm.requestFullscreen) {
                docElm.requestFullscreen();
            }
            else if (docElm.msRequestFullscreen) {
                docElm = document.body; //overwrite the element (for IE)
                docElm.msRequestFullscreen();
            }
            else if (docElm.mozRequestFullScreen) {
                docElm.mozRequestFullScreen();
            }
            else if (docElm.webkitRequestFullScreen) {
                docElm.webkitRequestFullScreen();
            }
        }, false);
    }

    var cancelFullScreen = document.getElementById("cancel-fullscreen");
    if (cancelFullScreen) {
        factorMul=1.5;
        cancelFullScreen.addEventListener("click", function () {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
            else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
            else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            }
            else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            }
        }, false);
    }


    var fullscreenState = document.getElementById("fullscreen-state");
    if (fullscreenState) {
        document.addEventListener("fullscreenchange", function () {
            fullscreenState.innerHTML = (document.fullscreenElement)? "" : "not ";
        }, false);
        
        document.addEventListener("msfullscreenchange", function () {
            fullscreenState.innerHTML = (document.msFullscreenElement)? "" : "not ";
        }, false);
        
        document.addEventListener("mozfullscreenchange", function () {
            fullscreenState.innerHTML = (document.mozFullScreen)? "" : "not ";
        }, false);
        
        document.addEventListener("webkitfullscreenchange", function () {
            fullscreenState.innerHTML = (document.webkitIsFullScreen)? "" : "not ";
        }, false);
    }

    var marioVideo = document.getElementById("masterTerreno");
    var videoFullscreen = document.getElementById("video-fullscreen");

    if (marioVideo && videoFullscreen) {
        videoFullscreen.addEventListener("click", function (evt) {
            if (marioVideo.requestFullscreen) {
                marioVideo.requestFullscreen();
            }
            else if (marioVideo.msRequestFullscreen) {
                marioVideo.msRequestFullscreen();
            }
            else if (marioVideo.mozRequestFullScreen) {
                marioVideo.mozRequestFullScreen();
            }
            else if (marioVideo.webkitRequestFullScreen) {
                marioVideo.webkitRequestFullScreen();
                /*
                    *Kept here for reference: keyboard support in full screen
                    * marioVideo.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
                */
            }
        }, false);
    }
})();


$('#video-fullscreen').trigger('click');


});

function actDisTie()
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

function tooglePlay()
{
  if(enGol==false)	
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
	enGol=true;
	goler="e1p";
	seer="e2p";
	pause();
	$("#msgGol").css("display","block");
	
	
	
	if(equipo==1)	{ 
		$("#imgGol").removeClass("imgVolteada");
		$("#imgGol").css("left","");
		$("#imgGol").css("rigth","-130px");
		$("#imgTeam1Gol").css("display","block");
		$("#imgTeam2Gol").css("display","none");
		
	}
	
	if(equipo==2)
	{		
		$("#imgGol").addClass("imgVolteada");
		$("#imgGol").css("rigth","");
		$("#imgGol").css("left","-130px");
		$("#imgTeam1Gol").css("display","none");
		$("#imgTeam2Gol").css("display","block");
		
		goler="e2p";
	    seer="e1p";
	}	
	 
	posTerreno= $("#divTerreno").offset(); 
	/*for(i=1;i<12;i++)
	  {
	  	  posi= $("#"+goler+i).offset();
		  
		  xd= posTerreno.left+360;
		  yd= posTerreno.top+240;	  	  	
		  
		  $("#"+goler+i).animate({left: '-='+ (posi.left-xd),top:'-='+(posi.top-yd)}, 5000);		  
  		}*/
	setTimeout(function(){
		$("#divTurno").html(parseInt($("#divTurno").html())+1);
		play();
		$("#msgGol").css("display","none");
		enGol=false;
	},2000);
 }
var lock=false;
function getDistance(p1,p2)
{
	return Math.sqrt(Math.pow(p2.top-p1.top,2)+Math.pow(p2.left-p1.left,2));
}
function volverDeGol()
{
	$("#msgGol").attr("display","none");
	play();
}
function cambiarLista()
{
	$("#cajalista").toggle("slow");
}

	
function numeroAleatorio(min, max) {
      return Math.round(Math.random() * (max - min) + min);
 }

function finJuego()
{
	$("#divFulltime").css("display","block");	
	$("#pizarra").css("display","none");
	$("#golTeam1").html(0);
	$("#golTeam2").html(0);
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
		   $("#ball").offset({left: posTerreno.left+ actual[0]*factorMul - 5, top: posTerreno.top+ actual[1]*factorMul - 5}); 		   
		   jug=1;
		   for(i=2;i<=22;i+=2)
		   {
			 x= parseInt(actual[i])*factorMul;
			 y= parseInt(actual[i+1])*factorMul;

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
	
	$("#golTeam1").html(0);
	$("#golTeam2").html(0);
}



