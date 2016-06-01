
<?php
 
 //////////////////FUNCIONES
 
 function expandirONo($row,$idioma,$search) {
	 $si=0;
	  
	 $pos = stripos($row["pregunta_".$idioma], $search,1);
	 $si = ($pos>0)? $si+1: $si;
	 //echo $row["pregunta_".$idioma]."_search=".$search."_ 1  _si=".$si."_:".$pos.":<br>";
	 
	 $pos = stripos($row["tags"], $search,1);
	 $si = ($pos>0)? $si+1: $si;
	 // echo $row["tags"]."_search=".$search."_ 2  _si=".$si."_:".$pos.":<br>";
	 
	  
	 if ($si>=1){
	   return "in";
	 }else{
	   return "";
	 }
	 
 }
 
 function gradoImportancia($row,$idioma,$search) {
	 $si=0;
	  
	 $pos = stripos($row["pregunta_".$idioma], $search,1);
	 $si = ($pos>0)? $si+1: $si;
	 //echo $row["pregunta_".$idioma]."_search=".$search."_ 1  _si=".$si."_:".$pos.":<br>";
	 $pos = stripos($row["respuesta_".$idioma], $search,1);
	 $si = ($pos>0)? $si+1: $si;
	 $si = ($pos==0 || $pos<6)? $si+1: $si;
	  
	 //echo $row["tags"]."_search=".$search."_ 2  _si=".$si."_:".$pos.":<br>";
	
	  
	 $pos = stripos($row["tags"], $search,1);
	 $si = ($pos>0)? $si+1: $si;
	 $si = ($pos==0)? $si+2: $si;
	 //echo $row["tags"]."_search=".$search."_ 3  _si=".$si."_:".$pos.":<br>";
	 return $si;
	 
	 
 }
 
 
 ////////////////////////////////////
 
$q = $_GET['q'];

$con = mysqli_connect('127.2.90.130','root','angelota','mcveladb');
if (!$con) {
  die('Could not connect: ' . mysqli_error($con));
}
$mainSearch="";
$mainFieldSearch="";
if(isset($_GET['main'])){
  $mainSearch=" and (tags like '%".$_GET['main']."%')";
  $mainFieldSearch=$_GET['main'];
}

mysqli_select_db($con,"mcveladb");
$sql_ES="SELECT * FROM pregunta WHERE (pregunta_ES like '%".$q."%'  or respuesta_ES like '%".$q."%' or pregunta_EN like '%".$q."%'  or respuesta_EN like '%".$q."%' or tags like '%".$q."%') ".$mainSearch." order by tags , prioridad desc";
$sql_EN="SELECT * FROM pregunta WHERE (pregunta_ES like '%".$q."%'  or respuesta_ES like '%".$q."%' or pregunta_EN like '%".$q."%'  or respuesta_EN like '%".$q."%' or tags like '%".$q."%') ".$mainSearch." order by tags , prioridad desc";

$content_ES="";
$content_EN="";
$content="";
$nivel_contenido=Array("","","","","","","","","","","","","","");
				 
$result = mysqli_query($con,$sql_EN);

//echo $sql_EN;
 
$mytags_EN=[];
$mytags_ES=[];
$espanol='style="display:none"';
 if(isset($_GET['e'])){ $espanol=($_GET['e']!="")? 'style="display:none"':'';}
$ingles="";
if(isset($_GET['i'])){ $ingles=($_GET['i']!="")? 'style="display:none"':'';}

$sql_categorias="SELECT * FROM categoria order by descripcion";

$mainTag=array('%'=>'Ver todo','aws'=>'AWS(Amazon Web Service)','java'=>'Java','spring'=>'Spring','unix'=>'Unix','software'=>'Software',
      'ror'=>'Ruby on Rails','grails'=>'Grails','web service'=>'Web Service','javascript'=>'Javascript','sql'=>'Sql','diagrams'=>'Diagrams');
$resultCategorias = mysqli_query($con,$sql_categorias);	  

