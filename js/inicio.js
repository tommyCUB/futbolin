// JavaScript Document


  $(document).ready(function(e) {
	
	   
  });



function cargarRanking(page)
{  	
	$.post("index.php",{rk:'rk',pg:page},function(data)
	{	
		ranking= $.parseJSON(data);
		cdn="";
		cdn2="";
		for(i=0;i<ranking.length;i++)
		{
			
			if(i==0)
				cdn+="<div class='carousel-item'>"
			
			if(i==0)
				cdn+="<table class='table table-sm' style='' align=''>";			
			
			escudo=ranking[i]['escudo'];
			cdn+= "<tr>	<td> <small>"+(60+i+1)+"</small></td>	<td style='font-size:12px;'>			   <img src='images/"+escudo+"' class='img-circle' style='width:25px;'>				"+ranking[i]['nombre']+" <small><b> Elo:</b>"+parseInt(ranking[i]['elo'])+"</small>			</td></tr>";
			
			
			if(i%10==0 && i!=0){
				cdn+="</table>";
				if(i+1<ranking.length)
				  cdn+="<table class='table table-sm' style='' align=''>";	
			}
			
			if(i%30==0 && i!=0){
				cdn+="</div>";
				
				cdn2= "<li data-target='#demo' data-slide-to='"+(60+i+1)+"'></li>";
				$("#contentPlecas").html($("#contentPlecas").html()+cdn2);			
				$("#contentCarusel").html($("#contentCarusel").html()+cdn);
				$("#contentCarusel").reload();
				
				if(i+1<ranking.length)
					cdn+="<div class='carousel-item'>"
			}
			
			
		}		
					
	});
	
	

}

