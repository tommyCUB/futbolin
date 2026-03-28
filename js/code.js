// JavaScript Document
var myCodeMirror;

$(document).ready(function(){ 
		myCodeMirror = CodeMirror.fromTextArea(document.getElementById("myTextArea"), {
		lineNumbers: true,
		smartIndent:true,
		autofocus:true,
			
	   extraKeys: {
		"Ctrl-Space": "autocomplete",   
		   
        "Ctrl-K": function(cm) {
            check(); 
        },
        "Ctrl-S": function(cm) {
            save(); 
        },		
        "F1": function(cm) {			
            $('#myModal2').modal('show'); //function called for full screen mode 
        },
        "Esc": function(cm) {
            alert("Cerrar ayuda"); //function to escape full screen mode
        }
    }			
	 });	
	myCodeMirror.setSize(null, 900);  
	myCodeMirror.setOption("theme", $("#inTheme").val());	
	myCodeMirror.on("change",function(){cambioCode();});   
	myCodeMirror.on("keyUp",function(cm){
		myCodeMirror.commands.autocomplete(cm, null, {completeSingle: false});}); 
	
	$("body").on('DOMSubtreeModified', "#divInfo", function() {
    ajustarAreaCodigo($("#divInfo"));
		});	
	ajustarAreaCodigo($("#divInfo"));
	
		 
   // setInterval(function(){getInfo(usuario,"code");} ,2000);
     
});


function ajustarAreaCodigo(elem)
{
	altura=elem.css("height");		
	$("#divContCode").css("top",altura);
	alto= $("#divContCode").css("height").split("px")[0];
	altura=altura.split("px")[0];
	$("#divTheme").css("top",(parseInt(alto)+parseInt(altura)+70)+"px");

}

function cambioCode(){
	 
	$("#saveBtn").css("visibility","hidden");
	$("#checkBtn").css("visibility","visible");
	$("#msjError").html("modified...");
	
	$("#msjError").removeClass("text-success");
	$("#msjError").removeClass("text-warning");	
	$("#msjError").addClass("text-danger");
	
}


var idIntervalAmistoso=-1;

function jugarAmistoso(idteam)
{		 
	idTeam2= $("#slPera").val();
	
	$("#slPera").attr("disabled","disabled");
	$("#btnTest").attr("disabled","disabled");
	$("#loading").css("display","inline");
	$("#spResultado").html("watting...");	
	
   /*	$.ajax(
	    {
	     type:"POST",
	     data:{idTeam1:idteam,idTeam2:idTeam2},
	     url: "http://www.futbolincode.com/utils/amistoso.php",
	     success: function(resp)
	     {
	         alert(resp);
	         idIntervalAmistoso= setInterval(function(){
			getAmistoso(data);
		},1000);
	     },
	     
	    error: function(xhr,err){
            alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
            alert("responseText: "+xhr.responseText);
        }
	    });*/
	
	
	  $.post("../utils/amistoso.php",{idTeam1:idteam,idTeam2:idTeam2}, function(data){	
	    alert(data);
	    idIntervalAmistoso= setInterval(function(){
			getAmistoso(data);
		},1000);		
	});
}


function getAmistoso(idGame)
{	
  $.post("https://www.futbolincode.com/utils/amistoso.php",{idGame:idGame,type:"que"}, function(data){	   
	   game= $.parseJSON(data);	  
	   cadena="";
		if(game.done==1)
			{				
				clearInterval(idIntervalAmistoso);
				
				if(game['winner']==game['idTeam1'])	
				cadena+="<strong class='text-success h3'>"+game['gol1']+"  -  "+game['gol2']+"</strong>  <i class='far fa-smile-o h4'>";	
				else
				   cadena+="<strong class='text-danger h3'>"+game['gol1']+"  -  "+game['gol2']+"</strong>  <i class='far fa-frown h4'>";
				
				$("#slPera").removeAttr("disabled");
				$("#btnTest").removeAttr("disabled");
				
				$("#loading").css("display","none");
				
				cadena+="<a href='#myModal' data-toggle='modal' data-target='#myModal'><img src='../images/campo.png' style='width:35px' onclick='llenarTerreno("+game['idFriendly']+")'></a>";
				
				cadena+="<a href='#myModal' data-toggle='modal' data-target='#myModal' onclick='llenarDebug("+game['idFriendly']+")' style='width:36px;'><img src='../images/campo2.png' style='width:35px' onclick='llenarDebug("+game['idFriendly']+")'></a>";
				
				$("#spResultado").html(cadena);				
			}
  });  
}

