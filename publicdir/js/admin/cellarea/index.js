var oTable;
var hTable;
var progressFlag = true;
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
					var data = JSON.parse(res);
					alert(data.success + " data inserted, "+data.error+ " failed.");
					//location.reload();
				}
		})
		getProgress();
	})
} );

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