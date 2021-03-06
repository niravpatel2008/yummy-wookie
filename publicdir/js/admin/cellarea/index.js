var oTable;
var hTable;
var locObj;
var progressFlag = true;
var markers = [];
$(document).ready(function() {
	$("#file-process-form").validationEngine();
	oTable = $('#cellareaTable').dataTable( {
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": admin_path ()+'cellarea/ajax_list/',
			"type": "POST"
		},
		aoColumnDefs: [
		  {
			 bSortable: false,
			 aTargets: [ 0,-1 ]
		  }
		]
	} ).columnFilter({sPlaceHolder:"head:after",aoColumns: [{ type: "select",values: companies},null,null,null,null,null,null,null,null]});	

	if ($("#my-awesome-dropzone").length > 0)
	{
		setTimeout(function(){
				var myDropzone = Dropzone.forElement("#my-awesome-dropzone");
				myDropzone.on("sending", function(file) {
					loadingMessage = "Uploading file";
					$("body").loading("show");
				});
				myDropzone.on("complete", function(file) {
					loadingMessage = "";
					$("body").loading("hide");
				});
				myDropzone.on("success", function(file, res) {
					if (res.indexOf("Error:") === -1)
					{
						var file = JSON.parse(res);
						$(".file-upload").hide();
						$(".file-process").show();
						$("#file-name").html (file.filename);
						$("#filename").val(file.filename_org)
						var optionsHtml = "<option value=''>Select</option>";
						for(key in file.header)
						{
								optionsHtml+="<option value='"+key+"'>"+file.header[key]+"</option>";
						}
						$(".file-process-field").html(optionsHtml);
					}
					else
					{
						var error = error_msg_box(res);
						$(".content-header").after(error);
					}
				});
		},1000)
	}

	$("#file-cancel").on("click",function(){
		$(".file-upload").show();
		$(".file-process").hide();
		$("#file-name").html ("");
		$("#file-process-form input,#file-process-form select").val("");
	})

	$("#del-company-btn").on("click",function(){
		var company = $("#del_company_name").val();
		if (company == "")
		{
			alert("Please select company name.");
			return;
		}
		delete_company_cellarea(company);
	});

	$("#file-submit").on("click",function(e){
		e.preventDefault();
		if(!$('#file-process-form').validationEngine('validate'))
			return false;
		
		var formdata =$("#file-process-form").serializeArray();
		var data = {}
		for ( key in formdata )
		{
			data[formdata[key].name] = formdata[key].value;
		}
		loadingMessage = "Processing file";
		$("body").loading("show");
		var url = admin_path ()+'cellarea/process_file/';
		progressFlag = true;
		$.post(url,data,function(res){
				$("#file-cancel").click();
				if (res.indexOf("Error:") !== -1)
				{
					progressFlag = false;
					$("body").loading("hide");
					var error = error_msg_box(res);
					$(".content-header").after(error);
				}
				else
				{
					progressFlag = false;
					$("body").loading("hide");
					oTable.fnClearTable(0);
					oTable.fnDraw();
					var data = JSON.parse(res);
					alert(data.success + " data inserted, "+data.error+ " failed.");
				}
		})
		getProgress();
	})

	locObj = $('#locationHolder').locationpicker({
		radius: 300,
		scrollwheel: true,
		inputBinding: {
			locationNameInput: $('#de_address_tmp'),
		},
		enableAutocomplete: true,
		onchanged: function(currentLocation, radius, isMarkerDropped) {
				var data = {latitude:currentLocation.latitude,longitude:currentLocation.longitude};
				searchCellArea(data);
		}
	});
} );

