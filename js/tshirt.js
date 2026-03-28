// JavaScript Document

function choice(cual)
{
    $("#"+cual).css("border","2px solid red");
    
    if(cual=="lbVisit"){
     $("#lbHome").css("border","");
     $("#swVisit").prop("checked", true);
    }
     
    if(cual=="lbHome"){
     $("#lbVisit").css("border","");
     $("#swHome").prop("checked", true);
    }
}