

function checkName(elem)
{
    var name= elem.value;
    
    if(name.length>5)
    {
        $.post("team.php",{inName:name,checkName:1},function(data){
            
            if(data==1){
              
                 $("#inName").removeClass("has-error");
    		     $("#inName").addClass("has-success");					 
    		     $("#checkName").html("Nombre disponible..");
    		    $("#btnCheck").removeAttr("disabled");
            }
            else{ 
                    if(data==0){
    				  $("#inName").removeClass("has-success");
    				  $("#inName").addClass("has-error");					 
    				  $("#checkName").html("Nombre no disponible..");
    				  $("#btnCheck").attr("disabled","true");
                    }
                    else
                    {
                      $("#inName").removeClass("has-success");
    				  $("#inName").addClass("has-error");					 
    				  $("#checkName").html("Nombre no disponible..");
    				  $("#btnCheck").attr("disabled","true");
                       $("#checkName").html(data); 
                    }
            }
            
            
            
        });
    }
    
}