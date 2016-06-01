function managePreguntas(){
	 $('.collapse').collapse('toggle')
	 
}
function moveQSelectCategoria(estado,str){
    //alert("Estado="+estado+", str= "+str);
 	 
    var str=(estado=="disabled") ? str:document.getElementById('q').value;
	//alert("str="+str);
	if ( $("#sCategoria option[value='"+str+"']").length > 0 ){
		 document.getElementById('sCategoria').value=str;
		 //alert(document.getElementById("sCategoria").options[document.getElementById("sCategoria").selectedIndex].value);
		 //document.getElementById("sCategoria").value=$('#q').val();
		 selectCategoria(document.getElementById('q').value);
	}else{
	   document.getElementById("sCategoria").value='%';
	   selectCategoria("%");
		//$("btnmoveselect").attr("disabled", "disabled");
	}
}
function selectCategoria(myvalor){
   
  var myselect = document.getElementById("sCategoria");
  var valor=myselect.options[myselect.selectedIndex].value;
  //alert("valor="+valor+",myvalor="+myvalor);
  var valorq=myvalor;
  if (myvalor!="%" && myvalor!="%%"){
    if (valor!=myvalor){
	  valorq=myvalor;
	  valor=myvalor;
	}else{
      valorq="%";
	  valor=myvalor;
	}
  }else if (myvalor=="%%"){
    valorq="%";
  }else{
    valor=myvalor;
	valorq=myvalor;
  }
  //alert("valor="+valor+",valorq="+valorq);
  goSearch(valor,valorq);
   
}

function inicio(){
 document.getElementById("q").value="";
}
function showHint(str,str2) {
  // alert(str+"  "+str2);
  if (typeof myVar != 'string' || !(myVar instanceof String)){
	  if (str.length==0) { 
		document.getElementById("McvelaBrainItems").innerHTML="";
		return;
	  }
  }
  document.getElementById("q").value=str;
  
  /*
  var xmlhttp=new XMLHttpRequest();
  
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      document.getElementById("McvelaBrainItems").innerHTML=xmlhttp.responseText;
    }
  }
  xmlhttp.open("GET","getinfo.php?q="+str+str2+"&r=" + Math.random(),true);
 
  xmlhttp.send();
  */
  
   if (navigator.onLine && modo=="online"){
     $.get("getinfo.php?q="+str+str2, {}, function(respuesta){
     $("#McvelaBrainItems").html(respuesta);});
	 
	}else{
	   var myselect = document.getElementById("sCategoria");
       var categoria=myselect.options[myselect.selectedIndex].value;
       // alert("acceso a BBDD local-->str"+str+"_str2="+str2);
	    queryBD(str,str,str,str,str,categoria);
	  
    }	

  
}

 
//alert("cargar todo ,entroAjax="+entroAjax);
showHint("%","&i=active&e=");
 
 
function goSearch(valor,mystr){
	var str=document.getElementById("q").value;
	str=(mystr!="")?mystr:str;
	document.getElementById("q").value=str;
    var str2="&main="+valor;
	
	showHint(str,str2)
	 
}