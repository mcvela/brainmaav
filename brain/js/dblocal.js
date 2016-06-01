 var html5rocks = {};
      html5rocks.webdb = {};
      html5rocks.webdb.db = null;
      
      html5rocks.webdb.open = function() {
        var dbSize = 5 * 1024 * 1024; // 5MB
        html5rocks.webdb.db = openDatabase("McvelaBrain", "1.0", "McvelaBrain manager", dbSize);
      }
      
      html5rocks.webdb.createTable = function() {
        var db = html5rocks.webdb.db;
        db.transaction(function(tx) {
          tx.executeSql("CREATE TABLE IF NOT EXISTS McvelaBrain(ID INTEGER PRIMARY KEY ASC, pregunta_ES TEXT, pregunta_EN TEXT, respuesta_EN TEXT, respuesta_ES TEXT, tags TEXT,prioridad INTEGER)", []);
        });
      }
      
      html5rocks.webdb.addMcvelaBrain = function(id,pes,pen,ren,res,tags,prioridad) {
        var db = html5rocks.webdb.db;
	    db.transaction(function(tx){
          var addedOn = new Date();
          tx.executeSql("INSERT INTO McvelaBrain(ID, pregunta_ES, pregunta_EN, respuesta_EN, respuesta_ES, tags,prioridad) VALUES (?,?,?,?,?,?,?)",
              [id,pes,pen,ren,res,tags,prioridad],
              html5rocks.webdb.onSuccess,
              html5rocks.webdb.onErrorInsert);
         });
      }
      
	  html5rocks.webdb.onErrorInsert = function(tx, e) {
       // alert("There has been an error al insertar: " + e.message);
      }
	  
      html5rocks.webdb.onError = function(tx, e) {
        alert("There has been an error: " + e.message);
      }
      
      html5rocks.webdb.onSuccess = function(tx, r) {
        // re-render the data.
        html5rocks.webdb.getAllMcvelaBrainItems(loadMcvelaBrainItems);
      }
      
      
      html5rocks.webdb.getAllMcvelaBrainItems = function(renderFunc) {
        var db = html5rocks.webdb.db;
        db.transaction(function(tx) {
          tx.executeSql("SELECT * FROM McvelaBrain", [], renderFunc,
              html5rocks.webdb.onError);
        });
      }
	    html5rocks.webdb.getQueryMcvelaBrainItems = function(renderFunc,q1,q2,q3,q4,q5,main) {
		//alert("getQueryMcvelaBrainItems 1:"+q1+" 2:"+q2+" 3:"+q3+" 4:"+q4+" 5:"+q5+" ");
		var mainsql=(main=="%")? "":"and (tags like '%"+main+"%')";
        var db = html5rocks.webdb.db;
        db.transaction(function(tx) {
          tx.executeSql("SELECT * FROM McvelaBrain where (pregunta_ES like '%"+q1+"%'  or respuesta_ES like '%"+q2+"%' or pregunta_EN like '%"+q3+"%'  or respuesta_EN like '%"+q4+"%' or tags like '%"+q5+"%') "+mainsql+" order by tags , prioridad desc", [],
       		  renderFunc,
              html5rocks.webdb.onError);
        });
      }
      
      html5rocks.webdb.deleteMcvelaBrain = function(id) {
        var db = html5rocks.webdb.db;
        db.transaction(function(tx){
          tx.executeSql("DELETE FROM McvelaBrain WHERE ID=?", [id],
              html5rocks.webdb.onSuccess,
              html5rocks.webdb.onError);
          });
      }
      
      function loadMcvelaBrainItems(tx, rs) {
	    var tagArrays={};
        var rowOutput = "";
		var row;
		var tagArraysTemp;
        for (var i=0; i < rs.rows.length; i++) {
		  row=rs.rows.item(i);
		  tagArraysTemp=new Array();
		  tagArraysTemp=row.tags.split(",");
		  for(var x in tagArraysTemp){
		   tagArrays[''+tagArraysTemp[x]]=tagArraysTemp[x];
		  }
          rowOutput += renderMcvelaBrain(rs.rows.item(i));
        }
        
		////////////// Botones claves
		var tagArraysSorted = assocSort(tagArrays);
		var botonestags=renderBotonesClave(tagArraysSorted);
	   
        $("#McvelaBrainItems").html(botonestags+rowOutput);
      }
	  
	  function renderBotonesClave(tagArrays){
	    var botonestags="";
	    for(var i in tagArrays){
		 botonestags+='<button type="button" class="btn btn-default" onclick="'+"showHint('"+tagArrays[i]+"','&amp;i=active&amp;e=')\">"+tagArrays[i]+"</button>";
		}
		return botonestags
	  }
      
      function renderMcvelaBrain(row) {
	    var tagArrays=new Array();
		tagArrays=row.tags.split(",");
		var botonestags=renderBotonesClave(tagArrays);
		 
	    var sp="";
		sp+='<div class="panel-default" id="en_p_'+row.ID+'">';
		sp+='<div class="panel-heading">';
		sp+='<table width="100%"><tbody><tr><td><h4 class="panel-title">';
		sp+='<a data-toggle="collapse" data-parent="#accordion" href="#icollapse_en'+row.ID+'">'+row.pregunta_EN+'</a>';
		sp+='</h4></td><td width="20px"> <button type="button" class="btn btn-default btn-xs" onclick="$(\'#es_p_'+row.ID+'\').show();$(\'#en_p_'+row.ID+'\').hide()">ES</button> </td></tr></tbody></table>';
		sp+='</div>';
		sp+='<div id="icollapse_en'+row.ID+'" class="panel-collapse collapse ">';
		sp+='<div class="panel-body">';
		sp+=row.respuesta_EN;
		sp+='</div> <center>'+botonestags+'</center></div></div>';
	 	
		sp+='<div class="panel-default" id="es_p_'+row.ID+'" style="display:none">';
		sp+='<div class="panel-heading">';
		sp+='<table width="100%"><tbody><tr><td><h4 class="panel-title">';
		sp+='<a data-toggle="collapse" data-parent="#accordion" href="#icollapse_es'+row.ID+'">'+row.pregunta_ES+'</a>';
		sp+='</h4></td><td width="20px"> <button type="button" class="btn btn-default btn-xs" onclick="$(\'#en_p_'+row.ID+'\').show();$(\'#es_p_'+row.ID+'\').hide()">US</button> </td></tr></tbody></table>';
		sp+='</div>';
		sp+='<div id="icollapse_es'+row.ID+'" class="panel-collapse collapse ">';
		sp+='<div class="panel-body">';
		sp+=row.respuesta_ES;
		sp+='</div><center>'+botonestags+ '</center></div></div>';
		 
        return sp;
//		"<li>" + row.pregunta_ES  + " [<a href='javascript:void(0);'  onclick='html5rocks.webdb.deleteMcvelaBrain(" + row.ID +");'>Delete</a>]</li>";
      }
      
      function initDBLocal() {
        html5rocks.webdb.open();
        html5rocks.webdb.createTable();
		cargarBBDD();
        if (modo=="offline") html5rocks.webdb.getAllMcvelaBrainItems(loadMcvelaBrainItems);
      }
      
      function addBD(id,pes,res,pen,ren,tags,prioridad) {
        html5rocks.webdb.addMcvelaBrain(id,pes,pen,ren,res,tags,prioridad);
       }
	  function queryBD(q1,q2,q3,q4,q5,main){
	      
		 if ( html5rocks.webdb.db){
		   
		  $("#McvelaBrainItems").html("");
	      html5rocks.webdb.getQueryMcvelaBrainItems(loadMcvelaBrainItems,q1,q2,q3,q4,q5,main);
		 }
	  }
	  
	  function assocSort (oAssoc) {
			var idx; var key; var arVal = []; var arValKey = []; var oRes = {};
			for (key in oAssoc) {
			arVal[arVal.length] = oAssoc[key];
			arValKey[oAssoc[key]] = key;
			}
			arVal.sort();
			for (idx in arVal)
			oRes[arValKey[arVal[idx]]] = arVal[idx];
			return oRes;
	}