$i=0;
while($row = mysqli_fetch_array($result)) {
            $mytags_EN =  array_merge($mytags_EN, explode(",", $row['tags']));
			// $bidiomas='<div class="btn-group" data-toggle="buttons">';
			//  $bidiomas=' <button type="button" class="btn btn-default" onclick="$(\'#es_p_'.$i.'\').show();$(\'#en_p_'.$i.'\').hide()"><img src="/image/flags/es.gif"></button> ';
			  $bidiomas=' <button type="button" class="btn btn-default btn-xs" onclick="$(\'#es_p_'.$i.'\').show();$(\'#en_p_'.$i.'\').hide()">ES</button> ';
			// $bidiomas=$bidiomas.'</div>';
			
            $temp_content_EN='<div class="panel panel-default" id="en_p_'.$i.'" '.$espanol.'>
					<div class="panel-heading">
						<table width="100%"><tr><td><h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#icollapse'.$i.'">'.$row['id'].".". utf8_encode (str_ireplace($q, "<font color='dark'>".$q."</font>",  $row['pregunta_EN']));  
			 $temp_content_EN=$temp_content_EN.'</a>
						</h4></td><td width="20px">'.$bidiomas.'</td></tr></table>
					</div>
					<div id="icollapse'.$i.'" class="panel-collapse collapse '.expandirONo($row,"EN",$q).'">
						<div class="panel-body">'. utf8_encode (str_ireplace($q, "<font color='dark'>".$q."</font>",  $row['respuesta_EN']));
			 $temp_content_EN=$temp_content_EN.'</div>';
			 $temp_content_EN=$temp_content_EN.'  <center>('. utf8_encode (str_ireplace($q, "<font color='dark'>".$q."</font>",  $row['tags'])).')</center>';
			 $temp_content_EN=$temp_content_EN.'</div>';
			 $temp_content_EN=$temp_content_EN.'	</div>';
				
			 //$bidiomas='<div class="btn-group" data-toggle="buttons">';
			 //$bidiomas=' <button type="button" class="btn btn-default" onclick="$(\'#es_p_'.$i.'\').hide();$(\'#en_p_'.$i.'\').show();"><img src="/image/flags/us.gif"></button> '; 
			 $bidiomas=' <button type="button" class="btn btn-default btn-xs" onclick="$(\'#es_p_'.$i.'\').hide();$(\'#en_p_'.$i.'\').show();">US</button> ';
			 //$bidiomas=$bidiomas.'</div>';
  
			
			 $mytags_ES =  array_merge($mytags_ES, explode(",", $row['tags']));
             $temp_content_ES='<div class="panel panel-default" id="es_p_'.$i.'" '.$ingles.'>
					<div class="panel-heading">
						<table width="100%"><tr><td><h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#ecollapse'.$i.'">'.$row['id'].".".utf8_encode (str_ireplace($q, "<font color='dark'>".$q."</font>",  $row['pregunta_ES']));  
			 $temp_content_ES=$temp_content_ES.'</a>
						</h4></td><td width="20px">'.$bidiomas.'</td></tr></table>
					</div>
					<div id="ecollapse'.$i.'" class="panel-collapse expande '.expandirONo($row,"ES",$q).'">
						<div class="panel-body">'. utf8_encode (str_ireplace($q, "<font color='dark'>".$q."</font>",  $row['respuesta_ES']));
			 $temp_content_ES=$temp_content_ES.'</div>';
			 $temp_content_ES=$temp_content_ES.'  <center>('. utf8_encode (str_ireplace($q, "<font color='dark'>".$q."</font>",  $row['tags'])).')</center>';
			 $temp_content_ES=$temp_content_ES.'</div>';
			 $temp_content_ES=$temp_content_ES.'	</div>';
			 
			 if (expandirONo($row,"EN",$q)!="" || expandirONo($row,"ES",$q)!=""){
			     $nivelImportancia=gradoImportancia($row,"EN",$q);
				 $nivel_contenido[$nivelImportancia]= $temp_content_EN.$temp_content_ES.$nivel_contenido[$nivelImportancia];
				 $temp_content_EN="";
				 $temp_content_ES="";
			 }
			 $content=$content.$temp_content_EN.$temp_content_ES;
				 
             $i++;
}


$content= $nivel_contenido[6]. $nivel_contenido[5].$nivel_contenido[4].$nivel_contenido[3].$nivel_contenido[2].$nivel_contenido[1].$nivel_contenido[0].$content;

$botonintercambio="disabled";

 ?>
 


 
<?php ////////////////////// BOTONES PRINCIPALES ///////////////?>
<?php /*?>
<div class="input-group">
 <div class="input-group-btn input-group-sm">
   <button type="button" class="btn btn-default" onclick="" disabled><span class="glyphicon glyphicon-cloud-download"></span>&nbsp;</button>
 
 <button type="button" class="btn btn-default" onclick="goSearch('%','%')">&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-th"></span>&nbsp;&nbsp;&nbsp;</button>
 </div>
 <select class="form-control"  onChange="selectCategoria('%%')" id="sCategoria" style="font-weight:bold">
				 <?php 
				   
				  while($row = mysqli_fetch_array($resultCategorias)) {
						 if(isset($_GET['main'])){
						   $mimain=($_GET['main']==$row["categoria"])? "selected":"";
						   if(isset($_GET['q'])){
						     if ($botonintercambio=="disabled" && $row["categoria"]==$_GET['q']){ 
							  $botonintercambio="";
							 }
						   }
						 }else{
						   $mimain="";
						 }	
						 
						   echo ' <option value="'. $row["categoria"].'" '.$mimain.' >';					 
						   // echo '<li class="'. $mimain.'"><a href="#" onclick="javascript:goSearch(\''.$row["categoria"].'\',\'%\');" role="tab" data-toggle="tab">';
						   echo $row["descripcion"];
						   echo '</option>';
					  }
					
					?>
		 
 </select>
  <div class="input-group-btn input-group-sm">
  <button type="button" class="btn btn-default" id="btnmoveselect" onclick="moveQSelectCategoria('<?php echo $botonintercambio; ?>','<?php echo $mainFieldSearch; ?>')" >
  <span class="glyphicon glyphicon-retweet"></span>&nbsp;</button>

 <button type="button" class="btn btn-default" id="btnmanagepreguntas" onclick="managePreguntas()">
		 <span class="glyphicon glyphicon-resize-vertical"></span>&nbsp; </button>  
 </div>
 </div>	
 <?php */?>
 
 <div  class="btn-group">
		
			<?php ////////////////////// BOTONES auxiliares ///////////////?>
						<?php 
						$mytags_EN=array_unique($mytags_EN);
						sort($mytags_EN);			
						foreach ($mytags_EN as &$valor) {
						  if ($valor!="" && $valor!=$mainFieldSearch){
						   if(isset($_GET['q'])){
						     if ($valor!=$_GET['q']){
						       echo '<div class="btn-group"><button type="button" class="btn btn-default" onclick="showHint(\''.$valor.'\',\'&i=active&e=\')">';
						       echo $valor;
						       echo '</button></div>';
						     }
							}
						   }
						}
						?>
		
 
 </div>				 
 
	 
			 
			
			
			 <div class="panel-group" id="accordion">
				 
						<?php echo  $content; ?>			
			</div>
			
	
<?php
mysqli_close($con);
?>



