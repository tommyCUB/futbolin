<?php
session_start();
require_once("utils/control.php");
$control= new control();
$id=""; 

if($control->checkSecurity())
{
	if(isset($_GET['id']))
	{
		$id=$_GET['id'];
	}
	
		$clientID="AYAMUOvpLK1LJK_ucegW6iLUowSf9eYHdsQypm1QDrATDYfDUnypRXnM67X7IcEvKMhyH1yqMZJXh1SG";
		$secret="EAaqwL1caQ5VbFEST8CLS8P19A47ZLt7jod0LgaGUDcAdFFcJJwVSrn08VMWZviXwBwIrY_dl9XeafMl";
		
		$login=curl_init();
		
		curl_setopt($login, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
		
		curl_setopt($login, CURLOPT_HEADER, false);
		curl_setopt($login, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($login, CURLOPT_POST, true);
		curl_setopt($login,CURLOPT_RETURNTRANSFER,true);
		
		curl_setopt($login,CURLOPT_USERPWD,$clientID.":".$secret);
		
		curl_setopt($login,CURLOPT_POSTFIELDS,"grant_type=client_credentials");
		
		$respuesta=curl_exec($login);
	    $objResp= json_decode($respuesta);
		$accessToken= $objResp->access_token;
		curl_close($login); 	
		
		$venta=curl_init();
	
	    curl_setopt($venta, CURLOPT_URL, "https://api.sandbox.paypal.com/v2/checkout/orders/".$id);
		curl_setopt($venta, CURLOPT_HEADER, true);
		curl_setopt($venta, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($venta, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: Bearer ".$accessToken));
	    curl_setopt($venta,CURLOPT_RETURNTRANSFER,true);		
		$respuestaVenta=curl_exec($venta);
		$objVenta= json_decode($respuestaVenta);
		
		 
	    	
	    $pos=strpos($respuestaVenta,'{');
		$posend= strrpos($respuestaVenta,'}');
		$cadena = substr($respuestaVenta,$pos,$posend-$pos+1);
	
		$objCdn= json_decode($cadena);		 
	
	    $idVenta= $objCdn->purchase_units[0]->custom_id;
	   
	    $pago=0;
	
	    foreach($objCdn->purchase_units[0]->payments->captures as $pagos)
		{
			if($pagos->status=='COMPLETED'){
				$pago+=$pagos->amount->value;
			}			
		}        
	
		if($objCdn->status=='COMPLETED')
		{			 
			$complete=1;
			$control->procesarVenta($idVenta,$pago,$id);
		}
}

header('Location: user.php');

?>

 

