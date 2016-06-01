<?php
// 
?>
 
<html lang="en" manifest="brain_appcache.php">
   <!-- ==========================
    	Meta Tags 
    =========================== -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <!-- ==========================
    	Favicons 
    =========================== -->
   
	<link rel="shortcut icon" href="./img/favicon.ico">
		
	 <!-- ==========================
    	CSS 
    =========================== -->
    <link href="./css/bootstrap.min.css" rel="stylesheet" type="text/css">
 

	<script type="text/javascript" src="./js/jquery-1.11.0.min.js"></script>
	
	<script type="text/javascript" src="./js/bootstrap.min.js"></script>
	<script type="text/javascript" src="./js/bootply/tab.js"></script>


    <script type="text/javascript" src="./js/dblocal.js"></script>
	
	<script>
	var modo="offline";
	
	if (window.applicationCache) {
    applicationCache.addEventListener('updateready', function() {
       /* if (confirm('An update is available. Reload now?')) {
            window.location.reload();
        }*/
    });
   }
    function updateIndicator() {
      if (navigator.onLine){
	  $('#localcopy').hide();
	  $('#webcopy').show();
	  if (document.location.href.indexOf("indexon.php")<0) document.location.href="indexon.php";
	}else{
	  
	  $('#localcopy').show();
	  $('#webcopy').hide();
	  if (document.location.href.indexOf("index.php")<0) document.location.href="index.php";
	}
	}
   
 ///////////// Cargar BBDD
  function cargarBBDD(){
<?php
 


$con = mysqli_connect('127.2.90.130','root','angelota','mcveladb');
$con->set_charset("utf8");
if (!$con) {
  die('Could not connect: ' . mysqli_error($con));
}
 
$sql="SELECT * FROM pregunta ";
$result = mysqli_query($con,$sql);

while($row = mysqli_fetch_array($result)) {
?>
		
			 <?php
			    $pregunta_ES = preg_replace("[\n|\r|\n\r]", '', $row['pregunta_ES']);
				$respuesta_ES = preg_replace("[\n|\r|\n\r]", '', $row['respuesta_ES']);
				$pregunta_EN = preg_replace("[\n|\r|\n\r]", '', $row['pregunta_EN']);
				$respuesta_EN = preg_replace("[\n|\r|\n\r]", '', $row['respuesta_EN']);
				$tags = preg_replace("[\n|\r|\n\r]", '', $row['tags']);
			    $prioridad=($row['prioridad'])? $row['prioridad']:0;
				echo "addBD(".$row['id'].",'".addslashes($pregunta_ES)."','".addslashes($respuesta_ES)."','".addslashes($pregunta_EN)."','".addslashes($respuesta_EN)."','".addslashes($tags)."',".$prioridad.");";
			 ?>
		 
<?php
}
?>
}
 </script>

</head>
<body  onload="updateIndicator();initDBLocal();"  ononline="updateIndicator()" onoffline="updateIndicator()">

<?php
$valorSearch="";
if(isset($_GET['q'])){ $valorSearch=$_GET['q'];}

$mainSearch="";
 
$mainFieldSearch="";
if(isset($_GET['main'])){
  $mainSearch="main=".$_GET['main']."&";
  $mainFieldSearch=$_GET['main'];
}

$sql_categorias="SELECT * FROM categoria order by descripcion";
$resultCategorias = mysqli_query($con,$sql_categorias);	  
$botonintercambio="disabled";
?>
 <form> 
<div class="input-group">
  <div class="input-group-btn input-group-sm">
   <button type="button" class="btn btn-default" onClick="showHint('what%','&i=active&e=')"><span class="glyphicon glyphicon-star"></span>&nbsp;</button>
   <button type="button" class="btn btn-default" onClick="showHint('%','&i=active&e=')"><span class="glyphicon glyphicon-list"></span> Q?</button></div>
  <input type="text" placeholder="Search" class="form-control" id="q" name="q"   value="<?php echo $valorSearch; ?>" >
  <div class="input-group-btn input-group-sm">
   <button type="button" class="btn btn-default" onclick="showHint($('#q').val(),'&i=active&e=')"> <span class="glyphicon glyphicon-search"></span>&nbsp;</button>
   <button type="button" class="btn btn-default" onClick="inicio()"><span class="glyphicon glyphicon-trash"></span>&nbsp;</button>
  </div>
</div>

<?php /////////////////////// 2 botonera ////?>
<div class="input-group">
 <div class="input-group-btn input-group-sm">
   <button type="button" class="btn btn-default" onclick="document.location.href='indexoff.php';" id="localcopy" style="display:none" ><span class="glyphicon glyphicon-open" style="color: red" ></span>&nbsp;</button>
   <button type="button" class="btn btn-default" onclick="document.location.href='index.php';" id="webcopy" ><span class="glyphicon glyphicon-cloud-download" style="color: green"></span>&nbsp;</button>
 
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
  <button type="button" class="btn btn-default" id="btnmoveselect" onclick="moveQSelectCategoria('<?php echo $botonintercambio; ?>',document.getElementById('q').value)"  >
  <span class="glyphicon glyphicon-retweet"></span>&nbsp;</button>

 <button type="button" class="btn btn-default" id="btnmanagepreguntas" onclick="managePreguntas()">
		 <span class="glyphicon glyphicon-resize-vertical"></span>&nbsp; </button>  
 </div>
 </div>
<?php ///fin//////////////////// 2 botonera ////?> 


<div id="McvelaBrainItems"></div>

 
</form>


 <script type="text/javascript" src="./js/brain.js"></script>
</body>
</html>