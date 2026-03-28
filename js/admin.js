// JavaScript Document


var cantToAdd=0;

$(document).ready(function(){	
	calcularLiga();	
	setOpcion(document.getElementsByName("modo")[0]);
	cambiarLiga();
	
});

$(document).on('click', '.panel-heading span.clickable', function(e){
    var $this = $(this);
	if(!$this.hasClass('panel-collapsed')) {
		$this.parents('.panel').find('.panel-body').slideUp();
		$this.addClass('panel-collapsed');
		$this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
		
	} else {
		$this.parents('.panel').find('.panel-body').slideDown();
		$this.removeClass('panel-collapsed');
		$this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');		
	}	
});

function calcularLiga(coins)
{
	cambiarslCantEquipos();
	cantEq=$("#slCantG").val();
	tipo=$("#slTipo").val();
	entrada=$("#inEntry").val();
	premio=$("#inPremio").val();
	
	if(tipo==2)
	  cantEq=$("#slCantT").val();	
	
	cantGames=0;
	costo=1500;
	
	if(entrada=="" || isNaN(entrada))
		entrada=0;
	if(premio=="" || isNaN(premio))
		premio=0;
	
	if(tipo==2)
		cantGames=cantEq*cantEq-cantEq;
	if(tipo==1)
	{
		c=cantEq/4;
		cantGames=cantEq/4*6-1;
		 
		while(c>0)
			{
			  cantGames+=c;
			  c/=2;				
			}	
	}
	
	costo+=parseInt(cantGames)*20+parseInt(premio);
			
	$("#costo").html(costo);
	$("#cantGames").html(cantGames);
	
	if(costo>coins){
		$("#costo").removeClass("text-success");
		$("#costo").addClass("text-danger");
		$("#costo").html($("#costo").html()+"<small> (saldo insuficiente). </small>");
		$("#btnNewLiga").attr("disabled","true");
		
	}	
	else
		{
		 $("#btnNewLiga").attr("enabled","true");	
		 $("#costo").removeClass("text-danger");
		 $("#costo").addClass("text-success");	
		}	
	nombre= $("#inName").val();
	 
	if(nombre.length>3){		 
		$.post("newliga.php",{type:"cn",n:nombre},function(data){
			 
			if(data.trim()=='no')
				{
				  	
				  $("#grName").removeClass("has-error");
				  $("#grName").addClass("has-success");					 
				  $("#helpName").html("Nombre disponible..");
				  $("#btnNewLiga").removeAttr("disabled");
				  
				}
			if(data.trim()=="yes")
				{
					 
				  $("#grName").removeClass("has-success");
				  $("#grName").addClass("has-error");					 
				  $("#helpName").html("Nombre no disponible..");
				  $("#btnNewLiga").attr("disabled","true");
				}
		});
	}
}

function cambiarLiga()
{
	idLiga=$("#slLiga").val();
	
	$.post("newliga.php",{type:"tl",i:idLiga},function(data){
		 
		valores= $.parseJSON(data);
		cdn="("+valores.length+") ";
		for(i=0;i<valores.length;i++)
			{
				team= valores[i];
				cdn+=(i+1)+"- <strong>"+team.nombre+"</strong> <small>[Rk: "+team.ranking+", Elo: "+parseInt(team.elo)+"]; </small>";
			}
		$("#divTeamsLiga").html(cdn);
	});
}

function setOpcion(elem)
{
	tipo=elem.value;
	$("#slTeam").empty();
	
	if(tipo=="elo")
	{
		$("#pnElo").css("display","block");
		$("#pnName").css("display","none");
		min= $("#elomin").val();
		max= $("#elomax").val();
		$.post("newliga.php",{type:"lo",min:min,max:max},function(data){			
			valores=$.parseJSON(data);			 
			llenarSelectTeams(valores);
		});
	}
	
	if(tipo=="name")
	{
		$("#pnElo").css("display","none");
		$("#pnName").css("display","block");
		nombre=$("#name").val();
		$.post("newliga.php",{type:"na",name:nombre},function(data){			
			valores=$.parseJSON(data);			 
			llenarSelectTeams(valores);
		});
	}
	
	if(tipo=="barrio")
	{
		$("#pnElo").css("display","none");
		$("#pnName").css("display","none");
		
		$.post("newliga.php",{type:"be"},function(data){
			valores=$.parseJSON(data);			 
			llenarSelectTeams(valores);
		});
	}
}

function llenarSelectTeams(valores)
{
	$("#slTeam").empty();
	$("#txtCantidad").html(valores.length);
	for(i=0;i<valores.length;i++)
			{
				team= valores[i];
				msg= team.nombre+" [ Rk: "+team.ranking+", Elo: "+parseInt(team.elo)+" ]";
				valor=team.idTeam;
				var o = new Option(msg, valor);
				/// jquerify the DOM object 'o' so we can use the html method
				$(o).html(msg);
				$("#slTeam").append(o);
				 
			}
}

function cambiarslCantEquipos()
{  	
	if($("#slTipo").val()==2)
		{	
			$("#slCantG").css("display","none");
		    $("#slCantT").css("display","block");			
		}
	if($("#slTipo").val()==1)
		{		
			$("#slCantG").css("display","block");
		    $("#slCantT").css("display","none");	
		}
}

function addToWait()
{ 	
   slTeam= document.getElementById("slTeam");
   op= slTeam.options[slTeam.selectedIndex];   
   slTeams= document.getElementById("slTeams");	
   estan= $("#divTeamsLiga").html().split(";");	
   color="black";
   nombre= op.text.split(' [')[0];
	nombre= nombre.trim();
   for(i=0;i<estan.length;i++)
	   {
		 if(estan[i]!='')
		 {
		 	nombre2=  estan[i].substring(estan[i].indexOf("<strong>")+8,estan[i].indexOf("</strong>"));
			 nombre2=nombre2.trim();
		  
		  
			if(nombre==nombre2)
			{
				color="red";
				break;
			}			
		 }
	   }
	if(color!='red')
		cantToAdd++;
	
   $("#txtCantAdd").html(cantToAdd);   
   slTeams.innerHTML="<option value='"+op.value+"' style='color:"+color+";'>"+op.text+"</option>"+slTeams.innerHTML;
   slTeam.options[slTeam.selectedIndex]=null;   
	
}

function erase()
{
   slTeams= document.getElementById("slTeams");
   op= slTeams.options[slTeams.selectedIndex];   
   slTeam= document.getElementById("slTeam");	
   slTeam.innerHTML+="<option value='"+op.value+"'>"+op.text+"</option>";
   slTeams.options[slTeams.selectedIndex]=null;
	cantToAdd--;
	 $("#txtCantAdd").html(cantToAdd); 
}

function probar()
{
	 $("#slTeams option").attr("selected", true);
}