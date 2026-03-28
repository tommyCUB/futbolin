// JavaScript Document

function addToLeague(idLiga, coins,nombre)
{
	if(confirm("Son "+coins+" coins por entrar en la liga?"))
	{
	    $.post('addLeague.php',{i:idLiga},function(data,textStatus,jqXHR){
			if(data=='TRUE') 
			 {
			    alert("Su equipo esta en la liga "+nombre+".");	 
			 }
			 else
			 {
			   alert("No se puede incluir en la liga "+nombre+" , puede que no tenga las coins necesarias para pagar la entrada.");	 
			 } 		
		});
	}
}

$(document).ready(function(){

	$('.miswitch a').click(function(){
		$('.swicht-btn').toggleClass('on');

		if($('#swicht-btn').attr('class') == 'swicht-btn on'){
			$('.pricing-table-cont').toggleClass('rotando-tabla');
		} else{
			$('.pricing-table-cont').toggleClass('rotando-tabla');
		}

	});


});