function toggleTactica()
{
	$("#divTactica").toggle();
}
function llenarDebug(idGame)
{
	$(".modal-content").css("width","950px;");
	$(".modal-body").css("width","950px;");
	$("#contentTerreno").css("width","1000px");
	$.get("debug2.php",{i:idGame},function(data){
		
		$("#contentTerreno").html(data)	;
	});	 
}

var idIntervalChec=-1;
var idIntervalSave=-1;

function check()
{	
	code=myCodeMirror.getValue();	
		
	$.post("https://www.futbolincode.com/utils/check.php",{checkBtn:'yes',myTextArea:code},function(data)
		   {	
			idChec= data;
		    $("#checkBtn").attr("disabled","disabled");
		    $("#checkBtn").html($("#checkBtn").html()+"<img src='../images/loading.gif' style='width:15px;'>");
		
		   idIntervalChec= setInterval(function(){
			   getChec(idChec);
		   },1000);
		
	});	 
}


function getChec(idChec)
{
	$.post("https://www.futbolincode.com/utils/check.php",{type:'check',idChec:idChec},function(data)
		  {		 
		chec= $.parseJSON(data);
		
		if(chec.done==1)
		{			  
		  $("#checkBtn").removeAttr("disabled");
		  $("#checkBtn").html("<i class='fa fa-check'></i> Check");
		  $("#msjError").html("checked...");
		  $("#msjError").addClass("text-warning");
		  $("#msjError").removeClass('text-danger'); 
		  $("#msjError").removeClass("text-success");
			
		   if(chec.cant==0)
		   {
				$("#saveBtn").css("visibility","visible");
			    $("#checkBtn").css("visibility","hidden");
			    $("#msjError").html("checked...");
			   $("#msjError").addClass("text-success");
				$("#msjError").removeClass("text-warnning");
				$("#msjError").removeClass("text-error");
			   $("#divTest").css("display","inline");
			   $("#cantError").html("");
			   $("#errors").html("");
		   }
		   else
		   {
			 $("#saveBtn").css("visibility","hidden");
			 $("#checkBtn").css("visibility","visible"); 
			    $("#divTest").css("display","none");
			 er=" error";
			   if(chec.cant>1)
				    er=" errores.";
			   
			 $("#cantError").html(chec.cant+er);
			 $("#cantError").removeClass("bg-success");
			 $("#cantError").addClass("bg-warning");
			 errores= chec.err.split('::');
			 errores.pop();  
			   cdn="<ol>";
			   for(i=0;i<errores.length;i++){	
				 
					   if(errores[i]!="")
						cdn+=(i+1)+".- <span class='li text-danger'>"+errores[i]+"</span><br>";				
			   }  
			   cdn+="</ol>";	   
			   
			$("#errors").html(cdn);
		   }
			
		  clearInterval(idIntervalChec);			
		}
	});
}

