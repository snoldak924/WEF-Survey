		</div>
		<div id="footer">Copyright &copy; Effluential Synergies, 2011</div>
	</div>
</div>
<script type="text/javascript" src="/media/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript">
<?php if($page=='Manage Permissions'){ ?>
function toggleRegion(region){
	switch(region){<?php 
		foreach($EPARegions as $region => $EPAstates){
			echo "case $region:\n";
			echo "\tvar region_states=[\"".implode('","',$EPAstates)."\"];\n\tbreak;\n";
		} ?>
	}
	for (var i = 0; i < region_states.length; i++){
		document.getElementById(region_states[i]).checked = !document.getElementById(region_states[i]).checked;
	}
}
<?php } ?>
//$(document).ready(function() {
	$( ".jui" ).buttonset();
	$('button').button();
//});
<?php if($page==''){ ?>
function load_counties(state){
	if(state==''){
		if(document.getElementById('county_form').childNodes.length>3){
			document.getElementById('county_form').removeChild(document.getElementById("county"));
			document.getElementById('county_form').removeChild(document.getElementById("countySubmit"));
		}
	}else{
		var loadGif = document.createElement('img');
			loadGif.src='/images/ajax-loader_small.gif';
			loadGif.id='loader';
			document.getElementById("county_form").appendChild(loadGif);
		if (window.XMLHttpRequest){xmlhttp=new XMLHttpRequest();}

		else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}

		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById('county_form').removeChild(document.getElementById('loader'));
				if(document.getElementById('county_form').childNodes.length>3){
					document.getElementById('county_form').removeChild(document.getElementById("county"));
					document.getElementById('county_form').removeChild(document.getElementById("countySubmit"));
				}
				
				var countySelect = document.createElement('select');
				countySelect.name='county';
				countySelect.id='county';
				countySelect.innerHTML="<option value='all'>All Counties</option>"+xmlhttp.responseText;
				document.getElementById("county_form").appendChild(countySelect);
				var submitButton = document.createElement('input');
				submitButton.type='submit';
				submitButton.value='View';
				submitButton.id='countySubmit';
				document.getElementById('county_form').appendChild(submitButton);
			}
		}

		xmlhttp.open("GET","getCounties.php?state="+state,true);
		xmlhttp.send();
	}
}
<?php } ?>
<?php if(in_array($page,array('Facility','Edit'))){ ?>
function deleteComment(comment_id){
	if (window.XMLHttpRequest){xmlhttp=new XMLHttpRequest();}
	else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}

	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			$("#comment-"+comment_id).remove();		
		}
	}

	xmlhttp.open("GET","deleteComment.php?id="+escape(comment_id),true);
	xmlhttp.send();
}
function addComment(facility){
	var comment = document.getElementById('newComment').value;
	if(comment!=''){
		if (window.XMLHttpRequest){xmlhttp=new XMLHttpRequest();}
		else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}

		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				var newComment = document.createElement("div");
				newComment.id="comment-"+xmlhttp.responseText;
				newComment.className="comment";
				newComment.innerHTML='<div style="float:right;"><button id="delete-'+
				xmlhttp.responseText+
				'" onclick="deleteComment('+
				xmlhttp.responseText+
				');"><span style="color:#991111;">&#10008;</span></button></div>'+
				'<a href="user.php?id=<?php echo $Auth->user->id; ?>"><b><?php echo nonempty(array($Auth->user->name,$Auth->user->email)); ?></b></a> - '+
				HtmlEncode(comment)+'<br />'+
				'<span style="color:#888;">just now</span>';
				document.getElementById('comment-list').innerHTML=newComment.outerHTML+document.getElementById('comment-list').innerHTML;	
				document.getElementById('newComment').value='';
				$('#delete-'+xmlhttp.responseText).button();
			}
		}

		xmlhttp.open("GET","addComment.php?id="+escape(facility)+"&comment="+escape(comment),true);
		xmlhttp.send();
	}
}
function toggle_AD_details(){
	if($('#anaerobic_digestion-0').attr("checked") != "undefined" && $('#anaerobic_digestion-0').attr("checked") == "checked"){
		$('#ad_details').css('display',"list-item");
	}else{
		$('#ad_details').css('display',"none");
	}
}
<?php }
if($Auth->isModerator() && $page=='Edits') {?>
function deleteEdit(edit_id){
	var r=confirm("Are you sure you want to delete this edit?");
	if (r==true){
		if (window.XMLHttpRequest){xmlhttp=new XMLHttpRequest();}
		else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}

		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				var pos = oTable.fnGetPosition(document.getElementById('edit-'+edit_id));
				oTable.fnDeleteRow(pos);	
			}
		}

		xmlhttp.open("GET","deleteEdit.php?id="+escape(edit_id),true);
		xmlhttp.send();
	}
}
<?php } ?>
function HtmlEncode(s)
{
  var el = document.createElement("div");
  el.innerText = el.textContent = s;
  s = el.innerHTML;
  delete el;
  return s;
}
</script>
<?php if($page=="Facility"){ ?>
<script>
$(function() {
	$( "#phase2" ).accordion({
		collapsible: true,
		active: false,
		animated: false
	});
});
</script>
<?php }
if(!empty($_GET['state']) || $page=='Edits'){ ?>
	<script type="text/javascript" src="/media/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="/extras/ColVis/media/js/ColVis.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="/extras/TableTools/media/js/TableTools.min.js"></script>
	<script type="text/javascript"><?php
	if($Auth->isModerator()){ ?>
		function approve_yes(facility_id){
			if (window.XMLHttpRequest){xmlhttp=new XMLHttpRequest();}
			else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
			xmlhttp.open("GET","approveYes.php?id="+escape(facility_id),true);
			xmlhttp.send();
		}
		function approve_no(facility_id){
			if (window.XMLHttpRequest){xmlhttp=new XMLHttpRequest();}
			else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
			xmlhttp.open("GET","approveNo.php?id="+escape(facility_id),true);
			xmlhttp.send();
		}<?php
	} ?>
	var oTable;<?php
	if($page!='Edits'){ ?>
		// Formating function for row details
		function fnFormatDetails ( oTable, nTr )
		{
			var aData = oTable.fnGetData( nTr );
			var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="margin-left:20px;width:98%">';
			sOut += '<tr><td>NPDES:</td><td colspan="5">'+aData[1]+'</td></tr>';
			sOut += '<tr><td>Stabilization:</td><td colspan="5">'+aData[8]+'</td></tr>';
			sOut += '<tr><td>Plant Capacity:</td><td>'+aData[9]+'</td>';
			sOut += '<td>Average Flow:</td><td>'+aData[10]+'</td>';
			sOut += '<td>MGD:</td><td>'+aData[11]+'</td>';
			sOut += '<td>Design Flow:</td><td>'+aData[12]+'</td>';
			sOut += '<td>Permitted Flow:</td><td>'+aData[13]+'</td></tr>';
			sOut += '<tr><td colspan="10"><table border="1" cellspacing="0"><tr><td>Annual Sludge Production, Total:</td><td>Annual amount of sludge land applied:</td><td>Annual amount of sludge disposed by other methods:</td><td>Annual amount of sludge incinerated:</td><td>Annual amount of sludge disposed in landfill:</td><td>Annual amount of sludge disposed in surface unit:</td></tr>';
			sOut +='<tr><td>'+aData[14]+'</td><td>'+aData[15]+'</td><td>'+aData[16]+'</td><td>'+aData[17]+'</td><td>'+aData[18]+'</td><td>'+aData[19]+'</td></tr></table></td></tr>';
			sOut += '<tr><td>Facility Address:</td><td colspan="5">'+aData[4]+'</td></tr>';
			if(aData[2]!=0){
				sOut += (aData[2]?'<tr><td>Links:</td><td colspan="4">'+
				'<a href="http://iaspub.epa.gov/enviro/fii_query_dtl.disp_program_facility?p_registry_id='+
				aData[2]+'" target="_blank">FRS</a> | '+
				'<a href="http://www.epa-echo.gov/cgi-bin/get1cReport.cgi?tool=echo&amp;IDNumber='+
				aData[2]+'" target="_blank">ECHO</a></td></tr>':'');
			}
			sOut += '</table>';
			
			return sOut;
		}
		
		TableTools.BUTTONS.download = {
			"sAction": "text",
			"sFieldBoundary": "",
			"sFieldSeperator": "\t",
			"sNewLine": "<br>",
			"sToolTip": "",
			"sButtonClass": "DTTT_button_text",
			"sButtonClassHover": "DTTT_button_text_hover",
			"sButtonText": "CSV",
			"mColumns": "all",
			"bHeader": true,
			"bFooter": true,
			"sDiv": "",
			"fnMouseover": null,
			"fnMouseout": null,
			"fnClick": function( nButton, oConfig ) {
				var oParams = this.s.dt.oApi._fnAjaxParameters( this.s.dt );
				var iframe = document.createElement('iframe');
				iframe.style.height = "0px";
				iframe.style.width = "0px";
				iframe.src = oConfig.sUrl+"?<?php echo $_SERVER['QUERY_STRING']; ?>";
				document.body.appendChild( iframe );
			},
			"fnSelect": null,
			"fnComplete": null,
			"fnInit": null
		};<?php
	} ?>
	$(document).ready(function() {
		$('#loading').remove();
		document.getElementById('facilities').style.display='table';
		
		<?php if($page!='Edits'){ ?>	
		//Begin details stuff
		var nCloneTh = document.createElement( 'th' );
		var nCloneTd = document.createElement( 'td' );
		nCloneTd.innerHTML = '<img src="/images/details_open.png">';
		nCloneTd.className = "center";
		
		$('#facilities thead tr').each( function () {this.insertBefore( nCloneTh, this.childNodes[0] );	} );
		$('#facilities tbody tr').each( function () {this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] ); } );
		//End details stuff
		
		//init datatable
		oTable = $('#facilities').dataTable( {
			"sPaginationType": "full_numbers",
			"sDom": '<"H"TCflr>t<"F"ip>',
			"oColVis": {"aiExclude": [0,21,22,23,24]},
			"oTableTools": {
				"aButtons": [ 
					{
						"sExtends": "download",
						"sButtonText": "CSV",
						"sUrl": "/text.php"
					} ]
				},
			"bAutoWidth": "true",
			"aoColumnDefs": [
				{"bSortable":		false, "aTargets": [0,-4] },
					{ "bVisible":		false, "aTargets":[1,2,4,<?=(!empty($county) && $county!='all')?'5,':'';?>6,<?=($Auth->isMABA()?'7':'8')?>,9,10,11,12,13,14,15,16,17,18,19,20] },
				{ "bSearchable":    false, "aTargets":[5,7,8,9,10,11,12,13,15,16,17,18,19,20,21,22,23,24] }		],
			"aaSorting": [[3, 'asc']],
			"bJQueryUI": true,
			"bStateSave": true
		  }); 
		  
		$('#facilities tbody td img').live('click', function () {
			var nTr = this.parentNode.parentNode;
			if ( this.src.match('details_close') ){
				this.src = "/images/details_open.png";
				oTable.fnClose( nTr );
			}
			else if(this.src.match('details_open')){
				this.src = "/images/details_close.png";
				oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );
			}
		} );<?php
		}else { //Edit Page ?>
			jQuery.fn.dataTableExt.oSort['status-asc'] = function(x,y) {
					var a,b;
					if(x=='draft') a=3;
					else if(x=='approved') a=2;
					else if(x=='rejected') a=1;
					if(y=='draft') b=3;
					else if(y=='approved') b=2;
					else if(y=='rejected') b=1;
					return ((a<b) ?  -1 : ((a>b) ? 1 : 0));
				};
				 
				jQuery.fn.dataTableExt.oSort['status-desc'] = function(x,y) {
					var a,b;
					if(x=='draft') a=3;
					else if(x=='approved') a=2;
					else if(x=='rejected') a=1;
					if(y=='draft') b=3;
					else if(y=='approved') b=2;
					else if(y=='rejected') b=1;
					return ((a<b) ?  1 : ((a>b) ? -1 : 0));
				};
			//init datatable
			oTable = $('#facilities').dataTable( {
				"sPaginationType": "full_numbers",
				"sDom": '<"H"Cflr>t<"F"ip>',
				"oColVis": {"aiExclude": [5,6]},
				"bAutoWidth": "false",
				"aoColumnDefs": [
					{"bSortable":false, "aTargets":	[-1,-2] },
					{"bSearchable":	false, "aTargets":	[3] },
					 {"sType":"status", "aTargets": [ 3 ] }
					],
				"aaSorting": [[3,'desc'],[4, 'desc']],
				"bJQueryUI": true,
				"bStateSave": true
			});<?php
		}?>
		
		<?php /* $(window).bind('resize', function () {
			oTable.fnAdjustColumnSizing();
		} );*/ ?>
	} );
	</script><?php 
} ?>
</body>
</html>