function searchCellArea(data)
{
	var url = admin_path()+'cellarea/search';
	$("body").loading("show");
	
	var map = $(locObj).data("locationpicker").map;
	
	$.post(url,data,function(ret){
		$("body").loading("hide");

		/* Clear All markers */
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(null);
		}

		var areas = JSON.parse(ret);
		var html = "<tr><td>Company</td><td>Cell id</td><td>Town</td><td>Distance</td></tr>";

		for (key in areas)
		{
			var area = areas[key];
			html += "<tr id='tr_"+area.id+"'><td>"+area.company+"</td><td>"+area.cell_id+"</td><td>"+area.town+"</td><td>"+area.distance+"</td></tr>";

			var myLatLng = {lat: parseFloat(area.latitude), lng: parseFloat(area.longitude)};
			console.log(myLatLng);
			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				tr_id:area.id,
				title: area.cell_id+" ("+area.company+")",
				icon: 'https://chart.googleapis.com/chart?chst=d_map_spin&chld=0.7|0|ABA8E0|13|b|'+area.company.substr(0,1).toUpperCase()
			});
			markers.push(marker);
			google.maps.event.addListener(marker, "click", function()
			{
				console.log(marker);
				var highlightTr = "tr_"+marker.tr_id;
				$("#cellarea-result tr").removeClass("highlight");
				$("#"+highlightTr).addClass("highlight");
			})
		}
		$("#cellarea-result").html(html);
	})
}


function getProgress(){
	var url = admin_path ()+'uploads/'+$("#filename").val()+".html";
	$.get(url,{},function(ret){
		$(".ajx-loading-msg").html("Progress: "+ret);
	}).always(function() {
		if (progressFlag)
		{
					setTimeout(function(){
						getProgress();
					},2000);
		}
	 });
}

function delete_company_cellarea(company)
{
	var r = confirm("Are you sure you want to delete?");
	if (!r) {
		return false;
	}
	$.ajax({
		type: 'post',
		url: admin_path()+'cellarea/deleteByCompany',
		data: {"company":company},
		success: function (data) {
			if (data == "success") {
				oTable.fnClearTable(0);
				oTable.fnDraw();
				$("#flash_msg").html(success_msg_box ('Cell Id deleted successfully.'));
			}else{
				$("#flash_msg").html(error_msg_box ('An error occurred while processing.'));
			}
		}
	});
}

function delete_cellarea(del_id)
{
	var r = confirm("Are you sure you want to delete?");
	if (!r) {
		return false;
	}
	$.ajax({
		type: 'post',
		url: admin_path()+'cellarea/delete',
		data: 'id='+del_id,
		success: function (data) {
			if (data == "success") {
				oTable.fnClearTable(0);
				oTable.fnDraw();
				$("#flash_msg").html(success_msg_box ('Cell Id deleted successfully.'));
			}else{
				$("#flash_msg").html(error_msg_box ('An error occurred while processing.'));
			}
		}
	});
}

function addedit_cellinfo(id)
{
	var op = (id)?"edit":"add";
	
	var url = admin_path()+'cellarea/'+op+'/'+id;
	$("body").loading("show");
	$.get(url,{},function(data){
			$("body").loading("hide");
			$('#edit-modal .modal-content').html(data);
			$('#edit-modal').modal('toggle');
	});
}

function submit_cellinfo(id)
{
	$('#cellinfo-addedit').validationEngine();
	if(!$('#cellinfo-addedit').validationEngine('validate'))
		return false;
		
	var formdata =$("#cellinfo-addedit").serializeArray();
	var data = {}
	for ( key in formdata )
	{
		data[formdata[key].name] = formdata[key].value;
	}

	var op = (id)?"edit":"add";
	var url = admin_path()+'cellarea/'+op+'/'+id;
	$("body").loading("show");
	$.post(url,data,function(data){

		if (data == "1") {
			oTable.fnClearTable(0);
			oTable.fnDraw();
			$("body").loading("hide");
			$('#edit-modal').modal('toggle');
			$("#flash_msg").html(success_msg_box ('Operation successfully.'));
		}else{
			$("body").loading("hide");
			$('#edit-modal').modal('toggle');
			$("#flash_msg").html(error_msg_box ('An error occurred while processing.'));
		}
	});
}