function getChecToSave(idChec)
{
	$.post("https://www.futbolincode.com/utils/check.php",{type:'check',idChec:idChec},function(data)
		  {
		chec= $.parseJSON(data);
		if(chec.done==1)
		{	
		  	
		  $("#saveBtn").removeAttr("disabled");
		  $("#saveBtn").html("<i class='fa fa-save'></i> Save");
						
		   if(chec.cant==0)
		   {
			   code=myCodeMirror.getValue();
			   $.post("https://www.futbolincode.com/utils/check.php",{saveBtn:'yes',myTextArea:code},function(data){
				  $("#msjError").addClass("text-success");
				  $("#msjError").removeClass('text-danger'); 
				  $("#msjError").removeClass("text-warning");				   
				  $("#msjError").html("Saved");
				  $("#saveBtn").css("visibility","hidden");				 
			  });	
		   }
		   else
		   {	
			 $("#cantError").html(chec.cant) ; 
			 $("#msjError").addClass("text-danger");
			  $("#msjError").removeClass('text-success');
			 $("#msjError").html("No se puede salvar, su codigo tiene errores.");  
			 $("#saveBtn").css("visibility","hidden");
			 $("#checkBtn").css("visibility","visible");  
			   
			 $("#saveBtn").css("visibility","hidden");
			 $("#checkBtn").css("visibility","visible"); 
			 $("#divTest").css("display","none");
			 er=" error";
			   if(chec.cant>1)
				    er=" errores.";
			   
			 $("#cantError").html(chec.cant+er);
			 $("#cantError").removeClass("bg-success");
			 $("#cantError").addClass("bg-warning");
			 errores= chec.err.split('::');
			 errores.pop();  
			   cdn="<ol>";
			   for(i=0;i<errores.length;i++){	
				 
					   if(errores[i]!="")
						cdn+=(i+1)+".- <span class='li text-danger'>"+errores[i]+"</span><br>";				
			   }  
			   cdn+="</ol>";	   
			   
			$("#errors").html(cdn);
		   }
			
		  clearInterval(idIntervalSave);			
		}
	});
}

function save()
{
	code=myCodeMirror.getValue();
	 
	$.post("https://www.futbolincode.com/utils/check.php",{checkBtn:'yes',myTextArea:code},function(data)
		   {	
			idChec= data;
		    $("#saveBtn").css("visibility","visible");
		    $("#saveBtn").attr("disabled","disabled");
		    $("#saveBtn").html($("#saveBtn").html()+"<img src='../images/loading.gif' style='width:15px;'>");		
			$("#checkBtn").attr("display","none");   
		    $("#errors").html("");
		    $("#cantError").html("");
		    
		   idIntervalSave= setInterval(function(){
			   getChecToSave(idChec);
		   },1000);
		
	});
}
function llenarTerreno(idGame)
{
    $("#contentTerreno").css("width","auto");
	$.get("show2.php",{g:idGame,type:'f'},function(data){
	    
		$("#contentTerreno").html(data);
	});	 
}


herramienta = "lapiz";
tamano = 2;
pintar = Boolean(false);
color_prim = "#fA0";

function limpiarCanvas()
{
    var canvas = document.getElementById("cuadro");
    var contexto = canvas.getContext("2d");
    contexto.clearRect(0, 0, canvas.width, canvas.height);
}

function activarLapiz()
{
    $("#cuadro").css("cursor","url(../images/pincel.cur),pointer");
    herramienta='lapiz';
}

function activarGoma()
{
    $("#cuadro").css("cursor","url(../images/eraser.cur),pointer");
    herramienta='borrador';
}

window.onload = function(){
	'use strict';
	var c = document.getElementById("cuadro");
	var ctx = c.getContext("2d");
	c.width = 277;
    c.height = 369;
    
	var posCuadro= $("#cuadro").offset();
	
	c.onmousedown = function (e){
	    pintar = true;
	     
		if( herramienta == "lapiz" )
		{
			ctx.moveTo(e.pageX - posCuadro.left, e.pageY - posCuadro.top);
		}
	}

	c.onmouseup = function(){

		pintar = false;

		ctx.beginPath();

	}

	c.onmousemove = function(e){

		if (pintar) {

			if (herramienta == "lapiz") {

				ctx.lineTo(e.pageX - posCuadro.left, e.pageY - posCuadro.top);

				ctx.lineWidth = tamano;

				ctx.strokeStyle = color_prim;

				ctx.stroke();

			}

			else if(herramienta == "borrador"){

				ctx.beginPath();

				ctx.clearRect(e.pageX - posCuadro.left, e.pageY -posCuadro.top,tamano*5,tamano*5);

			}

		}

	}

	c.onmouseout = function(){
		pintar = false;
	};

}


