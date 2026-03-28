

$(document).ready(function(){ 

myCodeMirror = CodeMirror.fromTextArea(document.getElementById("myTextArea"), {
		lineNumbers: true,
		smartIndent:true,
		autofocus:true,
   			
	 });	
	myCodeMirror.setSize(null, 900);
	myCodeMirror.setOption("theme", "midnigth");	
 });

function readURL(input)
{
	if(input.files && input.files[0]){
		var reader= new FileReader();
		
		reader.onload=function(e){
			$("#imgPreview").attr("src",e.target.result);
		}		
		reader.readAsDataURL(input.files[0]);
	}
}


$(document).on('change','#e',function(){
	readURL(this);
});


function checkName()
{
	name= $("#inName").val();
	if(name.length>=5)
	 {	  
	$.post('team.php',{inName:name,checkName:"check"},function(data,textStatus,jqXHR){ 
	    data=data.trim();
	    
	     if(data=="1")
		 {
		    
	        $("#infoError").html("Este nombre ya esta..");
			$("#formGroup").addClass("has-error");
			 $("#formGroup").removeClass("has-warning"); 
			 $("#formGroup").removeClass("has-success"); 
			$('#btnSubmit').attr("disabled", true);
		 }	 
		 if(data=="0")
		 {	
		   
			$("#formGroup").removeClass("has-warning"); 
			$("#formGroup").removeClass("has-error"); 
			$("#formGroup").addClass("has-success");  
			$("#infoError").html("OK");			
			$('#btnSubmit').removeAttr("disabled");
		  }
	 });	
	 }
	else
	{
		$("#infoError").html("El nombre debe tener más de 5 caracteres.");
		$("#formGroup").addClass("has-warning");
		$("#formGroup").removeClass("has-error"); 
		$("#formGroup").removeClass("has-success");
		$('#btnSubmit').attr("disabled", true);
	}
}

function getAmistoso(idGame)
{	
  $.post("../utils/amistoso.php",{idGame:idGame,type:"que"}, function(data){	   
	   game= $.parseJSON(data);	  
		if(game.done==1)
			{				
				clearInterval(idIntervalAmistoso);
				$("#gol1").html(game['gol1']);
				$("#gol2").html(game['gol2']);	

				if(game['winner']===game['idTeam1']){
					$("#divVerGame").addClass("alert-success");
					$("#divVerGame").removeClass("alert-danger");
					cadena="VICTORIA !!! <i class='fa fa-smile-o'>  ";
				}
				else{
					$("#divVerGame").addClass("alert-danger");
					$("#divVerGame").removeClass("alert-success");
					cadena="DERROTA  <i class='fa fa-sad'> !!!";
				}	

				cadena+="<a href='show.php?g="+game['idFriendly']+"&type=f'>Ver Partido</a>";
				valor= game['executed'] - game['fecha']
				cadena+="("+valor+" s.)";
				cadena+="<a href='debug.php?i="+game['idFriendly']+"' title='Analizar juego.'><i class='fa fa-tools'></i></a>";

				$("#btnJugar").attr("disabled",false);				
				$("#divVerGame").html(cadena);
				$("#pnInfo").css("display","none");
				$("#pnResult").css("display","block");			
			}
  });  
}

var idIntervalAmistoso=-1;
function jugarAmistoso(idteam,idTeam2)
{		 
	$("#btnJugar").attr("disabled",true);
	$("#pnInfo").css("display","block");
    $("#pnResult").css("display","none");
	$cadena="";
	$.post("../utils/amistoso.php",{idTeam1:idteam,idTeam2:idTeam2}, function(data){		 		
		idIntervalAmistoso= setInterval(function(){
			getAmistoso(data);
		},1000);		
	});
}


