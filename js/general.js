// JavaScript Document

$(document).ready(function(){ 
	setInterval(function(){getInfo(usuario,"code");} ,2000);
});

function getInfo(usuario,page)
{	 
    $.post("../utils/checkstate.php",{idUser:usuario,page:page},function(data)
	 {		
		news= $.parseJSON(data)	;	
		
		for(i=0;i<news.length;i++)
		{			
			n= news[i];
			tipo=n['tipo'];
			tipos=['Información','Victoria','Derrota','Ingreso','Gasto','Liga'];
			if(tipo<=3 || tipo==6)
				{					
				var unique_id = $.gritter.add({
						// (string | mandatory) the heading of the notification
						title: tipos[tipo-1],
						// (string | mandatory) the text inside the notification
						text: ''+n['msg'],				
						// (bool | optional) if you want it to fade out on its own or just sit there
						sticky: false,
						// (int | optional) the time you want it to be alive for before fading out
						time: 10000,
						// (string | optional) the class name you want to apply to that specific message
						class_name: 'my-sticky-class'
					  });
				}
		}
	});	
}